<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ClientAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if ( Auth::check() && (Auth::user()->company()->type == 1 || Auth::user()->company()->type == 0) )
      {
        if (Auth::user()->blocked == 0) {
          return $next($request);
        } else {
          return redirect()->route('user.blocked_page');
        }
      }
      return redirect()->action('Common\HomeController@index');
    }
}
