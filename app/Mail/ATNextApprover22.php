<?php

namespace App\Mail;

use App\ATRequisition22;
use App\ATEmailTokens22;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class ATNextApprover22 extends Mailable
{
    use Queueable, SerializesModels;

    public $requisition;
    public $emailToken;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ATRequisition22 $requisition)
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
            ->from('workflow22@putnamcityschools.org')
            ->view('workflow.mail.atNextApprover')
            ->with([
                'approvalLink'=>$approvalLink,
                'rejectionLink'=>$rejectionLink,
                'btLink'=>config('app.url'). '/staff/22/ATworkflow'
            ]);
    }

    private function buildApprovalLink()
    {
        $token = $this->emailToken->token;
        return config('app.url')
            . "/22/atWorkflowApproval/". $token . "/Approved";
    }

    private function buildRejectionLink()
    {
        $token = $this->emailToken->token;
        return config('app.url')
            . "/22/atWorkflowApproval/". $token . "/Rejected";
    }
    private function generateEmailToken()
    {
        $this->emailToken = new ATEmailTokens22();
        $this->emailToken->token = (string) Str::uuid();
        $this->emailToken->requisition_id = $this->requisition->pk;
        $this->emailToken->username = $this->requisition->Status;
        $this->emailToken->is_valid = true;
        $this->emailToken->save();
    }

}
