<?php

namespace App\Listeners;

use App\Events\NewNotificationArrive;
use App\Events\NewTenderPost;
use App\Events\NewUserRegister;
use App\Mail\EmailTenderPost;
use App\Mail\EmailUserRegister;
use App\Notification;
use App\Tender;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class AdminEventSubscriber implements ShouldQueue
{
    /**
     * Handle new user register events.
     */
    public function onNewUserRegister(NewUserRegister $event)
    {
        // push a new notification to admin for a registered user (Notification Type: 8)
        $notification = new Notification;
        $notification->notification_type_id = 8;
        $notification->recipient_id = User::admin()->first()->id;
        $notification->sender_id = $event->new_user->id;
        $notification->save();

        // Dispatch a new event for new notification arrival. (Broadcast to all recipient)
        event(new NewNotificationArrive($notification));

        // Send an email to an OSX administrator for a registered user
        $recipient = User::find($notification->recipient_id);
        $sender = User::find($notification->sender_id);
        $email = new EmailUserRegister($recipient, $sender);
        Mail::to($recipient->email)->send($email);
    }

    /**
     * Handle new tender post events.
     */
    public function onNewTenderPost(NewTenderPost $event)
    {
        // push a new notification to admin for a posted tender (Notification Type: 1)
        $notification = new Notification;
        $notification->notification_type_id = 1;
        $notification->recipient_id = User::admin()->first()->id;
        $notification->sender_id = $event->new_tender->createdBy()->id;
        $notification->tender_id = $event->new_tender->id;
        $notification->save();

        // Dispatch a new event for new notification arrival. (Broadcast to all recipient)
        event(new NewNotificationArrive($notification));

        // Send an email to an OSX administrator for new posted tender
        $recipient = User::find($notification->recipient_id);
        $sender = User::find($notification->sender_id);
        $tender = Tender::find($notification->tender_id);
        $email = new EmailTenderPost($recipient, $sender, $tender);
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
            'App\Events\NewUserRegister',
            'App\Listeners\AdminEventSubscriber@onNewUserRegister'
        );

        $events->listen(
            'App\Events\NewTenderPost',
            'App\Listeners\AdminEventSubscriber@onNewTenderPost'
        );
    }
}
