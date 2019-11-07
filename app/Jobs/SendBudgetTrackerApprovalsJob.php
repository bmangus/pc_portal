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

class SendBudgetTrackerApprovalsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bt;
    protected $approvalFields;
    protected $approvalDates;
    protected $activeRequisitions;


    public function __construct()
    {

        $this->approvalFields = [
            'ApprovedBy1', 'ApprovedBy2', 'ApprovedBy3', 'ApprovedBy4', 'ApprovedBy5',
            'ApprovedStatus1', 'ApprovedStatus2', 'ApprovedStatus3', 'ApprovedStatus4', 'ApprovedStatus5',
            'ApprovedDate1', 'ApprovedDate2', 'ApprovedDate3', 'ApprovedDate4', 'ApprovedDate5',
            'ApprovedComments1', 'ApprovedComments2', 'ApprovedComments3', 'ApprovedComments4', 'ApprovedComments5',
            'ApprovedByTE', 'ApprovedStatusTE', 'ApprovedDateTE', 'ApprovedCommentsTE',
            'FinalApprovalFonda', 'FinalApprovedBy', 'FinalApprovedDate', 'FinalApprovedStatus', 'FinalApprovedComments'
        ];

        $this->approvalDates = [
            'ApprovedDate1', 'ApprovedDate2', 'ApprovedDate3', 'ApprovedDate4', 'ApprovedDate5'
        ];
    }

    public function handle(BTWorkflowService $bt)
    {
        $this->bt = $bt;
        $this->getActiveRequisitions()
            ->parseAndUpdate();
    }

    public function getActiveRequisitions()
    {
        $this->activeRequisitions = $this->bt
            ->find('Web_Requisition_Approvals')
            ->where('Web_Status_New', 1)
            ->limit(10000)
            ->get();

        return $this;
    }

    public function parseAndUpdate()
    {
        $this->activeRequisitions->each(function($r){
            $local = BTRequisition::where('RecID', $r->RecID)->first();
            if($local->ApprovedStatus1 !== ""){
                $this->sendRecord($local, $r->zg_recid);
            }
        });

        return $this;
    }

    public function sendRecord($r, $id)
    {
        $record = $this->constructRec($r);
        $this->bt->update('Web_Requisition_Approvals', $record, $id)->exec();
    }

    public function constructRec($r)
    {
        $re = $r->toArray();
        $record = [];
        foreach ($re as $key => $item) {
            if (in_array($key, $this->approvalFields)) {
                if(in_array($key, $this->approvalDates)){
                    //dd($item);
                    $record[$key] = ($item != null) ? \Carbon\Carbon::parse($item)->format('m/d/Y') : null;
                } else {
                    $record[$key] = $item;
                }
            }
        }
        return $record;
    }
}
