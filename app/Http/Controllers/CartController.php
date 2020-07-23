<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use App\State;
use App\Category;
use App\SubCategory;
use App\Product;
use App\Package;
use App\Item;
use App\Samogri;
use App\Booking;
use App\Image;
use App\ProductDesc;
use Cart;
use Session;

class CartController extends Controller {

    private $state, $states, $location, $allCategorys, $allSubCategorys;

    public function __construct() {
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

        $this->allSubCategorys = SubCategory::where('sub_category_name', 'NOT LIKE', '%~%')->join('categories', 'categories.id', '=', 'sub_categories.category_id')->leftJoin('images', 'sub_categories.id', '=', 'images.sub_category_id')->select('sub_categories.id', 'sub_categories.category_id', 'categories.category_name', 'categories.category_url', 'sub_categories.sub_category_name', 'sub_categories.sub_category_url', 'images.image')->orderBy('sub_categories.id', 'desc')->get();
    }

    public function whatever($array, $key, $val) {
        foreach ($array as $k => $item)
            if (isset($item[$key]) && $item[$key] == $val)
                return $k;
        return false;
    }

    public function storeProduct(Request $request) {
        $product_id = Crypt::decryptString($request->input('atcpid'));
        $quantity = $request->input('quantity');
        $variation_type = $request->input('variation');
        if ($variation_type == 'size') {
            $variation = explode('-', $request->input('product_size'));
            $size = $variation[0];
            $size_unit = $variation[1];
            $weight = null;
            $weight_unit = null;
        } elseif ($variation_type == 'weight') {
            $variation = explode('-', $request->input('product_weight'));
            $weight = $variation[0];
            $weight_unit = $variation[1];
            $size = null;
            $size_unit = null;
        }
        $product = Product::find($product_id);
        $productDesc = ProductDesc::where(['product_id' => $product_id, 'size' => $size, 'weight' => $weight, 'weight_unit' => $weight_unit, 'size_unit' => $size_unit])
                ->first();


        if ($productDesc->discount != null) {
            $price = $productDesc->price - $productDesc->discount;
        } else {
            $price = $productDesc->price;
        }
        $image = Image::where(['product_id' => $product_id, 'main_image' => 1])
                        ->first()
                ->image;

        if (count(Cart::content()) > 0) {
            foreach (Cart::content() as $cart) {
                if ($cart->id == $product_id && $cart->size == $size && $cart->size_unit == $size_unit && $cart->weight == $weight && $cart->weight_unit == $weight_unit) {
                    $query = ProductDesc::where(['product_id' => $cart->id, 'size' => $size, 'weight' => $weight, 'weight_unit' => $weight_unit, 'size_unit' => $size_unit])->where('stock', '>=', $cart->qty + $quantity)->get();
                    if (count($query) > 0) {
                        Cart::add(['id' => $product_id, 'name' => $product->name . ' (' . $product->code . ')', 'qty' => $quantity, 'price' => $price, 'weight' => 0, 'options' => ['size' => $size, 'weight' => $weight, 'weight_unit' => $weight_unit, 'size_unit' => $size_unit, 'image' => $image, 'type' => 'product', 'code' => $product->code ]]);
                        return Redirect::back()
                                        ->with('success', 'Item was added to your cart!!');
                    } else {
                        return Redirect::back()
                                        ->with('error', 'Your cart is exceeding our stock limits!!');
                    }
                } else {
                    Cart::add(['id' => $product_id, 'name' => $product->name . ' (' . $product->code . ')', 'qty' => $quantity, 'price' => $price, 'weight' => 0, 'options' => ['size' => $size, 'weight' => $weight, 'weight_unit' => $weight_unit, 'size_unit' => $size_unit, 'image' => $image, 'type' => 'product', 'code' => $product->code]]);
                    return Redirect::back()
                                    ->with('success', 'Item was added to your cart!!');
                }
            }
        } else {
            Cart::add(['id' => $product_id, 'name' => $product->name . ' (' . $product->code . ')', 'qty' => $quantity, 'price' => $price, 'weight' => 0, 'options' => ['size' => $size, 'weight' => $weight, 'weight_unit' => $weight_unit, 'size_unit' => $size_unit, 'image' => $image, 'type' => 'product', 'code' => $product->code]]);
            return Redirect::back()
                            ->with('success', 'Item was added to your cart!!');
        }
    }

    public function storePackage(Request $request) {
        // $request->session()->forget('order');
        // $request->session()->save();
        $price = 0;
        $discount = 0;
        $packg = array();
        $i = 0;
        $status = $request->status;
        $item_total = $request->item_total;
        $same = false;
        if ($request->session()->has('order')) {
            $packg = $request->session()->get('order');
            $p = count($packg);
            $k = $this->whatever($packg, 'package_id', $request->package_id);
            if ($k == false) {
                $packg[$p]['package_id'] = $request->package_id;

                foreach ($request->item_id as $ind => $item) {
                    $prds = Item::where('id', $item)->get();
                    foreach ($prds as $prd) {
                        if ($status[$ind] == 1) {
                            $price += ($prd->price * $item_total[$ind]);
                            $packg[$p]['items'][$i] = ['item_id' => $item, 'quantity' => $item_total[$ind]];
                            $i++;
                        }
                    }
                }
                $packages = Package::where('id', $request->package_id)->get();
                foreach ($packages as $package) {
                    $discount = $package->discount;
                }
                $price = ($price - (($discount * $price) / 100));
                $packg[$p]['price'] = $price;
            } else {
                $same = true;
                $packg[$k]['package_id'] = $request->package_id;
                unset($packg[$k]['items']);
                foreach ($request->item_id as $ind => $item) {
                    $prds = Item::where('id', $item)->get();
                    foreach ($prds as $prd) {
                        if ($status[$ind] == 1) {
                            $price += ($prd->price * $item_total[$ind]);
                            $packg[$k]['items'][$i] = ['item_id' => $item, 'quantity' => $item_total[$ind]];
                            $i++;
                        }
                    }
                }
                $packages = Package::where('id', $request->package_id)->get();
                foreach ($packages as $package) {
                    $discount = $package->discount;
                }
                $price = ($price - (($discount * $price) / 100));
                $packg[$k]['price'] = $price;
            }
        } else {
            // dd('Session not avelable');
            $packg[0]['package_id'] = $request->package_id;

            foreach ($request->item_id as $ind => $item) {
                $prds = Item::where('id', $item)->get();
                foreach ($prds as $prd) {
                    if ($status[$ind] == 1) {
                        $price += ($prd->price * $item_total[$ind]);
                        $packg[0]['items'][$i] = ['item_id' => $item, 'quantity' => $item_total[$ind]];
                        $i++;
                    }
                }
            }
            $packages = Package::where('id', $request->package_id)->get();
            foreach ($packages as $package) {
                $discount = $package->discount;
            }
            $price = ($price - (($discount * $price) / 100));
            $packg[0]['price'] = $price;
        }
        $request->session()->forget('order');
        $request->session()->put('order', $packg);
        $request->session()->save();
        // dd(session('order'));

        if ($request->session()->has('order')) {
            foreach ($request->session()->get('order') as $each_pkg) {
                $packages = Package::join('images', 'packages.id', '=', 'images.package_id')->where('images.main_image', '=', '1')->select('packages.id', 'packages.name', 'packages.code', 'images.image')->where('packages.id', $each_pkg['package_id'])->get();
                foreach ($packages as $package) {
                    $id = $package->id;
                    $name = $package->name;
                    $code = $package->code;
                    $image = $package->image;
                }
                foreach (Cart::content() as $row) {
                    if ($id == $row->id) {
                        Cart::remove($row->rowId);
                    }
                }

                Cart::add(['id' => $id, 'name' => $name . ' (' . $code . ')', 'qty' => 1, 'price' => round($each_pkg['price']), 'weight' => 0, 'options' => ['image' => $image, 'url' => route('packag') . '/' . $code]]);
            }
        }
        if ($same == true) {
            Session::put('error', 'This item already added to your cart!');
        } else {
            Session::put('success', 'Item was added to your cart!!');
        }
        return Response::json(['status' => 'success', 'url' => route('cart')]);
    }

    /* public function storeBooking(Request $request)
      {
      $booking_id = $request->input('booking_id');
      $booking = Booking::find($booking_id);
      $price = $booking->price;


      $bookings = Booking::join('images', 'bookings.id',   '=', 'images.booking_id')
      ->where('images.main_image', '=', '1')->select('bookings.id', 'bookings.name', 'bookings.code', 'bookings.price', 'images.image')->where('bookings.id', $booking_id)
      ->get();
      foreach ($bookings as $booking) {
      $image = $booking->image;
      }
      //dd($image);
      Cart::add(array('id' => $booking_id, 'name' => $booking->name, 'qty' => 1, 'price' => $price, 'options' => ['image' => $booking->image, 'url' => route('bookin').'/'.$booking->code]));
      Session::put('success', 'Item was added to your cart!!');
      return redirect('cart');
      } */

    public function show() {
        $cart = Cart::content();

        return view('cart', ['locations' => session('location'), 'categorys' => $this->allCategorys, 'subcategorys' => $this->allSubCategorys, 'states' => $this->states, 'cart' => $cart]);


        // return view('cart', array('locations' => session('location'), 'categorys' => $this->allCategorys, 'subcategorys' => $this->allSubCategorys, 'states' => $this->states, 'cart' => $cart));
    }

    public function checkout(Request $request) {
        $user = Auth::user();
        $cart = Cart::content();

        if (Cart::count() < 1) {
            return redirect('cart');
        }

        if (Auth::check()) {
            if ($user->role == 'Buyer') {
                return redirect('buyer');
            } else {
                return redirect('login');
            }
        } else {
            return redirect('login');
        }
    }

    public function update(Request $request) {
        $quantity = $request->quantity;
        $rowId = $request->rowId;
        $cart = Cart::get($rowId);
        $status = false;

        $url = $cart->options->url;
        $url = explode('/', $url);
        $str = $url[count($url) - 2];
        if ($str == 'package') {
            $query = Package::where('id', $cart->id)->where('stock', '>=', $quantity)->get();
            if (count($query) > 0) {
                $status = true;
            }
        } else if ($str == 'product') {
            $query = Product::where('id', $cart->id)->where('stock', '>=', $quantity)->get();
            if (count($query) > 0) {
                $status = true;
            }
        }
        if ($status == true) {
            if ($quantity < 1) {
                $quantity = 1;
                Session::put('error', 'Your cart quantity must be greater than 1 !');
            } else if ($quantity > 10) {
                $quantity = 10;
                Session::put('error', 'Your cart quantity must be less than 10 !');
            } else {
                Session::put('success', 'Your cart update successfully!!');
            }
            Cart::update($rowId, $quantity);
        } else {
            Session::put('error', 'Your cart is exceeding our stock limits!!');
        }
        return redirect('cart');
    }

    public function remove(Request $request) {
        $rowId = $request->input('rowId');

        $cart = Cart::content('rowId', $rowId);

        $url = '';
        $str = '';
        foreach ($cart as $item) {
            $url = $item->options->url;
            $url = explode('/', $url);
            $str = $url[count($url) - 2];
            $i = 0;
            if ($str == 'package') {
                if (Session::has('order')) {
                    $k = $this->whatever(Session('order'), 'package_id', $item->id);
                    if ($k != false) {
                        Session::forget('order.' . $k);
                        Session::save();
                    }
                }
            }
        }
        Cart::remove($rowId);
        Session::put('success', 'Your cart item remove successfully!!');
        return redirect('cart');
    }

    public function destroy(Request $request) {
        Cart::destroy();
        Session::forget('order');
        Session::save();
        return redirect('cart');
    }

}
