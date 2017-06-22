<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
  // Return winner bids
  public function scopeWinners($query, $flag = 1)
  {
    return $query->where('winner', $flag);
  }
  // Returns wich user placed the bid
  public function author()
  {
      return $this->hasOne('App\User', 'id', 'user_id')->first();
  }
  // Return the tender where the bid was placed
  public function tender()
  {
      return $this->hasOne('App\Tender', 'id', 'tender_id')->first();
  }
  // Get all Documents for this Bid
  public function documents()
  {
      return $this->hasMany('App\Document')->get();
  }
  // Get Bid Document for this Bid
  public function bidDocument()
  {
      return $this->documents()->where('type', 2)->first();
  }
  // Get supporting Documents for this Bid
  public function supportingDocuments()
  {
      return $this->documents()->where('type', 3);
  }
  // Get the passed time of the user's latest bid
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
  public function setAsWinner()
  {
      $this->winner = true;
      $this->save();
      return $this;
  }
  public function removeAsWinner()
  {
      $this->winner = false;
      $this->save();
      return $this;
  }
}
