<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailBidSubmit extends Mailable
{
    use Queueable, SerializesModels;

    public $recipient, $sender, $bid;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($recipient, $sender, $bid)
    {
        $this->recipient = $recipient;
        $this->sender = $sender;
        $this->bid = $bid;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.client.bid_submit')
            ->subject("You've received a new bid");
    }
}
