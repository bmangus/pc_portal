<?php

namespace App\Http\Controllers;

use App\ATRequisition22;
use App\ATEmailTokens22;
use App\BTApproverSetup22;
use App\BTRequisition22;
use App\Jobs\SyncActivityTracker22Job;
use App\Mail\ATFinal22;
use App\Mail\ATForward22;
use App\Mail\ATNextApprover22;
use App\Services\ATWorkflowService22;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ATWorkflow22Controller extends Controller
{
    protected $at;
    protected $teApprover;
    protected $teApproverEmail;
    protected $approvalFields;
    protected $approvalDates;

    public function __construct(ATWorkflowService22 $at)
    {
        $this->at = $at;
        $this->teApprover = config('app.at_te_approver');
        $this->teApproverEmail = config('app.at_te_approver_email');

        $this->approvalFields = [
            'ApprovedBy1', 'ApprovedStatus1', 'ApprovedDate1', 'ApprovedComments1',
            'ApprovedByTE', 'ApprovedStatusTE', 'ApprovedDateTE', 'ApprovedCommentsTE','Status'
        ];

        $this->approvalDates = [
            'ApprovedDate1', 'ApprovedDateTE'
        ];
    }

    public function index()
    {
        $this->canAccess();
        $user = auth()->user();
        $workflowUser = BTApproverSetup22::where('Approver', $user->uid)->firstOrFail();
        return view('workflow.atIndex22', compact('user', 'workflowUser'));
    }

    public function manualSync()
    {
        $this->canAccess();
        SyncActivityTracker22Job::dispatchNow();
        return response()->json(['success']);
    }


    public function requisitionsByApprover($username = null)
    {
        $this->canAccess();
        $username = $username ?? strtolower(auth()->user()->uid);

        $activeRequisitions =  ATRequisition22::whereNotIn('Status', ['Approved', 'Rejected'])
            ->get();

        $requisitionsForThisApprover = $this->filterRequisitionsByApprover($activeRequisitions, $username);

        return response()->json($requisitionsForThisApprover);
    }

    public function requisitionsByStatus($status)
    {
        $this->canAccess();

        $requisitions = ATRequisition22::where('Status', $status)
            ->get();

        return response()->json($requisitions);
    }

    public function requisitionAction($id, $status, $username = null, $bypassAuth = false)
    {
        $this->canAccess($bypassAuth);

        $requisition = ATRequisition22::findOrFail($id);

        if($this->isRejected($requisition)) return response()->json(['error']);

        //get passed in user or assign to current logged in user
        $username = $username ?? strtolower(auth()->user()->uid);

        //reassign if the logged in user is not the same as the passed in user
        //if($username !== strtolower(auth()->user()->uid)){
        //    $this->reassignRequisition($id, $username, strtolower(auth()->user()->uid), true, $requisition);
        //}

        //set to current logged in user
        //$username = strtolower(auth()->user()->uid);

        if($this->currentPositionMatchesUser($requisition, $username)){
            $this->setRequisitionStatus($requisition, $status, $username);
        }


        if($requisition->Status === 'Approved' || $requisition->Status === 'Rejected'){
            Mail::to($requisition->SubmitterEmail)->send(new ATFinal22($requisition));
        } else {
            $nextApproverEmail = $this->getNextApproverEmail($requisition);
            if($this->approverWantsEmail($nextApproverEmail)) Mail::to($nextApproverEmail)->send(new ATNextApprover22($requisition));
        }

        $this->sendStatusToFM($requisition);
        return response()->json(['success']);
    }

    public function approveFromEmail($token = null, $status = null)
    {

        $tokenRecord = ATEmailTokens22::where('token', $token)->firstOrFail();

        //return response()->json($tokenRecord);

        if($tokenRecord->is_valid){
            $requisition = ATRequisition22::findOrFail($tokenRecord->requisition_id);
            $ponum = $requisition->PONumber;
            $this->requisitionAction($tokenRecord->requisition_id, $status, $tokenRecord->username, true);
            $tokenRecord->is_valid = false;
            $tokenRecord->save();
            return view('workflow.emailApproval')->with(['message'=>"PO #${ponum} requisition has been successfully ${status}.", 'valid'=>1, 'status' =>$status, 'ponum'=>$ponum]);
        } else {
            return view('workflow.emailApproval')->with(['message'=>'This link is no longer valid.', 'valid'=>0]);
        }
    }

    public function postComment($id, Request $request)
    {
        $requisition = ATRequisition22::findOrFail($id);
        $username = $request->get('username') ?? strtolower(auth()->user()->uid);
        $comment = $request->get('comment');
        if($requisition->Status === $username)
        {
            if($username === $requisition->ApproverUsername || ($requisition->Reassigned && $requisition->ReassignedPosition === "Approver1")){
                $requisition->ApprovedComments1 .= $comment;
            } else if($username === $this->teApprover || ($requisition->Reassigned && $requisition->ReassignedPosition === "ApproverTE")){
                $requisition->ApprovedCommentsTE = $comment;
            } else {
                $requisition->ApprovedCommentsTE .= 'Unknown approver position comments added to TE approver comments field ... ' . $comment;
            }
            $requisition->save();
        }
        return response()->json(['success']);
    }

    public function viewPDF($id)
    {

        ini_set('max_execution_time', 60);
        $po = ATRequisition22::findOrFail($id);

        //return response()->json($po);
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('workflow.atpdf', compact('po'));
        return $pdf->stream();
    }

    public function forwardPDF(Request $request)
    {
        $po = ATRequisition22::findOrFail($request->get('id'));
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('workflow.atpdf', compact('po'));
        $path = \Storage::path('/pdfs/'. $po->PONumber . '.pdf');
        $pdf->save($path);
        Mail::to($request->get('recipientEmail'))->send(new ATForward22($po, $path, $request->get('custMessage'), $po->Status . '@putnamcityschools.org'));
        return response()->json('ok');
    }

    public function setRequisitionStatus($requisition, $status, $username)
    {
        $requisition = $this->setApproverFields($requisition, $status, $username);

        //Call self to handle situation where the next approver has already approved this requisition in another position (mainly for Cory).
        if($this->nextApproverHasAlreadyApproved($requisition)) {
            $requisition = $this->setApproverFields($requisition, $status, $requisition->Status);
        }

        return $requisition->save();
    }

    private function setApproverFields($requisition, $status, $username)
    {
        if($status === 'Rejected'){
            $requisition->Status = 'Rejected';
        }
        if($requisition->ApprovedBy1 === "" && $requisition->ApproverUsername === $username) {
            return $this->setApprover1($requisition, $status, $username);
        }else if($requisition->ApprovedByTE === "" && $requisition->Technology === 'TE') {
            return $this->setApproverTE($requisition, $status, $username);
        }
        return $requisition;
    }

    private function setApprover1($requisition, $status, $username)
    {
        $requisition->ApprovedBy1 = $username;
        $requisition->ApprovedStatus1 = $status;
        $requisition->ApprovedDate1 = now()->format('m/d/Y');
        if ($status === "Rejected") {
            $requisition->Status = "Rejected";
        } else if($requisition->Technology === "TE"){
            $requisition->Status = $this->teApprover;
        } else {
            $requisition->Status = 'Approved';
        }
        $requisition->save();
        return $requisition;
    }

    private function setApproverTE($requisition, $status, $username)
    {
        $requisition->ApprovedByTE = $username;
        $requisition->ApprovedStatusTE = $status;
        $requisition->ApprovedDateTE = now()->format('m/d/Y');
        $requisition->Status = $status === 'Rejected' ? 'Rejected' : 'Approved';
        $requisition->save();
        return $requisition;
    }

    private function nextApproverHasAlreadyApproved($requisition)
    {
        return $requisition->ApprovedBy1 === $requisition->Status ||
            $requisition->ApprovedByTE === $requisition->Status;
    }

    private function getNextApproverEmail($requisition)
    {
        if($requisition->ApprovedBy1 === "") {
            return $requisition->ApproverEmail;
        }else if($requisition->Technology === "TE") {
            return $this->teApproverEmail;
        }
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
        $this->at->update('Web_Requisition_Approvals', $record, $id)->exec();
    }

    private function approverWantsEmail($email)
    {
        $approver = BTApproverSetup22::where('ApproverEmail', $email)->first();
        if($approver !== null && $approver->ReceiveEmails === "Yes") return true;
        return false;
    }
}
