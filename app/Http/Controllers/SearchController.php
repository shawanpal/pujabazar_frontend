<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Category;

use App\SubCategory;

use App\Product;

use App\Booking;

use App\Package;

use App\Samogri;

use App\Item;

use App\BusinessLocation;

use App\Review;

use App\State;

use App\Image;

use App\Pincode;

use Session;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    private $location,$allCategorys,$allSubCategorys,$states;
    public function __construct()
    {
      // if(!Session::has('location')){
      //     $ip = \Request::ip();
      //     if($ip == '127.0.0.1'){
      //       $ip = '202.78.236.1';
      //     }
      //     $this->location = json_decode(file_get_contents("https://www.iplocate.io/api/lookup/".$ip));
      //     $state = State::where('name', $this->location->subdivision)->get();
      //     if(count($state)==0){
      //         $this->location = json_decode(file_get_contents("https://www.iplocate.io/api/lookup/202.78.236.1"));
      //     }
      //     session(['location' => $this->location->subdivision]);
      // }
      session(['location' => 'West Bengal']);

        $this->states = State::orderBy('name', 'asc')->get();
        $this->allCategorys = Category::where('category_name', 'NOT LIKE', '%~%')->orderBy('position', 'asc')->get();
        $this->allSubCategorys = SubCategory::join('categories', 'categories.id', '=', 'sub_categories.category_id')->leftJoin('images', 'sub_categories.id',   '=', 'images.sub_category_id')->select('sub_categories.id', 'sub_categories.category_id', 'categories.category_name', 'categories.category_url', 'sub_categories.sub_category_name', 'sub_categories.sub_category_url', 'images.image')->orderBy('sub_categories.id', 'desc')->get();
    }

    public function short(Request $request)
    {
        /*if ($request->isMethod('get')){
            return response()->json(['products' => 'This is get method']);
        }*/
        $query = '';
        $products = '';
        $query = Product::join('categories', 'products.category_id', '=', 'categories.id')->leftJoin('images', 'products.id',   '=', 'images.product_id')->leftJoin('reviews', 'products.id',   '=', 'reviews.product_id')->where('images.main_image', '=', '1')->select('products.id', 'categories.category_name', 'products.sub_category_id', 'products.name', 'products.code', 'products.quality', 'products.price', 'products.discount', 'images.image');

        $urls = Category::where('category_url', $request->cate)->get();
        foreach ($urls as $url) {
            $cat = $url->id;
        }
        if($request->sub_cate!=''){
            $uls = SubCategory::where('sub_category_url', $request->sub_cate)->get();
            foreach ($uls as $ul) {
                $sub_cat = $ul->id;
            }
        }
        $query->distinct('products.id')->where('products.category_id', $cat);
        if($request->sub_cate!=''){
            $query->where('products.sub_category_id', $sub_cat);
        }

        if($request->short == 'rating'){
            $query->orderBy('reviews.rating', 'desc');
        }else if($request->short == 'price'){
            $query->orderBy('products.price', 'asc');
        }else if($request->short == '-price'){
            $query->orderBy('products.price', 'desc');
        }else if($request->short == 'title'){
            $query->orderBy('products.name', 'asc');
        }else if($request->short == '-title'){
            $query->orderBy('products.name', 'desc');
        }else if($request->short == 'discount'){
            $query->orderBy('products.discount', 'asc');
        }else if($request->short == null){
            $query->orderBy('products.quality', 'asc');
        }

        $allProducts = $query->get();
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
                $products .= '<div class="col-xl-4">
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
        /////////////////////////////////////////// Package /////////////////////////////////////////////
        $query = '';
        $query = Package::select('packages.id', 'categories.category_name', 'packages.sub_category_id', 'packages.name', 'packages.code', 'packages.quality', 'packages.discount', 'images.image')->leftJoin('categories', 'packages.category_id', '=', 'categories.id')->leftJoin('images', 'packages.id',   '=', 'images.package_id')->where('images.main_image', '=', '1')->leftJoin('reviews', 'packages.id',   '=', 'reviews.package_id');
        $query->distinct('packages.id')->where('packages.category_id', $cat);
        if($request->sub_cate!=''){
            $query->where('packages.sub_category_id', $sub_cat);
        }
        if($request->short == 'rating'){
            $query->orderBy('reviews.rating', 'desc');
        }else if($request->short == 'title'){
            $query->orderBy('packages.name', 'asc');
        }else if($request->short == '-title'){
            $query->orderBy('packages.name', 'desc');
        }else if($request->short == 'discount'){
            $query->orderBy('packages.discount', 'desc');
        }else if($request->short == ''){
            $query->orderBy('packages.quality', 'asc');
        }
        $allPackages = $query->get();

        // $packages = '';
        //
        // foreach ($allPackages as $package) {
        //     $dis=false;
        //     $locs = BusinessLocation::where('package_id', $package->id)->get();
        //     foreach ($locs as $loc) {
        //         $stt = State::where('id', $loc->state_id)->where('name', session('location'))->get();
        //         if(count($stt) > 0){
        //             $dis=true;
        //         }
        //     }
        //
        //     if($dis==true){
        //         $price = 0;
        //         $samogris = Samogri::where('package_id', $package->id)->get();
        //         foreach ($samogris as $samg) {
        //            $prds = Item::where('id', $samg->item_id)->get();
        //            foreach ($prds as $prd) {
        //                 $price += $prd->price;
        //            }
        //         }
        //         $packages .= '<div class="col-sm-4">
        //             <div class="right-area">
        //                 <div class="quality">
        //                     <h6>';
        //                         if($package->quality=='A'){
        //                             $packages .= 'Deluxe';
        //                         }else if($package->quality=='B'){
        //                             $packages .= 'Premium';
        //                         }else if($package->quality=='C'){
        //                             $packages .= 'Standard';
        //                         }else if($package->quality=='D'){
        //                             $packages .= 'Basic';
        //                         }
        //                         $packages .= '</h6>
        //                 </div>
        //                     <img src="'.asset('images/'.$package->image).'" class="img-fluid right-img">
        //
        //                 <div class="right-text">
        //
        //                     <h5>'.$package->name.'</h5>';
        //                 $packages .= '<ul class="star list-inline">';
        //                 $average = Review::where('package_id', $package->id)->avg('rating');
        //                 for ($i=1; $i <= 5; $i++) {
        //                     if($i<=$average){
        //                         $packages .= '<li class="list-inline-item"><span class="fa fa-star checked"></span></li>';
        //                     }else{
        //                         $packages .= '<li class="list-inline-item"><span class="fa fa-star"></span></li> ';
        //                     }
        //                 }
        //                 $packages .= '</ul>
        //                     <span class="right-price">';
        //                     if($package->discount != null){
        //                         $packages .= 'Rs. '.round(($price-(($package->discount * $price)/100))).'
        //                             <strike>Rs. '.$price.'</strike>';
        //                     }else{
        //                         $packages .= 'Rs. '.$price;
        //                     }
        //                     $packages .= '</span>
        //                 </div>
        //                  <button type="submit" id="pack'.$package->id.'" onclick="singleProduct(this.id)" value="'.route('packag').'/'.$package->code.'" class="right-btn">View package</button>
        //             </div>
        //         </div>';
        //     }
        // }


        $packs = array();
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
                $average = Review::where('package_id', $package->id)->avg('rating');
                array_push($packs, array('id' => $package->id, 'code' => $package->code, 'quality' => $package->quality, 'image' => $package->image, 'name' => $package->name, 'rating' => $average, 'price' => $price, 'discount' => $package->discount));
            }
        }

        if($request->short == 'price'){
            $pri = array();
            foreach ($packs as $key => $row){
                $pri[$key] = $row['price'];
            }
            array_multisort($pri, SORT_ASC, $packs);
        }else if($request->short == '-price'){
            $price = array();
            foreach ($packs as $key => $row){
                $price[$key] = $row['price'];
            }
            array_multisort($price, SORT_DESC, $packs);
        }
        //$packages = $request->short;
        $packages = '';
        foreach ($packs as $pack) {
            //$packages .= $pack['name'].'<br>';
           $packages .= '<div class="col-xl-4">
                <div class="right-area">
                    <div class="quality">
                        <h6>';
                            if($pack['quality']=='A'){
                                $packages .= 'Deluxe';
                            }else if($pack['quality']=='B'){
                                $packages .= 'Premium';
                            }else if($pack['quality']=='C'){
                                $packages .= 'Standard';
                            }else if($pack['quality']=='D'){
                                $packages .= 'Basic';
                            }
                            $packages .= '</h6>
                    </div>
                        <img src="'.asset('images/'.$pack['image']).'" class="img-fluid right-img">

                    <div class="right-text">

                        <h5>'.$pack['name'].'</h5>';
                    $packages .= '<ul class="star list-inline">';
                    $average = $pack['rating'];
                    for ($i=1; $i <= 5; $i++) {
                        if($i<=$average){
                            $packages .= '<li class="list-inline-item"><span class="fa fa-star checked"></span></li>';
                        }else{
                            $packages .= '<li class="list-inline-item"><span class="fa fa-star"></span></li> ';
                        }
                    }
                    $packages .= '</ul>
                        <span class="right-price">';
                        if($pack['discount'] != null){
                            $packages .= 'Rs. '.round(($pack['price']-(($pack['discount'] * $pack['price'])/100))).'
                                <strike>Rs. '.$pack['price'].'</strike>';
                        }else{
                            $packages .= 'Rs. '.$pack['price'];
                        }
                        $packages .= '</span>
                    </div>
                     <button type="submit" id="pack'.$pack['id'].'" onclick="singleProduct(this.id)" value="'.route('packag').'/'.$pack['code'].'" class="right-btn">View package</button>
                </div>
            </div>';
        }

        ////////////////////////////////////////////// BOOKING /////////////////////////////////////
        $query = '';
        $query = Booking::join('images', 'bookings.id',   '=', 'images.booking_id')->leftJoin('reviews', 'bookings.id',   '=', 'reviews.booking_id')->where('images.main_image', '=', '1')->select('bookings.id', 'bookings.name', 'bookings.code', 'bookings.performance_fee', 'images.image');

        $query->distinct('bookings.id')->where('bookings.category_id', $cat);
        if($request->sub_cate!=''){
            $query->where('bookings.sub_category_id', $sub_cat);
        }
        $query->where('bookings.status', '1');
        if($request->short == 'rating'){
            $query->orderBy('reviews.rating', 'desc');
        }else if($request->short == 'price'){
            $query->orderBy('bookings.performance_fee', 'asc');
        }else if($request->short == '-price'){
            $query->orderBy('bookings.performance_fee', 'desc');
        }else if($request->short == 'title'){
            $query->orderBy('bookings.name', 'asc');
        }else if($request->short == '-title'){
            $query->orderBy('bookings.name', 'desc');
        }

        $allBookings = $query->get();

        $bookings = '';
        foreach($allBookings as $booking){

                $bookings .= '<div class="col-xl-4">
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

        return Response::json(['products' => $products, 'packages' => $packages, 'bookings' => $bookings]);
    }
    public function searchSuggation(Request $request)
    {
        $querys = Product::where('name', 'LIKE', '%'.$request->sr_con.'%')->get();
        if(count($querys)!=0){
            foreach ($querys as $query) {
                $title = preg_replace("/".$request->sr_con."/i", $request->sr_con, $query->name);
            }
        }else{
            $querys = Package::where('name', 'LIKE', '%'.$request->sr_con.'%')->get();
            if(count($querys)!=0){
                foreach ($querys as $query) {
                    $title = preg_replace("/".$request->sr_con."/i", $request->sr_con, $query->name);
                }
            }else{
                $querys = Booking::where('name', 'LIKE', '%'.$request->sr_con.'%')->get();
                if(count($querys)!=0){
                    foreach ($querys as $query) {
                        $title = preg_replace("/".$request->sr_con."/i", $request->sr_con, $query->name);
                    }
                }else{
                    $title = '';
                }
            }
        }
        /*-------------------- SECEND PSART ------------------------*/
        $suggation = '';
        $url = '';
        $querys = Product::distinct('id')->where('name', 'LIKE', '%'.$request->sr_con.'%')->orWhere('code', 'LIKE', '%'.$request->sr_con.'%')->orWhere('details', 'LIKE', '%'.$request->sr_con.'%')->get();
        if(count($querys) > 0){
            foreach ($querys as $query) {
                $images = Image::where('product_id', $query->id)->where('main_image','1')->get();
                if(count($images)!=0){
                    foreach ($images as $image) {
                        $im_src = asset('images/'.$image->image);
                    }
                }else{
                    $im_src = asset('images/noPhoto.jpg');
                }
                $url = 'product/'.$query->code;
                $suggation .= '<li id="'.$query->name.'"
                onclick=location.assign("'.url($url).'")>
                        <ul class="list-unstyled list-inline search-product">
                            <li><img src="'.$im_src.'" class="img-responsive" width="68" height="66"/></li>
                            <li class="srch-prdct">'.preg_replace("/".$request->sr_con."/i", '<b>'.$request->sr_con.'</b>', $query->name).'</li>
                        </ul>
                    </li>';
            }
        }else{
            $querys = Package::distinct('id')->where('name', 'LIKE', '%'.$request->sr_con.'%')->orWhere('code', 'LIKE', '%'.$request->sr_con.'%')->orWhere('details', 'LIKE', '%'.$request->sr_con.'%')->get();
            if(count($querys) > 0){
                foreach ($querys as $query) {
                    $images = Image::where('package_id', $query->id)->where('main_image','1')->get();
                    if(count($images)!=0){
                        foreach ($images as $image) {
                            $im_src = asset('images/'.$image->image);
                        }
                    }else{
                        $im_src = asset('images/noPhoto.jpg');
                    }
                    $url = 'package/'.$query->code;
                    $suggation .= '<li id="'.$query->name.'"
                    onclick=location.assign("'.url($url).'")>
                            <ul class="list-unstyled list-inline search-product">
                                <li><img src="'.$im_src.'" class="img-responsive" width="68" height="66"/></li>
                                <li class="srch-prdct">'.preg_replace("/".$request->sr_con."/i", '<b>'.$request->sr_con.'</b>', $query->name).'</li>
                            </ul>
                        </li>';
                }
            }else{
                $querys = Booking::distinct('id')->where('name', 'LIKE', '%'.$request->sr_con.'%')->orWhere('code', 'LIKE', '%'.$request->sr_con.'%')->orWhere('details', 'LIKE', '%'.$request->sr_con.'%')->get();
                if(count($querys) > 0){
                    foreach ($querys as $query) {
                        $images = Image::where('booking_id', $query->id)->where('main_image','1')->get();
                        if(count($images)!=0){
                            foreach ($images as $image) {
                                $im_src = asset('images/'.$image->image);
                            }
                        }else{
                            $im_src = asset('images/noPhoto.jpg');
                        }
                        $url = 'booking/'.$query->code;
                        $suggation .= '<li id="'.$query->name.'"
                        onclick=location.assign("'.url($url).'")>
                                <ul class="list-unstyled list-inline search-product">
                                    <li><img src="'.$im_src.'" class="img-responsive" width="68" height="66"/></li>
                                    <li class="srch-prdct">'.preg_replace("/".$request->sr_con."/i", '<b>'.$request->sr_con.'</b>', $query->name).'</li>
                                </ul>
                            </li>';
                    }
                }else{
                    $suggation = '<li>Sorry we are unable to find '.$request->sr_con.'</li>';
                }
            }
        }
        return Response::json(['look' => $title, 'suggation' => $suggation]);
    }

    public function mainSearch(Request $request)
    {
        $allProducts = Product::join('images', 'products.id',   '=', 'images.product_id')->where('images.main_image', '=', '1')->select('products.id', 'products.name', 'products.code', 'products.quality', 'products.price', 'products.discount', 'images.image')->where('products.name', 'LIKE', '%'.$request->term.'%')->orWhere('products.code', 'LIKE', '%'.$request->term.'%')->orWhere('products.details', 'LIKE', '%'.$request->term.'%')->distinct('products.id')->orderBy('products.quality', 'asc')->get();
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
                $products .= '<div class="col-xl-3">
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

        ////////////////////////////////////////// PACKAGE //////////////////////////////////////////

        $allPackages = Package::select('packages.id', 'packages.name', 'packages.code', 'packages.quality', 'packages.discount', 'images.image')->leftJoin('images', 'packages.id',   '=', 'images.package_id')->where('images.main_image', '=', '1')->where('packages.name', 'LIKE', '%'.$request->term.'%')->orWhere('packages.code', 'LIKE', '%'.$request->term.'%')->orWhere('packages.details', 'LIKE', '%'.$request->term.'%')->distinct('packages.id')->orderBy('packages.quality', 'asc')->get();

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
                $packages .= '<div class="col-xl-3">
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
                                $packages .= 'Rs. '.($price-(($package->discount * $price)/100)).'
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

        $allBookings = Booking::join('images', 'bookings.id',   '=', 'images.booking_id')->where('images.main_image', '=', '1')->where('bookings.status', '1')->orWhere('bookings.name', 'LIKE', '%'.$request->term.'%')->orWhere('bookings.code', 'LIKE', '%'.$request->term.'%')->orWhere('bookings.details', 'LIKE', '%'.$request->term.'%')->select('bookings.id', 'bookings.name', 'bookings.code', 'bookings.performance_fee', 'images.image')->distinct('bookings.id')->get();

        $bookings = '';
        foreach($allBookings as $booking){
            $bookings .= '<div class="col-xl-3">
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

        return view('search', ['locations' => session('location'), 'categorys' => $this->allCategorys, 'subcategorys' => $this->allSubCategorys, 'products' => $products, 'packages' => $packages, 'bookings' => $bookings, 'states' => $this->states]);
    }

    public function pinSuggation(Request $request)
    {
        $suggation = '';
        $querys = Pincode::where('pincode', 'LIKE', '%'.$request->sr_con.'%')->where('state', '=', session('location'))->where('status', '=', '1')->get();
        if(count($querys)!=0){
            foreach ($querys as $query) {
                $suggation .= '<li class="list-unstyled list-inline search-pin srch-prdct" id="pin_sg'.$query->id.'">'.preg_replace("/".$request->sr_con."/i", $request->sr_con, $query->pincode).'</li>';
            }
        }else{
            $suggation = '<li>Sorry we are unable to find '.$request->sr_con.'</li>';
        }

        return Response::json(['suggation' => $suggation]);
    }
}
