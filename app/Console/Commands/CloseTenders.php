<?php

namespace App\Console\Commands;

use App\Tender;
use App\Events\AuctionComingToEnd;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

class CloseTenders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenders:close {id?} {bid?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will check if there are expiring tenders and close them';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = Carbon::now()->toDateTimeString();
        $interval = Carbon::now()->addMinute()->toDateTimeString();
        $within_an_hour = Carbon::now()->addMinutes(60)->toDateTimeString();

        // if not id argument is provided, it will run a batch check for expired in the currently open tenders
        if ($this->argument('id') == null) {
            /*Log::info('Running batch check of tenders which are about to close within an hour.');
            $alert_tenders = Tender::Open()
                                    ->where('notification_flag', false)
                                    ->whereBetween('expires_at', array($now, $within_an_hour))
                                    ->get();
            if ($alert_tenders->count() > 0) {
                Log::info('Will be about to close ' . $alert_tenders->count() . ' tenders within an hour.');
                foreach ($alert_tenders as $ten) {
                    Log::info('Notify all relevant users about Closing Tender #' . $ten->id . ' - ' . $ten->name . ' within an hour.');
                    // Dispatch a new event to send the auction notification to be about to close within an hour.
                    event(new AuctionComingToEnd($ten));
                    $auc->notification_flag = true;
                    $auc->save();
                }
            } else {
                Log::info('No auctions will be notified to the relevant users about coming to end.');
            }*/

            Log::info('Running batch check of expired tenders to close');
            $tenders = Tender::Open()->whereBetween('deadline_at', array($now, $interval))->get();
            if ($tenders->count() > 0) {
                Log::info('Will close ' . $tenders->count() . ' Tenders');
                foreach ($tenders as $tender) {
                    Log::info('Closing Tender #' . $tender->id . ' - ' . $tender->name);
                    $tender->ended_at = $now;
                    $tender->save();
                }
            } else {
                Log::info('No tenders expired at ' . $now . ', batch check completed');
            }
        } else {
            // if an id argument is provided (user generated action), it will close the specific auction
            Log::info('(User generated) Closing tender #' . $this->argument('id'));
            $tender = Tender::Open()->find($this->argument('id'));
            if ($tender != null) {
                Log::info('Closing tender #' . $tender->id . ' - ' . $tender->name);
                if ($this->argument('bid') == null) {
                    //
                } else {
                    $winnerbid = $tender->bids()->find($this->argument('bid'));
                }
                if ($winnerbid != null) {
                    $winnerbid->winner = true;
                    $winnerbid->save();
                    Log::info('Winner bid for tender #' . $tender->id . ' is bid #' . $winnerbid->id);
                } else {
                    Log::info('No winner bid was selected');
                }
                $tender->ended_at = $now;
                $tender->save();
                return true;
            } else {
                Log::warning('Not open tender with id #' . $this->argument('id') . ' found. Exiting');
                return false;
            }
        }
    }
}
