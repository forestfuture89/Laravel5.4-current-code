<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /*
    * Companies has two numeric fields to consider as follows
    * Status field has three possible values
    * 0 = pending
    * 1 = active
    * 2 = blocked
    * Type field has three possible values
    * 0 = reserved for admins usually not used on the system
    * 1 = Clients
    * 2 = Providers
    */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'website', 'type', 'status'
    ];

    // Return Companies that are pending
    public function scopePending($query)
    {
        return $query->where('status', 0);
    }

    // Return Companies that are active
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Return Companies that are blocked
    public function scopeBlocked($query)
    {
        return $query->where('status', 2);
    }

    // Return Companies that are Clients
    public function scopeClients($query)
    {
        return $query->where('type', 1);
    }

    // Return Companies that are Providers
    public function scopeProviders($query)
    {
        return $query->where('type', 2);
    }

    // Get users for this Company
    public function users()
    {
        return $this->hasMany('App\User')->get();
    }

    // Boolean if the Company is pending
    public function isPending()
    {
        if ($this->status == 0) {
            return true;
        } else {
            return false;
        }
    }

    // Boolean if the Company is active
    public function isActive()
    {
        if ($this->status == 1) {
            return true;
        } else {
            return false;
        }
    }

    // Boolean if the Company is blocked
    public function isBlocked()
    {
        if ($this->status == 2) {
            return true;
        } else {
            return false;
        }
    }

    // Boolean if the Company is Client
    public function isClient()
    {
        if ($this->type == 1) {
            return true;
        } else {
            return false;
        }
    }

    // Boolean if the Company is Provider
    public function isProvider()
    {
        if ($this->type == 2) {
            return true;
        } else {
            return false;
        }
    }

    // Get the Tenders for this company (all users)
    public function tenders()
    {
        $tenders = collect([]);
        $usersOnCompany = $this->users();
        foreach ($usersOnCompany as $user) {
            foreach ($user->tenders() as $tender) {
                $tenders->push($tender);
            }
        }
        return $tenders;
    }

    // Get the Bids for this company (all users)
    public function bids()
    {
        $bids = collect([]);
        $usersOnCompany = $this->users();
        foreach ($usersOnCompany as $user) {
            foreach ($user->bids() as $bid) {
                $bids->push($bid);
            }
        }
        return $bids;
    }

    // Get the Tenders where this company has bidded (all users)
    public function tendersBidded()
    {
        $tenders = collect([]);
        $bids = $this->bids()->unique('tender_id');
        foreach ($bids as $bid) {
            $tenders->push($bid->tender());
        }
        return $tenders;
    }

    // Get the Winner Bids for this company (all users)
    public function winnerbids()
    {
        $bids = collect([]);
        $usersOnCompany = $this->users();
        foreach ($usersOnCompany as $user) {
            foreach ($user->winnerbids() as $bid) {
                $bids->push($bid);
            }
        }
        return $bids;
    }
}
