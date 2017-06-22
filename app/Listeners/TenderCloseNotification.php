<?php

namespace App\Listeners;

use App\Events\NewNotificationArrive;
use App\Events\TenderClose;
use App\Mail\EmailTenderClose;
use App\Notification;
use App\Tender;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class TenderCloseNotification implements ShouldQueue
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
     * @param  TenderClose  $event
     * @return void
     */
    public function handle(TenderClose $event)
    {
        // push a new notification to all users for a closed tender (Notification Type: 2)
        $users = User::active()->get();
        foreach ($users as $user) {
            if ($event->close_tender->awarded_at == null) {
                $notification = new Notification;
                $notification->notification_type_id = 2;
                $notification->recipient_id = $user->id;
                $notification->sender_id = $event->close_tender->createdBy()->id;
                $notification->tender_id = $event->close_tender->id;
                $notification->save();

                // Dispatch a new event for new notification arrival. (Broadcast to all recipient)
                event(new NewNotificationArrive($notification));

                // Send an email to all active users for a closed tender without any contract
                $recipient = User::find($notification->recipient_id);
                $sender = User::find($notification->sender_id);
                $tender = Tender::find($notification->tender_id);
                $email = new EmailTenderClose($recipient, $sender, $tender);
                Mail::to($recipient->email)->send($email);
            } else {
                if ($user->company()->isClient() || $user->admin == 1) {
                    $notification = new Notification;
                    $notification->notification_type_id = 2;
                    $notification->recipient_id = $user->id;
                    $notification->sender_id = $event->close_tender->createdBy()->id;
                    $notification->tender_id = $event->close_tender->id;
                    $notification->save();

                    // Dispatch a new event for new notification arrival. (Broadcast to all recipient)
                    event(new NewNotificationArrive($notification));

                    // Send an email to all active users for an ended contract
                    $recipient = User::find($notification->recipient_id);
                    $sender = User::find($notification->sender_id);
                    $tender = Tender::find($notification->tender_id);
                    $email = new EmailTenderClose($recipient, $sender, $tender);
                    Mail::to($recipient->email)->send($email);
                }
            }
        }
    }
}
