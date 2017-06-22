<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Tender;

class DashboardController extends Controller
{
    /**
     * Create a new array client instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'client']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companyTenders = Auth::user()->company()->tenders();

        $openTenders = $companyTenders
          ->where('ended_at', null)
          ->where('awarded_at', null);

        $closedTenders = $companyTenders
          ->where('awarded_at', null)
          ->where('ended_at', '<>', null);

        $openContracts = $companyTenders
          ->where('awarded_at', '<>', null)
          ->where('ended_at', null);

        $endedContracts = $companyTenders
          ->where('awarded_at', '<>', null)
          ->where('ended_at', '<>', null);

        return view('pages.client.dashboard')
          ->with('openTenders', $openTenders)
          ->with('closedTenders', $closedTenders)
          ->with('openContracts', $openContracts)
          ->with('endedContracts', $endedContracts);
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
