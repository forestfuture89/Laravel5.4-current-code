<?php

namespace App\Listeners;

use App\Bid;
use App\Events\BidUpdate;
use App\Events\NewBidSubmit;
use App\Events\NewNotificationArrive;
use App\Mail\EmailBidSubmit;
use App\Mail\EmailBidUpdate;
use App\Notification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class ClientEventSubscriber implements ShouldQueue
{
    /**
     * Handle new bid submit events.
     */
    public function onNewBidSubmit(NewBidSubmit $event)
    {
        // push a new notification to tender creator for a submitted bid (Notification Type: 4)
        $notification = new Notification;
        $notification->notification_type_id = 4;
        $notification->recipient_id = $event->new_bid->tender()->createdBy()->id;
        $notification->sender_id = $event->new_bid->author()->id;
        $notification->bid_id = $event->new_bid->id;
        $notification->save();

        // Dispatch a new event for new notification arrival. (Broadcast to all recipient)
        event(new NewNotificationArrive($notification));

        // Send an email to a client for a new submitted bid
        $recipient = User::find($notification->recipient_id);
        $sender = User::find($notification->sender_id);
        $bid = Bid::find($notification->bid_id);
        $email = new EmailBidSubmit($recipient, $sender, $bid);
        Mail::to($recipient->email)->send($email);
    }

    /**
     * Handle bid update events.
     */
    public function onBidUpdate(BidUpdate $event)
    {
        // push a new notification to tender creator for a updated bid (Notification Type: 5)
        $notification = new Notification;
        $notification->notification_type_id = 5;
        $notification->recipient_id = $event->update_bid->tender()->createdBy()->id;
        $notification->sender_id = $event->update_bid->author()->id;
        $notification->bid_id = $event->update_bid->id;
        $notification->save();

        // Dispatch a new event for new notification arrival. (Broadcast to all recipient)
        event(new NewNotificationArrive($notification));

        // Send an email to a client for a updated bid
        $recipient = User::find($notification->recipient_id);
        $sender = User::find($notification->sender_id);
        $bid = Bid::find($notification->bid_id);
        $email = new EmailBidUpdate($recipient, $sender, $bid);
        Mail::to($recipient->email)->send($email);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\NewBidSubmit',
            'App\Listeners\ClientEventSubscriber@onNewBidSubmit'
        );

        $events->listen(
            'App\Events\BidUpdate',
            'App\Listeners\ClientEventSubscriber@onBidUpdate'
        );
    }
}
