<?php

namespace App\Http\Controllers;

use App\ATRequisition22;
use App\BTApprovers22;
use App\BTApproverSetup22;
use App\BTRequisition22;
use App\BTWebSetup22;
use App\EmailTokens22;
use App\Jobs\SyncBudgetTracker22Job;
use App\Mail\BTFinal22;
use App\Mail\BTForward22;
use App\Mail\BTNextApprover22;
use App\Services\BTWorkflowService22;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class Workflow22Controller extends Controller
{

    protected $approvalFields;
    protected $approvalDates;
    protected $bt;

    public function __construct(BTWorkflowService22 $bt)
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
        $all = BTApproverSetup22::first()->get();
        //dd($all);
        try{
            $workflowUser = BTApproverSetup22::where('Approver', $user->uid)->firstOrFail();
        } catch(\Exception $e){
            if($user->uid === 'bmangus'){
                return view('workflow.index', compact('user', 'workflowUser'));
            }
            return abort(403, 'You do not have access to Workflow. Please contact IT for assistance.');
        }

        return view('workflow.index22', compact('user', 'workflowUser'));
    }

    public function getCounts($username = null){
        $this->canAccess();
        $username = $username ?? strtolower(auth()->user()->uid);
        $btCount = BTRequisition22::where('Status', $username)->get()->count();
        $atCount = ATRequisition22::where('Status', $username)->get()->count();
        return response()->json(['btCount'=>$btCount, 'atCount'=>$atCount]);
    }

    public function manualSync()
    {
        $this->canAccess();
        SyncBudgetTracker22Job::dispatchNow();
        return response()->json(['success']);
    }


    public function requisitionsByApprover($app, $username = null)
    {
        $this->canAccess();
        $username = $username ?? strtolower(auth()->user()->uid);

        if($app === 'budgetTracker'){
            $activeRequisitions =  BTRequisition22::whereNotIn('Status', ['Approved', 'Rejected'])
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
            $requisitions = BTRequisition22::where('Status', $status)
                ->get();
        } else {
            $requisitions = null; //@todo: build logic for AT here....
        }

        return response()->json($requisitions);
    }

    public function requisitionsByUserStatus($status, $username = null)
    {
        $this->canAccess();

        $username = $username ?? auth()->user()->uid;

        $requisitions = BTRequisition22::wasApprover($username)
            ->where('Status', $status)
            ->get();

        return response()->json($requisitions);
    }

    public function requisitionAction($app, $id, $status, $username = null, $bypassAuth = false)
    {
        $this->canAccess($bypassAuth);
        if($app === 'budgetTracker'){

            $requisition = BTRequisition22::findOrFail($id);

            if($this->isRejected($requisition)) return response()->json(['error']);

            //get passed in user or assign to current logged in user
            $username = $username ?? strtolower(auth()->user()->uid);

            //reassign if the logged in user is not the same as the passed in user
            if(!$bypassAuth && $username !== strtolower(auth()->user()->uid)){
                $this->reassignRequisition($id, $username, strtolower(auth()->user()->uid), true, $requisition);
            }

            //set to current logged in user
            if(!$bypassAuth){
                $username = strtolower(auth()->user()->uid);
            }

            if($this->currentPositionMatchesUser($requisition, $username)){
                $this->setRequisitionStatus($requisition, $status, $username);
            }


            if($requisition->Status === 'Approved' || $requisition->Status === 'Rejected'){
                Mail::to($requisition->SubmitterEmail)->send(new BTFinal22($requisition));
            } else {
                $nextApproverEmail = $this->getNextApproverEmail($requisition);
                if($this->approverWantsEmail($nextApproverEmail)) Mail::to($nextApproverEmail)->send(new BTNextApprover22($requisition));
            }

            $this->sendStatusToFM($requisition);
        }
        return response()->json(['success']);
    }

    public function getComments($id)
    {
        $r = BTRequisition22::findOrFail($id);

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
        $requisition = BTRequisition22::findOrFail($id);
        $username = $request->get('username') ?? strtolower(auth()->user()->uid);
        $comment = $request->get('comment');
        if($requisition->Status === $username)
        {
            if($username === $requisition->approvers->Approver1 || ($requisition->approvers->Approver1 === "SITELOOKUP" && $this->isSiteApprover($requisition, $username)) || ($requisition->Reassigned && $requisition->ReassignedPosition === "Approver1")){
                $requisition->ApprovedComments1 = $comment;
            } else if($username === $requisition->approvers->Approver2 || ($requisition->Reassigned && $requisition->ReassignedPosition === "Approver2")){
                $requisition->ApprovedComments2 = $comment;
            } else if($username === $requisition->approvers->Approver3 || ($requisition->Reassigned && $requisition->ReassignedPosition === "Approver3")){
                $requisition->ApprovedComments3 = $comment;
            } else if($username === $requisition->approvers->Approver4 || ($requisition->Reassigned && $requisition->ReassignedPosition === "Approver4")){
                $requisition->ApprovedComments4 = $comment;
            } else if($username === $requisition->approvers->Approver5 || ($requisition->Reassigned && $requisition->ReassignedPosition === "Approver5")){
                $requisition->ApprovedComments5 = $comment;
            } else if($username === $requisition->approvers->ApproverTE || ($requisition->Reassigned && $requisition->ReassignedPosition === "ApproverTE")){
                $requisition->ApprovedCommentsTE = $comment;
            } else if($username === $requisition->approvers->ApproverFinal || ($requisition->Reassigned && $requisition->ReassignedPosition === "ApproverFinal")){
                $requisition->FinalApprovedComments .= $comment;
            } else {
                $requisition->FinalApprovedComments .= 'Unknown approver position comments added to final approver comments field ... ' . $comment;
            }
            $requisition->save();
        }
        return response()->json(['success']);
    }

    public function getCurrentPositionComment($id, $username = null)
    {
        $requisition = BTRequisition22::findOrFail($id);
        $username = $username ?? strtolower(auth()->user()->uid);
        if($requisition->Status === $username){
            if($username === $requisition->approvers->Approver1 || ($requisition->approvers->Approver1 === "SITELOOKUP" && $this->isSiteApprover($requisition, $username)) || ($requisition->Reassigned && $requisition->ReassignedPosition === "Approver1")){
                $comment = $requisition->ApprovedComments1;
            } else if($username === $requisition->approvers->Approver2 || ($requisition->Reassigned && $requisition->ReassignedPosition === "Approver2")){
                $comment = $requisition->ApprovedComments2;
            } else if($username === $requisition->approvers->Approver3 || ($requisition->Reassigned && $requisition->ReassignedPosition === "Approver3")){
                $comment = $requisition->ApprovedComments3;
            } else if($username === $requisition->approvers->Approver4 || ($requisition->Reassigned && $requisition->ReassignedPosition === "Approver4")){
                $comment = $requisition->ApprovedComments4;
            } else if($username === $requisition->approvers->Approver5 || ($requisition->Reassigned && $requisition->ReassignedPosition === "Approver5")){
                $comment = $requisition->ApprovedComments5;
            } else if($username === $requisition->approvers->ApproverTE || ($requisition->Reassigned && $requisition->ReassignedPosition === "ApproverTE")){
                $comment = $requisition->ApprovedCommentsTE;
            } else if($username === $requisition->approvers->ApproverFinal || ($requisition->Reassigned && $requisition->ReassignedPosition === "ApproverFinal")){
                $comment = $requisition->FinalApprovedComments;
            } else {
                $comment = "";
            }
        }
        return response()->json(['comment'=>$comment]);
    }

    public function reassign($id, Request $request)
    {
        $this->reassignRequisition($id, $request->get('from_user'), $request->get('to_user'), false);
        return response()->json(['success']);
    }

    public function getApproverList()
    {
        $approvers = BTApproverSetup22::all();
        $approvers->sortBy('ApproverLName');
        return response()->json($approvers->values()->all());
    }



    public function viewPDF($id)
    {

        ini_set('max_execution_time', 60);
        $po = BTRequisition22::findOrFail($id);

        //return response()->json($po);
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('workflow.pdf', compact('po'));
        return $pdf->stream();
    }

    public function forwardPDF(Request $request)
    {
        $po = BTRequisition22::findOrFail($request->get('id'));
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('workflow.pdf', compact('po'));
        $path = \Storage::path('/pdfs/'. $po->PONumber . '.pdf');
        $pdf->save($path);
        Mail::to($request->get('recipientEmail'))->send(new BTForward22($po, $path, $request->get('custMessage'), $po->Status . '@putnamcityschools.org'));
        return response()->json('ok');
    }


    public function approveFromEmail($app, $token = null, $status = null)
    {

        $tokenRecord = EmailTokens22::where('token', $token)->firstOrFail();

        //return response()->json($tokenRecord);

        if($tokenRecord->is_valid){
            $requisition = BTRequisition22::findOrFail($tokenRecord->requisition_id);
            $ponum = $requisition->PONumber;
            $this->requisitionAction($app, $tokenRecord->requisition_id, $status, $tokenRecord->username, true);
            $tokenRecord->is_valid = false;
            $tokenRecord->save();
            return view('workflow.emailApproval')->with(['message'=>"PO #${ponum} requisition has been successfully ${status}.", 'valid'=>1, 'status' =>$status, 'ponum'=>$ponum]);
        } else {
            return view('workflow.emailApproval')->with(['message'=>'This link is no longer valid.', 'valid'=>0]);
        }
    }

    private function reassignRequisition($id, $from_user, $to_user, $shouldBypassEmail, $requisition = null)
    {
        $requisition = $requisition ?? BTRequisition22::findOrFail($id);
        $requisition->Reassigned = true;
        $requisition->ReassignedBy = $from_user ?? auth()->user()->uid;
        if($this->checkApprover1($requisition, $from_user)){
            $requisition->ReassignedPosition = 'Approver1';
        }else if ($this->checkApprover2($requisition, $from_user)){
            $requisition->ReassignedPosition = 'Approver2';
        }else if ($this->checkApprover3($requisition, $from_user)){
            $requisition->ReassignedPosition = 'Approver3';
        }else if ($this->checkApprover4($requisition, $from_user)){
            $requisition->ReassignedPosition = 'Approver4';
        }else if ($this->checkApprover5($requisition, $from_user)){
            $requisition->ReassignedPosition = 'Approver5';
        }else if ($this->checkTEApprover($requisition, $from_user)){
            $requisition->ReassignedPosition = 'ApproverTE';
        }else{
            $requisition->ReassignedPosition = 'ApproverFinal';
        }
        $requisition->Status = $to_user;
        $nextApproverEmail = $to_user . '@putnamcityschools.org';
        $requisition->save();

        $this->sendStatusToFM($requisition);

        if(!$shouldBypassEmail && $this->approverWantsEmail($nextApproverEmail)) Mail::to($nextApproverEmail)->send(new BTNextApprover22($requisition));
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
        return BTRequisition22::where('id', $id)
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

    public function setRequisitionStatus($requisition, $status, $username)
    {
        $requisition = $this->setApproverFields($requisition, $status, $username);

        //dd($requisition);
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
        }
        if($requisition->Reassigned){
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
        }else if($requisition->ApprovedBy1 === "") {
            if($requisition->approvers->Approver1 === $username ||  ($requisition->approvers->Approver1 === "SITELOOKUP" && $this->isSiteApprover($requisition, $username)) ){
                return $this->setApprover1($requisition, $status, $username);
            }
        }else if($requisition->ApprovedBy2 === "" && $requisition->approvers->Approver2 === $username) {
            return $this->setApprover2($requisition, $status, $username);
        }else if($requisition->ApprovedBy3 === "" && $requisition->approvers->Approver3 === $username) {
            return $this->setApprover3($requisition, $status, $username);
        }else if($requisition->ApprovedBy4 === "" && $requisition->approvers->Approver4 === $username) {
            return $this->setApprover4($requisition, $status, $username);
        }else if($requisition->ApprovedBy5 === "" && $requisition->approvers->Approver5 === $username) {
            return $this->setApprover5($requisition, $status, $username);
        }else if($requisition->ApprovedByTE === "" && $requisition->approvers->ApproverTE === $username && $requisition->Technology === 'TE') {
            return $this->setApproverTE($requisition, $status, $username);
        }else if($requisition->FinalApprovedBy === "" && $requisition->approvers->ApproverFinal === $username) {
            return $this->setApproverFinal($requisition, $status, $username);
        }

        return $requisition;
    }

    private function setApprover1($requisition, $status, $username)
    {
        $requisition->ApprovedBy1 = $username;
        $requisition->ApprovedStatus1 = $status;
        $requisition->ApprovedDate1 = now()->format('m/d/Y');
        $this->getNextApprover($requisition, $status, 'ap1');
        $requisition->save();
        return $requisition;
    }

    private function setApprover2($requisition, $status, $username)
    {
        $requisition->ApprovedBy2 = $username;
        $requisition->ApprovedStatus2 = $status;
        $requisition->ApprovedDate2 = now()->format('m/d/Y');
        $this->getNextApprover($requisition, $status, 'ap2');
        $requisition->save();
        return $requisition;
    }

    private function setApprover3($requisition, $status, $username)
    {
        $requisition->ApprovedBy3 = $username;
        $requisition->ApprovedStatus3 = $status;
        $requisition->ApprovedDate3 = now()->format('m/d/Y');
        $this->getNextApprover($requisition, $status, 'ap3');
        $requisition->save();
        return $requisition;
    }

    private function setApprover4($requisition, $status, $username)
    {
        $requisition->ApprovedBy4 = $username;
        $requisition->ApprovedStatus4 = $status;
        $requisition->ApprovedDate4 = now()->format('m/d/Y');
        $this->getNextApprover($requisition, $status, 'ap4');
        $requisition->save();
        return $requisition;
    }

    private function setApprover5($requisition, $status, $username)
    {
        $requisition->ApprovedBy5 = $username;
        $requisition->ApprovedStatus5 = $status;
        $requisition->ApprovedDate5 = now()->format('m/d/Y');
        $this->getNextApprover($requisition, $status, 'ap5');
        $requisition->save();
        return $requisition;
    }

    private function setApproverTE($requisition, $status, $username)
    {
        $requisition->ApprovedByTE = $username;
        $requisition->ApprovedStatusTE = $status;
        $requisition->ApprovedDateTE = now()->format('m/d/Y');
        $requisition->Status = $status === 'Rejected' ? 'Rejected' : $requisition->approvers->ApproverFinal;
        $requisition->save();
        return $requisition;
    }

    private function setApproverFinal($requisition, $status, $username)
    {
        $requisition->FinalApprovedBy = $username;
        $requisition->FinalApprovedStatus = $status;
        $requisition->FinalApprovedDate = now()->format('m/d/Y');
        $requisition->Status = ($status === 'Approved') ? 'Approved' : 'Rejected';
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
            $approvers = (trim($approvers) === "" && $requisition->Technology === "TE") ? $requisition->approvers->ApproverTE : $approvers;
            $requisition->Status = (trim($approvers) === "") ? $requisition->approvers->ApproverFinal : $approvers;
        }
        return $requisition;
    }

    private function checkApprover1($requisition, $username)
    {
        if($this->isRejected($requisition)) return false;
        if($requisition->approvers->Approver1 === "SITELOOKUP"){
            try {
                $site = BTWebSetup22::where('SiteNo', $requisition->Site)->first();
            } catch (\Exception $e) {
                return false;
            }
            $approver = $site->ApproverUsername;
        } else {
            $approver = $requisition->approvers->Approver1;
        }
        return  $requisition->ApprovedBy1 === "" &&
            $requisition->ApprovedStatus1 === "" &&
            $approver === $requisition->Status &&
            $requisition->Status === $username;

    }

    private function isSiteApprover($requisition, $username)
    {
        try {
            $site = BTWebSetup22::where('SiteNo', $requisition->Site)->first();
        } catch (\Exception $e) {
            return false;
        }
        return $site->ApproverUsername === $username;
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
        if($requisition->ApprovedStatus1 === "Rejected") return true;
        if($requisition->ApprovedStatus2 === "Rejected") return true;
        if($requisition->ApprovedStatus3 === "Rejected") return true;
        if($requisition->ApprovedStatus4 === "Rejected") return true;
        if($requisition->ApprovedStatus5 === "Rejected") return true;
        if($requisition->ApprovedStatusTE === "Rejected") return true;
        if($requisition->ApprovedFinalStatus === "Rejected") return true;
        return false;
    }


    private function getNextApproverEmail($requisition)
    {
        $approvers = BTApprovers22::where('ProjectCode', $requisition->Project)->first();


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
        }

        return null;

    }

    private function approverWantsEmail($email)
    {
        $approver = BTApproverSetup22::where('ApproverEmail', $email)->first();
        if($approver !== null && $approver->ReceiveEmails === "Yes") return true;
        return false;
    }

}
