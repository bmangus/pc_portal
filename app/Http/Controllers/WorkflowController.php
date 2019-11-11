<?php

namespace App\Http\Controllers;

use App\BTApprovers;
use App\BTRequisition;
use App\Jobs\SyncBudgetTrackerJob;
use App\Services\BTWorkflowService;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class WorkflowController extends Controller
{

    protected $approvalFields;
    protected $approvalDates;
    protected $bt;

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
            'ApprovedDate1', 'ApprovedDate2', 'ApprovedDate3', 'ApprovedDate4', 'ApprovedDate5'
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

    public function generatePDF($id)
    {
        $po = BTRequisition::where('pk', $id)->with('approvers', 'requisitionItems')->first();

        return view('workflow.pdf', compact('po'));
    }

    public function showPDF($id)
    {
        return response()->streamDownload(function() use ($id){
           echo Browsershot::url(url("/workflow/{$id}/pdf"))
               ->waitUntilNetworkIdle()
               ->windowSize(200,250)
               ->deviceScaleFactor(.5)
               ->format('Letter')
               ->margins(20, 5,20,5)
               ->pdf();
        }, 'test.pdf', ['Content-Type'=>'application/pdf']);
    }

    public function testPDF(Request $request)
    {
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($request->d);
        return $pdf->download('purchase_order.pdf');
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

            $this->sendStatusToFM($requisition);
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
