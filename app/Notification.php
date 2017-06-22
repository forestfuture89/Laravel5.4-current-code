<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    // Return the content by Notification Type ID
    public function content()
    {
        $recipient = $this->hasOne('App\User', 'id', 'recipient_id')->first();
        $sender = $this->hasOne('App\User', 'id', 'sender_id')->first();
        $tender = $this->hasOne('App\Tender', 'id', 'tender_id')->first();
        $bid = $this->hasOne('App\Bid', 'id', 'bid_id')->first();

        $content = null;
        switch($this->notification_type_id)
        {
            case 1:
                $content = $sender->name . ' posted a new tender "' . $tender->name . '".';
                break;
            case 2:
                $content = $sender->name . ' closed a tender "' . $tender->name . '".';
                break;
            case 3:
                $content = 'You received a new message from ' . $sender->name . '.';
                break;
            case 4:
                $content = $sender->name . ' submitted a new bid on your tender "' . $bid->tender()->name . '".';
                break;
            case 5:
                $content = $sender->name . ' updated their bid on your tender "' . $bid->tender()->name . '".';
                break;
            case 6:
                $content = $sender->name . ' updated their tender "' . $tender->name . '".';
                break;
            case 7:
                if ($recipient == $bid->author()){
                    $content = 'Contract "' . $bid->tender()->name . '" is awarded to you.';
                } else {
                    $content = 'Contract "' . $bid->tender()->name . '" is awarded to another provider.';
                }
                break;
            case 8:
                $content = 'New User "' . $sender->name . '" registers and is waiting for approval.';
                break;
            case 9:
                $content = 'Your account is activated by OSX.';
                break;
            default :
                break;
        }

        return $content;
    }

    // Get the passed time of the notification
    public function passedTime()
    {
        $create_time = new Carbon($this->created_at);
        $suffix = "ago";
        $now = Carbon::now();
        $days = $now->diffInDays($create_time);
        $hours = $now->diffInHours($create_time);
        $minutes = $now->diffInMinutes($create_time);
        $results = "";
        if ($days > 0) {
            $results = $days . " days " . $suffix;
            return $results;
        } elseif ($hours > 0) {
            $results = $hours . " hours " . $suffix;
            return $results;
        } elseif ($minutes > 0) {
            $results = $minutes . " minutes " . $suffix;
            return $results;
        } else {
            $results = "a few seconds ago";
            return $results;
        }
    }
}
