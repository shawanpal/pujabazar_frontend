<?php

namespace App\Http\Controllers\FrontendControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\Product;
use App\Image;
use App\Review;
use App\ProductDesc;
use App\Category;
use App\SubCategory;

class ViewController extends Controller {
    
    public function index() {
        return view('home');
    }
    
    public function productDetails($code) {
        $data['product'] = Product::where(['code'=>$code])->first();
        $data['images'] = Image::where(['product_id' => $data['product']->id])->get();
        $data['reviews'] = Review::where(['product_id' => $data['product']->id])->get();
        $data['desces'] = ProductDesc::where(['product_id' => $data['product']->id])->get();
        $data['category'] = Category::where(['id' => $data['product']->category_id])->first();
        $data['subcategory'] = SubCategory::where(['id' => $data['product']->sub_category_id])->first();
        $data['related_products'] = Product::where(['sub_category_id' => $data['product']->sub_category_id])->get();
        $data['average'] = Review::where('product_id', $data['product']->id)->avg('rating');
        $star = '';
        for ($i=1; $i <= 5; $i++) {
            if($i <= $data['average']){
                $star .= '<li>';
                $star .= '<i class="fa fa-star"></i> ';
                $star .= '</li>';
            }else{
                $star .= '<li class="dark">';
                $star .= '<i class="fa fa-star-o"></i> ';
                $star .= '</li>';
            }
        }
        $data['stars'] = $star;
        
        return view('product-details',$data);
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
        if(isset($data['encpid'])){
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
    
    public function submitReview(Request $request){
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
}
