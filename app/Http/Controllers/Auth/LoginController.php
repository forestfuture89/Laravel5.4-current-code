<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function authenticated(Request $request, $user)
    {
      if (Auth::user()->blocked == 0) {
        if (Auth::user()->company()->type == 1) {
            return redirect()->route('client.dashboard');
        } elseif (Auth::user()->company()->type == 2) {
            return redirect()->route('provider.dashboard');
        } elseif (Auth::user()->company()->type == 0) {
            return redirect()->route('admin.dashboard');
        }
      } else {
        return redirect()->route('user.blocked_page');
      }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
}
