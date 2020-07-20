<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Routing\UrlGenerator;

use App\Category;
use App\SubCategory;
use App\Banner;

class CategoryControler extends Controller
{
    protected $url;

    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    public function whatever($array, $key, $val) {
        foreach ($array as $k => $item){
            if (isset($item[$key]) && $item[$key] == $val){
                return $k;
            }
        }
        
        return false;
    }

    public function index()
    {
        $categorys = Category::where('category_name', 'NOT LIKE', '%~%')->orderBy('category_name', 'desc')->get();

        $sub_categorys = SubCategory::where('sub_category_name', 'NOT LIKE', '%~%')->join('categories', 'categories.id', '=', 'sub_categories.category_id')->leftJoin('images', 'sub_categories.id',   '=', 'images.sub_category_id')->select('sub_categories.id', 'sub_categories.category_id', 'categories.category_name', 'categories.category_url', 'sub_categories.sub_category_name', 'sub_categories.sub_category_url', 'images.image')->where('categories.category_name', 'NOT LIKE', '%~%')->orderBy('sub_categories.id', 'desc')->get();
        $sub = [];
        $bann = [];

        foreach ($sub_categorys as $data) {
            $sb = explode(",",$data->sub_category_name);
            $sb = $sb[0];
            if(count($sub) > 0){
                
                $k = $this->whatever($sub, 'category_id', $data->category_id);
                    
                if($k === false){
                    $ky = count($sub);
                    $sub[$ky]['category_id'] = $data->category_id;
                    $sub[$ky]['category_name'] = $data->category_name;
                    
                    $sub[$ky]['sub'][0] = ['id' => $data->id, 'category_id' => $data->category_id, 'name' => $sb, 'image' => $this->url->to('/').'/images/'.$data->image];
                }else{
                    if(isset($sub[$k]['sub'])){
                        $p = count($sub[$k]['sub']);
                        $sub[$k]['sub'][$p] = ['id' => $data->id, 'category_id' => $data->category_id, 'name' => $sb, 'image' => $this->url->to('/').'/images/'.$data->image];
                    }else{
                        $sub[$k]['sub'][0] = ['id' => $data->id, 'category_id' => $data->category_id, 'name' => $sb, 'image' => $this->url->to('/').'/images/'.$data->image];
                    }
                    
                }
            }else{
                $sub[0]['category_id'] = $data->category_id;
                $sub[0]['category_name'] = $data->category_name;
                
                $sub[0]['sub'][0] = ['id' => $data->id, 'category_id' => $data->category_id, 'name' => $sb, 'image' => $this->url->to('/').'/images/'.$data->image];
            }
        }

        $banners = Banner::all();
        $i = 0;
        foreach ($banners as $banner) {
            $bann[$i] = $this->url->to('/').'/images/'.$banner->banner_image;
            $i++;
        }

        usort($sub, function ($item1, $item2) {
            return $item1['category_id'] <=> $item2['category_id'];
        });
        
        return response()->json(['categorys' => $categorys, 'subcategorys' => $sub, 'banners' => $bann]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
