<?php

namespace App\Http\Controllers;

use App\BTApprovers;
use App\BTRequisition;
use App\Jobs\SyncBudgetTrackerJob;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{

    public function __construct()
    {

    }

    public function index()
    {
        $this->canAccess();
        $user = auth()->user();
        return view('workflow.index', compact('user'));
    }

    public function manualSync()
    {
        $this->canAccess();
        SyncBudgetTrackerJob::dispatchNow();
        return response()->json(['success']);
    }

    public function requisitionsByApprover($app, $username = null)
    {
        $this->canAccess();
        $username = $username ?? auth()->user()->uid;

        if($app === 'budgetTracker'){
            $activeRequisitions =  BTRequisition::whereNotIn('Status', ['Completed', 'Rejected'])
                ->with('approvers', 'requisitionItems')
                ->get();
        } else {
            $activeRequisitions = null; //@todo: build logic for AT here....
        }

         //dd($activeRequisitions);
        $requisitionsForThisApprover = $this->filterRequisitionsByApprover($activeRequisitions, $username);

        return response()->json($requisitionsForThisApprover);
    }

    public function requisitionsByStatus($app, $status)
    {
        $this->canAccess();

        if($app === 'budgetTracker'){
            $requisitions = BTRequisition::where('Status', $status)
                ->with('approvers', 'requisitionItems')
                ->get();
        } else {
            $requisitions = null; //@todo: build logic for AT here....
        }

        return response()->json($requisitions);
    }

    public function requisitionAction($app, $id, $status, $username = null)
    {
        $this->canAccess();
        if($app === 'budgetTracker'){
            $requisition = BTRequisition::findOrFail($id);

            $username = $username ?? auth()->user()->uid;

            if($this->currentPositionMatchesUser($requisition, $username)){
                $this->setRequisitionStatus($requisition, $status, $username);
            }

        }
        return response()->json(['success']);
    }

    public function update($app, $id)
    {

    }

    private function filterRequisitionsByApprover($requisitions, $username)
    {
        return $requisitions->filter(function ($requisition) use ($username) {
            return $this->checkApprover1($requisition, $username) ||
                $this->checkApprover2($requisition, $username) ||
                $this->checkApprover3($requisition, $username) ||
                $this->checkApprover4($requisition, $username) ||
                $this->checkApprover5($requisition, $username) ||
                $this->checkTEApprover($requisition, $username) ||
                $this->checkFinalApprover($requisition, $username);
        });
    }

    private function currentPositionMatchesUser($requisition, $username)
    {

       return  $this->checkApprover1($requisition, $username) ||
                $this->checkApprover2($requisition, $username) ||
                $this->checkApprover3($requisition, $username) ||
                $this->checkApprover4($requisition, $username) ||
                $this->checkApprover5($requisition, $username) ||
                $this->checkTEApprover($requisition, $username) ||
                $this->checkFinalApprover($requisition, $username);

    }

    private function setRequisitionStatus($requisition, $status, $username)
    {
        $approversRecord = BTApprovers::where('ProjectCode', $requisition->Project)
            ->first();

        if($requisition->ApprovedBy1 === "" && $approversRecord->Approver1 === $username) {
            $requisition->ApprovedBy1 = $username;
            $requisition->ApprovedStatus1 = $status;
            $requisition->ApprovedDate1 = now();
        }

        if($requisition->ApprovedBy2 === "" && $approversRecord->Approver2 === $username) {
            $requisition->ApprovedBy2 = $username;
            $requisition->ApprovedStatus2 = $status;
            $requisition->ApprovedDate2 = now();
        }

        if($requisition->ApprovedBy3 === "" && $approversRecord->Approver3 === $username) {
            $requisition->ApprovedBy3 = $username;
            $requisition->ApprovedStatus3 = $status;
            $requisition->ApprovedDate3 = now();
        }

        if($requisition->ApprovedBy4 === "" && $approversRecord->Approver4 === $username) {
            $requisition->ApprovedBy4 = $username;
            $requisition->ApprovedStatus4 = $status;
            $requisition->ApprovedDate4 = now();
        }

        if($requisition->ApprovedBy5 === "" && $approversRecord->Approver5 === $username) {
            $requisition->ApprovedBy5 = $username;
            $requisition->ApprovedStatus5 = $status;
            $requisition->ApprovedDate5 = now();
        }

        if($requisition->ApprovedByTE === "" && $approversRecord->ApproverTE === $username && $requisition->Technology === 'TE') {
            $requisition->ApprovedByTE = $username;
            $requisition->ApprovedStatusTE = $status;
            $requisition->ApprovedDateTE = now();
        }

        if($requisition->FinalApprovedBy === "" && $approversRecord->ApproverFinal=== $username) {
            $requisition->FinalApprovedBy = $username;
            $requisition->FinalApprovedStatus = $status;
            $requisition->FinalApprovedDate = now();
            $requisition->Status = ($status === 'Approved') ? 'Completed' : 'Rejected';
        }

        $status === 'Rejected' ? $requisition->Status = $status : null;

        return $requisition->save();
    }

    private function checkApprover1($requisition, $username)
    {
        return  $requisition->ApprovedBy1 === "" &&
                $requisition->ApprovedStatus1 === "" &&
                $requisition->approvers->Approver1 === $username;

    }

    private function checkApprover2($requisition, $username)
    {
        return  $requisition->ApprovedBy2 === "" &&
            $requisition->ApprovedStatus2 === "" &&
            $requisition->approvers->Approver2 === $username;

    }

    private function checkApprover3($requisition, $username)
    {
        return  $requisition->ApprovedBy3 === "" &&
            $requisition->ApprovedStatus3 === "" &&
            $requisition->approvers->Approver3 === $username;

    }

    private function checkApprover4($requisition, $username)
    {
        return  $requisition->ApprovedBy4 === "" &&
            $requisition->ApprovedStatus4 === "" &&
            $requisition->approvers->Approver4 === $username;

    }

    private function checkApprover5($requisition, $username)
    {
        return  $requisition->ApprovedBy5 === "" &&
            $requisition->ApprovedStatus5 === "" &&
            $requisition->approvers->Approver5 === $username;

    }

    private function checkTEApprover($requisition, $username)
    {
        return  $requisition->Technology === "TE" &&
                $requisition->ApprovedByTE === "" &&
                $requisition->ApprovedStatusTE === "" &&
                $this->getUserFromEmail($requisition->approvers->ApproverTEEmail) === $username;
    }

    private function checkFinalApprover($requisition, $username)
    {
        return  $requisition->FinalApprovedBy === "" &&
                $requisition->FinalApprovedStatus === "" &&
                $this->getUserFromEmail($requisition->approvers->ApproverFinalEmail) === $username;
    }

    private function getUserFromEmail($email) {
        return substr($email, 0, strpos($email, '@'));
    }

    private function canAccess()
    {
        $groups = json_decode(auth()->user()->groups);
        if(!in_array('workflow_users', $groups) && !in_array('DOTPCAdmin', $groups)) {
            return abort(403, "You are not authorized to view purchase orders.");
        }
        return $this;
    }

    private function verifyApprover()
    {

    }

    private function approve()
    {

    }

    private function reject()
    {

    }

    private function emailNextApprover()
    {

    }

    private function sendFinalEmail()
    {

    }
}
