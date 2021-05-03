<?php

namespace App\Mail;

use App\ATRequisition22;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ATForward22 extends Mailable
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
    public function __construct(ATRequisition22 $requisition, $path, $custMessage, $senderEmail)
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
        return $this->subject('Activity Tracker PO Forward')
            ->from('workflow22@putnamcityschools.org')
            ->replyTo($this->senderEmail)
            ->view('workflow.mail.forward')
            //->with(['message'=>$this->message])
            ->attach($this->path);
    }
}
