<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailTenderPost extends Mailable
{
    use Queueable, SerializesModels;

    public $recipient, $sender, $tender;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($recipient, $sender, $tender)
    {
        $this->recipient = $recipient;
        $this->sender = $sender;
        $this->tender = $tender;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin.tender_post')
            ->subject('A new tender was posted');
    }
}
