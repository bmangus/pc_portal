<?php

namespace App\Mail;

use App\BTRequisition;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BTFinal extends Mailable
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
        return $this->subject('BT Purchase Order #' . $this->requisition->PONumber . ' - ' . $this->requisition->Status)
            ->from('workflow23@putnamcityschools.org')
            ->view('workflow.mail.final');
    }
}
