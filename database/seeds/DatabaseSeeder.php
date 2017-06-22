<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->delete();
        App\Company::create(array('name' => 'OSX', 'website' => 'http://osx.app', 'type' => 0, 'status' => 1));
        App\Company::create(array('name' => 'Client Company', 'website' => 'www.clientcompany.com', 'type' => 1, 'status' => 1));
        App\Company::create(array('name' => 'Provider Company', 'website' => 'providercompany.com', 'type' => 2, 'status' => 1));
        App\Company::create(array('name' => 'Another Provider', 'website' => 'anotherprovider.com', 'type' => 2, 'status' => 1));
        App\Company::create(array('name' => 'Another Client', 'website' => 'anotherclient.com', 'type' => 1, 'status' => 1));
        DB::table('users')->delete();
        App\User::create(array('name' => 'Admin', 'email' => 'fooadmin@osx.com', 'password' => '$2y$10$ZxnqD5aag4wXEs4aX3vJn..oTSz5sFJArSvcD8ByMRSgSDDsRoUty', 'admin' => true, 'company_id' => 1));
        App\User::create(array('name' => 'Regular Client', 'email' => 'fooclient@osx.com', 'password' => '$2y$10$ZxnqD5aag4wXEs4aX3vJn..oTSz5sFJArSvcD8ByMRSgSDDsRoUty', 'company_id' => 2, 'phone' => '1234567890'));
        App\User::create(array('name' => 'Regular Provider', 'email' => 'fooprovider@osx.com', 'password' => '$2y$10$ZxnqD5aag4wXEs4aX3vJn..oTSz5sFJArSvcD8ByMRSgSDDsRoUty', 'company_id' => 3));
        App\User::create(array('name' => 'Joe Doe', 'email' => 'joe@anotherprovider.com', 'password' => '$2y$10$ZxnqD5aag4wXEs4aX3vJn..oTSz5sFJArSvcD8ByMRSgSDDsRoUty', 'company_id' => 4));
        App\User::create(array('name' => 'Jane Doe', 'email' => 'jane@anotherclient.com', 'password' => '$2y$10$ZxnqD5aag4wXEs4aX3vJn..oTSz5sFJArSvcD8ByMRSgSDDsRoUty', 'company_id' => 5));
        DB::table('tenders')->delete();
        App\Tender::create(array('name' => 'Job A', 'description' => 'FSV - hotshot', 'service' => 'Vessel', 'deadline_at' => Carbon::now()->addDays(30)->toDateTimeString(), 'expires_at' => Carbon::now()->addDays(60)->toDateTimeString(), 'user_id' => 2));
        App\Tender::create(array('name' => 'Job B', 'description' => 'PSV - 90 day', 'service' => 'Flight', 'deadline_at' => Carbon::now()->addMinutes(5)->toDateTimeString(), 'expires_at' => Carbon::now()->addDays(30)->toDateTimeString(), 'user_id' => 2));
        App\Tender::create(array('name' => 'Job C', 'description' => 'Origin / Destination - Miles', 'service' => 'Truck', 'deadline_at' => Carbon::now()->addDays(2)->toDateTimeString(), 'expires_at' => Carbon::now()->addDays(3)->toDateTimeString(), 'awarded_at' => Carbon::now()->toDateTimeString(), 'ended_at' => Carbon::now()->toDateTimeString(),'user_id' => 2));
        App\Tender::create(array('name' => 'Job D', 'description' => 'Port Facility', 'service' => 'Other', 'deadline_at' => Carbon::now()->addDays(1)->toDateTimeString(), 'expires_at' => Carbon::now()->addDays(4)->toDateTimeString(), 'ended_at' => Carbon::now()->toDateTimeString(),'user_id' => 2));
        App\Tender::create(array('name' => 'Job E', 'description' => 'From Another Client', 'service' => 'Other', 'deadline_at' => Carbon::now()->addDays(30)->toDateTimeString(), 'expires_at' => Carbon::now()->addDays(60)->toDateTimeString(), 'user_id' => 5));
        DB::table('bids')->delete();
        App\Bid::create(array('tender_id' => 1, 'user_id' => 3));
        App\Bid::create(array('tender_id' => 1, 'user_id' => 4));
        App\Bid::create(array('tender_id' => 2, 'user_id' => 3));
        App\Bid::create(array('tender_id' => 3, 'user_id' => 3, 'winner' => true));
        App\Bid::create(array('tender_id' => 3, 'user_id' => 4));
        App\Bid::create(array('tender_id' => 5, 'user_id' => 3));
        App\Bid::create(array('tender_id' => 5, 'user_id' => 4));
        DB::table('documents')->delete();
        App\Document::create(array('path' => 'public/documents/tender1doc1.pdf', 'tender_id' => 1, 'type' => 0, 'user_id' => 2));
        App\Document::create(array('path' => 'public/documents/tender1doc2.pdf', 'tender_id' => 1, 'type' => 0, 'user_id' => 2));
        App\Document::create(array('path' => 'public/documents/tender2doc1.pdf', 'tender_id' => 2, 'type' => 0, 'user_id' => 2));
        App\Document::create(array('path' => 'public/documents/tender3doc1.pdf', 'tender_id' => 3, 'type' => 0, 'user_id' => 2));
        App\Document::create(array('path' => 'public/documents/tender4doc1.pdf', 'tender_id' => 4, 'type' => 0, 'user_id' => 2));
        App\Document::create(array('path' => 'public/documents/tender5doc1.pdf', 'tender_id' => 5, 'type' => 0, 'user_id' => 5));
        App\Document::create(array('path' => 'public/documents/tender1bidtemplate.pdf', 'tender_id' => 1, 'type' => 1, 'user_id' => 2));
        App\Document::create(array('path' => 'public/documents/tender2bidtemplate.pdf', 'tender_id' => 2, 'type' => 1, 'user_id' => 2));
        App\Document::create(array('path' => 'public/documents/tender3bidtemplate.pdf', 'tender_id' => 3, 'type' => 1, 'user_id' => 2));
        App\Document::create(array('path' => 'public/documents/tender4bidtemplate.pdf', 'tender_id' => 4, 'type' => 1, 'user_id' => 2));
        App\Document::create(array('path' => 'public/documents/tender5bidtemplate.pdf', 'tender_id' => 5, 'type' => 1, 'user_id' => 5));
        App\Document::create(array('path' => 'public/documents/tender1bid1.pdf', 'tender_id' => 1, 'bid_id' => 1, 'type' => 2, 'user_id' => 3));
        App\Document::create(array('path' => 'public/documents/tender1bid2.pdf', 'tender_id' => 1, 'bid_id' => 2, 'type' => 2, 'user_id' => 4));
        App\Document::create(array('path' => 'public/documents/tender2bid1.pdf', 'tender_id' => 2, 'bid_id' => 3, 'type' => 2, 'user_id' => 3));
        App\Document::create(array('path' => 'public/documents/tender3bid1.pdf', 'tender_id' => 3, 'bid_id' => 4, 'type' => 2, 'user_id' => 3));
        App\Document::create(array('path' => 'public/documents/tender3bid2.pdf', 'tender_id' => 3, 'bid_id' => 5, 'type' => 2, 'user_id' => 4));
        App\Document::create(array('path' => 'public/documents/tender5bid1.pdf', 'tender_id' => 5, 'bid_id' => 6, 'type' => 2, 'user_id' => 3));
        App\Document::create(array('path' => 'public/documents/tender5bid2.pdf', 'tender_id' => 5, 'bid_id' => 7, 'type' => 2, 'user_id' => 4));
        DB::table('notification_types')->delete();
        App\NotificationType::create(array('description' => 'New Tender is posted.'));
        App\NotificationType::create(array('description' => 'Tender is closed.'));
        App\NotificationType::create(array('description' => 'New message is received.'));
        App\NotificationType::create(array('description' => 'Provider submitted a bid.'));
        App\NotificationType::create(array('description' => 'Provider updated their bid.'));
        App\NotificationType::create(array('description' => 'Client updated their tender.'));
        App\NotificationType::create(array('description' => 'Contract is awarded to a provider.'));
        App\NotificationType::create(array('description' => 'New user registers and is waiting for approval.'));
        App\NotificationType::create(array('description' => 'New user is activated by an OSX administrator.'));
    }
}
