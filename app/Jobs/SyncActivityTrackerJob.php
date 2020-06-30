<?php

namespace App\Jobs;

use App\ATRequisition;
use App\ATRequisitionItem;
use App\Mail\ATNextApprover;
use App\Services\ATWorkflowService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SyncActivityTrackerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $at;
    protected $requisitions;
    protected $syncDt;
    protected $createExclusions;
    protected $updateExclusions;
    protected $approvalFields;
    protected $recItemExclusions;
    protected $teApprover;
    protected $teApproverEmail;


    public function __construct()
    {
        $this->createExclusions = [
            'Web_Status_New', 'Requisition | Setup::SubmitterEmail', 'Requisition | Setup::Approver',
            'Requisition | Setup::ApproverEmail', 'Requisition | Setup::ApproverUsername'
        ];

        $this->updateExclusions = [
            'ApprovedBy1', 'ApprovedStatus1', 'ApprovedDate1', 'ApprovedComments1',
            'ApprovedByTE', 'ApprovedStatusTE', 'ApprovedDateTE', 'ApprovedCommentsTE',
            'Web_Status_New', 'Requisition | Setup::SubmitterEmail', 'Requisition | Setup::Approver',
            'Requisition | Setup::ApproverEmail', 'Requisition | Setup::ApproverUsername'
        ];

        $this->approvalFields = [
            'ApprovedBy1', 'ApprovedStatus1', 'ApprovedDate1', 'ApprovedComments1',
            'ApprovedByTE', 'ApprovedStatusTE', 'ApprovedDateTE', 'ApprovedCommentsTE',
        ];

        $this->recItemExclusions = [
            'gTotalPages', 'MockPO', 'zd_Constant', 'zd_CreatedBy', 'zd_CreatedDate', 'zd_FoundCount', 'PO', 'TotalChargeTo', 'zd_ModifiedBy',
            'zd_ModifiedDate', 'zd_RecordCount', 'VendorID', 'zd_RecordStatus', 'TableName', 'id'
        ];
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ATWorkflowService $at)
    {
        $this->at = $at;
        $this->teApprover = config('app.at_te_approver');
        $this->teApproverEmail = config('app.at_te_approver_email');
        $this->syncDt = now()->toDateTimeString();
        $this->findActiveFMRequisitions()
            ->runImport()
            ->updateApprovals();
    }

    private function findActiveFMRequisitions()
    {
        try{
            $this->requisitions = $this->at
                ->find('Web_Requisition')
                ->where('Web_Status_New', 1)
                ->limit(10000)
                ->get();
        } catch (\Exception $e) {
            $this->requisitions = [];
        }

        return $this;
    }

    private function runImport()
    {
        foreach ($this->requisitions as $recId => $requisition) {
            //var_dump($requisition['zd_ModifiedDateTime']);
            $exisitngRec = ATRequisition::where('RecID', '=', $requisition['RecID'])->first();
            if ($exisitngRec !== null) {
                $syncTimestamp = \Carbon\Carbon::parse($exisitngRec->lvl_lastSyncDateTime);
                $modTimestamp = \Carbon\Carbon::parse($requisition['zd_ModifiedDateTime']);
                if ($modTimestamp > $syncTimestamp) $this->requisitionBuilder($requisition, $exisitngRec, $this->updateExclustions);
            } else {
                //New requisition is created here...
                $requisition = $this->requisitionBuilder($requisition, new ATRequisition(), $this->createExclusions);

                //... then send initial email to first approver when new requisition is imported.
                if(isset($requisition->Status) && $requisition->Status !== 'Completed' && $requisition->Status !== 'Approved' && $requisition->Status !== 'Rejected'){
                    Mail::to($this->getNextApproverEmail($requisition))->send(new ATNextApprover($requisition));
                }
            }

        }
        return $this;
    }

    private function getNextApproverEmail($requisition)
    {
        if($requisition->ApprovedBy1 === ""){
            return $requisition["ApproverEmail"];
        } else if($requisition["Technology"] === "TE" && $requisition["ApprovedStatusTE"] === "") {
            return $this->teApproverEmail;
        }
        return null;
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
            } elseif ($key === "Requisition | Setup::SubmitterEmail") {
                $rec->SubmitterEmail = $item;
            } elseif ($key === "Requisition | Setup::Approver") {
                $rec->Approver = $item;
            } elseif ($key === "Requisition | Setup::ApproverEmail") {
                $rec->ApproverEmail = $item;
            } elseif ($key === "Requisition | Setup::ApproverUsername") {
                $rec->ApproverUsername = $item;
            }
        }
        $rec->lvl_lastSyncDateTime = $this->syncDt;

        $this->deleteExistingReqItems($requisition)->constructRequisitionItems($requisition);
        $rec->save();
        return $rec;
    }

    private function deleteExistingReqItems($requisition)
    {
        ATRequisitionItem::where('RequisitionNo', $requisition['RequisitionNo'])->delete();
        return $this;
    }

    private function constructRequisitionItems($requisition)
    {
        $items = $this->at
            ->find('Web_ReqItems')
            ->where('RequisitionNo', (int)$requisition['RequisitionNo'])
            ->limit(500)
            ->get();
        foreach($items as $recordId => $recItem) {
            $recId = (string)$recItem['RequisitionNo'];
            $requisitionItem = ATRequisitionItem::where(['RequisitionNo'=> $recId, 'fmId' => $recItem['id']])->first();
            if($requisitionItem !== null) {
                $this->requisitionItemBuilder($requisitionItem, $recItem);
            } else {
                $this->requisitionItemBuilder(new ATRequisitionItem(), $recItem);
            }
            $requisitionItem = null;
        }
    }

    private function requisitionItemBuilder($localRec, $fmRec)
    {
        foreach($fmRec as $fname => $fvalue) {
            if(!in_array($fname, $this->recItemExclusions)){
                if($fname === 'unitPrice' || $fname === 'Total') {
                    $localRec->{$fname} = (float)$fvalue;
                } elseif ($fname === 'Access' || $fname === 'AccountDesc' || $fname === 'CatalogNo' || $fname === 'Description') {
                    $localRec->{$fname} = $fvalue;
                } elseif ($fname === 'id') {
                    $localRec->fmId = $fvalue;
                } else {
                    $localRec->{$fname} = (int) $fvalue;
                }
            }
            $localRec->save();
        };
    }

    private function updateApprovals()
    {
        SendActivityTrackerApprovalsJob::dispatchNow();
        return $this;
    }

}
