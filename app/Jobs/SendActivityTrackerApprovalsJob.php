<?php

namespace App\Jobs;

use App\ATRequisition;
use App\ATRequisitionItem;
use App\Services\ATWorkflowService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendActivityTrackerApprovalsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $at;
    protected $approvalFields;
    protected $approvalDates;
    protected $activeRequisitions;

    public function __construct()
    {
        $this->approvalFields = [
            'ApprovedBy1', 'ApprovedStatus1', 'ApprovedDate1', 'ApprovedComments1',
            'ApprovedByTE', 'ApprovedStatusTE', 'ApprovedDateTE', 'ApprovedCommentsTE', 'Status',
        ];

        $this->approvalDates = [
            'ApprovedDate1', 'ApprovedDateTE',
        ];
    }

    public function handle(ATWorkflowService $at)
    {
        $this->at = $at;
        $this->getActiveRequisitions()
            ->parseAndUpdate();
    }

    public function getActiveRequisitions()
    {
        try {
            $this->activeRequisitions = collect($this->at
                ->find('Web_Requisition_Approvals')
                ->where('Web_Status_New', 1)
                ->limit(10000)
                ->get());
        } catch (\Exception $e) {
            $this->activeRequisitions = collect();
        }

        return $this;
    }

    public function parseAndUpdate()
    {
        $this->activeRequisitions->each(function ($r) {
            $local = ATRequisition::where('RecID', $r['RecID'])->first();
            if ($local->ApprovedStatus1 !== '') {
                $this->sendRecord($local, $r['zg_recid']);
            }
        });

        return $this;
    }

    public function sendRecord($r, $id)
    {
        $record = $this->constructRec($r);
        $this->at->update('Web_Requisition_Approvals', $record, $id)->exec();
    }

    public function constructRec($r)
    {
        $re = $r->toArray();
        $record = [];
        foreach ($re as $key => $item) {
            if (in_array($key, $this->approvalFields)) {
                if (in_array($key, $this->approvalDates)) {
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
