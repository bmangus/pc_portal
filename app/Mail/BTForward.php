<?php

namespace App\Mail;

use App\BTRequisition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BTForward extends Mailable
{
    use Queueable, SerializesModels;

    public $requisition;
    public $path;
    public $senderEmail;
    public $toEmail;
    public $custMessage;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(BTRequisition $requisition, $path, $custMessage, $senderEmail)
    {
        $this->requisition = $requisition;
        $this->path = $path;
        $this->senderEmail = $senderEmail;
        $this->custMessage = $custMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return response()->json($this->message);
        return $this->subject('Budget Tracker PO Forward')
            ->from('workflow21@putnamcityschools.org')
            ->replyTo($this->senderEmail)
            ->view('workflow.mail.forward')
            //->with(['message'=>$this->message])
            ->attach($this->path);
    }
}
