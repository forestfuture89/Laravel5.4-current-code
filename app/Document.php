<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
  /*
  * Document has a numeric field to consider as follows
  * Type field has three possible values
  * 0 = Tender Documents (uploaded by clients on a tender - multiple per tender)
  * 1 = Bid Templates (uploaded by clients on a tender - just one per tender)
  * 2 = Bid Documents (uploaded by providers on a bid - just one per bid)
  * 3 = Bid Supporting Documents (uploaded by providers on a bid - multiple per bid)
  */
  // Return Documents that are Tender Documents
  public function scopeTenderDocs($query)
  {
    return $query->where('type', 0);
  }
  // Return Documents that are Bid templates
  public function scopeBidTemplates($query)
  {
    return $query->where('type', 1);
  }
  // Return Documents that are Bid Docs
  public function scopeBidDocs($query)
  {
    return $query->where('type', 2);
  }
  // Return Documents that are Bid Supporting Docs
  public function scopeSupportingDocs($query)
  {
    return $query->where('type', 3);
  }
  // Return the Document's tender
  public function tender()
  {
      return $this->hasOne('App\Tender', 'id', 'tender_id')->first();
  }
  // Return the Document's Bid
  public function bid()
  {
      return $this->hasOne('App\Bid', 'id', 'bid_id')->first();
  }
  // Returns wich user posted the document
  public function author()
  {
      return $this->hasOne('App\User', 'id', 'user_id')->first();
  }
  // Return the Document's Company
  public function company()
  {
      return $this->author()->company();
  }
}
