<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Banner;
use App\Category;
use App\SubCategory;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    private $allCategorys;
    public function __construct()
    {
        $this->middleware('auth');
        $this->allCategorys = Category::orderBy('category_name', 'asc')->get();
    }

    public function index()
    {
        $allBanners = Banner::orderBy('id', 'asc')->get();
        $banners = '';
        if(count($allBanners) != 0){
            $banners .= '<div class="row">';
            foreach ($allBanners as $banner) {
                $banners .= '<div class="col">
                                <img src="'.asset('images/'.$banner->banner_image).'" alt="" class="img-rounded img-responsive">
                                <button type="button" class="btn btn-danger btn-block delet_banner" value="'.$banner->id.'">Delete</button>
                            </div>';

            }
            $banners .= '</div>';
        }else{
            $banners = '<div class="alert alert-info alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      Banner images is not available yet.
                    </div>';
        }
        return view('admin/banner', ['categorys' => $this->allCategorys, 'banners' => $banners]);
    }

    public function store(Request $request)
    {
        $rules = array(
          'photo' => 'required',
          'photo.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()));
        }else{
          $url = '';
          $url_sub = '';
          if($request->category_id !== null){
            $category = Category::findOrFail($request->category_id);
            $url = $category->category_url;
            if($request->sub_category_id !== null){
              $subCategory = SubCategory::findOrFail($request->sub_category_id);
              $url_sub = $subCategory->sub_category_url;
            }
          }
            if($request->hasFile('photo')){
              $path = public_path('images');

              foreach ($request->file('photo') as $image) {
                $file_name = 'banner_'.$url.'_'.$url_sub.'_'.time().'.'.$image->extension();
                $image->move($path, $file_name);
                Banner::create([
                    'banner_image' => $file_name
                ]);
              }
            }

            $allBanners = Banner::orderBy('id', 'asc')->get();
            $banners = '';
            if(count($allBanners) != 0){
                $banners .= '<div class="row">';
                foreach ($allBanners as $banner) {
                    $banners .= '<div class="col">
                                <img src="'.asset('images/'.$banner->banner_image).'" alt="" class="img-rounded img-responsive">
                                <button type="button" class="btn btn-danger btn-block delet_banner" value="'.$banner->id.'">Delete</button>
                            </div>';
                }
                $banners .= '</div>';
            }else{
                $banners = '<div class="alert alert-info alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert">&times;</button>
                          Banner images is not available yet.
                        </div>';
            }

            return Response::json(['banners'=>$banners, 'msg'=>'Banner Add Successfully']);
        }
    }

    public function delete(Request $request)
    {
        $rules = array(
            'id' => 'required|numeric',
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toarray()));
        }else{
          $imag = Banner::where('id', $request->id)->first();
          $path = public_path('images/'.$imag->banner_image);
          if(file_exists($path)){
              unlink($path);
          }
            $banner = Banner::where('id', $request->input('id'))->delete();
            $allBanners = Banner::orderBy('id', 'asc')->get();
            $banners = '';
            if(count($allBanners) != 0){
                $banners .= '<div class="row">';
                foreach ($allBanners as $banner) {
                    $banners .= '<div class="col">
                                    <img src="'.asset('images/'.$banner->banner_image).'" alt="" class="img-rounded img-responsive">
                                    <button type="button" class="btn btn-danger btn-block delet_banner" value="'.$banner->id.'">Delete</button>
                                </div>';

                }
                $banners .= '</div>';
            }else{
                $banners = '<div class="alert alert-info alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert">&times;</button>
                          Banner images is not available yet.
                        </div>';
            }

            return Response::json(['banners'=>$banners, 'msg'=>'Banner Delete Successfully']);
        }
    }
}
