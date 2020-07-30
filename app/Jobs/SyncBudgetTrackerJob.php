<?php

namespace App\Jobs;

use App\BTApprovers;
use App\BTApproverSetup;
use App\BTRequisition;
use App\BTRequisitionItem;
use App\BTWebSetup;
use App\Mail\BTNextApprover;
use App\Services\BTWorkflowService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

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
    protected $includedApproverSetupFields;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->createExclusions = [
            'submissionLog', 'Web_Status_New', 'Requisition | Setup::SubmitterEmail'
        ];

        $this->updateExclustions = [
            'ApprovedBy1', 'ApprovedBy2', 'ApprovedBy3', 'ApprovedBy4', 'ApprovedBy5',
            'ApprovedStatus1', 'ApprovedStatus2', 'ApprovedStatus3', 'ApprovedStatus4', 'ApprovedStatus5',
            'ApprovedDate1', 'ApprovedDate2', 'ApprovedDate3', 'ApprovedDate4', 'ApprovedDate5',
            'ApprovedComments1', 'ApprovedComments2', 'ApprovedComments3', 'ApprovedComments4', 'ApprovedComments5',
            'ApprovedByTE', 'ApprovedStatusTE', 'ApprovedDateTE', 'ApprovedCommentsTE',
            'FinalApprovalFonda', 'FinalApprovedBy', 'FinalApprovedDate', 'FinalApprovedStatus', 'FinalApprovedStatus6Rejected',
            'submissionLog', 'Web_Status_New', 'Requisition | Setup::SubmitterEmail'
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

        $this->includedApproverSetupFields = [
            'Approver', 'ApproverEmail', 'ApproverFName', 'ApproverLName', 'ReceiveEmails', 'SuperUser'
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
        $this->syncApprovers()
            ->syncApproverSetup()
            ->syncWebSetup()
            ->findActiveFMRequisitions()
            ->runImport()
            ->updateApprovals();
    }

    private function findActiveFMRequisitions()
    {
        try{
            $this->requisitions = $this->bt
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
            $exisitngRec = BTRequisition::where('RecID', '=', $requisition['RecID'])->first();
            if ($exisitngRec !== null) {
                $syncTimestamp = \Carbon\Carbon::parse($exisitngRec->lvl_lastSyncDateTime);
                $modTimestamp = \Carbon\Carbon::parse($requisition['zd_ModifiedDateTime']);
                if ($modTimestamp > $syncTimestamp) $this->requisitionBuilder($requisition, $exisitngRec, $this->updateExclustions);
            } else {
                //New requisition is created here...
                $requisition = $this->requisitionBuilder($requisition, new BTRequisition(), $this->createExclusions);

                //... then send initial email to first approver when new requisition is imported.
                if(isset($requisition->Status) && $requisition->Status !== 'Completed' && $requisition->Status !== 'Approved' && $requisition->Status !== 'Rejected'){
                    $email = $this->getNextApproverEmail($requisition);
                    if($this->approverWantsEmail($email)){
                        Mail::to($email)->send(new BTNextApprover($requisition));
                    }
                }
            }

        }
        return $this;
    }

    private function approverWantsEmail($email)
    {
        $approver = BTApproverSetup::where('ApproverEmail', $email)->first();
        if($approver !== null && $approver->ReceiveEmails === "Yes") return true;
        return false;
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
            }
        }
        $rec->lvl_lastSyncDateTime = $this->syncDt;

        $this->deleteExistingReqItems($requisition)->constructRequisitionItems($requisition);
        $rec->save();
        return $rec;
    }

    private function getNextApproverEmail($requisition)
    {
        $approvers = BTApprovers::where('ProjectCode', $requisition->Project)->first();

        if($approvers->Approver1 === "SITELOOKUP") {
            try {
                $site = BTWebSetup::where('SiteNo', $requisition->Site)->first();
            } catch (\Exception $e) {
                return false;
            }
            return $site->ApproverEmail;
        }

        switch($requisition->Status){
            case $approvers->Approver1:
                return $approvers->Approver1Email;
            case $approvers->Approver2:
                return $approvers->Approver2Email;
            case $approvers->Approver3:
                return $approvers->Approver3Email;
            case $approvers->Approver4:
                return $approvers->Approver4Email;
            case $approvers->Approver5:
                return $approvers->Approver5Email;
            case $approvers->ApproverTE:
                return $approvers->ApproverTEEmail;
            case $approvers->ApproverFinal:
                return $approvers->ApproverFinalEmail;
            default:

                $rec = BTApproverSetup::where('Approver', $requisition->Status)->first();
                return $rec->ApproverEmail;

        }
    }


    private function deleteExistingReqItems($requisition)
    {
        BTRequisitionItem::where('zd_RequisRecId', $requisition['RecID'])->delete();
        return $this;
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

    private function syncApproverSetup()
    {
        $approvers = $this->bt
            ->records('Web_ApproverSetup')
            ->limit(10000)
            ->get();

        foreach ($approvers as $recId => $approver) {
            $localRec = BTApproverSetup::where('fm_id', $recId)->first();
            if($localRec === null) $localRec = new BTApproverSetup();
            foreach ($approver as $key=>$value) {
                if(in_array($key, $this->includedApproverSetupFields)){
                    $localRec->{$key} = $value;
                }
            }
            $localRec->fm_id = $recId;
            $localRec->save();
        }
        return $this;
    }

    private function syncWebSetup()
    {
        $setup = $this->bt
            ->records('Web_Setup')
            ->limit(10000)
            ->get();

        BTWebSetup::truncate();
        foreach($setup as $recId => $rec) {
            $localRec = new BTWebSetup();
            foreach ($rec as $key=>$value) {
                $localRec->{$key} = $value;
            }
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

