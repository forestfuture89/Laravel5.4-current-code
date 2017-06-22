<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailBidWinner extends Mailable
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
        return $this->view('emails.provider.bid_winner')
            ->subject('Congratulations, a contract has been awarded');
    }
}
