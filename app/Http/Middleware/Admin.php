<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;

class Admin
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
         /*if (Auth::check() && in_array($request->user()->isRole(), $role)) {

            return $next($request);

        }

        return redirect('login');*/
        if (Auth::check()){
            if ($request->user()->isRole() != "Admin"){
                // return whatever you want here, I'd redirect to login page
                return redirect('login');
                //return 'login';
            }

            return $next($request);
        }

        return $next($request);
    }
}
