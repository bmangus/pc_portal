<?php

namespace App\Jobs;

use App\BTApprovers22;
use App\BTApproverSetup22;
use App\BTRequisition22;
use App\BTRequisitionItem22;
use App\BTWebSetup22;
use App\Mail\BTNextApprover22;
use App\Services\BTWorkflowService22;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SyncBudgetTracker22Job implements ShouldQueue
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
    protected $createdRecIds;
    protected $createdRecItemIds;

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
            'submissionLog', 'Web_Status_New', 'Requisition | Setup::SubmitterEmail', 'Status'
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

        $this->createdRecIds = [];
        $this->createdRecItemIds = [];

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(BTWorkflowService22 $bt)
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
            $exisitngRec = BTRequisition22::where('RecID', '=', $requisition['RecID'])->first();
            if ($exisitngRec !== null) {
                $syncTimestamp = \Carbon\Carbon::parse($exisitngRec->lvl_lastSyncDateTime);
                $modTimestamp = \Carbon\Carbon::parse($requisition['zd_ModifiedDateTime']);
                if ($modTimestamp > $syncTimestamp) $this->requisitionBuilder($requisition, $exisitngRec, $this->updateExclustions);
            } else {
                //New requisition is created here...
                $newRequisition = $this->requisitionBuilder($requisition, new BTRequisition22(), $this->createExclusions);

                //... then send initial email to first approver when new requisition is imported.
                if(isset($newRequisition->Status) && $newRequisition->Status !== 'Completed' && $newRequisition->Status !== 'Approved' && $newRequisition->Status !== 'Rejected'){
                    $email = $this->getNextApproverEmail($newRequisition);
                    if($this->approverWantsEmail($email)){
                        Mail::to($email)->send(new BTNextApprover22($newRequisition));
                    }
                }
            }

        }
        return $this;
    }

    private function approverWantsEmail($email)
    {
        $approver = BTApproverSetup22::where('ApproverEmail', $email)->first();
        if($approver !== null && $approver->ReceiveEmails === "Yes") return true;
        return false;
    }

    private function requisitionBuilder($requisition, $rec, $exclusions)
    {
        if(!in_array($requisition['RecID'], $this->createdRecIds)){
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
            $this->createdRecIds[] = $requisition['RecID'];
        }
        return $rec;
    }

    private function getNextApproverEmail($requisition)
    {
        $approvers = BTApprovers22::where('ProjectCode', $requisition->Project)->first();

        if($approvers->Approver1 === "SITELOOKUP") {
            try {
                $site = BTWebSetup22::where('SiteNo', $requisition->Site)->first();
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

                $rec = BTApproverSetup22::where('Approver', $requisition->Status)->first();
                return $rec->ApproverEmail;

        }
    }


    private function deleteExistingReqItems($requisition)
    {
        BTRequisitionItem22::where('zd_RequisRecId', $requisition['RecID'])->delete();
        return $this;
    }

    private function constructRequisitionItems($requisition)
    {
        try{
            $items = $this->bt
                ->find('Web_ReqItems')
                ->where('zd_RequisRecID', $requisition['RecID'])
                ->limit(500)
                ->get();
        } catch (\Exception $e) {
            $items = [];
        }

        foreach($items as $recordId => $recItem) {
            if(!in_array($recordId, $this->createdRecItemIds)){
                $recId = (string)$recItem['zd_RequisRecID'];
                $requisitionItem = BTRequisitionItem22::where(['zd_RequisRecId'=> $recId, 'fmId' => $recItem['id']])->first();
                if($requisitionItem !== null) {
                    $this->requisitionItemBuilder($requisitionItem, $recItem);
                } else {
                    $this->requisitionItemBuilder(new BTRequisitionItem22(), $recItem);
                }
                $requisitionItem = null;
                $this->createdRecItemIds[] = $recordId;
            }
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
        try{
            $approvers = $this->bt
                ->records('Web_Approvers')
                ->limit(10000)
                ->get();
        } catch(\Exception $e) {
            $approvers = [];
        }


        foreach($approvers as $recId => $approver) {
            $localRec = BTApprovers22::where('fmId', $recId)->first();
            if($localRec === null) $localRec = new BTApprovers22();
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
        try{
            $approvers = $this->bt
                ->records('Web_ApproverSetup')
                ->limit(10000)
                ->get();
        } catch(\Exception $e) {
            $approvers = [];
        }


        foreach ($approvers as $recId => $approver) {
            $localRec = BTApproverSetup22::where('fm_id', $recId)->first();
            if($localRec === null) $localRec = new BTApproverSetup22();
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
        try{
            $setup = $this->bt
                ->records('Web_Setup')
                ->limit(10000)
                ->get();
        } catch(\Exception $e) {
            $setup = [];
        }


        BTWebSetup22::truncate();
        foreach($setup as $recId => $rec) {
            $localRec = new BTWebSetup22();
            foreach ($rec as $key=>$value) {
                $localRec->{$key} = $value;
            }
            $localRec->save();
        }
        return $this;
    }

    private function updateApprovals()
    {
        SendBudgetTrackerApprovals22Job::dispatchNow();
        return $this;
    }

   /* private function deleteDuplicates()
    {
        $deletedRequisitions = DB::delete(
            'delete t1 from b_t_requisitions t1
                    inner join b_t_requisitions t2
                    where
                    t1.pk < t2.pk AND
                    t1.RecID = t2.RecID'
        );

        $deletedRequisitionItems = DB::delete(
            'delete t1 from b_t_requisition_items t1
                    inner join b_t_requisition_items t2
                    where
                    t1.id < t2.id AND
                    t1.RecID = t2.RecID'
        );

        if($deletedRequisitions > 0 || $deletedRequisitionItems > 0){
            return null;
        }
    }*/

}

