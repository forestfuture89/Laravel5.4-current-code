<?php

namespace App\Events;

use App\Bid;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewBidSubmit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $new_bid;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Bid $bid)
    {
        $this->new_bid = $bid;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
