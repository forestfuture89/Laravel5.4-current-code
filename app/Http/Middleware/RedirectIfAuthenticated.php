<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
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

        return $next($request);
    }
}
