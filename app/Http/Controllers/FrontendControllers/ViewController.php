<?php

namespace App\Http\Controllers\FrontendControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Product;
use App\Image;
use App\Review;
use App\ProductDesc;
use App\Category;
use App\SubCategory;
use App\State;
use Cart;

class ViewController extends Controller {

    protected function get_client_state() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }
        $details = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}/json"));
        if(isset($details->region)){
            if($details && $details->status == 'success'){
                return State::where(['name' => $details->region])->first();
            }else{
                return State::where(['name' => 'West Bengal'])->first();
            }
        }else{
            return State::where(['name' => 'West Bengal'])->first();
        }
        
    }
    
    protected function signInValidator(array $data) {
        return Validator::make($data, [
                    'email' => ['required', 'email'],
                    'password' => ['required'],
        ]);
    }
    
    protected function signUpvalidator(array $data) {
        return Validator::make($data, [
                    'name' => ['required', 'string'],
                    'email' => ['required', 'email', 'unique:users'],
                    'phone' => ['required', 'numeric', 'unique:users', 'min:10'],
                    'password' => ['required']
        ]);
    }

    public function index() {
        $data['state'] = $this->get_client_state();
        return view('home', $data);
    }

    public function productDetails($code) {
        $data['product'] = Product::where(['code' => $code])->first();
        $data['images'] = Image::where(['product_id' => $data['product']->id])->get();
        $data['reviews'] = Review::where(['product_id' => $data['product']->id])->get();
        $data['desces'] = ProductDesc::where(['product_id' => $data['product']->id])->get();
        $data['category'] = Category::where(['id' => $data['product']->category_id])->first();
        $data['subcategory'] = SubCategory::where(['id' => $data['product']->sub_category_id])->first();
        $data['related_products'] = Product::where(['sub_category_id' => $data['product']->sub_category_id])->get();
        $data['average'] = Review::where('product_id', $data['product']->id)->avg('rating');
        $data['state'] = $this->get_client_state();
        $star = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $data['average']) {
                $star .= '<li>';
                $star .= '<i class="fa fa-star"></i> ';
                $star .= '</li>';
            } else {
                $star .= '<li class="dark">';
                $star .= '<i class="fa fa-star-o"></i> ';
                $star .= '</li>';
            }
        }
        $data['stars'] = $star;

        return view('product-details', $data);
    }

    protected function reviewValidator(array $data) {
        return Validator::make($data, [
                    'name' => ['required'],
                    'email' => ['required', 'email'],
                    'rating' => ['required'],
                    'encpid' => ['required'],
        ]);
    }

    protected function createReview(array $data) {
        if (isset($data['encpid'])) {
            return Review::create([
                        'product_id' => Crypt::decryptString($data['encpid']),
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'phone' => $data['phone_no'],
                        'rating' => $data['rating'],
                        'review' => $data['message'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public function submitReview(Request $request) {
        $validator = $this->reviewValidator($request->input());
        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $this->createReview($request->input());
            return Redirect::back()
                            ->with('success', 'Thank you for the review!');
        }
    }
    
    public function storeLocation(){
        $data['state'] = $this->get_client_state();
        $data['locations'] = State::all();
        return view('store-location', $data);
    }
    
    public function signin() {
        $data['state'] = $this->get_client_state();
        return view('signin', $data);
    }
    
    public function signup() {
        $data['state'] = $this->get_client_state();
        return view('signup', $data);
    }
    
    public function userSignin(Request $request) {
        $validator = $this->signInValidator($request->input());
        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $email = $request->input('email');
            $password = $request->input('password');
            $credentials = ['email' => $email, 'password' => $password];
            if (Auth::attempt($credentials)) {
                return Redirect('/');
            } else {
                return Redirect::back()
                        ->with('error', 'Wrong Credientials!');
            }
        }
    }
    
    protected function createNewUser(array $data) {
        return User::create([
                    'role' => 'Buyer',
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'password' => Hash::make($data['password']),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
    
    public function userSignUp(Request $request) {
        $validator = $this->signUpvalidator($request->input());
        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator)
                            ->withInput();
        }
        $this->createNewUser($request->input());
        return Redirect('/signin')
                    ->with('success', 'Thank you for the register!');
    }
    
    public function signout() {
        if (Auth::check()) {
            Auth::logout();
            return Redirect('/');
        }
    }
    
    
}
