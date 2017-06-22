<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Render the homepage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::check() && Auth::user()->company()->type == 1) {
            return redirect()->route('client.dashboard');
        } elseif (Auth::check() && Auth::user()->company()->type == 2) {
            return redirect()->route('provider.dashboard');
        } elseif (Auth::check() && Auth::user()->company()->type == 0) {
            return redirect()->route('admin.dashboard');
        }


        return view('pages.home');
    }
}
