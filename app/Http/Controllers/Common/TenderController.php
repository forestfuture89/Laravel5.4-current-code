<?php

namespace App\Http\Controllers\Common;

use App\Bid;
use App\Events\BidWinnerAward;
use App\Events\NewTenderPost;
use App\Events\TenderClose;
use App\Events\TenderUpdate;
use App\Tender;
use App\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class TenderController extends Controller
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
        return view('pages.client.tender_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'tender_name' => 'required|string|max:255',
            'tender_desc' => 'required|string',
            'tender_service' => 'required|string|max:255',
            'tender_deadline' => 'required|date_format:"j F, Y h:iA"|after:today',
            'tender_expires' => 'required|date_format:"j F, Y h:iA"|after:today|after:tender_deadline',
            'user_id' => 'required|integer'
        ]);

        $expires_at = Carbon::createFromFormat("j F, Y h:iA", $request->tender_expires, 'America/Chicago');
        $deadline_at = Carbon::createFromFormat("j F, Y h:iA", $request->tender_deadline, 'America/Chicago');
        $tender = new Tender;
        $tender->user_id = Auth::user()->id;
        $tender->name = $request->tender_name;
        $tender->description = $request->tender_desc;
        $tender->service = $request->tender_service;
        $tender->expires_at = $expires_at->setTimezone('UTC');
        $tender->deadline_at = $deadline_at->setTimezone('UTC');
        $tender->save();

        if ($request->hasFile('tender_docs')) {
            $files = $request->file('tender_docs');
            foreach ($files as $file) {
                if ($file->isValid()) {
                    $destinationPath = 'public/documents';
                    $path = $file->store($destinationPath);
                    $document = new Document;
                    $document->path = $path;
                    $document->tender_id = $tender->id;
                    $document->user_id = $tender->user_id;
                    $document->type = 0;
                    $document->save();
                }
            }
        }

        if ($request->hasFile('bid_template')) {
            $file = $request->file('bid_template');
            if ($file->isValid()) {
                $destinationPath = 'public/documents';
                $path = $file->store($destinationPath);
                $document = new Document;
                $document->path = $path;
                $document->tender_id = $tender->id;
                $document->user_id = $tender->user_id;
                $document->type = 1;
                $document->save();
            }
        }

        // Dispatch a new event for a created tender. (Notification Type: 1)
        event(new NewTenderPost($tender));

        return redirect()->route('client.dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tender $tender
     * @return \Illuminate\Http\Response
     */
    public function show(Tender $tender)
    {
        if (Auth::user()->company()->type == 2) {
            $bid = Bid::where('user_id', Auth::user()->id)
                ->where('tender_id', $tender->id)
                ->first();

            $bid_status = null;
            $bid_documents = null;
            if (is_null($bid)) {
                if ($tender->ended_at == null) {
                    $bid_status = 'not_bid';
                } else {
                    $bid_status = 'closed';
                }
            } else {
                if ($bid->winner == 0) {
                    $bid_status = 'not_win';
                    $bid_documents = $tender->documents()
                        ->where('type', 2)
                        ->where('tender_id', $tender->id)
                        ->where('bid_id', $bid->id)
                        ->where('user_id', Auth::user()->id);
                } else {
                    $bid_status = 'win';
                }
            }

            return view('pages.common.tender')
                ->with('tender', $tender)
                ->with('bid_status', $bid_status)
                ->with('bid_documents', $bid_documents);
        } elseif (Auth::user() == $tender->createdBy() || Auth::user()->company()->type == 0) {
            return view('pages.common.tender')
                ->with('tender', $tender);
        } else {
            return redirect()->route('client.dashboard');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tender $tender
     * @return \Illuminate\Http\Response
     */
    public function edit(Tender $tender)
    {
        if (Auth::user()->company() == $tender->createdBy()->company() || Auth::user()->company()->type == 0) {
            return view('pages.client.tender_edit')
                ->with('tender', $tender);
        } else {
            return redirect()->route('client.dashboard');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Tender $tender
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tender $tender)
    {
        $this->validate($request, [
            'tender_id' => 'required|integer',
            'tender_name' => 'required|string|max:255',
            'tender_desc' => 'required|string',
            'tender_service' => 'required|string|max:255',
            'tender_deadline' => 'required|date_format:"j F, Y h:iA"|after:today',
            'tender_expires' => 'required|date_format:"j F, Y h:iA"|after:today|after:tender_deadline',
            'user_id' => 'required|integer'
        ]);

        $tender = Tender::find($request->tender_id);

        if (($tender->createdBy()->company() == Auth::user()->company()) || Auth::user()->company()->type == 0) {
            $expires_at = Carbon::createFromFormat("j F, Y h:iA", $request->tender_expires, 'America/Chicago');
            $deadline_at = Carbon::createFromFormat("j F, Y h:iA", $request->tender_deadline, 'America/Chicago');
            if (Auth::user()->company()->type == 1) {
                $tender->user_id = Auth::user()->id;
            }
            $tender->name = $request->tender_name;
            $tender->description = $request->tender_desc;
            $tender->service = $request->tender_service;
            if ($tender->deadline_at->diffInDays($deadline_at->setTimezone('UTC')) > 0) {
              $tender->ended_at = null;
            }
            $tender->expires_at = $expires_at->setTimezone('UTC');
            $tender->deadline_at = $deadline_at->setTimezone('UTC');
            $tender->save();

            if ($request->hasFile('tender_docs')) {
                $files = $request->file('tender_docs');
                foreach ($files as $file) {
                    if ($file->isValid()) {
                        $destinationPath = 'public/documents';
                        $path = $file->store($destinationPath);
                        $document = new Document;
                        $document->path = $path;
                        $document->tender_id = $tender->id;
                        $document->user_id = $tender->user_id;
                        $document->type = 0;
                        $document->save();
                    }
                }
            }

            if ($request->hasFile('bid_template')) {
                $file = $request->file('bid_template');
                if ($file->isValid()) {
                    // delete previous bid_template
                    $toDelete = Document::find($tender->documents()->where('type', 1)->first()->id);
                    $path = storage_path() . '/app/' . $toDelete->path;
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    $toDelete->delete();
                    // save the new one
                    $destinationPath = 'public/documents';
                    $path = $file->store($destinationPath);
                    $document = new Document;
                    $document->path = $path;
                    $document->tender_id = $tender->id;
                    $document->user_id = $tender->user_id;
                    $document->type = 1;
                    $document->save();
                }
            }

            // Dispatch a new event for a updated tender. (Notification Type: 6)
            event(new TenderUpdate($tender));

            return redirect()->route('tender.show', ['id' => $tender->id]);
        } else {
            return redirect()->route('client.dashboard');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tender $tender
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tender $tender)
    {
        //
    }

    // Award the Tender as contract to an specific Bid
    public function award(Request $request)
    {
        $this->validate($request, [
            'tender_id' => 'required|integer',
            'bid_id' => 'required|integer'
        ]);

        $tender = Tender::find($request->tender_id);

        if (Auth::user()->company() == $tender->createdBy()->company()) {
            $previousWinner = $tender->winnerBid();
            if ($previousWinner != null) {
                $previousWinner->removeAsWinner();
            }
            $bid = $tender->bids()->find($request->bid_id);
            $bid->setAsWinner();
            $tender->setAsContract();

            // Dispatch a new event for an awarded bid. (Notification Type: 7)
            event(new BidWinnerAward($bid));
        }

        return redirect()->route('tender.show', [$tender]);
    }

    // Change the status of a tender from Open Tender to Closed Tender (type 1) or from Open Contract to Ended Contract (type 2)
    public function change(Request $request)
    {
        $this->validate($request, [
            'tender_id' => 'required|integer',
            'type' => 'required|integer'
        ]);

        $tender = Tender::find($request->tender_id);

        if (Auth::user()->company() == $tender->createdBy()->company()) {
            if ($request->type == 1) {
                $tender->setAsClosedTender();
            } elseif ($request->type == 2) {
                $tender->setAsEndedContract();
            }

            // Dispatch a new event for a closed tender. (Notification Type: 2)
            event(new TenderClose($tender));
        }

        return redirect()->route('tender.show', [$tender]);
    }
}
