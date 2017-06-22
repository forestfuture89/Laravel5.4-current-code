<?php

namespace App\Http\Controllers\Common;

use App\Bid;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.common.notification');
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
        $notification = Notification::find($id);

        // check & update the read_status of this notification.
        if (!$notification->read) {
            $notification->read = true;
            $notification->save();
        }

        /*
         * redirect the relevant tender & user profile.
         */
        if ($notification->tender_id) { // Notification Type: 1, 2, 6
            return redirect()->route('tender.show', ['id' => $notification->tender_id]);
        } else if ($notification->bid_id) { // Notification Type: 4, 5, 7
            $bid = Bid::find($notification->bid_id);
            return redirect()->route('tender.show', ['id' => $bid->tender_id]);
        } else {
            if ($notification->notification_type_id == 8) { // Notification Type: 8
                return redirect()->route('profile', ['id' => $notification->sender_id]);
            } else { // Notification Type: 9
                return redirect()->route('profile', ['id' => $notification->recipient_id]);
            }
        }
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

    /**
     * Mark all notification as read from storage.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function markRead()
    {
        $user = Auth::user();
        $unread_notifications = $user->notifications()->where('read', false);

        foreach ($unread_notifications as $notification) {
            $notification->read = true;
            $notification->save();
        }

        return Response::make('Ok', 200);
    }
}
