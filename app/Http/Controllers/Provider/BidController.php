<?php

namespace App\Http\Controllers\Provider;

use App\Bid;
use App\Document;
use App\Events\BidUpdate;
use App\Events\NewBidSubmit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class BidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  $tenderID, \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($tenderID, Request $request)
    {
        $bid = new Bid;
        $bid->user_id = Auth::user()->id;
        $bid->tender_id = $tenderID;
        $bid->save();

        if ($request->hasFile('bid_docs')) {
            $files = $request->file('bid_docs');
            foreach ($files as $file) {
                if ($file->isValid()) {
                    $destinationPath = 'public/documents';
                    $path = $file->store($destinationPath);
                    $document = new Document();
                    $document->path = $path;
                    $document->type = 2;
                    $document->tender_id = $tenderID;
                    $document->bid_id = $bid->id;
                    $document->user_id = Auth::user()->id;
                    $document->save();
                }
            }
        }

        // Dispatch a new event for a submitted bid. (Notification Type: 4)
        event(new NewBidSubmit($bid));

        return redirect()->route('provider.dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bid  $bid
     * @return \Illuminate\Http\Response
     */
    public function show(Bid $bid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bid  $bid
     * @return \Illuminate\Http\Response
     */
    public function edit(Bid $bid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $tenderID, \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($tenderID, Request $request)
    {
        $bid = Bid::where('user_id', Auth::user()->id)
            ->where('tender_id', $tenderID)
            ->first();


        if ($request->input('deletedDocs_id_list')) {
            $deletedDocs_id_list = json_decode($request->input('deletedDocs_id_list'));
            foreach ($deletedDocs_id_list as $docID) {
                $doc = Document::find($docID);
                if ($doc != null) {
                    $path = storage_path() . '/app/' . $doc->path;
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    $doc->delete();
                }
            }
        }

        if ($request->hasFile('bid_docs')) {
            $files = $request->file('bid_docs');
            foreach ($files as $file) {
                if ($file->isValid()) {
                    $destinationPath = 'public/documents';
                    $path = $file->store($destinationPath);
                    $document = new Document();
                    $document->path = $path;
                    $document->type = 2;
                    $document->tender_id = $tenderID;
                    $document->bid_id = $bid->id;
                    $document->user_id = Auth::user()->id;
                    $document->save();
                }
            }
        }

        // Dispatch a new event for a updated bid. (Notification Type: 5)
        event(new BidUpdate($bid));

        return redirect()->route('provider.dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $tenderID
     * @return \Illuminate\Http\Response
     */
    public function destroy($tenderID)
    {
        $userID = Auth::user()->id;
        $bid = Bid::where('user_id', $userID)
                    ->where('tender_id', $tenderID)
                    ->first();
        $documents = Document::where('tender_id', $tenderID)
                            ->where('bid_id', $bid->id)
                            ->where('user_id', $userID)
                            ->get();
        foreach ($documents as $document) {
            $path = storage_path() . '/app/' . $document->path;
            if (File::exists($path)) {
                File::delete($path);
            }
            $document->delete();
        }
        $bid->delete();

        return redirect()->route('provider.dashboard');
    }
}
