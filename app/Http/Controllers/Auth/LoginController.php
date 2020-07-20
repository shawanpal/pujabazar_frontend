<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Cart;

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


     protected function authenticated(Request $request, $user){
         // return $user->isRole();
         if($user->isRole() == 'Buyer'){
           if(Cart::count()<1){
                return redirect('/');
           }else{
             return redirect('cart');
           }
             // return back();
             //return redirect('buyer');
         }else if($user->isRole() == 'Seller'){
             return redirect('seller');
         }else if($user->isRole() == 'Admin'){
             return redirect('admin');
         }
     }
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
