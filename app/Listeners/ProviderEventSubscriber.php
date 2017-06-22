<?php

namespace App\Listeners;

use App\Bid;
use App\Events\BidWinnerAward;
use App\Events\NewNotificationArrive;
use App\Events\TenderUpdate;
use App\Mail\EmailBidWinner;
use App\Mail\EmailTenderUpdate;
use App\Notification;
use App\Tender;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class ProviderEventSubscriber implements ShouldQueue
{
    /**
     * Handle tender update events.
     */
    public function onTenderUpdate(TenderUpdate $event)
    {
        // push a new notification to all providers for a updated tender (Notification Type: 6)
        $users = User::all();
        foreach ($users as $user) {
            if ($user->company()->isProvider()) {
                $notification = new Notification;
                $notification->notification_type_id = 6;
                $notification->recipient_id = $user->id;
                $notification->sender_id = $event->update_tender->createdBy()->id;
                $notification->tender_id = $event->update_tender->id;
                $notification->save();

                // Dispatch a new event for new notification arrival. (Broadcast to all recipient)
                event(new NewNotificationArrive($notification));

                // Send an email to a provider for a updated tender
                $recipient = User::find($notification->recipient_id);
                $sender = User::find($notification->sender_id);
                $tender = Tender::find($notification->tender_id);
                $email = new EmailTenderUpdate($recipient, $sender, $tender);
                Mail::to($recipient->email)->send($email);
            }
        }
    }

    /**
     * Handle bid winner award events.
     */
    public function onBidWinnerAward(BidWinnerAward $event)
    {
        // push a new notification to a winning provider for an awarded contract (Notification Type: 7)
        $tender = $event->winner_bid->tender();
        $bid_winner = $event->winner_bid->author();
        foreach ($tender->bidders() as $user) {
            $notification = new Notification;
            $notification->notification_type_id = 7;
            $notification->recipient_id = $user->id;
            $notification->sender_id = $tender->createdBy()->id;
            $notification->bid_id = $event->winner_bid->id;
            $notification->save();

            // Dispatch a new event for new notification arrival. (Broadcast to all recipient)
            event(new NewNotificationArrive($notification));

            // Send an email to a provider for an awarded contract
            $recipient = User::find($notification->recipient_id);
            $sender = User::find($notification->sender_id);
            $bid = Bid::find($notification->bid_id);
            $email = new EmailBidWinner($recipient, $sender, $bid);
            Mail::to($recipient->email)->send($email);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\TenderUpdate',
            'App\Listeners\ProviderEventSubscriber@onTenderUpdate'
        );

        $events->listen(
            'App\Events\BidWinnerAward',
            'App\Listeners\ProviderEventSubscriber@onBidWinnerAward'
        );
    }
}
