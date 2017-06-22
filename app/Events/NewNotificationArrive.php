<?php

namespace App\Events;

use App\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewNotificationArrive implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $new_notification;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Notification $notification)
    {
        $this->new_notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('notification');
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $data = [
            'id' => $this->new_notification->id,
            'notification_type_id' => $this->new_notification->notification_type_id,
            'read' => $this->new_notification->read,
            'recipient_id' => $this->new_notification->recipient_id,
            'sender_id' => $this->new_notification->sender_id,
            'content' => $this->new_notification->content(),
            'passedTime' => $this->new_notification->passedTime()
        ];

        return $data;
    }
}
