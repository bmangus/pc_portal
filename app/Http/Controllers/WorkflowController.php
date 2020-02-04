<?php

namespace App\Http\Controllers;

use App\BTApprovers;
use App\BTRequisition;
use App\EmailTokens;
use App\Jobs\SyncBudgetTrackerJob;
use App\Mail\BTNextApprover;
use App\Services\BTWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
            'FinalApprovalFonda', 'FinalApprovedBy', 'FinalApprovedDate', 'FinalApprovedStatus', 'FinalApprovedComments'
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

    public function requisitionAction($app, $id, $status, $username = null)
    {
        $this->canAccess();
        if($app === 'budgetTracker'){
            $requisition = BTRequisition::findOrFail($id);

            if($this->isRejected($requisition)) return response()->json(['error']);

            $username = $username ?? auth()->user()->uid;

            if($this->currentPositionMatchesUser($requisition, $username)){
                $this->setRequisitionStatus($requisition, $status, $username);
            }

            Mail::to('b.mangus@me.com')->send(new BTNextApprover($requisition));
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

    public function update($app, $id)
    {

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

        $this->emailToken = new EmailTokens();
        $this->emailToken->requisition_id = $requisition->pk;
        $this->emailToken->username = $requisition->Status;
        $this->emailToken->token = $this->uuidGen();
        $this->emailToken->save();
        return $requisition->save();
    }

    private function setApproverFields($requisition, $status, $username)
    {
        if($requisition->ApprovedBy1 === "" && $requisition->approvers->Approver1 === $username) {
            $requisition->ApprovedBy1 = $username;
            $requisition->ApprovedStatus1 = $status;
            $requisition->ApprovedDate1 = now();
            $approvers =  $requisition->approvers->Approver2 ?? $requisition->approvers->Approver3 ?? $requisition->approvers->Approver4 ?? $requisition->approvers->Approver5;
            $approvers = (trim($approvers) === "" && $requisition->Technology === "TE") ? $this->getUserFromEmail($requisition->approvers->ApproverTEEmail) : $approvers;
            $requisition->Status = (trim($approvers) === "") ? $this->getUserFromEmail($requisition->approvers->ApproverFinalEmail) : $approvers;
            $requisition->save();
            return $requisition;
        }

        if($requisition->ApprovedBy2 === "" && $requisition->approvers->Approver2 === $username) {
            $requisition->ApprovedBy2 = $username;
            $requisition->ApprovedStatus2 = $status;
            $requisition->ApprovedDate2 = now();
            $approvers =  $requisition->approvers->Approver3 ?? $requisition->approvers->Approver4 ?? $requisition->approvers->Approver5;
            $approvers = (trim($approvers) === "" && $requisition->Technology === "TE") ? $this->getUserFromEmail($requisition->approvers->ApproverTEEmail) : $approvers;
            $requisition->Status = (trim($approvers) === "") ? $this->getUserFromEmail($requisition->approvers->ApproverFinalEmail) : $approvers;
            $requisition->save();
            return $requisition;
        }

        if($requisition->ApprovedBy3 === "" && $requisition->approvers->Approver3 === $username) {
            $requisition->ApprovedBy3 = $username;
            $requisition->ApprovedStatus3 = $status;
            $requisition->ApprovedDate3 = now();
            $approvers = $requisition->approvers->Approver4 ?? $requisition->approvers->Approver5;
            $approvers = (trim($approvers) === "" && $requisition->Technology === "TE") ? $this->getUserFromEmail($requisition->approvers->ApproverTEEmail) : $approvers;
            $requisition->Status = (trim($approvers) === "") ? $this->getUserFromEmail($requisition->approvers->ApproverFinalEmail) : $approvers;
            $requisition->save();
            return $requisition;
        }

        if($requisition->ApprovedBy4 === "" && $requisition->approvers->Approver4 === $username) {
            $requisition->ApprovedBy4 = $username;
            $requisition->ApprovedStatus4 = $status;
            $requisition->ApprovedDate4 = now();
            $approvers = $requisition->approvers->Approver5;
            $approvers = (trim($approvers) === "" && $requisition->Technology === "TE") ? $this->getUserFromEmail($requisition->approvers->ApproverTEEmail) : $approvers;
            $requisition->Status = (trim($approvers) === "") ? $this->getUserFromEmail($requisition->approvers->ApproverFinalEmail) : $approvers;
            $requisition->save();
            return $requisition;
        }

        if($requisition->ApprovedBy5 === "" && $requisition->approvers->Approver5 === $username) {
            $requisition->ApprovedBy5 = $username;
            $requisition->ApprovedStatus5 = $status;
            $requisition->ApprovedDate5 = now();
            $approvers = ($requisition->Technology === "TE") ? $this->getUserFromEmail($requisition->approvers->ApproverTEEmail) : null;
            $requisition->Status = (trim($approvers) === "") ? $this->getUserFromEmail($requisition->approvers->ApproverFinalEmail) : $approvers;
            $requisition->save();
            return $requisition;
        }

        if($requisition->ApprovedByTE === "" && $this->getUserFromEmail($requisition->approvers->ApproverTEEmail) === $username && $requisition->Technology === 'TE') {
            $requisition->ApprovedByTE = $username;
            $requisition->ApprovedStatusTE = $status;
            $requisition->ApprovedDateTE = now();
            $requisition->Status = $this->getUserFromEmail($requisition->approvers->ApproverFinalEmail);
            $requisition->save();
            return $requisition;
        }

        if($requisition->FinalApprovedBy === "" && $this->getUserFromEmail($requisition->approvers->ApproverFinalEmail) === $username) {
            $requisition->FinalApprovedBy = $username;
            $requisition->FinalApprovedStatus = $status;
            $requisition->FinalApprovedDate = now();
            $requisition->Status = ($status === 'Approved') ? 'Completed' : 'Rejected';
            $requisition->save();
            return $requisition;
        }
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
                $requisition->approvers->ApproverTE === $requisition->Status &&
                $requisition->Status === $username;

    }

    private function checkFinalApprover($requisition, $username)
    {
        if($this->isRejected($requisition)) return false;
        return  $requisition->FinalApprovedBy === "" &&
                $requisition->FinalApprovedStatus === "" &&
                $requisition->approvers->ApproverFinal === $requisition->Status &&
                $requisition->Status === $username;
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

    private function uuidGen()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
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
