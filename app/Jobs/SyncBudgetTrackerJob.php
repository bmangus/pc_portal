<?php

namespace App\Jobs;

use App\BTApprovals;
use App\BTApprovers;
use App\BTRequisition;
use App\BTRequisitionItem;
use App\Services\BTWorkflowService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncBudgetTrackerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bt;
    protected $requisitions;
    protected $syncDt;

    protected $createExclusions;
    protected $updateExclustions;
    protected $recItemExclusions;
    protected $includedApproverFields;
    protected $approvalFields;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->createExclusions = [
            'submissionLog', 'Web_Status_New'
        ];

        $this->updateExclustions = [
            'ApprovedBy1', 'ApprovedBy2', 'ApprovedBy3', 'ApprovedBy4', 'ApprovedBy5',
            'ApprovedStatus1', 'ApprovedStatus2', 'ApprovedStatus3', 'ApprovedStatus4', 'ApprovedStatus5',
            'ApprovedDate1', 'ApprovedDate2', 'ApprovedDate3', 'ApprovedDate4', 'ApprovedDate5',
            'ApprovedComments1', 'ApprovedComments2', 'ApprovedComments3', 'ApprovedComments4', 'ApprovedComments5',
            'ApprovedByTE', 'ApprovedStatusTE', 'ApprovedDateTE', 'ApprovedCommentsTE',
            'FinalApprovalFonda', 'FinalApprovedBy', 'FinalApprovedDate', 'FinalApprovedStatus', 'FinalApprovedStatus6Rejected',
            'submissionLog', 'Web_Status_New'
        ];

        $this->approvalFields = [
            'ApprovedBy1', 'ApprovedBy2', 'ApprovedBy3', 'ApprovedBy4', 'ApprovedBy5',
            'ApprovedStatus1', 'ApprovedStatus2', 'ApprovedStatus3', 'ApprovedStatus4', 'ApprovedStatus5',
            'ApprovedDate1', 'ApprovedDate2', 'ApprovedDate3', 'ApprovedDate4', 'ApprovedDate5',
            'ApprovedComments1', 'ApprovedComments2', 'ApprovedComments3', 'ApprovedComments4', 'ApprovedComments5',
            'ApprovedByTE', 'ApprovedStatusTE', 'ApprovedDateTE', 'ApprovedCommentsTE',
            'FinalApprovalFonda', 'FinalApprovedBy', 'FinalApprovedDate', 'FinalApprovedStatus', 'FinalApprovedStatus6Rejected',
        ];

        $this->recItemExclusions = [
          'gTotalPages', 'MockPO', 'zd_Constant', 'zd_CreatedBy', 'zd_CreatedDate', 'zd_FoundCount', 'PO', 'TotalChargeTo', 'zd_ModifiedBy',
            'zd_ModifiedDate', 'zd_RecordCount', 'VendorID', 'zd_RecordStatus', 'TableName', 'id'
        ];

        $this->includedApproverFields = [
          'ProjectCode', 'SiteNo', 'Approver1', 'Approver1Email', 'Approver1FullName', 'Approver2', 'Approver2Email', 'Approver2FullName',
            'Approver3', 'Approver3Email', 'Approver3FullName', 'Approver4', 'Approver4Email', 'Approver4FullName', 'Approver5', 'Approver5FullName',
            'Approver5Email', 'ApproverFinal', 'ApproverFinalEmail', 'ApproverFinalName', 'ApproverTE', 'ApproverTEEmail', 'ApproverTEFullName'
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(BTWorkflowService $bt)
    {
        $this->bt = $bt;
        $this->syncDt = now()->toDateTimeString();
        $this->findActiveFMRequisitions()
            ->runImport()
            ->syncApprovers()
            ->updateApprovals();
    }

    private function findActiveFMRequisitions()
    {
        $this->requisitions = $this->bt
            ->find('Web_Requisition')
            ->where('Web_Status_New', 1)
            ->limit(10000)
            ->get();

        return $this;
    }

    private function runImport()
    {
        foreach ($this->requisitions as $recId => $requisition) {
            //var_dump($requisition['zd_ModifiedDateTime']);
            $exisitngRec = BTRequisition::where('RecID', '=', $requisition['RecID'])->first();
            if ($exisitngRec !== null) {
                $syncTimestamp = \Carbon\Carbon::parse($exisitngRec->lvl_lastSyncDateTime);
                $modTimestamp = \Carbon\Carbon::parse($requisition['zd_ModifiedDateTime']);
                if ($modTimestamp > $syncTimestamp) $this->requisitionBuilder($requisition, $exisitngRec, $this->updateExclustions);
            } else {
                $this->requisitionBuilder($requisition, new BTRequisition(), $this->createExclusions);
            }

        }
        return $this;
    }

    private function requisitionBuilder($requisition, $rec, $exclusions)
    {
        foreach ($requisition as $key => $item) {
            if (!in_array($key, $exclusions)) {
                if($key === 'Project'){
                    $rec->Project = (int) $item;
                } else {
                    $rec->{$key} = $item;
                }
            }
        }
        $rec->lvl_lastSyncDateTime = $this->syncDt;
        $this->constructRequisitionItems($requisition);
        $rec->save();
    }

    private function constructRequisitionItems($requisition)
    {
        $items = $this->bt
            ->find('Web_ReqItems')
            ->where('zd_RequisRecID', $requisition['RecID'])
            ->limit(500)
            ->get();
        foreach($items as $recordId => $recItem) {
            $recId = (string)$recItem['zd_RequisRecID'];
            $requisitionItem = BTRequisitionItem::where(['zd_RequisRecId'=> $recId, 'fmId' => $recItem['id']])->first();
            var_dump($requisitionItem);
            if($requisitionItem !== null) {
                $this->requisitionItemBuilder($requisitionItem, $recItem);
            } else {
                $this->requisitionItemBuilder(new BTRequisitionItem(), $recItem);
            }
            $requisitionItem = null;
        }
    }

    private function requisitionItemBuilder($localRec, $fmRec)
    {
        //dd($fmRec);
        //var_dump($fmRec);
        foreach($fmRec as $fname => $fvalue) {
            if(!in_array($fname, $this->recItemExclusions)){
                if($fname === 'UnitPrice' || $fname === 'Total') {
                    $localRec->{$fname} = (float)$fvalue;
                } elseif ($fname === 'Qty' || $fname === 'ReqNo') {
                    $localRec->{$fname} = (int)$fvalue;
                } elseif ($fname === 'id') {
                    $localRec->fmId = $fvalue;
                } else {
                    $localRec->{$fname} = $fvalue;
                }
            }
            $localRec->save();
        };
    }

    private function syncApprovers()
    {
        $approvers = $this->bt
            ->records('Web_Approvers')
            ->limit(10000)
            ->get();

        foreach($approvers as $recId => $approver) {
            $localRec = BTApprovers::where('fmId', $recId)->first();
            if($localRec === null) $localRec = new BTApprovers();
            foreach($approver as $key => $value) {
                if(in_array($key, $this->includedApproverFields)){
                    if($key === 'SiteNo' || $key === 'ProjectCode') {
                        $localRec->{$key} = (int)$value;
                    } else {
                        $localRec->{$key} = $value;
                    }
                }
            }
            $localRec->fmId = $recId;
            $localRec->save();
        }
        return $this;
    }

    private function updateApprovals()
    {
        SendBudgetTrackerApprovalsJob::dispatchNow();
        return $this;
    }

}

