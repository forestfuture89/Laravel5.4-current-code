<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
  // Adds Carbon management to expires_at, ended_at, awarded_at and deadline_at
  protected $dates = ['expires_at', 'ended_at', 'awarded_at', 'deadline_at'];

  // Return open Tenders
  public function scopeOpen($query)
  {
    return $query->where('ended_at', null);
  }
  // Return closed Tenders
  public function scopeClosed($query)
  {
      return $query->where('ended_at', '<>', null);
  }
  /**
   * Public functions for Tenders
   *
   * Get bids for an Tenders
  */
  public function bids()
  {
      return $this->hasMany('App\Bid');
  }
  // Get the winner bid of a tender and return null if there is none
  public function winnerbid()
  {
      return $this->bids->where('winner', true)->first();
  }
  // Get the bidders
  public function bidders()
  {
      $bidders = collect([]);
      $bidonTender = $this->bids->unique('user_id');
      foreach  ($bidonTender as $bid) {
        $bidders->push($bid->author());
      }
      return $bidders;
  }
  // Get Documents for this Tender
  public function documents()
  {
      return $this->hasMany('App\Document')->get();
  }
  // Returns wich user created the tender
  public function createdBy()
  {
      return $this->hasOne('App\User', 'id', 'user_id')->first();
  }
  // Get remaining time for this Tender
  public function timeRemain()
  {
    $results = "";
    if ($this->ended_at <> null) {
      $end_time = new Carbon($this->ended_at);
      $results = "Ended on ".$end_time->timezone('America/Chicago')->format('n/j/y');
      return $results;
    } else {
      $end_time = $this->expires_at->timezone('America/Chicago');
      $now = Carbon::now()->timezone('America/Chicago');
      $interval = $end_time->diff($now);
      $days = $now->diffInDays($end_time);
      $hours = $now->diffInHours($end_time);
      if ($days > 0) {
        $results = $interval->format('%d').' days left';
        return $results;
      } elseif ($days < 1 && $hours > 0) {
        $results = $interval->format('%h').' hours left';
        return $results;
      } elseif ($days < 1 && $hours < 1) {
        $results = $interval->format('%i').' minutes left';
        return $results;
      } elseif ($this->ended_at <> null) {
        $results = "Tender ended";
        return $results;
      }
    }
  }

  public function setAsContract()
  {
    $this->awarded_at = Carbon::now();
    $this->ended_at = null;
    $this->save();
    return $this;
  }

  public function setAsOpenTender()
  {
    $this->awarded_at = null;
    $this->ended_at = null;
    if ($this->winnerBid() != null) {
      $this->winnerBid()->removeAsWinner();
    }
    $this->save();
    return $this;
  }

  public function setAsClosedTender()
  {
    $this->awarded_at = null;
    $this->ended_at = Carbon::now();
    if ($this->winnerBid() != null) {
      $this->winnerBid()->removeAsWinner();
    }
    $this->save();
    return $this;
  }

  public function setAsEndedContract()
  {
    $this->ended_at = Carbon::now();
    $this->save();
    return $this;
  }
}
