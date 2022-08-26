<?php

namespace App\Mail;

use App\ATRequisition;
use App\ATEmailTokens;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class ATNextApprover extends Mailable
{
    use Queueable, SerializesModels;

    public $requisition;
    public $emailToken;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ATRequisition $requisition)
    {
        $this->requisition = $requisition;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->generateEmailToken();
        $approvalLink = $this->buildApprovalLink();
        $rejectionLink = $this->buildRejectionLink();
        return $this->subject('Activity Tracker Request for Approval')
            ->from('workflow23@putnamcityschools.org')
            ->view('workflow.mail.atNextApprover')
            ->with([
                'approvalLink'=>$approvalLink,
                'rejectionLink'=>$rejectionLink,
                'btLink'=>config('app.url'). '/staff/ATworkflow'
            ]);
    }

    private function buildApprovalLink()
    {
        $token = $this->emailToken->token;
        return config('app.url')
            . "/atWorkflowApproval/". $token . "/Approved";
    }

    private function buildRejectionLink()
    {
        $token = $this->emailToken->token;
        return config('app.url')
            . "/atWorkflowApproval/". $token . "/Rejected";
    }
    private function generateEmailToken()
    {
        $this->emailToken = new ATEmailTokens();
        $this->emailToken->token = (string) Str::uuid();
        $this->emailToken->requisition_id = $this->requisition->pk;
        $this->emailToken->username = $this->requisition->Status;
        $this->emailToken->is_valid = true;
        $this->emailToken->save();
    }

}
