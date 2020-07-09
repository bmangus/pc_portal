<?php

namespace App\Mail;

use App\ATRequisition;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ATFinal extends Mailable
{
    use Queueable, SerializesModels;

    public $requisition;

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
        return $this->subject('AT Purchase Order #' . $this->requisition->PONumber . ' - ' . $this->requisition->Status)
            ->from('workflow21@putnamcityschools.org')
            ->view('workflow.mail.final');
    }
}
