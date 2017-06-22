<?php

namespace App\Listeners;

use App\Events\NewNotificationArrive;
use App\Events\NewUserActivate;
use App\Mail\EmailUserActivate;
use App\Notification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class UserActivateNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewUserActivate  $event
     * @return void
     */
    public function handle(NewUserActivate $event)
    {
        // push a new notification for an activated user. (Notification Type: 9)
        $notification = new Notification;
        $notification->notification_type_id = 9;
        $notification->recipient_id = $event->activate_user->id;
        $notification->sender_id = User::admin()->first()->id;
        $notification->save();

        // Dispatch a new event for new notification arrival. (Broadcast to all recipient)
        event(new NewNotificationArrive($notification));

        // Send an email to an activated user from OSX administrator
        $recipient = User::find($notification->recipient_id);
        $sender = User::find($notification->sender_id);
        $email = new EmailUserActivate($recipient, $sender);
        Mail::to($recipient->email)->send($email);
    }
}
