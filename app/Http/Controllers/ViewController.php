<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\ContuctUs;
use App\Mail\SendBooking;

use App\Banner;

use App\Category;

use App\SubCategory;

use App\Product;

use App\Item;

use App\Samogri;

use App\Package;

use App\Booking;

use App\Image;

use App\Attribute;

use App\Term;

use App\AttributeTerm;

use App\BusinessLocation;

use App\Review;

use App\State;

use App\User;

use App\Page;

use App\Blog;

use Session;

use Cart;

use Redirect;

class ViewController extends Controller
{
    private $location,$allBanners,$allCategorys,$allSubCategorys,$states;
    public function __construct()
    {
        // if(!Session::has('location')){
        //     $ip = \Request::ip();
        //     if($ip == '127.0.0.1'){
        //       $ip = '202.78.236.1';
        //     }
        //     $this->location = json_decode(file_get_contents("https://www.iplocate.io/api/lookup/".$ip));
        //
        //     $state = State::where('name', $this->location->subdivision)->get();
        //     if(count($state)==0){
        //         $this->location = json_decode(file_get_contents("https://www.iplocate.io/api/lookup/202.78.236.1"));
        //     }
        //     session(['location' => $this->location->subdivision]);
        // }
        session(['location' => 'West Bengal']);

        $this->states = State::orderByRaw('LENGTH(name) desc')->get();
        $this->allBanners = Banner::all();
        $this->allCategorys = Category::where('category_name', 'NOT LIKE', '%~%')->orderBy('position', 'asc')->get();

        $this->allSubCategorys = SubCategory::where('sub_category_name', 'NOT LIKE', '%~%')->join('categories', 'categories.id', '=', 'sub_categories.category_id')->leftJoin('images', 'sub_categories.id',   '=', 'images.sub_category_id')->select('sub_categories.id', 'sub_categories.category_id', 'categories.category_name', 'categories.category_url', 'sub_categories.sub_category_name', 'sub_categories.sub_category_url', 'images.image')->orderBy('sub_categories.id', 'desc')->get();
    }

    public function index()
    {
        // dd( $this->allBanners);
        $product = Product::orderBy('id', 'desc')->take(3)->with('images')->get();
        // dd($product);
        return view('welcome', [
            'locations' => session('location'),
            'banners' => $this->allBanners, 
            'categorys' => $this->allCategorys, 
            'subcategorys' => $this->allSubCategorys,
            'states' => $this->states,
            'new_arr' => $product,
          ]);
    }

    public function contact()
    {
        return view('contact', ['locations' => session('location'), 'banners' => $this->allBanners, 'categorys' => $this->allCategorys, 'subcategorys' => $this->allSubCategorys, 'states' => $this->states]);
    }

    public function showBlogs()
    {
        $blogs = Blog::all();
        return view('blogs', ['locations' => session('location'), 'categorys' => $this->allCategorys, 'subcategorys' => $this->allSubCategorys, 'states' => $this->states, 'blogs' => $blogs]);
    }

    public function singleBlog(Request $request)
    {
        $allBlogs = Blog::where('url', '<>', $request->url)->get();
        $blogs = Blog::where('url', $request->url)->get();
        return view('blog', ['locations' => session('location'), 'categorys' => $this->allCategorys, 'subcategorys' => $this->allSubCategorys, 'allBlogs' => $allBlogs, 'blogs' => $blogs, 'states' => $this->states]);
    }

    public function sendEmail(Request $request)
    {
        $rules = array(
            'name' => 'required|string',
            'email' => 'required|email',
            'mobile' => 'required|digits:10|numeric',
            'address' => 'nullable|string',
            'subject' => 'required|string',
            'message' => 'required|string'
        );
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return Redirect::back()->withInput()->withErrors($validator);
        }else{

            $data = ['name'=> $request->name, 'email' => $request->email, 'phone' => $request->mobile, 'address' => $request->address, 'text' => $request->message];
            Mail::to(['niladripritu@gmail.com'])->send(new ContuctUs($data));
            // Mail::send('email.contact', $data, function ($message) use ($request) {
            //     $message->from('info@pujabazar.com', 'PUJA BAZAR');
            //     $message->to(['niladripritu@gmail.com']);
            //     $message->subject($request->name.' Want to contact with you');
            //
            // });
            if( count(Mail::failures()) > 0 ) {
                Session::put('error','You have some error, plesse try again!!');
            }else{
                Session::put('success', 'We got your email & we will respons you soon!!');
            }
        }

        return redirect('contact');
    }

    public function showAbout()
    {
        $pages = Page::where('name', 'about')->get();
        $about = '';
        foreach ($pages as $page) {
            $about = $page->content;
        }
        return view('about', ['locations' => session('location'), 'banners' => $this->allBanners, 'categorys' => $this->allCategorys, 'subcategorys' => $this->allSubCategorys, 'states' => $this->states, 'about' => $about]);
    }

    public function showPrivacy()
    {
        $pages = Page::where('name', 'privacy')->get();
        $privacy = '';
        foreach ($pages as $page) {
            $privacy = $page->content;
        }

        return view('privacy', ['locations' => session('location'), 'banners' => $this->allBanners, 'categorys' => $this->allCategorys, 'subcategorys' => $this->allSubCategorys, 'states' => $this->states, 'privacy' => $privacy]);
    }

    public function showReturn()
    {
        $pages = Page::where('name', 'return')->get();
        $return = '';
        foreach ($pages as $page) {
            $return = $page->content;
        }
        return view('return', ['locations' => session('location'), 'banners' => $this->allBanners, 'categorys' => $this->allCategorys, 'subcategorys' => $this->allSubCategorys, 'states' => $this->states, 'return' => $return]);
    }

    public function showTerms()
    {
        $pages = Page::where('name', 'terms')->get();
        $terms = '';
        foreach ($pages as $page) {
            $terms = $page->content;
        }
        return view('terms', ['locations' => session('location'), 'banners' => $this->allBanners, 'categorys' => $this->allCategorys, 'subcategorys' => $this->allSubCategorys, 'states' => $this->states, 'terms' => $terms]);
    }

    public function changeLocation(Request $request)
    {
        /*if($request->has('stat')){
             session(['location' => $request->stat]);
        }*/
        session(['location' => $request->stat]);
        return Response::json(['locations' => session('location'), 'msg'=>'Item Add Successfully']);
    }

    public function customLogin(Request $request)
    {

        $rules = array(
            'email' => 'required|email|max:255',
            'password' => 'required|min:8',
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('success' => false, 'errors'=>$validator->getMessageBag()));
        }else{
            // create our user data for the authentication
            $userdata = array(
                'email'     => $request->email,
                'password'  => $request->password
            );
            // attempt to do the login
            if (Auth::attempt($userdata)) {
                return Response::json(array('success' => 'Login Successfully!'));
                return redirect('admin');
            } else {
                return Response::json(array('success' => false, 'msg'=>'Login Failed! Username and password is incorrect.'));
            }
        }
    }

    public function showItemsByCategory(Request $request)
    {
        $urls = Category::where('category_url', $request->url)->get();
        foreach ($urls as $url) {
            $id = $url->id;
        }

        $category = Category::where('id', $id)->get();

        $subcates = SubCategory::where('sub_category_name', 'NOT LIKE', '%~%')->join('categories', 'categories.id', '=', 'sub_categories.category_id')->select('categories.category_url', 'sub_categories.sub_category_name', 'sub_categories.sub_category_url')->orderBy('sub_categories.sub_category_name', 'asc')->where('category_id', $id)->get();


        $allProducts = Product::join('categories', 'products.category_id', '=', 'categories.id')->leftJoin('images', 'products.id',   '=', 'images.product_id')->where('images.main_image', '=', '1')->select('products.id', 'categories.category_name', 'products.sub_category_id', 'products.name', 'products.code', 'images.image')->where('products.category_id', '=', $id)->get();

        $products = '';
        foreach($allProducts as $product){
            $dis=false;
            $locs = BusinessLocation::where('product_id', $product->id)->get();
            foreach ($locs as $loc) {
                $stt = State::where('id', $loc->state_id)->where('name', session('location'))->get();
                if(count($stt) > 0){
                    $dis=true;
                }
            }

            if($dis==true){
                $products .= '<div class="col-sm-4">
                    <div class="right-area">
                        <div class="quality">
                            <h6></h6>
                        </div>
                            <img src="'.asset('images/'.$product->image).'" class="img-fluid right-img">

                        <div class="right-text">

                            <h5>'.$product->name.'</h5>';
                        $products .= '<ul class="star list-inline">';
                        $average = Review::where('product_id', $product->id)->avg('rating');
                        for ($i=1; $i <= 5; $i++) {
                            if($i<=$average){
                                $products .= '<li class="list-inline-item"><span class="fa fa-star checked"></span></li>';
                            }else{
                                $products .= '<li class="list-inline-item"><span class="fa fa-star"></span></li> ';
                            }
                        }
                        $products .= '</ul>

                            <span class="right-price">';
                            if($product->discount != null){
                                $products .= 'Rs. ';
                                $products .= ($product->price-$product->discount);
                                $products .= '<strike>Rs.'.$product->price.'</strike>';
                            }else{
                                $products .= 'Rs. '.$product->price;
                            }
                            $products .= '</span>
                        </div>
                         <button type="submit" id="pro'.$product->id.'" onclick="singleProduct(this.id)" value="'.route('code').'/'.$product->code.'" class="right-btn">View Product</button>
                    </div>
                </div>';
            }
        }

        ////////////////////////////////////////// PACKAGE //////////////////////////////////////////

        $allPackages = Package::select('packages.id', 'categories.category_name', 'packages.sub_category_id', 'packages.name', 'packages.code', 'images.image')->leftJoin('categories', 'packages.category_id', '=', 'categories.id')->leftJoin('images', 'packages.id',   '=', 'images.package_id')->where('images.main_image', '=', '1')->where('packages.category_id', '=', $id)->get();

        $packages = '';


        foreach ($allPackages as $package) {
            $dis=false;
            $locs = BusinessLocation::where('package_id', $package->id)->get();
            foreach ($locs as $loc) {
                $stt = State::where('id', $loc->state_id)->where('name', session('location'))->get();
                if(count($stt) > 0){
                    $dis=true;
                }
            }

            if($dis==true){
                $price = 0;
                $samogris = Samogri::where('package_id', $package->id)->get();
                foreach ($samogris as $samg) {
                   $prds = Item::where('id', $samg->item_id)->get();
                   foreach ($prds as $prd) {
                        $price += $prd->price;
                   }
                }
                $packages .= '<div class="col-sm-4">
                    <div class="right-area">
                        <div class="quality">
                            <h6>';
                                if($package->quality=='A'){
                                    $packages .= 'Deluxe';
                                }else if($package->quality=='B'){
                                    $packages .= 'Premium';
                                }else if($package->quality=='C'){
                                    $packages .= 'Standard';
                                }else if($package->quality=='D'){
                                    $packages .= 'Basic';
                                }
                                $packages .= '</h6>
                        </div>
                            <img src="'.asset('images/'.$package->image).'" class="img-fluid right-img">

                        <div class="right-text">

                            <h5>'.$package->name.'</h5>';
                        $packages .= '<ul class="star list-inline">';
                        $average = Review::where('package_id', $package->id)->avg('rating');
                        for ($i=1; $i <= 5; $i++) {
                            if($i<=$average){
                                $packages .= '<li class="list-inline-item"><span class="fa fa-star checked"></span></li>';
                            }else{
                                $packages .= '<li class="list-inline-item"><span class="fa fa-star"></span></li> ';
                            }
                        }
                        $packages .= '</ul>
                            <span class="right-price">';
                            if($package->discount != null){
                                $packages .= 'Rs. '.round(($price-(($package->discount * $price)/100))).'
                                    <strike>Rs. '.$price.'</strike>';
                            }else{
                                $packages .= 'Rs. '.$price;
                            }
                            $packages .= '</span>
                        </div>
                         <button type="submit" id="pack'.$package->id.'" onclick="singleProduct(this.id)" value="'.route('packag').'/'.$package->code.'" class="right-btn">View package</button>
                    </div>
                </div>';
            }
        }

        //////////////////////////////////////////// BOOKING ///////////////////////////////////////

        $allBookings = Booking::join('images', 'bookings.id',   '=', 'images.booking_id')->where('images.main_image', '=', '1')->where('bookings.category_id', '=', $id)->where('bookings.status', '1')->select('bookings.id', 'bookings.name', 'bookings.code', 'bookings.performance_fee', 'images.image')->get();

        $bookings = '';
        foreach($allBookings as $booking){
            $bookings .= '<div class="col-sm-4">
                <div class="right-area">

                        <img src="'.asset('images/'.$booking->image).'" class="img-fluid right-img">

                    <div class="right-text">

                        <h5>'.$booking->name.'</h5>';
                    $bookings .= '<ul class="star list-inline">';
                    $average = Review::where('booking_id', $booking->id)->avg('rating');
                    for ($i=1; $i <= 5; $i++) {
                        if($i<=$average){
                            $bookings .= '<li class="list-inline-item"><span class="fa fa-star checked"></span></li>';
                        }else{
                            $bookings .= '<li class="list-inline-item"><span class="fa fa-star"></span></li> ';
                        }
                    }
                    $bookings .= '</ul>
                        <span class="right-price">';
                    $bookings .= 'Fees. '.$booking->performance_fee.'/-';
                        $bookings .= '</span>
                    </div>
                     <button type="submit" id="pro'.$booking->id.'" onclick="singleProduct(this.id)" value="'.route('bookin').'/'.$booking->code.'" class="right-btn">View Profile</button>
                </div>
            </div>';
        }
        // $pro_array = '';
        $attr = '';
        $attr = '<div class="side-panel">
        <div id="accordian-list">
            <ul class="lis-unstyled first-list">';
        foreach ($subcates as $subcate) {
          // $pro_array .= $subcate->id.'/';
          if($subcate->id == null){

            // $prod = Product::where('sub_category_id', $subcate->id)->get();
            // foreach ($prod as $pr) {
            //
            //   // array_push($pro_array, array('id' => $pr->id));
            // }
          }
            $attr .= '<li class="first-sub">
                    <a href="'.$subcate->category_url.'/'.$subcate->sub_category_url.'">
                        <div><h2>'.str_replace(",","<br>",$subcate->sub_category_name).'</h2></div>
                    </a>
                </li>';
        }
        $attr .= '</ul>
            </div>';
        $attr .= '</div>
        <div class="clearfix"></div>';

        return view('items', ['locations' => session('location'), 'categorys' => $this->allCategorys, 'currentCategory' => $category, 'subcategorys' => $this->allSubCategorys, 'products' => $products, 'packages' => $packages, 'bookings' => $bookings, 'attrs' => $attr, 'states' => $this->states]);
    }

    public function showItemsBySubCategory(Request $request)
    {
        $urls = Category::where('category_url', $request->url)->get();
        foreach ($urls as $url) {
            $cat_id = $url->id;
        }
        $suburls = SubCategory::where('category_id', $cat_id)->where('sub_category_url', $request->sub)->get();
        foreach ($suburls as $suburl) {
            $sub_id = $suburl->id;
        }


        $allProducts = Product::join('categories', 'products.category_id', '=', 'categories.id')->leftJoin('images', 'products.id',   '=', 'images.product_id')->where('images.main_image', '=', '1')->select('products.id', 'categories.category_name', 'products.sub_category_id', 'products.name', 'products.code', 'images.image')->where('products.category_id', $cat_id)->where('products.sub_category_id', $sub_id)->get();
        $products = '';
        foreach($allProducts as $product){
            $dis=false;
            $locs = BusinessLocation::where('product_id', $product->id)->get();
            foreach ($locs as $loc) {
                $stt = State::where('id', $loc->state_id)->where('name', session('location'))->get();
                if(count($stt) > 0){
                    $dis=true;
                }
            }

            if($dis==true){
                $products .= '<div class="col-sm-4">
                    <div class="right-area">
                        <div class="quality">
                            <h6>';
                            if($product->quality=='A'){
                                $products .= 'Deluxe';
                            }else if($product->quality=='B'){
                                $products .= 'Premium';
                            }else if($product->quality=='C'){
                                $products .= 'Standard';
                            }else if($product->quality=='D'){
                                $products .= 'Basic';
                            }
                            $products .= '</h6>
                        </div>
                            <img src="'.asset('images/'.$product->image).'" class="img-fluid right-img">

                        <div class="right-text">

                            <h5>'.$product->name.'</h5>';
                        $products .= '<ul class="star list-inline">';
                        $average = Review::where('product_id', $product->id)->avg('rating');
                        for ($i=1; $i <= 5; $i++) {
                            if($i<=$average){
                                $products .= '<li class="list-inline-item"><span class="fa fa-star checked"></span></li>';
                            }else{
                                $products .= '<li class="list-inline-item"><span class="fa fa-star"></span></li> ';
                            }
                        }
                        $products .= '</ul>

                            <span class="right-price">';
                            if($product->discount != null){
                                $products .= 'Rs. ';
                                $products .= ($product->price-$product->discount);
                                $products .= '<strike>Rs.'.$product->price.'</strike>';
                            }else{
                                $products .= 'Rs. '.$product->price;
                            }
                            $products .= '</span>
                        </div>
                         <button type="submit" id="pro'.$product->id.'" onclick="singleProduct(this.id)" value="'.route('code').'/'.$product->code.'" class="right-btn">View Product</button>
                    </div>
                </div>';
            }
        }

        $filters = array();
        foreach ($allProducts as $prod) {
            $attTrms =  AttributeTerm::where('product_id', $prod->id)->get();
            foreach ($attTrms as $attTrm) {
                $attr=false;
                $trm=false;
                $attributes = Attribute::where('id', $attTrm->attribute_id)->get();
                foreach ($attributes as $attribute) {
                    $terms = Term::where('id', $attTrm->term_id)->where('attribute_id', $attribute->id)->orderBy('name', 'asc')->get();
                    foreach ($terms as $term) {
                        if($attTrm->attribute_id!='' && $attTrm->term_id!=''){
                          // dd($filters);
                          if(array_key_exists($attribute->name, $filters)){
                            $attr=true;
                            // dd($attr);
                            foreach($filters as $key => $value){
                              foreach ($value as $k => $itm) {
                                if($term->name == $itm['name']){
                                    $trm=true;
                                    $filters[$key][$k]['qty']=($itm['qty']+1);
                                }
                              }
                            }
                          }

                            if($attr==true && $trm==false){
                                foreach($filters as $key => $value){
                                  if($key == $attribute->name){
                                    if($attTrm->term_id!=''){
                                        array_push($filters[$key], array('name' => $term->name, 'qty' => 1, 'url' => $term->url));
                                    }
                                  }
                                }
                            }
                            if($attr==false && $trm==false){
                              $filters[$attribute->name][0] = array('name' => $term->name, 'qty' => 1, 'url' => $term->url);
                            }
                        }
                    }
                }
            }
        }
        // dd($filters);
        $attr_p = '';
        $attr_p = '<div class="side-panel">';

        foreach ($filters as $key => $value) {
            // dd($value);
            $attr_p .= '<div id="accordian-list">
                <ul class="lis-unstyled first-list">
                    <li class="first-sub">';
            $attr_p .= '<a class="expand">
                            <div class="right-ico"><i class="fa fa-chevron-right fa-lg"></i></div>
                            <div><h3>'.str_replace(",","<br>",$key).'</h3></div>

                        </a>';
            $attr_p .= '<div class="detail">';
            $attr_p .= '<ul class="list-unstyled sub-list">';
            foreach($value as $k => $v){
              $attr = Attribute::where('name', $key)->first();
               $attr_p .= '<li><a href="'.route('itemsShowByTerms').'/'.$request->url.'/'.$request->sub.'/'.$attr->id.'/'.$v['url'].'">('.$v['qty'].') '.str_replace(",","<br>",$v['name']).'</a></a></li>';
            }
            $attr_p .= '</ul>';
            $attr_p .= '</div>';
            $attr_p .= '</li>
                    </ul>
                </div>';
        }
        $attr_p .= '</div>
            <div class="clearfix"></div>';

        $allPackages = Package::select('packages.id', 'categories.category_name', 'packages.sub_category_id', 'packages.name', 'packages.code', 'images.image')->leftJoin('categories', 'packages.category_id', '=', 'categories.id')->leftJoin('images', 'packages.id',   '=', 'images.package_id')->where('images.main_image', '=', '1')->where('packages.category_id', $cat_id)->where('packages.sub_category_id', $sub_id)->get();

        $packages = '';

        foreach ($allPackages as $package) {
            $dis=false;
            $locs = BusinessLocation::where('package_id', $package->id)->get();
            foreach ($locs as $loc) {
                $stt = State::where('id', $loc->state_id)->where('name', session('location'))->get();
                if(count($stt) > 0){
                    $dis=true;
                }
            }

            if($dis==true){
                $price = 0;
                $samogris = Samogri::where('package_id', $package->id)->get();
                foreach ($samogris as $samg) {
                   $prds = Item::where('id', $samg->item_id)->get();
                   foreach ($prds as $prd) {
                        $price += $prd->price;
                   }
                }
                $packages .= '<div class="col-sm-4">
                    <div class="right-area">
                        <div class="quality">
                            <h6>';
                            if($package->quality=='A'){
                                $packages .= 'Deluxe';
                            }else if($package->quality=='B'){
                                $packages .= 'Premium';
                            }else if($package->quality=='C'){
                                $packages .= 'Standard';
                            }else if($package->quality=='D'){
                                $packages .= 'Basic';
                            }
                            $packages .= '</h6>
                        </div>
                            <img src="'.asset('images/'.$package->image).'" class="img-fluid right-img">

                        <div class="right-text">

                            <h5>'.$package->name.'</h5>';
                        $packages .= '<ul class="star list-inline">';
                        $average = Review::where('package_id', $package->id)->avg('rating');
                        for ($i=1; $i <= 5; $i++) {
                            if($i<=$average){
                                $packages .= '<li class="list-inline-item"><span class="fa fa-star checked"></span></li>';
                            }else{
                                $packages .= '<li class="list-inline-item"><span class="fa fa-star"></span></li> ';
                            }
                        }
                        $packages .= '</ul>
                            <span class="right-price">';
                            if($package->discount != null){
                                $packages .= 'Rs. '.round(($price-(($package->discount * $price)/100))).'
                                    <strike>Rs. '.$price.'</strike>';
                            }else{
                                $packages .= 'Rs. '.$price;
                            }
                            $packages .= '</span>
                        </div>
                         <button type="submit" id="pack'.$package->id.'" onclick="singleProduct(this.id)" value="'.route('packag').'/'.$package->code.'" class="right-btn">View package</button>
                    </div>
                </div>';
            }
        }

        $packfilters = array();
        foreach ($allPackages as $pack) {
            $attTrms =  AttributeTerm::where('package_id', $pack->id)->get();
            foreach ($attTrms as $attTrm) {
                $attr=false;
                $trm=false;
                $attributes = Attribute::where('id', $attTrm->attribute_id)->get();
                foreach ($attributes as $attribute) {
                    $terms = Term::where('id', $attTrm->term_id)->where('attribute_id', $attribute->id)->orderBy('name', 'asc')->get();
                    foreach ($terms as $term) {
                        if($attTrm->attribute_id!='' && $attTrm->term_id!=''){
                          // dd($packfilters);
                          if(array_key_exists($attribute->name, $packfilters)){
                            $attr=true;
                            // dd($attr);
                            foreach($packfilters as $key => $value){
                              foreach ($value as $k => $itm) {
                                if($term->name == $itm['name']){
                                    $trm=true;
                                    $packfilters[$key][$k]['qty']=($itm['qty']+1);
                                }
                              }
                            }
                          }

                            if($attr==true && $trm==false){
                                foreach($packfilters as $key => $value){
                                  if($key == $attribute->name){
                                    if($attTrm->term_id!=''){
                                        array_push($packfilters[$key], array('name' => $term->name, 'qty' => 1, 'url' => $term->url));
                                    }
                                  }
                                }
                            }
                            if($attr==false && $trm==false){
                              $packfilters[$attribute->name][0] = array('name' => $term->name, 'qty' => 1, 'url' => $term->url);
                            }
                        }
                    }
                }
            }
        }
        // dd($packfilters);
        $attr_pak = '';
        $attr_pak = '<div class="side-panel">';

        foreach ($packfilters as $key => $value) {
            // dd($value);
            $attr_pak .= '<div id="accordian-list">
                <ul class="lis-unstyled first-list">
                    <li class="first-sub">';
            $attr_pak .= '<a class="expand">
                            <div class="right-ico"><i class="fa fa-chevron-right fa-lg"></i></div>
                            <div><h3>'.str_replace(",","<br>",$key).'</h3></div>
                        </a>';
            $attr_pak .= '<div class="detail">';
            $attr_pak .= '<ul class="list-unstyled sub-list">';
            foreach($value as $k => $v){
              $attr = Attribute::where('name', $key)->first();
               $attr_pak .= '<li><a href="'.route('itemsShowByTerms').'/'.$request->url.'/'.$request->sub.'/'.$attr->id.'/'.$v['url'].'">('.$v['qty'].') '.str_replace(",","<br>",$v['name']).'</a></li>';
            }
            $attr_pak .= '</ul>';
            $attr_pak .= '</div>';
            $attr_pak .= '</li>
                    </ul>
                </div>';
        }
        $attr_pak .= '</div>
            <div class="clearfix"></div>';

        $allBookings = Booking::join('images', 'bookings.id',   '=', 'images.booking_id')->where('images.main_image', '=', '1')->where('bookings.category_id', $cat_id)->where('bookings.sub_category_id', '=', $sub_id)->where('bookings.status', '1')->select('bookings.id', 'bookings.name', 'bookings.code', 'bookings.performance_fee', 'images.image')->get();
        $bookings = '';
        foreach($allBookings as $booking){
                $bookings .= '<div class="col-sm-4">
                    <div class="right-area">

                            <img src="'.asset('images/'.$booking->image).'" class="img-fluid right-img">

                        <div class="right-text">

                            <h5>'.$booking->name.'</h5>';
                        $bookings .= '<ul class="star list-inline">';
                        $average = Review::where('booking_id', $booking->id)->avg('rating');
                        for ($i=1; $i <= 5; $i++) {
                            if($i<=$average){
                                $bookings .= '<li class="list-inline-item"><span class="fa fa-star checked"></span></li>';
                            }else{
                                $bookings .= '<li class="list-inline-item"><span class="fa fa-star"></span></li> ';
                            }
                        }
                        $bookings .= '</ul>
                            <span class="right-price">';
                        $bookings .= 'Fees. '.$booking->performance_fee.'/-';
                            $bookings .= '</span>
                        </div>
                         <button type="submit" id="pro'.$booking->id.'" onclick="singleProduct(this.id)" value="'.route('bookin').'/'.$booking->code.'" class="right-btn">View Profile</button>
                    </div>
                </div>';
        }

        return view('items', ['locations' => session('location'), 'categorys' => $this->allCategorys, 'subcategorys' => $this->allSubCategorys, 'products' => $products, 'packages' => $packages, 'bookings' => $bookings, 'productAttr' => $attr_p, 'packageAttr' => $attr_pak, 'states' => $this->states]);
    }

    public function itemsShowByTerms(Request $request)
    {
        //dd($request->url);
        //dd($request->sub);
        // dd($request->attr);
        //dd($request->term);
        $urls = Category::where('category_url', $request->url)->get();
        foreach ($urls as $url) {
            $cat_id = $url->id;
        }
        $suburls = SubCategory::where('sub_category_url', $request->sub)->get();
        foreach ($suburls as $suburl) {
            $sub_id = $suburl->id;
        }

        $terms = Term::where('attribute_id', $request->attr)->where('url', $request->term)->get();
        foreach ($terms as $term) {
            $term_id = $term->id;
        }

        $allProducts = Product::join('categories', 'products.category_id', '=', 'categories.id')->leftJoin('images', 'products.id',   '=', 'images.product_id')->where('images.main_image', '=', '1')->select('products.id', 'categories.category_name', 'products.sub_category_id', 'products.name', 'products.code', 'images.image')->where('products.category_id', $cat_id)->where('products.sub_category_id', $sub_id)->get();
        $products = '';
        // $pro = '';
        foreach($allProducts as $product){
            $dis=false;
            $locs = BusinessLocation::where('product_id', $product->id)->get();
            foreach ($locs as $loc) {
                $stt = State::where('id', $loc->state_id)->where('name', session('location'))->get();
                if(count($stt) > 0){
                    $dis=true;
                }
            }

            if($dis==true){
                $attrm = AttributeTerm::where('product_id', $product->id)->where('term_id', $term_id)->get();
                // $pro .= $product->id.'--'.$term_id.'//';
                if(count($attrm)>0){

                    $products .= '<div class="col-sm-4">
                        <div class="right-area">
                            <div class="quality">
                                <h6>';
                                if($product->quality=='A'){
                                    $products .= 'Deluxe';
                                }else if($product->quality=='B'){
                                    $products .= 'Premium';
                                }else if($product->quality=='C'){
                                    $products .= 'Standard';
                                }else if($product->quality=='D'){
                                    $products .= 'Basic';
                                }
                                $products .= '</h6>
                            </div>
                                <img src="'.asset('images/'.$product->image).'" class="img-fluid right-img">

                            <div class="right-text">

                                <h5>'.$product->name.'</h5>';
                            $products .= '<ul class="star list-inline">';
                            $average = Review::where('product_id', $product->id)->avg('rating');
                            for ($i=1; $i <= 5; $i++) {
                                if($i<=$average){
                                    $products .= '<li class="list-inline-item"><span class="fa fa-star checked"></span></li>';
                                }else{
                                    $products .= '<li class="list-inline-item"><span class="fa fa-star"></span></li> ';
                                }
                            }
                            $products .= '</ul>

                                <span class="right-price">';
                                if($product->discount != null){
                                    $products .= 'Rs. ';
                                    $products .= ($product->price-$product->discount);
                                    $products .= '<strike>Rs.'.$product->price.'</strike>';
                                }else{
                                    $products .= 'Rs. '.$product->price;
                                }
                                $products .= '</span>
                            </div>
                             <button type="submit" id="pro'.$product->id.'" onclick="singleProduct(this.id)" value="'.route('code').'/'.$product->code.'" class="right-btn">View Product</button>
                        </div>
                    </div>';
                }

            }
        }
        $filters = array();
        foreach ($allProducts as $prod) {
            $attTrms =  AttributeTerm::where('product_id', $prod->id)->get();
            foreach ($attTrms as $attTrm) {
                $attr=false;
                $trm=false;
                $attributes = Attribute::where('id', $attTrm->attribute_id)->get();
                foreach ($attributes as $attribute) {
                    $terms = Term::where('id', $attTrm->term_id)->where('attribute_id', $attribute->id)->orderBy('name', 'asc')->get();
                    foreach ($terms as $term) {
                        if($attTrm->attribute_id!='' && $attTrm->term_id!=''){
                          // dd($filters);
                          if(array_key_exists($attribute->name, $filters)){
                            $attr=true;
                            // dd($attr);
                            foreach($filters as $key => $value){
                              foreach ($value as $k => $itm) {
                                if($term->name == $itm['name']){
                                    $trm=true;
                                    $filters[$key][$k]['qty']=($itm['qty']+1);
                                }
                              }
                            }
                          }

                            if($attr==true && $trm==false){
                                foreach($filters as $key => $value){
                                  if($key == $attribute->name){
                                    if($attTrm->term_id!=''){
                                        array_push($filters[$key], array('name' => $term->name, 'qty' => 1, 'url' => $term->url));
                                    }
                                  }
                                }
                            }
                            if($attr==false && $trm==false){
                              $filters[$attribute->name][0] = array('name' => $term->name, 'qty' => 1, 'url' => $term->url);
                            }
                        }
                    }
                }
            }
        }
        // dd($filters);
        $attr_p = '';
        $attr_p = '<div class="side-panel">';

        foreach ($filters as $key => $value) {
            // dd($value);
            $attr_p .= '<div id="accordian-list">
                <ul class="lis-unstyled first-list">
                    <li class="first-sub">';
            $attr_p .= '<a class="expand">
                            <div class="right-ico"><i class="fa fa-chevron-right fa-lg"></i></div>
                            <div><h3>'.str_replace(",","<br>",$key).'</h3></div>
                        </a>';
            $attr_p .= '<div class="detail">';
            $attr_p .= '<ul class="list-unstyled sub-list">';
            foreach($value as $k => $v){
              $attr = Attribute::where('name', $key)->first();
               $attr_p .= '<li><a href="'.route('itemsShowByTerms').'/'.$request->url.'/'.$request->sub.'/'.$attr->id.'/'.$v['url'].'">('.$v['qty'].') '.str_replace(",","<br>",$v['name']).'</a></a></li>';
            }
            $attr_p .= '</ul>';
            $attr_p .= '</div>';
            $attr_p .= '</li>
                    </ul>
                </div>';
        }
        $attr_p .= '</div>
            <div class="clearfix"></div>';

        $allPackages = Package::select('packages.id', 'categories.category_name', 'packages.sub_category_id', 'packages.name', 'packages.code', 'images.image')->leftJoin('categories', 'packages.category_id', '=', 'categories.id')->leftJoin('images', 'packages.id',   '=', 'images.package_id')->where('images.main_image', '=', '1')->where('packages.category_id', $cat_id)->where('packages.sub_category_id', $sub_id)->get();

        $packages = '';

        foreach ($allPackages as $package) {
            $dis=false;
            $locs = BusinessLocation::where('package_id', $package->id)->get();
            foreach ($locs as $loc) {
                $stt = State::where('id', $loc->state_id)->where('name', session('location'))->get();
                if(count($stt) > 0){
                    $dis=true;
                }
            }

            if($dis==true){
                $price = 0;
                $samogris = Samogri::where('package_id', $package->id)->get();
                foreach ($samogris as $samg) {
                   $prds = Item::where('id', $samg->item_id)->get();
                   foreach ($prds as $prd) {
                        $price += $prd->price;
                   }
                }
                $attrm = AttributeTerm::where('package_id', $package->id)->where('term_id', $term_id)->get();
                if(count($attrm)>0){
                    $packages .= '<div class="col-sm-4">
                        <div class="right-area">
                            <div class="quality">
                                <h6>';
                                if($package->quality=='A'){
                                    $packages .= 'Deluxe';
                                }else if($package->quality=='B'){
                                    $packages .= 'Premium';
                                }else if($package->quality=='C'){
                                    $packages .= 'Standard';
                                }else if($package->quality=='D'){
                                    $packages .= 'Basic';
                                }
                                $packages .= '</h6>
                            </div>
                                <img src="'.asset('images/'.$package->image).'" class="img-fluid right-img">

                            <div class="right-text">

                                <h5>'.$package->name.'</h5>';
                            $packages .= '<ul class="star list-inline">';
                            $average = Review::where('package_id', $package->id)->avg('rating');
                            for ($i=1; $i <= 5; $i++) {
                                if($i<=$average){
                                    $packages .= '<li class="list-inline-item"><span class="fa fa-star checked"></span></li>';
                                }else{
                                    $packages .= '<li class="list-inline-item"><span class="fa fa-star"></span></li> ';
                                }
                            }
                            $packages .= '</ul>
                                <span class="right-price">';
                                if($package->discount != null){
                                    $packages .= 'Rs. '.round(($price-(($package->discount * $price)/100))).'
                                        <strike>Rs. '.$price.'</strike>';
                                }else{
                                    $packages .= 'Rs. '.$price;
                                }
                                $packages .= '</span>
                            </div>
                             <button type="submit" id="pack'.$package->id.'" onclick="singleProduct(this.id)" value="'.route('packag').'/'.$package->code.'" class="right-btn">View package</button>
                        </div>
                    </div>';
                }
            }
        }

        $packfilters = array();
        foreach ($allPackages as $pack) {
            $attTrms =  AttributeTerm::where('package_id', $pack->id)->get();
            foreach ($attTrms as $attTrm) {
                $attr=false;
                $trm=false;
                $attributes = Attribute::where('id', $attTrm->attribute_id)->get();
                foreach ($attributes as $attribute) {
                    $terms = Term::where('id', $attTrm->term_id)->where('attribute_id', $attribute->id)->orderBy('name', 'asc')->get();
                    foreach ($terms as $term) {
                        if($attTrm->attribute_id!='' && $attTrm->term_id!=''){
                          // dd($packfilters);
                          if(array_key_exists($attribute->name, $packfilters)){
                            $attr=true;
                            // dd($attr);
                            foreach($packfilters as $key => $value){
                              foreach ($value as $k => $itm) {
                                if($term->name == $itm['name']){
                                    $trm=true;
                                    $packfilters[$key][$k]['qty']=($itm['qty']+1);
                                }
                              }
                            }
                          }

                            if($attr==true && $trm==false){
                                foreach($packfilters as $key => $value){
                                  if($key == $attribute->name){
                                    if($attTrm->term_id!=''){
                                        array_push($packfilters[$key], array('name' => $term->name, 'qty' => 1, 'url' => $term->url));
                                    }
                                  }
                                }
                            }
                            if($attr==false && $trm==false){
                              $packfilters[$attribute->name][0] = array('name' => $term->name, 'qty' => 1, 'url' => $term->url);
                            }
                        }
                    }
                }
            }
        }
        // dd($packfilters);
        $attr_pak = '';
        $attr_pak = '<div class="side-panel">';

        foreach ($packfilters as $key => $value) {
            // dd($value);
            $attr_pak .= '<div id="accordian-list">
                <ul class="lis-unstyled first-list">
                    <li class="first-sub">';
            $attr_pak .= '<a class="expand">
                            <div class="right-ico"><i class="fa fa-chevron-right fa-lg"></i></div>
                            <div><h3>'.str_replace(",","<br>",$key).'</h3></div>
                        </a>';
            $attr_pak .= '<div class="detail">';
            $attr_pak .= '<ul class="list-unstyled sub-list">';
            foreach($value as $k => $v){
              $attr = Attribute::where('name', $key)->first();
               $attr_pak .= '<li><a href="'.route('itemsShowByTerms').'/'.$request->url.'/'.$request->sub.'/'.$attr->id.'/'.$v['url'].'">('.$v['qty'].') '.str_replace(",","<br>",$v['name']).'</a></a></li>';
            }
            $attr_pak .= '</ul>';
            $attr_pak .= '</div>';
            $attr_pak .= '</li>
                    </ul>
                </div>';
        }
        $attr_pak .= '</div>
            <div class="clearfix"></div>';

        $allBookings = Booking::join('images', 'bookings.id',   '=', 'images.booking_id')->where('images.main_image', '=', '1')->where('bookings.category_id', $cat_id)->where('bookings.sub_category_id', '=', $sub_id)->where('bookings.status', '1')->select('bookings.id', 'bookings.name', 'bookings.code', 'bookings.performance_fee', 'images.image')->get();
        $bookings = '';
        foreach($allBookings as $booking){
                $bookings .= '<div class="col-sm-4">
                    <div class="right-area">

                            <img src="'.asset('images/'.$booking->image).'" class="img-fluid right-img">

                        <div class="right-text">

                            <h5>'.$booking->name.'</h5>';
                        $bookings .= '<ul class="star list-inline">';
                        $average = Review::where('booking_id', $booking->id)->avg('rating');
                        for ($i=1; $i <= 5; $i++) {
                            if($i<=$average){
                                $bookings .= '<li class="list-inline-item"><span class="fa fa-star checked"></span></li>';
                            }else{
                                $bookings .= '<li class="list-inline-item"><span class="fa fa-star"></span></li> ';
                            }
                        }
                        $bookings .= '</ul>
                            <span class="right-price">';
                        $bookings .= 'Fees. '.$booking->performance_fee.'/-';
                            $bookings .= '</span>
                        </div>
                         <button type="submit" id="pro'.$booking->id.'" onclick="singleProduct(this.id)" value="'.route('bookin').'/'.$booking->code.'" class="right-btn">View Profile</button>
                    </div>
                </div>';
        }

        return view('items', ['locations' => session('location'), 'categorys' => $this->allCategorys, 'subcategorys' => $this->allSubCategorys, 'products' => $products, 'packages' => $packages, 'bookings' => $bookings, 'productAttr' => $attr_p, 'packageAttr' => $attr_pak, 'states' => $this->states]);
    }

    public function singleProduct(Request $request)
    {
        $code = $request->route('code');

        $products = Product::join('images', 'products.id',   '=', 'images.product_id')->where('images.main_image', '=', '1')->select('products.id', 'products.name', 'products.code', 'images.image')->where('products.code', '<>', $code)->orderBy('products.name', 'asc')->get();

        $product = Product::where('code', $code)->get();
        foreach ($product as $key => $value) {
           $id = $value->id;
        }
        $images = Image::where('product_id', $id)->get();

        $allRevious = Review::where('product_id', $id)->get();
        $revi = '<div style="overflow-x:auto;">
          <table class="table">';
          $revi .= '<tr>';
          $revi .= '<th>Reating</th><th>Review</th><th>Review By</th>';
          /*if(Auth::check()){
             $revi .= '<th>Action</th>';
          }*/
          $revi .= '</tr>';
        foreach ($allRevious as $review) {
            $revi .= '<tr>
                <td>';
            for ($i=1; $i <= 5; $i++) {
                if($i<=$review->rating){
                    $revi .= '<span class="fa fa-star checked"></span> ';
                }else{
                    $revi .= '<span class="fa fa-star"></span> ';
                }
            }

            $revi .= '</td><td>';
            $revi .=  $review->review;
            $revi .= '</td><td>';
            $revi .=  $review->name;
            $revi .= '</td>';
            /*if(Auth::check()){
                $user = Auth::user();
                if($user->email==$review->email){
                    $revi .= '<td>';
                    $revi .=  '<button type="button" value="'.$review->id.'" class="btn btn-link delete_review"><i class="fa fa-trash-o"></i></button>';
                    $revi .= '</td>';
                }else{
                    $revi .= '<td>';
                    $revi .= '</td>';
                }
            }*/
            $revi .= '</tr>';
        }
        $revi .= '</table>
            </div>';
        $average = Review::where('product_id', $id)->avg('rating');
        $star = '';
        for ($i=1; $i <= 5; $i++) {
            if($i<=$average){
                $star .= '<span class="fa fa-star checked"></span> ';
            }else{
                $star .= '<span class="fa fa-star"></span> ';
            }
        }
        $totalReviews = Review::where('product_id', $id)->count();

        return view('product', ['locations' => session('location'), 'reviews'=>$star, 'allReviews'=>$revi, 'totalReviews'=>$totalReviews.' reviews', 'categorys' => $this->allCategorys, 'subcategorys' => $this->allSubCategorys, 'product' => $product, 'products' => $products, 'images' => $images, 'states' => $this->states]);
    }

    public function singlePackage(Request $request)
    {
        $code = $request->route('code');

        $packages = Package::join('images', 'packages.id',   '=', 'images.package_id')->where('images.main_image', '=', '1')->select('packages.id', 'packages.name', 'packages.code', 'images.image')->where('packages.code', '<>', $code)->orderBy('packages.name', 'asc')->get();

        $package = Package::where('code', $code)->get();
        $price = 0;
        $grand_price = 0;
        $discount_price = 0;
        $view_smg = '';
        $i = 1;
        //dd($request->session()->get('order'));
        $pak = false;
        foreach ($package as $key => $value) {
            $id = $value->id;
            $samogris = Samogri::where('package_id', $id)->get();
            foreach ($samogris as $samg) {
                $prds = Item::where('id', $samg->item_id)->get();
                foreach ($prds as $prd) {

                    if($request->session()->has('order')){

                        foreach ($request->session()->get('order') as $each_array) {

                            if($each_array['package_id']==$value->id){
                                $pak = true;
                                $ave = false;
                                foreach ($each_array['items'] as $k => $v) {
                                        if($v['item_id']==$prd->id){
                                            $ave = true;
                                            $itm_qty = $v['quantity'];

                                        }


                                     //echo $v['item_id'].'<br>';
                                    /*if($v['item_id']==$prd->id){
                                        $ave = true;
                                        $itm_qty = $v['quantity'];

                                    }else{
                                       $ave = false;
                                    }*/
                                }

                            }
                        }
                    }
                    if($pak == true){
                        if($ave == true){
                            //dd($itm_qty);
                            $view_smg .= '<tr>
                                <td>
                                    <input type="hidden" id="total_itm'.$i.'" name="item_total[]" value="'.$itm_qty.'">
                                    <input type="hidden" name="status[]" value="1">
                                    <input type="checkbox" id="itm'.$i.'" name="item_id[]" value="'.$prd->id.'" checked> '.$prd->name.'
                                </td>
                                <td>
                                    <input type="hidden" id="size_weight'.$i.'" value="'.$prd->size_weight.'">
                                    <span id="ws'.$i.'">'.$prd->size_weight*$itm_qty.'</span>'.$prd->sw_unit.'
                                </td>
                                <td>
                                    <input type="hidden" id="quantaty'.$i.'" value="'.$prd->quantity.'">
                                    <span id="qnt'.$i.'">'.$prd->quantity*$itm_qty.'</span>'.$prd->q_unit.'
                                </td>
                                <td>
                                    <span class="crd-btn">
                                        <button type="button" id="plus'.$i.'">+</button>
                                        <button type="button" id="minus'.$i.'">-</button>
                                    </span>
                                </td>
                            </tr>';
                            $price += $prd->price*$itm_qty;
                            $grand_price += $prd->price*$itm_qty;
                        }else{
                           $view_smg .= '<tr>
                                <td>
                                    <input type="hidden" id="total_itm'.$i.'" name="item_total[]" value="1">
                                    <input type="hidden" name="status[]" value="1">
                                    <input type="checkbox" id="itm'.$i.'" name="item_id[]" value="'.$prd->id.'"> '.$prd->name.'
                                </td>
                                <td>
                                    <input type="hidden" id="size_weight'.$i.'" value="'.$prd->size_weight.'">
                                    <span id="ws'.$i.'">'.$prd->size_weight.'</span>'.$prd->sw_unit.'
                                </td>
                                <td>
                                    <input type="hidden" id="quantaty'.$i.'" value="'.$prd->quantity.'">
                                    <span id="qnt'.$i.'">'.$prd->quantity.'</span>'.$prd->q_unit.'
                                </td>
                                <td>
                                    <span class="crd-btn">
                                        <button type="button" id="plus'.$i.'">+</button>
                                        <button type="button" id="minus'.$i.'">-</button>
                                    </span>
                                </td>
                            </tr>';
                            // $price += $prd->price;
                            $grand_price += $prd->price;
                        }
                    }else{
                       $view_smg .= '<tr>
                            <td>
                                <input type="hidden" id="total_itm'.$i.'" name="item_total[]" value="1">
                                <input type="hidden" name="status[]" value="1">
                                <input type="checkbox" id="itm'.$i.'" name="item_id[]" value="'.$prd->id.'" checked> '.$prd->name.'
                            </td>
                            <td>
                                <input type="hidden" id="size_weight'.$i.'" value="'.$prd->size_weight.'">
                                <span id="ws'.$i.'">'.$prd->size_weight.'</span>'.$prd->sw_unit.'
                            </td>
                            <td>
                                <input type="hidden" id="quantaty'.$i.'" value="'.$prd->quantity.'">
                                <span id="qnt'.$i.'">'.$prd->quantity.'</span>'.$prd->q_unit.'
                            </td>
                            <td>
                                <span class="crd-btn">
                                    <button type="button" id="plus'.$i.'">+</button>
                                    <button type="button" id="minus'.$i.'">-</button>
                                </span>
                            </td>
                        </tr>';
                        $price += $prd->price;
                        $grand_price += $prd->price;
                    }


                $i++;
               }
            }
            if($value->discount != null){
                $discount_price = round(($price-(($value->discount*$price)/100)));
            }else{
                $discount_price = $price;
            }
        }
        //dd($request->session()->get('order'));
        $images = Image::where('package_id', $id)->get();

        $allRevious = Review::where('package_id', $id)->get();
        $revi = '<div style="overflow-x:auto;">
          <table class="table">';
          $revi .= '<tr>';
          $revi .= '<th>Reating</th><th>Review</th><th>Review By</th>';
          $revi .= '</tr>';
        foreach ($allRevious as $review) {
            $revi .= '<tr>
                <td>';
            for ($i=1; $i <= 5; $i++) {
                if($i<=$review->rating){
                    $revi .= '<span class="fa fa-star checked"></span> ';
                }else{
                    $revi .= '<span class="fa fa-star"></span> ';
                }
            }

            $revi .= '</td><td>';
            $revi .=  $review->review;
            $revi .= '</td><td>';
            $revi .=  $review->name;
            $revi .= '</td>';
            $revi .= '</tr>';
        }
        $revi .= '</table>
            </div>';
        $average = Review::where('package_id', $id)->avg('rating');
        $star = '';
        for ($i=1; $i <= 5; $i++) {
            if($i<=$average){
                $star .= '<span class="fa fa-star checked"></span> ';
            }else{
                $star .= '<span class="fa fa-star"></span> ';
            }
        }
        $totalReviews = Review::where('package_id', $id)->count();

        return view('package', ['locations' => session('location'), 'reviews'=>$star, 'allReviews'=>$revi, 'totalReviews'=>$totalReviews.' reviews', 'allReviews'=>$revi, 'categorys' => $this->allCategorys, 'subcategorys' => $this->allSubCategorys, 'package' => $package, 'packages' => $packages, 'price' => $grand_price, 'samogries' => $view_smg, 'discountPrice' => $discount_price, 'images' => $images, 'states' => $this->states]);
    }

    public function packageModify(Request $request)
    {
        $price = 0;
        $discount = 0;
        $sw = 0;
        $qnt = 0;
        $status = $request->status;
        $item_total = $request->item_total;

        foreach($request->item_id as $ind => $item){
            $prds = Item::where('id', $item)->get();
            foreach ($prds as $prd) {
                if($status[$ind] == 1){
                    $price += ($prd->price * $item_total[$ind]);
                    //$price += $prd->price;
                }
            }
        }
        $packages = Package::where('id', $request->package_id)->get();
        foreach ($packages as $package) {
            $discount = $package->discount;
        }

        $price = ($price-(($discount*$price)/100));

        if($request->select_status == 0){
            $items = Item::where('id', $request->select_id)->get();
            foreach ($items as $item) {
                $sw = $item->size_weight;
                $qnt = $item->quantity;
            }
        }

        return Response::json(['price' => round($price), 'size_weight' => $sw, 'quantaty' => $qnt]);
    }

    public function packageBooking(Request $request)
    {
        $price = 0;
        $discount = 0;
        $sw = 0;
        $qnt = 0;
        $status = $request->status;
        $item_total = $request->item_total;

        foreach($request->item_id as $ind => $item){
            $prds = Item::where('id', $item)->get();
            foreach ($prds as $prd) {
                if($status[$ind] == 1){
                    $price += ($prd->price * $item_total[$ind]);
                    //$price += $prd->price;
                }
            }
        }
        $packages = Package::where('id', $request->package_id)->get();
        foreach ($packages as $package) {
            $discount = $package->discount;
        }

        $price = ($price-(($discount*$price)/100));

        if($request->select_status == 0){
            $items = Item::where('id', $request->select_id)->get();
            foreach ($items as $item) {
                $sw = $item->size_weight;
                $qnt = $item->quantity;
            }
        }

        return Response::json(['price' => $price, 'size_weight' => $sw, 'quantaty' => $qnt]);
    }

    public function singleBooking(Request $request)
    {
        $code = $request->route('code');

        $bookings = Booking::join('images', 'bookings.id',   '=', 'images.booking_id')->where('images.main_image', '=', '1')->select('bookings.id', 'bookings.name', 'bookings.code', 'images.image')->where('bookings.code', '<>', $code)->orderBy('bookings.name', 'asc')->get();

        $booking = Booking::where('code', $code)->get();
        foreach ($booking as $key => $value) {
           $id = $value->id;
        }
        $images = Image::where('booking_id', $id)->get();

        $allRevious = Review::where('booking_id', $id)->get();
        $revi = '<div style="overflow-x:auto;">
          <table class="table">';
          $revi .= '<tr>';
          $revi .= '<th>Reating</th><th>Review</th><th>Review By</th>';
          $revi .= '</tr>';
        foreach ($allRevious as $review) {
            $revi .= '<tr>
                <td>';
            for ($i=1; $i <= 5; $i++) {
                if($i<=$review->rating){
                    $revi .= '<span class="fa fa-star checked"></span> ';
                }else{
                    $revi .= '<span class="fa fa-star"></span> ';
                }
            }

            $revi .= '</td><td>';
            $revi .=  $review->review;
            $revi .= '</td><td>';
            $revi .=  $review->name;
            $revi .= '</td>';
            $revi .= '</tr>';
        }
        $revi .= '</table>
            </div>';
        $average = Review::where('booking_id', $id)->avg('rating');
        $star = '';
        for ($i=1; $i <= 5; $i++) {
            if($i<=$average){
                $star .= '<span class="fa fa-star checked"></span> ';
            }else{
                $star .= '<span class="fa fa-star"></span> ';
            }
        }
        $totalReviews = Review::where('booking_id', $id)->count();
        //dd($images);
        return view('booking', ['locations' => session('location'), 'reviews'=>$star, 'allReviews'=>$revi, 'totalReviews'=>$totalReviews.' reviews', 'categorys' => $this->allCategorys, 'subcategorys' => $this->allSubCategorys, 'booking' => $booking, 'bookings' => $bookings, 'images' => $images, 'states' => $this->states]);
    }

    public function showProductImage(Request $request)
    {
        $id = $request->input('id');
        $Images = Image::orderBy('id', 'asc')->where('product_id', $id)->get();
        return Response::json(['images'=>$Images->image, 'main_image'->main_image]);
    }
    public function showPackageImage(Request $request)
    {
        $id = $request->input('id');
        $Images = Image::orderBy('id', 'asc')->where('package_id', $id)->get();
        return Response::json(['images'=>$Images->image, 'main_image'->main_image]);
    }
    public function showBookingImage(Request $request)
    {
        $id = $request->input('id');
        $Images = Image::orderBy('id', 'asc')->where('booking_id', $id)->get();
        return Response::json(['images'=>$Images->image, 'main_image'->main_image]);
    }

    public function showBookingVideo(Request $request)
    {
       $video = '<div class="row">';
        $videos = Booking::where('id', $request->id)->get();
        foreach ($videos as $vid) {
            if($vid->video==''){
                $video .= '<div class="col text-center"><h2>Sorry Video is not avelable</h2></div>';
            }else{
                $links = explode(',', $vid->video);
                foreach ($links as $link) {
                    $video_id = explode("?v=", $link); // For videos like http://www.youtube.com/watch?v=...
                    if (empty($video_id[1]))
                        $video_id = explode("/v/", $link); // For videos like http://www.youtube.com/watch/v/..

                    $video_id = explode("&", $video_id[1]); // Deleting any other params
                    $video .= '<div class="col-md-4 text-center"><a href="'.$link.'" target="_blank"><img src="http://img.youtube.com/vi/'.$video_id[0].'/default.jpg" class="img-fluid pro-img"></a></div>';
                }
            }
        }
        $video .= '</div>';

        return Response::json(['videos'=> $video]);
    }

    public function takeBooking(Request $request)
    {
        $rules = array(
            'booking_id' => 'required|numeric',
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|digits:10|numeric',
            'customer_qry' => 'nullable|string|min:1|max:400'
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()));
        }else{
            $bookings = Booking::where('id', $request->booking_id)->get();
            foreach ($bookings as $booking) {
                $code = $booking->code;
            }
           // dd($request->email);
            $data = ['code' => $code, 'name'=> $request->name, 'email' => $request->email, 'phone' => $request->phone, 'comm' => $request->customer_qry];
            Mail::to([$request->email,'niladripritu@gmail.com'])->send(new SendBooking($data));
            // Mail::send('email.booking', $data, function ($message) use ($request) {
            //     $message->from('info@pujabazar.com', 'Puja Bazar');
            //     $message->to([$request->email,'niladripritu@gmail.com']);
            //     $message->subject('Booking from '.$request->name);
            //
            // });
            // check for failures
            $msg = '';
            if( count(Mail::failures()) > 0 ) {
               $msg .= "There was one or more failures. They were: <br />";
               foreach(Mail::failures as $email_address) {
                   $msg .= " - $email_address <br />";
                }
            }else {
                $msg = "Your booking is confirmed, our contact person contact you soon!";
            }
            return Response::json(['msg'=>$msg]);
        }
    }

    public function reviewProduct(Request $request)
    {
        $rules = array(
            'review_for' => 'required|numeric',
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|digits:10|numeric',
            'rating' => 'required|numeric|min:1',
            'review' => 'required|string|min:1|max:400'
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()));
        }else{
            $q_reviews = Review::where('product_id', $request->review_for)->where('email', $request->email)->get();
            if(count($q_reviews)>0){
                foreach ($q_reviews as $revi) {
                   Review::where('id', $revi->id)->update(['name' => $request->name, 'rating' => $request->rating, 'review' => $request->review]);
                }

            }else{
                Review::create([
                    'product_id' => $request->review_for,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'rating' => $request->rating,
                    'review' => $request->review
                ]);
            }


            $totalReviews = Review::where('product_id', $request->review_for)->count();

            $average = Review::where('product_id', $request->review_for)->avg('rating');
            $star = '';
            for ($i=1; $i <= 5; $i++) {
                if($i<=$average){
                    $star .= '<span class="fa fa-star checked"></span>';
                }else{
                    $star .= '<span class="fa fa-star"></span>';
                }
            }
            $allRevious = Review::where('product_id', $request->review_for)->get();
            $revi = '<div style="overflow-x:auto;">
              <table class="table">';
            $revi .= '<tr>';
            $revi .= '<th>Reating</th><th>Review</th><th>Review By</th>';
            $revi .= '</tr>';
            foreach ($allRevious as $review) {
                $revi .= '<tr>
                    <td>';
                for ($i=1; $i <= 5; $i++) {
                    if($i<=$review->rating){
                        $revi .= '<span class="fa fa-star checked"></span> ';
                    }else{
                        $revi .= '<span class="fa fa-star"></span> ';
                    }
                }

                $revi .= '</td><td>';
                $revi .=  $review->review;
                $revi .= '</td><td>';
                $revi .=  $review->name;
                $revi .= '</td>';
                $revi .= '</tr>';
            }
            $revi .= '</table>
                </div>';
            return Response::json(['reviews'=>$star, 'allReviews'=>$revi, 'totalReviews'=>$totalReviews.' reviews', 'msg'=>'Review Add Successfully']);
        }
    }
    public function reviewPackage(Request $request)
    {
        $rules = array(
            'review_for' => 'required|numeric',
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|digits:10|numeric',
            'rating' => 'required|numeric|min:1',
            'review' => 'required|string|min:1|max:400'
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()));
        }else{

            $q_reviews = Review::where('package_id', $request->review_for)->where('email', $request->email)->get();
            if(count($q_reviews)>0){
                foreach ($q_reviews as $revi) {
                   Review::where('id', $revi->id)->update(['name' => $request->name, 'rating' => $request->rating, 'review' => $request->review]);
                }

            }else{
                Review::create([
                    'package_id' => $request->review_for,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'rating' => $request->rating,
                    'review' => $request->review
                ]);
            }


            $totalReviews = Review::where('package_id', $request->review_for)->count();

            $average = Review::where('package_id', $request->review_for)->avg('rating');
            $star = '';
            for ($i=1; $i <= 5; $i++) {
                if($i<=$average){
                    $star .= '<span class="fa fa-star checked"></span>';
                }else{
                    $star .= '<span class="fa fa-star"></span>';
                }
            }
            $allRevious = Review::where('package_id', $request->review_for)->get();
            $revi = '<div style="overflow-x:auto;">
              <table class="table">';
            $revi .= '<tr>';
            $revi .= '<th>Reating</th><th>Review</th><th>Review By</th>';
            $revi .= '</tr>';
            foreach ($allRevious as $review) {
                $revi .= '<tr>
                    <td>';
                for ($i=1; $i <= 5; $i++) {
                    if($i<=$review->rating){
                        $revi .= '<span class="fa fa-star checked"></span> ';
                    }else{
                        $revi .= '<span class="fa fa-star"></span> ';
                    }
                }

                $revi .= '</td><td>';
                $revi .=  $review->review;
                $revi .= '</td><td>';
                $revi .=  $review->name;
                $revi .= '</td>';
                $revi .= '</tr>';
            }
            $revi .= '</table>
                </div>';
            return Response::json(['reviews'=>$star, 'allReviews'=>$revi, 'totalReviews'=>$totalReviews.' reviews', 'msg'=>'Review Add Successfully']);
        }
    }
    public function reviewBooking(Request $request)
    {
        $rules = array(
            'review_for' => 'required|numeric',
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|digits:10|numeric',
            'rating' => 'required|numeric|min:1',
            'review' => 'required|string|min:1|max:400'
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()));
        }else{
            $q_reviews = Review::where('booking_id', $request->review_for)->where('email', $request->email)->get();
            if(count($q_reviews)>0){
                foreach ($q_reviews as $revi) {
                   Review::where('id', $revi->id)->update(['name' => $request->name, 'rating' => $request->rating, 'review' => $request->review]);
                }

            }else{
                Review::create([
                    'booking_id' => $request->review_for,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'rating' => $request->rating,
                    'review' => $request->review
                ]);
            }


            $totalReviews = Review::where('booking_id', $request->review_for)->count();

            $average = Review::where('booking_id', $request->review_for)->avg('rating');
            $star = '';
            for ($i=1; $i <= 5; $i++) {
                if($i<=$average){
                    $star .= '<span class="fa fa-star checked"></span>';
                }else{
                    $star .= '<span class="fa fa-star"></span>';
                }
            }
            $allRevious = Review::where('booking_id', $request->review_for)->get();
            $revi = '<div style="overflow-x:auto;">
              <table class="table">';
            $revi .= '<tr>';
            $revi .= '<th>Reating</th><th>Review</th><th>Review By</th>';
            $revi .= '</tr>';
            foreach ($allRevious as $review) {
                $revi .= '<tr>
                    <td>';
                for ($i=1; $i <= 5; $i++) {
                    if($i<=$review->rating){
                        $revi .= '<span class="fa fa-star checked"></span> ';
                    }else{
                        $revi .= '<span class="fa fa-star"></span> ';
                    }
                }

                $revi .= '</td><td>';
                $revi .=  $review->review;
                $revi .= '</td><td>';
                $revi .=  $review->name;
                $revi .= '</td>';
                $revi .= '</tr>';
            }
            $revi .= '</table>
                </div>';
            return Response::json(['reviews'=>$star, 'allReviews'=>$revi, 'totalReviews'=>$totalReviews.' reviews', 'msg'=>'Review Add Successfully']);
        }
    }
}
