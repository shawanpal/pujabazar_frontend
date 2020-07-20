<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;

class Buyer
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
      if (Auth::user()->isRole() != "Buyer"){
          // return whatever you want here, I'd redirect to login page
          return redirect('/login');
      }
      return $next($request);
    }
}
