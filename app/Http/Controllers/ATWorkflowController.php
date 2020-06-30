<?php

namespace App\Http\Controllers;

use App\ATRequisition;
use App\ATEmailTokens;
use App\BTApproverSetup;
use App\Jobs\SyncActivityTrackerJob;
use App\Mail\ATFinal;
use App\Mail\ATNextApprover;
use App\Services\ATWorkflowService;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ATWorkflowController extends Controller
{
    protected $at;
    protected $teApprover;
    protected $teApproverEmail;

    public function __construct(ATWorkflowService $at)
    {
        $this->at = $at;
        $this->teApprover = config('app.at_te_approver');
        $this->teApproverEmail = config('app.at_te_approver_email');
    }

    public function index()
    {
        $this->canAccess();
        $user = auth()->user();
        $workflowUser = BTApproverSetup::where('Approver', $user->uid)->firstOrFail();
        return view('workflow.atIndex', compact('user', 'workflowUser'));
    }

    public function manualSync()
    {
        $this->canAccess();
        SyncActivityTrackerJob::dispatchNow();
        return response()->json(['success']);
    }


    public function requisitionsByApprover($username = null)
    {
        $this->canAccess();
        $username = $username ?? strtolower(auth()->user()->uid);

        $activeRequisitions =  ATRequisition::whereNotIn('Status', ['Approved', 'Rejected'])
            ->get();

        $requisitionsForThisApprover = $this->filterRequisitionsByApprover($activeRequisitions, $username);

        return response()->json($requisitionsForThisApprover);
    }

    public function requisitionsByStatus($status)
    {
        $this->canAccess();

        $requisitions = ATRequisition::where('Status', $status)
            ->get();

        return response()->json($requisitions);
    }

    private function canAccess($bypass = false)
    {
        if($bypass) return $this;
        $groups = json_decode(auth()->user()->groups);
        if(!in_array('workflow_users', $groups) && !in_array('DOTPCAdmin', $groups)) {
            return abort(403, "You are not authorized to view purchase orders.");
        }
        return $this;
    }

    private function filterRequisitionsByApprover($requisitions, $username)
    {
        return $requisitions->filter(function ($requisition) use ($username) {
            return $this->currentPositionMatchesUser($requisition, $username);
        })->flatten(1);
    }

    private function currentPositionMatchesUser($requisition, $username)
    {
        $BaseFilter = $this->checkApprover($requisition, $username) ||
            $this->checkTEApprover($requisition, $username);
        return $BaseFilter && $username === $requisition->Status;
    }

    private function checkApprover($requisition, $username)
    {
        if($this->isRejected($requisition)) return false;
        return  $requisition->ApprovedBy1 === "" &&
            $requisition->ApprovedStatus1 === "" &&
            $requisition->ApproverUsername === $requisition->Status &&
            $requisition->Status === $username;
    }

    private function checkTEApprover($requisition, $username)
    {
        if($this->isRejected($requisition)) return false;
        return  $requisition->Technology === "TE" &&
            $requisition->ApprovedByTE === "" &&
            $requisition->ApprovedStatusTE === "" &&
            $requisition->Status === $username;
    }

    private function isRejected($requisition)
    {
        if($requisition->ApprovedStatus1 === "Rejected") return true;
        if($requisition->ApprovedStatusTE === "Rejected") return true;
        return false;
    }
}
