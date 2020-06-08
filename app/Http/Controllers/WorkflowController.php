<?php

namespace App\Http\Controllers;

use App\BTApprovers;
use App\BTApproverSetup;
use App\BTRequisition;
use App\EmailTokens;
use App\Jobs\SyncBudgetTrackerJob;
use App\Mail\BTFinal;
use App\Mail\BTForward;
use App\Mail\BTNextApprover;
use App\Services\BTWorkflowService;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Dompdf\Options;

class WorkflowController extends Controller
{

    protected $approvalFields;
    protected $approvalDates;
    protected $bt;
    protected $emailToken;

    public function __construct(BTWorkflowService $bt)
    {
        $this->bt = $bt;

        $this->approvalFields = [
            'ApprovedBy1', 'ApprovedBy2', 'ApprovedBy3', 'ApprovedBy4', 'ApprovedBy5',
            'ApprovedStatus1', 'ApprovedStatus2', 'ApprovedStatus3', 'ApprovedStatus4', 'ApprovedStatus5',
            'ApprovedDate1', 'ApprovedDate2', 'ApprovedDate3', 'ApprovedDate4', 'ApprovedDate5',
            'ApprovedComments1', 'ApprovedComments2', 'ApprovedComments3', 'ApprovedComments4', 'ApprovedComments5',
            'ApprovedByTE', 'ApprovedStatusTE', 'ApprovedDateTE', 'ApprovedCommentsTE',
            'FinalApprovalFonda', 'FinalApprovedBy', 'FinalApprovedDate', 'FinalApprovedStatus', 'FinalApprovedComments', 'Status'
        ];

        $this->approvalDates = [
            'ApprovedDate1', 'ApprovedDate2', 'ApprovedDate3', 'ApprovedDate4', 'ApprovedDate5', 'ApprovedDateTE', 'FinalApprovedDate'
        ];
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
        $username = $username ?? strtolower(auth()->user()->uid);

        if($app === 'budgetTracker'){
            $activeRequisitions =  BTRequisition::whereNotIn('Status', ['Completed', 'Rejected'])
                ->get();
        } else {
            $activeRequisitions = null; //@todo: build logic for AT here....
        }

        $requisitionsForThisApprover = $this->filterRequisitionsByApprover($activeRequisitions, $username);

        return response()->json($requisitionsForThisApprover);
    }

    public function requisitionsByStatus($app, $status)
    {
        $this->canAccess();

        if($app === 'budgetTracker'){
            $requisitions = BTRequisition::where('Status', $status)
                ->get();
        } else {
            $requisitions = null; //@todo: build logic for AT here....
        }

        return response()->json($requisitions);
    }

    public function requisitionAction($app, $id, $status, $username = null, $bypassAuth = false)
    {
        $this->canAccess($bypassAuth);
        if($app === 'budgetTracker'){
            $requisition = BTRequisition::findOrFail($id);

            if($this->isRejected($requisition)) return response()->json(['error']);

            $username = $username ?? auth()->user()->uid;

            if($this->currentPositionMatchesUser($requisition, $username)){
                $this->setRequisitionStatus($requisition, $status, $username);
            }


            if($requisition->Status === 'Completed' || $requisition->Status === 'Rejected'){
                Mail::to($requisition->SubmitterEmail)->send(new BTFinal($requisition));
            } else {
                $nextApproverEmail = $this->getNextApproverEmail($requisition);
                if($this->approverWantsEmail($nextApproverEmail)) Mail::to($nextApproverEmail)->send(new BTNextApprover($requisition));
            }

            $this->sendStatusToFM($requisition);
        }
        return response()->json(['success']);
    }

    public function getComments($id)
    {
        $r = BTRequisition::findOrFail($id);

        $commentArray = [
            $r->ApprovedComments1,
            $r->ApprovedComments2,
            $r->ApprovedComments3,
            $r->ApprovedComments4,
            $r->ApprovedComments5,
            $r->ApprovedCommentsTE,
            $r->FinalApprovedComments
        ];

        return response()->json($commentArray);
    }

    public function postComment($id, Request $request)
    {
        $requisition = BTRequisition::findOrFail($id);
        $username = $request->get('username') ?? strtolower(auth()->user()->uid);
        $comment = $request->get('comment');
        if($requisition->Status === $username)
        {
            if($username === $requisition->approvers->Approver1){
                $requisition->ApprovedComments1 = $comment;
            } else if($username === $requisition->approvers->Approver2){
                $requisition->ApprovedComments2 = $comment;
            } else if($username === $requisition->approvers->Approver3){
                $requisition->ApprovedComments3 = $comment;
            } else if($username === $requisition->approvers->Approver4){
                $requisition->ApprovedComments4 = $comment;
            } else if($username === $requisition->approvers->Approver5){
                $requisition->ApprovedComments5 = $comment;
            } else if($username === $this->getUserFromEmail($requisition->approvers->ApproverTEEmail)){
                $requisition->ApprovedCommentsTE = $comment;
            } else if($username === $this->getUserFromEmail($requisition->approvers->ApproverFinalEmail)){
                $requisition->FinalApprovedComments = $comment;
            }
            $requisition->save();
        }
        return response()->json(['success']);
    }

    public function reassign($id, Request $request)
    {
        $requisition = BTRequisition::findOrFail($id);
        $requisition->Reassigned = true;
        $requisition->ReassignedBy = $request->get('from_user') ?? auth()->user()->uid;
        if($this->checkApprover1($requisition, $request->get('from_user'))){
            $requisition->ReassignedPosition = 'Approver1';
        }else if ($this->checkApprover2($requisition, $request->get('from_user'))){
            $requisition->ReassignedPosition = 'Approver2';
        }else if ($this->checkApprover3($requisition, $request->get('from_user'))){
            $requisition->ReassignedPosition = 'Approver3';
        }else if ($this->checkApprover4($requisition, $request->get('from_user'))){
            $requisition->ReassignedPosition = 'Approver4';
        }else if ($this->checkApprover5($requisition, $request->get('from_user'))){
            $requisition->ReassignedPosition = 'Approver5';
        }else if ($this->checkTEApprover($requisition, $request->get('from_user'))){
            $requisition->ReassignedPosition = 'ApproverTE';
        }else{
            $requisition->ReassignedPosition = 'ApproverFinal';
        }
        $requisition->Status = $request->get('to_user');
        $nextApproverEmail = $requisition->Status . '@putnamcityschools.org';
        $requisition->save();

        if($this->approverWantsEmail($nextApproverEmail)) Mail::to($nextApproverEmail)->send(new BTNextApprover($requisition));
        return response()->json(['success']);
    }

    public function getApproverList()
    {
        $approvers = BTApproverSetup::all();
        $approvers->sortBy('ApproverLName');
        return response()->json($approvers->values()->all());
    }


    public function forwardPDF(Request $request)
    {
        ini_set('max_execution_time', 60);
        $po = BTRequisition::findOrFail($request->get('id'));
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('workflow.pdf', compact('po'));
        $path = \Storage::path('/pdfs/'. $po->PONumber . 'pdf');
        $pdf->save($path);
        Mail::to($request->get('recipientEmail'))->send(new BTForward($po, $path, $request->get('custMessage'), $po->Status . '@putnamcityschools.org'));
        return response()->json('ok');
    }


    public function approveFromEmail($app, $token = null, $status = null)
    {

        $tokenRecord = EmailTokens::where('token', $token)->firstOrFail();

        //return response()->json($tokenRecord);

        if($tokenRecord->is_valid){
            $requisition = BTRequisition::findOrFail($tokenRecord->requisition_id);
            $this->requisitionAction($app, $tokenRecord->requisition_id, $status, $tokenRecord->username, true);
            $tokenRecord->is_valid = false;
            $tokenRecord->save();
            return view('workflow.emailApproval')->with(['message'=>"This requisition has been successfully ${status}."]);
        } else {
            return view('workflow.emailApproval')->with(['message'=>'This link is no longer valid.']);
        }
    }

    private function sendStatusToFM($r)
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

        $id = $r->zg_recid;
        $this->bt->update('Web_Requisition_Approvals', $record, $id)->exec();
    }

    private function requisitionById($id)
    {
        return BTRequisition::where('id', $id)
            ->with('approvers', 'requisitionItems')
            ->get();
    }

    private function filterRequisitionsByApprover($requisitions, $username)
    {
        return $requisitions->filter(function ($requisition) use ($username) {
            return $this->currentPositionMatchesUser($requisition, $username);
        })->flatten(1);
    }

    private function currentPositionMatchesUser($requisition, $username)
    {
        $BaseFilter = $this->checkApprover1($requisition, $username) ||
            $this->checkApprover2($requisition, $username) ||
            $this->checkApprover3($requisition, $username) ||
            $this->checkApprover4($requisition, $username) ||
            $this->checkApprover5($requisition, $username) ||
            $this->checkTEApprover($requisition, $username) ||
            $this->checkFinalApprover($requisition, $username);
        return $BaseFilter && $username === $requisition->Status;

    }

    private function setRequisitionStatus($requisition, $status, $username)
    {
        $requisition = $this->setApproverFields($requisition, $status, $username);

        //dd($this->nextApproverHasAlreadyApproved($requisition));
        //Call self to handle situation where the next approver has already approved this requisition in another position (mainly for Cory).
        if($this->nextApproverHasAlreadyApproved($requisition)) {
            //dd($requisition, $status, $requisition->Status);
            $requisition = $this->setApproverFields($requisition, $status, $requisition->Status);
        }

        return $requisition->save();
    }

    private function setApproverFields($requisition, $status, $username)
    {
        if($status === 'Rejected'){
            $requisition->Status = 'Rejected';
        } else if($requisition->Reassigned){
            $position = $requisition->ReassignedPosition;
            $requisition->Reassigned = false;
            if($position === 'Approver1'){
                return $this->setApprover1($requisition, $status, $username);
            }else if($position === 'Approver2'){
                return $this->setApprover2($requisition, $status, $username);
            }else if($position === 'Approver3'){
                return $this->setApprover3($requisition, $status, $username);
            }else if ($position === 'Approver4'){
                return $this->setApprover4($requisition, $status, $username);
            }else if ($position === 'Approver5'){
                return $this->setApprover5($requisition, $status, $username);
            }else if ($position === 'ApproverTE'){
                return $this->setApproverTE($requisition, $status, $username);
            }else {
                return $this->setApproverFinal($requisition, $status, $username);
            }
        }else if($requisition->ApprovedBy1 === "" && $requisition->approvers->Approver1 === $username) {
            return $this->setApprover1($requisition, $status, $username);
        }else if($requisition->ApprovedBy2 === "" && $requisition->approvers->Approver2 === $username) {
            return $this->setApprover2($requisition, $status, $username);
        }else if($requisition->ApprovedBy3 === "" && $requisition->approvers->Approver3 === $username) {
            return $this->setApprover3($requisition, $status, $username);
        }else if($requisition->ApprovedBy4 === "" && $requisition->approvers->Approver4 === $username) {
            return $this->setApprover4($requisition, $status, $username);
        }else if($requisition->ApprovedBy5 === "" && $requisition->approvers->Approver5 === $username) {
            return $this->setApprover5($requisition, $status, $username);
        }else if($requisition->ApprovedByTE === "" && $this->getUserFromEmail($requisition->approvers->ApproverTEEmail) === $username && $requisition->Technology === 'TE') {
            return $this->setApproverTE($requisition, $status, $username);
        }else if($requisition->FinalApprovedBy === "" && $this->getUserFromEmail($requisition->approvers->ApproverFinalEmail) === $username) {
            return $this->setApproverFinal($requisition, $status, $username);
        }

        return $requisition;
    }

    private function setApprover1($requisition, $status, $username)
    {
        $requisition->ApprovedBy1 = $username;
        $requisition->ApprovedStatus1 = $status;
        $requisition->ApprovedDate1 = now();
        $this->getNextApprover($requisition, $status, 'ap1');
        $requisition->save();
        return $requisition;
    }

    private function setApprover2($requisition, $status, $username)
    {
        $requisition->ApprovedBy2 = $username;
        $requisition->ApprovedStatus2 = $status;
        $requisition->ApprovedDate2 = now();
        $this->getNextApprover($requisition, $status, 'ap2');
        $requisition->save();
        return $requisition;
    }

    private function setApprover3($requisition, $status, $username)
    {
        $requisition->ApprovedBy3 = $username;
        $requisition->ApprovedStatus3 = $status;
        $requisition->ApprovedDate3 = now();
        $this->getNextApprover($requisition, $status, 'ap3');
        $requisition->save();
        return $requisition;
    }

    private function setApprover4($requisition, $status, $username)
    {
        $requisition->ApprovedBy4 = $username;
        $requisition->ApprovedStatus4 = $status;
        $requisition->ApprovedDate4 = now();
        $this->getNextApprover($requisition, $status, 'ap4');
        $requisition->save();
        return $requisition;
    }

    private function setApprover5($requisition, $status, $username)
    {
        $requisition->ApprovedBy5 = $username;
        $requisition->ApprovedStatus5 = $status;
        $requisition->ApprovedDate5 = now();
        $this->getNextApprover($requisition, $status, 'ap5');
        $requisition->save();
        return $requisition;
    }

    private function setApproverTE($requisition, $status, $username)
    {
        $requisition->ApprovedByTE = $username;
        $requisition->ApprovedStatusTE = $status;
        $requisition->ApprovedDateTE = now();
        $requisition->Status = $status === 'Rejected' ? 'Rejected' : $this->getUserFromEmail($requisition->approvers->ApproverFinalEmail);
        $requisition->save();
        return $requisition;
    }

    private function setApproverFinal($requisition, $status, $username)
    {
        $requisition->FinalApprovedBy = $username;
        $requisition->FinalApprovedStatus = $status;
        $requisition->FinalApprovedDate = now();
        $requisition->Status = ($status === 'Approved') ? 'Completed' : 'Rejected';
        $requisition->save();
        return $requisition;
    }

    private function getNextApprover($requisition, $status, $position = null)
    {
        if($status === 'Rejected'){
            $requisition->Status = 'Rejected';
        } else {
            switch ($position){
                case 'ap1':
                    $approvers =  $requisition->approvers->Approver2 ?? $requisition->approvers->Approver3 ?? $requisition->approvers->Approver4 ?? $requisition->approvers->Approver5;
                    break;
                case 'ap2':
                    $approvers =  $requisition->approvers->Approver3 ?? $requisition->approvers->Approver4 ?? $requisition->approvers->Approver5;
                    break;
                case 'ap3':
                    $approvers =  $requisition->approvers->Approver4 ?? $requisition->approvers->Approver5;
                    break;
                case 'ap4':
                    $approvers = $requisition->approvers->Approver5;
                    break;
                default:
                    $approvers = "";
                    break;
            }
            $approvers = (trim($approvers) === "" && $requisition->Technology === "TE") ? $this->getUserFromEmail($requisition->approvers->ApproverTEEmail) : $approvers;
            $requisition->Status = (trim($approvers) === "") ? $this->getUserFromEmail($requisition->approvers->ApproverFinalEmail) : $approvers;
        }
        return $requisition;
    }

    private function checkApprover1($requisition, $username)
    {
        if($this->isRejected($requisition)) return false;
        return  $requisition->ApprovedBy1 === "" &&
                $requisition->ApprovedStatus1 === "" &&
                $requisition->approvers->Approver1 === $requisition->Status &&
                $requisition->Status === $username;

    }

    private function checkApprover2($requisition, $username)
    {
        if($this->isRejected($requisition)) return false;
        return  $requisition->ApprovedBy2 === "" &&
            $requisition->ApprovedStatus2 === "" &&
            $requisition->approvers->Approver2 === $requisition->Status &&
            $requisition->Status === $username;

    }

    private function checkApprover3($requisition, $username)
    {
        if($this->isRejected($requisition)) return false;
        return  $requisition->ApprovedBy3 === "" &&
            $requisition->ApprovedStatus3 === "" &&
            $requisition->approvers->Approver3 === $requisition->Status &&
            $requisition->Status === $username;

    }

    private function checkApprover4($requisition, $username)
    {
        if($this->isRejected($requisition)) return false;
        return  $requisition->ApprovedBy4 === "" &&
            $requisition->ApprovedStatus4 === "" &&
            $requisition->approvers->Approver4 === $requisition->Status &&
            $requisition->Status === $username;
    }

    private function checkApprover5($requisition, $username)
    {
        if($this->isRejected($requisition)) return false;
        return  $requisition->ApprovedBy5 === "" &&
            $requisition->ApprovedStatus5 === "" &&
            $requisition->approvers->Approver5 === $requisition->Status &&
            $requisition->Status === $username;
    }

    private function checkTEApprover($requisition, $username)
    {
        if($this->isRejected($requisition)) return false;
        return  $requisition->Technology === "TE" &&
                $requisition->ApprovedByTE === "" &&
                $requisition->ApprovedStatusTE === "" &&
                //$requisition->approvers->ApproverTE === $requisition->Status &&
                $requisition->Status === $username;

    }

    private function checkFinalApprover($requisition, $username)
    {
        if($this->isRejected($requisition)) return false;
        return  $requisition->FinalApprovedBy === "" &&
                $requisition->FinalApprovedStatus === "" &&
                //$requisition->approvers->ApproverFinalEmail === $requisition->Status . '@putnamcityschools.org' &&
                $requisition->Status === $username;
    }

    private function getUserFromEmail($email) {
        return substr($email, 0, strpos($email, '@'));
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



    private function nextApproverHasAlreadyApproved($requisition)
    {
        return $requisition->ApprovedBy1 === $requisition->Status ||
            $requisition->ApprovedBy2 === $requisition->Status ||
            $requisition->ApprovedBy3 === $requisition->Status ||
            $requisition->ApprovedBy4 === $requisition->Status ||
            $requisition->ApprovedBy5 === $requisition->Status ||
            $requisition->ApprovedByTE === $requisition->Status ||
            $requisition->FinalApprovedBy === $requisition->Status;
    }



    private function isRejected($requisition)
    {
        if($requisition->ApprovedStatus1 === "Rejected" || $requisition->ApprovedStatus1 === "Completed" ) return true;
        if($requisition->ApprovedStatus2 === "Rejected" || $requisition->ApprovedStatus2 === "Completed") return true;
        if($requisition->ApprovedStatus3 === "Rejected") return true;
        if($requisition->ApprovedStatus4 === "Rejected") return true;
        if($requisition->ApprovedStatus5 === "Rejected") return true;
        if($requisition->ApprovedStatusTE === "Rejected") return true;
        if($requisition->ApprovedFinalStatus === "Rejected") return true;
        return false;
    }


    private function getNextApproverEmail($requisition)
    {
        $approvers = BTApprovers::where('ProjectCode', $requisition->Project)->first();


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
            case $this->getUserFromEmail($approvers->ApproverTEEmail):
                return $approvers->ApproverTEEmail;
            case $this->getUserFromEmail($approvers->ApproverFinalEmail):
                return $approvers->ApproverFinalEmail;
        }

        return null;

    }

    private function approverWantsEmail($email)
    {
        $approver = BTApproverSetup::where('ApproverEmail', $email)->first();
        if($approver->ReceiveEmails === "Yes") return true;
        return false;
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
