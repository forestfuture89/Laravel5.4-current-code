<?php

namespace App;

use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'job_title', 'phone', 'pic_path', 'admin', 'company_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     *  Over-ridden the {sendPasswordResetNotification} method from the "CanResetPassword" trait
     *  Remember to take care while upgrading laravel
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    // Return admin users
    public function scopeAdmin($query, $flag = 1)
    {
        return $query->where('admin', $flag);
    }
    // Return Blocked User
    public function scopeBlocked($query, $flag = 1)
    {
        return $query->where('blocked', $flag);
    }
    // Return Active User
    public function scopeActive($query, $flag = 0)
    {
        return $query->where('blocked', $flag);
    }
    // Tenders from this user
    public function tenders()
    {
        return $this->hasMany('App\Tender')->get();
    }
    // Bids from this user
    public function bids()
    {
        return $this->hasMany('App\Bid')->get();
    }
    // Winner bids from an User
    public function winnerbids()
    {
        return $this->bids()->where('winner', true);
    }
    // Return the User's Company
    public function company()
    {
        return $this->hasOne('App\Company', 'id', 'company_id')->first();
    }
    // Tenders where the user bidded
    public function tendersBidded()
    {
        $tenders = collect([]);
        $bidonTenders = $this->bids()->unique('tender_id');
        foreach  ($bidonTenders as $bid) {
          $tenders->push($bid->tender());
        }
        return $tenders;
    }
    public function toggleUser() {
      $this->blocked = !$this->blocked;
      return $this;
    }

    // Notifications for the user
    public function notifications()
    {
        return $this->hasMany('App\Notification', 'recipient_id', 'id')->orderBy('created_at', 'desc')->get();
    }

    // Count the unread notifications for the user
    public function countUnreadNotifications() {
        return $this->notifications()->where('read', false)->count();
    }
}
