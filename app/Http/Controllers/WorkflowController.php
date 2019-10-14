<?php

namespace App\Http\Controllers;

use App\BTApprovers;
use App\BTRequisition;
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

    public function requisitionsByApprover($app, $username = null)
    {
        $this->canAccess();
        $username = ($username === null) ?? auth()->user()->username;
        if($app === 'budgetTracker'){
            $activeRequisitions =  BTRequisition::whereNotIn('Status', ['Completed', 'Rejected'])
                ->with('approvers')
                ->get();
        } else {
            $activeRequisitions = null; //@todo: build logic for AT here....
        }

        $requisitionsForThisApprover = $this->filterRequisitionsByApprover($activeRequisitions, $username);

        return response()->json($activeRequisitions);
    }

    public function update($app, $id)
    {

    }

    private function filterRequisitionsByApprover($requisitions, $username)
    {
        return $requisitions->filter(function ($requisition) use ($username) {
            if( $this->checkApprover1($requisition, $username) ||
                $this->checkApprover2($requisition, $username) ||
                $this->checkApprover3($requisition, $username) ||
                $this->checkApprover4($requisition, $username) ||
                $this->checkApprover5($requisition, $username) ||
                $this->checkTEApprover($requisition, $username) ||
                $this->checkFinalApprover($requisition, $username)) {
                return true;
            }
            return false;
        });
    }

    private function currentPositionMatchesUser($requisition, $username)
    {
        $approversRecord = BTApprovers::where('ProjectCode', $requisition->Project)
            ->where('SiteNo', $requisition->Site)
            ->first();
        dd($approversRecord);
    }

    private function checkApprover1($requisition, $username)
    {
        return  $requisition->ApprovedBy1 === "" &&
                $requisition->Approved1Status === "" &&
                $requisition->approvers->Approver1 === $username;

    }

    private function checkApprover2($requisition, $username)
    {
        return  $requisition->ApprovedBy2 === "" &&
            $requisition->Approved2Status === "" &&
            $requisition->approvers->Approver2 === $username;

    }

    private function checkApprover3($requisition, $username)
    {
        return  $requisition->ApprovedBy3 === "" &&
            $requisition->Approved3Status === "" &&
            $requisition->approvers->Approver3 === $username;

    }

    private function checkApprover4($requisition, $username)
    {
        return  $requisition->ApprovedBy4 === "" &&
            $requisition->Approved4Status === "" &&
            $requisition->approvers->Approver4 === $username;

    }

    private function checkApprover5($requisition, $username)
    {
        return  $requisition->ApprovedBy5 === "" &&
            $requisition->Approved5Status === "" &&
            $requisition->approvers->Approver5 === $username;

    }

    private function checkTEApprover($requisition, $username)
    {
        return  $requisition->Technology === "TE" &&
                $requisition->ApprovedByTE === "" &&
                $requisition->ApprovedTEStatus === "" &&
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
