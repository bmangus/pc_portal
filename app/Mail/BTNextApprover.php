<?php

namespace App\Mail;

use App\BTRequisition;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BTNextApprover extends Mailable
{
    use Queueable, SerializesModels;

    public $requisition;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(BTRequisition $requisition)
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
        return $this->from('workflow@putnamcityschools.org')
            ->view('workflow.mail.nextApprover')
            ->with([
                'approvalLink'=>'http://somewhere.com',
                'btLink'=>'http://somewhereelse.com'
            ]);
    }
}
