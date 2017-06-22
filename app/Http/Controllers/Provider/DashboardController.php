<?php

namespace App\Http\Controllers\Provider;

use App\Tender;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new array client instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'provider']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $openTenders = Tender::open()->get();
        $closedTenders = Tender::closed()->get();
        $biddedTenders = Auth::user()->tendersBidded();

        $contracts = collect();
        $winnerBids = Auth::user()->winnerbids();
        foreach ($winnerBids as $bid) {
            $contracts->push($bid->tender());
        }

        $completedContracts = $contracts->where('ended_at', '<>', null);
        $ended_biddedTenders = $biddedTenders->where('ended_at', '<>', null);
        $endedContracts = collect();
        foreach ($ended_biddedTenders as $tender) {
            if ($completedContracts->contains($tender)) {
                $tender['status'] = 'accepted';
            } else {
                $tender['status'] = 'unaccepted';
            }
            $endedContracts->push($tender);
        }

        $activeContracts = $contracts->where('ended_at', null);
        $proposedContracts = $biddedTenders->where('ended_at', null)->diff($activeContracts);

        // filter only openTenders which has no bidder
        $openTenders = $openTenders->diff($biddedTenders);

        // filter only closedTenders which no winner was selected
        $closedTenders = $closedTenders->diff($biddedTenders);

        return view('pages.provider.dashboard')
            ->with('proposedContracts', $proposedContracts)
            ->with('activeContracts', $activeContracts)
            ->with('endedContracts', $endedContracts)
            ->with('openTenders', $openTenders)
            ->with('closedTenders', $closedTenders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
