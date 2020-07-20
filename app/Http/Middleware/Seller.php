<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;

class Seller
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
        if (Auth::user()->isRole() != "Seller"){
            //if ($request->user()->isRole() != "Admin"){
                return redirect('login');
            //}
        }
        return $next($request);
    }
}
