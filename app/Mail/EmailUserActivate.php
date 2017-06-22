<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailUserActivate extends Mailable
{
    use Queueable, SerializesModels;

    public $recipient, $sender;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($recipient, $sender)
    {
        $this->recipient = $recipient;
        $this->sender = $sender;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user_activate')
            ->subject('You\'re in :-)');
    }
}
