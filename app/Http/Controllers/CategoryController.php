<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Category;

use App\SubCategory;

use App\Image;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $allCategorys = Category::orderBy('category_name', 'asc')->get();
        $position = '<option value="">Select Menu Position</option>';
        for($i=1; $i<=count($allCategorys); $i++){
            $position .= '<option value="'.$i.'">'.$i.'</option>';
        }
        return view('admin/category', ['categorys' => $allCategorys, 'positions'=>$position]);
    }

    public function show(Request $request)
    {
    	$cat_id = $request->route('cat_id');
       	$subcategories = SubCategory::where('category_id', '=', $cat_id)->orderBy('sub_category_name', 'asc')->get();

       	$allCategorys = Category::where('id', $cat_id)->get();
        $position = '';
        foreach ($allCategorys as $category) {
            $position = $category->position;
        }
        // return "foo";
        $pos = '';
        for($i=1; $i<=count($subcategories); $i++){
            $pos .= '<option value="'.$i.'">'.$i.'</option>';
        }
        // $pos.='</select>';
        // return $pos;
        return Response::json(['subcategories' => $subcategories, 'position'=>$position,'subposition'=>$pos]);
    }

    public function store(Request $request)
    {
    	$rules = array(
    		'category_name' => 'required|string',
    	);

    	$validator = Validator::make($request->all(), $rules);
    	if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()));
    	}else{
            $url = str_replace(' ', '-', $request->category_name);
            $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url);
            $url = strtolower($url);
    		$category = Category::create([
			    'category_name' => $request->input('category_name'),
                'category_url' => $url,
			]);
            $allCategorys = Category::orderBy('category_name', 'asc')->get();
            if($category->id){
                Category::where('id', $category->id)->update([
                    'position' => count($allCategorys),
                ]);
            }
            $position = '<option value="">Select Menu Position</option>';
            for($i=1; $i<=count($allCategorys); $i++){
                $position .= '<option value="'.$i.'">'.$i.'</option>';
            }
    		return Response::json(['categorys'=>$allCategorys, 'positions'=>$position, 'msg'=>'Category Add Successfully']);
    	}
    }

    public function editSub(Request $request)
    {
        $sub = SubCategory::findOrFail($request->id);
        $images = Image::where('sub_category_id', '=', $request->id)->orderBy('id', 'desc')->get();
        if (count($images)!=0){
            foreach ($images as $img){
                $image = '<img src="'.asset('images/'.$img->image).'" width="100%" height="100">';
            }
        }else{
           $image = '';
        }

        return Response::json(['images' => $image,'pos'=>$sub->sub_position]);
    }

    public function storeSub(Request $request)
    {
        $rules = array(
            'category_id' => 'required|numeric',
            'sub_category_name' => 'required|string',
            'photo' => 'required',
            'photo.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(['errors'=>$validator->getMessageBag()]);
        }else{
            $check = Category::where('id',$request->input('category_id'))->first();

            if (is_null($check)) {
                return Response::json(['error'=>'Category is not exist']);
            }else{
                $url = str_replace(' ', '-', $request->sub_category_name);
                $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url);
                $url = strtolower($url);
                $subcate = SubCategory::create([
                    'category_id' => $request->input('category_id'),
                    'sub_category_name' => $request->input('sub_category_name'),
                    'sub_category_url' => $url,
                    'sub_position' => $request->input('sub_position') =='' ? count(SubCategory::where('category_id',$request->input('category_id'))->get()):$request->input('sub_position'),
                ]);
                if($subcate->id){
                    $i = 1;
                    if($request->hasFile('photo')){
                        $path = public_path('images/');
                        $file_name = $request->id.'_'.time().'_'.$i.'.'.$request->file('photo')->getClientOriginalExtension();

                        $request->file('photo')->move($path, $file_name);
                        Image::create([
                            'sub_category_id' => $subcate->id,
                            'main_image' => '1',
                            'image' => $file_name
                        ]);
                    }
                }


                $subcategorys = SubCategory::where('category_id', $request->input('category_id'))->orderBy('sub_category_name', 'asc')->get();

                return Response::json(['subcategorys'=>$subcategorys, 'msg'=>'Sub Category Add Successfully']);
            }



        }
    }

    public function update(Request $request)
    {
        $rules = array(
            'id' => 'required|numeric',
            'category_name' => 'required|string',
            'position' => 'required|numeric',
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toarray()));
        }else{
            $url = str_replace(' ', '-', $request->category_name);
            $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url);
            $url = strtolower($url);

            $cat_pos = Category::where('id', $request->id)->get();
            foreach ($cat_pos as $pos) {
                $old_pos = $pos->position;
            }

            Category::where('position', $request->position)->update(['position' => $old_pos]);

            Category::where('id', $request->id)->update(['category_name' => $request->category_name, 'category_url' => $url, 'position' => $request->position]);


            $allCategorys = Category::orderBy('category_name', 'asc')->get();
            return Response::json(['categorys'=>$allCategorys, 'msg'=>'Category Save Successfully']);
        }
    }

    public function updateSub(Request $request)
    {
        $rules = array(
            'id' => 'required|numeric',
            'cate_id' => 'required|numeric',
            'sub_category_name' => 'required|string',
            'photo' => 'nullable',
            'photo.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(['errors'=>$validator->getMessageBag()]);
        }else{
            $url = str_replace(' ', '-', $request->sub_category_name);
            $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url);
            $url = strtolower($url);
            $oldSub = SubCategory::findOrFail($request->input('id'));
            $subPosition = SubCategory::where('category_id', $request->input('cate_id'))
            ->where('sub_position',$request->input('sub_position'))->first();
            if($oldSub->sub_position !== 0){
                $subPosition->sub_position = $oldSub->sub_position;
                $subPosition->update();
            }
            
            // return Response::json($subPosition);
            SubCategory::where('id', $request->input('id'))->update(['sub_category_name' => $request->input('sub_category_name'),
             'sub_category_url' => $url,'sub_position'=>$request->input('sub_position')]);
            $subcategorys = SubCategory::where('category_id', $request->input('cate_id'))->orderBy('sub_category_name', 'asc')->get();
            $i = 1;
            if($request->hasFile('photo')){
                $path = public_path('images/');
                $file_name = $request->id.'_'.time().'_'.$i.'.'.$request->file('photo')->getClientOriginalExtension();
                $image = Image::where('sub_category_id', '=', $request->id)->get();
                if(count($image)!=0){
                    foreach ($image as $img){
                        @unlink(public_path().'/images/'.$img->image);
                        Image::where('id', $img->id)->delete();
                    }
                }
                $request->file('photo')->move($path, $file_name);
                Image::create([
                    'sub_category_id' => $request->id,
                    'main_image' => '1',
                    'image' => $file_name
                ]);
            }
            return Response::json(['subcategorys'=>$subcategorys, 'msg'=>'Sub Category Save Successfully']);
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
            $sub_category = SubCategory::where('category_id', $request->id)->get();
            foreach ($sub_category as $sub) {
              $image = Image::where('sub_category_id', '=', $sub->id)->get();
              if(count($image)!=0){
                  foreach ($image as $img){
                      @unlink(public_path().'/images/'.$img->image);
                      Image::where('id', $img->id)->delete();
                  }
              }
            }
            $sub_category = SubCategory::where('category_id', $request->id)->delete();
            $category = Category::where('id', $request->id)->delete();
            $allCategorys = Category::orderBy('category_name', 'asc')->get();
            $position = '<option value="">Select Menu Position</option>';
            for($i=1; $i<=count($allCategorys); $i++){
                $position .= '<option value="'.$i.'">'.$i.'</option>';
            }
            return Response::json(['categorys'=>$allCategorys, 'positions'=>$position, 'msg'=>'Category Delete Successfully']);
        }
    }

    public function deleteSub(Request $request)
    {
        $rules = array(
            'id' => 'required|numeric',
            'cate_id' => 'required|numeric',
        );
        
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()->toarray()));
        }else{

            $subCate = SubCategory::where('id', $request->input('id'))->first();
            // return $subCate->sub_position;
            $position  = SubCategory::where('sub_position','>',$subCate->sub_position)->get();
            // return $position;
            foreach($position as $pos){
                $pos->sub_position = $pos->sub_position-1;
                $pos->update();
            }
            $category = SubCategory::where('id', $request->input('id'))->delete();
            $image = Image::where('sub_category_id', '=', $request->id)->get();
            if(count($image)!=0){
                foreach ($image as $img){
                    @unlink(public_path().'/images/'.$img->image);
                    Image::where('id', $img->id)->delete();
                }
            }
            $subcategors = SubCategory::where('category_id', $request->input('cate_id'))->orderBy('sub_category_name', 'asc')->get();
            return Response::json(['categorys'=>$subcategors, 'msg'=>'Sub Category Delete Successfully']);
        }
    }

    // made on 20/06/2020
    // public function getSub($cat_id){
    //     return $cat_id;

    // }
}
