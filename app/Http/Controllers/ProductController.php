<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Category;

use App\SubCategory;

use App\State;

use App\Seller;

use App\Product;

use App\Image;

use App\Attribute;

use App\Term;
use App\ProductDesc;

use App\AttributeTerm;

use App\BusinessLocation;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    private $categorys,$attributes,$states,$sellers;
    public function __construct()
    {
        $this->middleware('auth');
        $this->categorys = Category::orderBy('category_name', 'asc')->get();
        $this->attributes = Attribute::orderBy('name', 'asc')->get();
        $this->states = State::orderBy('name', 'asc')->get();
        $this->sellers = Seller::orderBy('name', 'asc')->get();
    }

    public static function limit_words($string, $word_limit)
    {
        $words = explode(" ",$string);
        return implode(" ", array_splice($words, 0, $word_limit));
    }

    public function index()
    {
        $user = Auth::user();
        if($user->role == 'Admin'){
            return view('admin/product');
        }else if($user->role == 'Seller'){
            return view('seller/product');
        }else{
            return redirect('buyer/booking');
        }
        
    }

    public function showProduct(Request $request)
    {
        $user = Auth::user();
        $page_no = $request->page_no;
        $table_body = '';

        if($user->role == 'Admin'){
            $products = Product::join('categories', 'products.category_id', '=', 'categories.id')->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')->select('products.id', 'categories.category_name', 'sub_categories.sub_category_name', 'products.name', 'products.code','products.color','products.details', 'products.updated_at')->orderBy('products.id', 'desc')->simplePaginate(3);
        }
        if($user->role == 'Seller'){
            $products = Product::join('categories', 'products.category_id', '=', 'categories.id')->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')->select('products.id', 'categories.category_name', 'sub_categories.sub_category_name', 'products.name', 'products.code','products.color','products.details', 'products.updated_at')->where('user_id', $user->id)->orderBy('products.id', 'desc')->simplePaginate(3);
        }

        if($products->isEmpty()){
            $table_body = '<tr>
            <td class="text-center" colspan="'.$request->length.'">
                Product is not available yet.
            </td>
            </tr>';
        }else{
            foreach ($products as $product) {
                $states = State::orderBy('name', 'asc')->get();
                $stat = '';
                $stock = true;
                foreach ($states as $state) {
                    $business_locations = BusinessLocation::where('product_id', $product->id)->where('state_id', $state->id)->get();
                    if(count($business_locations)!=0){
                       $stat .= $state->name.', ';
                    }
                }
                $productDesc = ProductDesc::where('product_id', $product->id)->get();
                if(count($productDesc) > 0){
                    foreach ($productDesc as $desc) {
                        if($desc->stock < 1){
                            $stock = false;
                        }
                    }
                }else{
                    $stock = false; 
                }
                
                $table_body .= '<tr class='.($stock == false ? "table-danger" : "").'>
                    <td>'.$product->code.'</td>
                    <td>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="'.$user->name.'">
                    '.$product->name.'
                    </a>
                    </td>
                    <td>'.$product->category_name.'</td>
                    <td>'.$product->sub_category_name.'</td>
                    <td>'.rtrim($stat,', ').'</td>
                    
                    <td>'.ProductController::limit_words($product->details,10).'...</td>
                    <td><button type="button" value="'.$page_no.'-'.$product->id.'" class="btn btn-link edit_product"><i class="fa fa-pencil"></i></button></td>
                    <td><button type="button" value="'.$page_no.'-'.$product->id.'" class="btn btn-link delete_product"><i class="fa fa-trash-o"></i></button></td>
                </tr>';
            }
        }
        
        return response()->json(['page_no' => $page_no, 'table_body' => $table_body, 'pagination' => $products]);

        
        // dd($allProducts);

        // return view('admin/product', ['categorys' => $this->categorys, 'states' => $this->states, 'sellers' => $this->sellers, 'attributes' => $this->attributes, 'products' => $products, 'pagination' => $allProducts]);
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        $page_no = $request->page_no;
        $search = $request->content;
        $table_body = '';

        if($user->role == 'Admin'){
            $products = Product::join('categories', 'products.category_id', '=', 'categories.id')->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')->select('products.id', 'categories.category_name', 'sub_categories.sub_category_name', 'products.name', 'products.code','products.color','products.details', 'products.updated_at')->where('products.name', 'LIKE', '%'.$search.'%')->orWhere('products.code', 'LIKE', '%' . $search . '%')->orderBy('products.id', 'desc')->simplePaginate(3);
        }else if($user->role == 'Seller'){
            $products = Product::join('categories', 'products.category_id', '=', 'categories.id')->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')->select('products.id', 'categories.category_name', 'sub_categories.sub_category_name', 'products.name', 'products.code','products.color','products.details', 'products.updated_at')->where('products.name', 'LIKE', '%'.$search.'%')->orWhere('products.code', 'LIKE', '%' . $search . '%')->where('user_id', $user->id)->orderBy('products.id', 'desc')->simplePaginate(3);
        }

        if($products->isEmpty()){
            $table_body = '<tr>
            <td class="text-center" colspan="'.$request->length.'">
                Product is not available yet.
            </td>
            </tr>';
        }else{
            foreach ($products as $product) {
                $states = State::orderBy('name', 'asc')->get();
                $stat = '';
                $stock = true;
                $productDesc = ProductDesc::where('product_id', $product->id)->get();
                if(count($productDesc) > 0){
                    foreach ($productDesc as $desc) {
                        if($desc->stock < 1){
                            $stock = false;
                        }
                    }
                }else{
                    $stock = false; 
                }
                
                foreach ($states as $state) {
                    $business_locations = BusinessLocation::where('product_id', $product->id)->where('state_id', $state->id)->get();
                    if(count($business_locations)!=0){
                       $stat .= $state->name.', ';
                    }
                }
                
                $table_body .= '<tr class='.($stock == false ? "table-danger" : "").'>
                    <td>'.$product->code.'</td>
                    <td>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="'.$user->name.'">
                    '.$product->name.'
                    </a>
                    </td>
                    <td>'.$product->category_name.'</td>
                    <td>'.$product->sub_category_name.'</td>
                    <td>'.rtrim($stat,', ').'</td>
                    
                    <td>'.ProductController::limit_words($product->details,10).'...</td>
                    <td><button type="button" value="'.$page_no.'-'.$product->id.'" class="btn btn-link edit_product"><i class="fa fa-pencil"></i></button></td>
                    <td><button type="button" value="'.$page_no.'-'.$product->id.'" class="btn btn-link delete_product"><i class="fa fa-trash-o"></i></button></td>
                </tr>';
            }
        }
        
        return response()->json(['table_body' => $table_body, 'pagination' => $products]);
    }

    public function create()
    {
        $category_list = '';
        $state_list = '';
        $seller_list = '';

        if(!$this->categorys->isEmpty()){
            $category_list = '<option value="">Choose...</option>';
            foreach ($this->categorys as $category) {
                $category_list .= '<option value="'.$category->id.'">'.$category->category_name.'</option>';
            }
        }else{
            $category_list = '<option value="">No Category</option>';
        }

        if(!$this->states->isEmpty()){
            foreach ($this->states as $state) {
                $state_list .= '<option value="'.$state->id.'">'.$state->name.'</option>';
            }
        }else{
            $state_list = '<option value="">No Stare</option>';
        }

        if(!$this->sellers->isEmpty()){
            $seller_list = '<option value="">Choose...</option>';
            foreach ($this->sellers as $seller) {
                $seller_list .= '<option value="'.$seller->id.'">'.$seller->name.'</option>';
            }
        }else{
            $seller_list = '<option value="">No Seller</option>';
        }
        $body = '<div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Category <span style="color:red;">*</span></label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            '.$category_list.'
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Sub Category</label>
                        <select class="form-control" id="sub_category_id" name="sub_category_id">
                        <option value="">Select Category first</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Name <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" placeholder="Separate by , (comma)" name="name" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Images <span style="color:red;">*</span></label>
                        <input id="photo" type="file" class="form-control" name="photo[]" multiple required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>State <span style="color:red;">*</span></label>
                        <select id="id_state" class="form-control multipleSelect" name="state_id[]" multiple required>
                            '.$state_list.'
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Seller</label>
                        <select class="form-control" name="seller_id">
                            '.$seller_list.'     
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Color</label>
                        <input type="color" class="form-control" name="color">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Size/Weight <span style="color:red;">*</span></label>
                        <select id="size_wet" name="size_weight" class="form-control" required>
                            <option value="">Choose...</option>
                            <option value="size">Size</option>
                            <option value="weigth">Weight</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Description:</label>
                        <table class="table table-bordered table-sm" id="pro_more_desc">
                            <thead>
                                <tr>
                                <th width="25%">Size/Waight</th>
                                <th width="20%">Price</th>
                                <th width="20%">Discount</th>
                                <th width="20%">Stock</th>
                                <th width="15%">
                                    <button type="button" class="btn btn-link" id="pro_desc"><i class="fa fa-plus"></i></button>
                                </th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Total Attributes: <span class="badge badge-secondary" id="total-attribute">0</span></label>
                        <table class="table table-bordered table-sm" id="moreAttribute">
                            <thead>
                                <tr>
                                    <th width="20%">Attribute</th>
                                    <th width="70%">Term</th>
                                    <th width="10%" id="add-more-attr"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Details <span style="color:red;">*</span></label>
                        <textarea class="form-control" rows="5" name="details" required></textarea>
                    </div>
                </div>
                <div class="form-row">

                    <div class="col-md-12 alert alert-danger alert-dismissible" role="alert" id="form-error">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <div class="alert-message">
                            <strong>Hi there!</strong> <span><span>!
                        </div>
                    </div>
                </div>';
        return response()->json(['title' => 'Add Product Details', 'body' => $body, 'button_text' => 'Add New Product']);
    }

    public function showProDesc(Request $request)
    {
        $data = '';
        if($request->size_wet == 'size'){
            $data = '<tr>
            <td>
            <div class="form-group row">
                <div class="col-sm-6">
                    <input id="size" type="text" class="form-control" name="size[]" value="" required>
                </div>
                <div class="col-sm-6">
                    <select id="size_unit" class="form-control" name="size_unit[]" required>
                        <option value=""></option>
                        <option value="m">m</option>
                        <option value="cm">cm</option>
                        <option value="ft">ft</option>
                        <option value="inch">inch</option>
                    </select>
                </div>
            </div>
            </td>
            <td>
            <div class="col-md-12">
                <input id="price" type="text" class="form-control" name="price[]" value="" required>
            </div>   
            </td>
            <td>
            <div class="col-md-12">
                <input id="discount" type="text" class="form-control" name="discount[]" value="">
            </div>
            </td>
            <td>
            <div class="col-md-12">
                <input id="stock" type="text" class="form-control" name="stock[]" value="" required>
            </div>
            </td>
            <td>
            <div class="col-md-12">
            <button type="button" class="btn btn-link remove-des"><i class="fa fa-minus-circle"></i></button>
            </div>
            </td>
          </tr>';
        }else if($request->size_wet == 'weigth'){
            $data = '<tr>
            <td>
            <div class="form-group row">
                <div class="col-sm-6">
                    <input id="weight" type="text" class="form-control" name="weight[]" value="" required>
                </div>
                <div class="col-sm-6">
                    <select id="weight_unit" class="form-control" name="weight_unit[]" required>
                        <option value=""></option>
                        <option value="mg">mg</option>
                        <option value="gm">gm</option>
                        <option value="kg">kg</option>
                    </select>
                </div>
            </div>
            </td>
            <td>
            <div class="col-md-12">
                <input id="price" type="text" class="form-control" name="price[]" value="" required>
            </div>   
            </td>
            <td>
            <div class="col-md-12">
                <input id="discount" type="text" class="form-control" name="discount[]" value="">
            </div>
            </td>
            <td>
            <div class="col-md-12">
                <input id="stock" type="text" class="form-control" name="stock[]" value="" required>
            </div>
            </td>
            <td>
                <button type="button" class="btn btn-link remove-des"><i class="fa fa-minus-circle"></i></button>
            </td>
          </tr>';
        }

        return Response::json(['tr'=>$data]);
    }

    public function addMoreAttribute(Request $request)
    {
        
        $total_attribute = $request->input('total_attribute')+1;
        if($request->listAttr){
            $arr = explode(',',$request->listAttr);
            $attributes = Attribute::where('category_id',$request->catId)
            ->where('subcategory_id',$request->subId)
            ->orderBy('name', 'asc')->get()->except($arr);
        }else{
            $attributes = Attribute::where('category_id',$request->catId)
            ->where('subcategory_id',$request->subId)
            ->orderBy('name', 'asc')->get();
        }
       
        $more = '';
        $more .= '<tr>
                    <td>
                        <select id="attribute_id'.$total_attribute.'" class="form-control" name="attribute_id[]" onchange="showTerm(this.id,\'term_td'.$total_attribute.'\')">';
                            if(count($attributes) == 0){
                                $more .= '<option value="">Attribute</option>';
                            }else{
                            $more .= '<option value="">Attributes</option>';
                                foreach($attributes as $key => $attribute){
                                    $more .= '<option value="'.$attribute->id.'">'.$attribute->name.'</option>';
                                }
                            }
                        $more .= '</select>
                    </td>
                    <td id="term_td'.$total_attribute.'">
                        <select id="term_id'.$total_attribute.'" class="form-control multipleSelect" multiple name="term_id[]">

                        </select>
                    </td>
                    <td>
                    <button type="button" class="btn btn-link remove-att"><i class="fa fa-minus-circle"></i></button>
                    </td>

                </tr>';

        return Response::json(['totalAttribute' =>$total_attribute, 'more'=>$more]);
    }

    public function store(Request $request)
    {
       
        $rules = array(
            'category_id' => 'required|numeric',
            'sub_category_id' => 'nullable|numeric',
            'name' => 'required|string',
            'photo' => 'required',
            'photo.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'state_id' => 'required',
            'size_weight' => 'required',
            'size' => 'required_if:size_weight,==,size|array',
            'size_unit' => 'required_if:size_weight,==,size',
            'weight' => 'required_if:size_weight,==,weight|array',
            'weight_unit' => 'required_if:size_weight,==,weight',
            'price.*' => 'required',
            'price' => ['required', 'array'],
            'stock.*' => 'required',
            'stock' => ['required', 'array'],
            'details' => 'required|string'               
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()));
        }else{
            // return $request;
            if($request->discount!=''){
                if($request->discount >= $request->price){
                    return Response::json(array('errors' => ['discount' => 'Discount must be smaller then price']));
                }
            }
            $i = 1;
            $posible_text='123456789';
            $code='';
            $p=0;
            while($p<8){
                $code .= substr($posible_text,mt_rand(0,strlen($posible_text)-1),1);
                $p++;
            }
            $code = 'PR-'.$code;

            $user = Auth::user();
            if($request->color == '#000000'){
                $color = '';
            }else{
               $color = $request->color;
            }
            $product = Product::create([
                'category_id' => $request->input('category_id'),
                'sub_category_id' => $request->input('sub_category_id'),
                'name' => $request->input('name'),
                'code' => $code,
                'user_id' => $user->id,
                'seller_id' => $request->input('seller_id'),
                'color' => $color,
                'details' => $request->input('details')
            ]);
                
            if($product->id){
                if($request->hasFile('photo')){
                    foreach($request->file('photo') as $file){
                        $path = public_path('images/');
                        $file_name = $product->id.'_'.time().'_'.$i.'.'.$file->getClientOriginalExtension();
                        $file->move($path, $file_name);
                        $active = '0';
                        if($i == 1){
                            $active = '1';
                        }
                        Image::create([
                            'product_id' => $product->id,
                            'package_id' => null,
                            'booking_id' => null,
                            'main_image' => $active,
                            'image' => $file_name,
                        ]);
                        $i++;
                    }
                }
                $prices = $request->price;
                for($i =0;$i <count($prices);$i++){
                    $productDesc = new ProductDesc;
                    $productDesc->product_id = $product->id;
                    if($request->weight){
                        $productDesc->weight = $request->weight[$i];
                    }
                    if($request->size){
                        $productDesc->size = $request->size[$i];
                    }
                    if($request->weight_unit){
                        $productDesc->weight_unit = $request->weight_unit[$i];
                    }
                    if($request->size_unit){
                        $productDesc->size_unit = $request->size_unit[$i]; 
                    }
                    $productDesc->price = $request->price[$i]; 
                    $productDesc->discount = $request->discount[$i]; 
                    $productDesc->stock = $request->stock[$i]; 
                    $productDesc->save();
                }
                $state_id = $request->state_id;
                foreach($state_id as $ind => $state){
                    BusinessLocation::create([
                        'product_id' => $product->id,
                        'state_id' => $state,
                    ]);
                }
                $term_id = $request->term_id;
                for($i=0; $i < count($term_id); $i++){
                    $term = explode('-',$term_id[$i]);
                    $attribute_id = $term[0];
                    $term  = $term[1];
                    AttributeTerm::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attribute_id,
                            'term_id' => $term,
                        ]);
                }

            }

            return response()->json(['msg'=>'Product Add Successfully']);
        }
    }

    public function edit($id)
    {
        $category_list = '';
        $sub_category_list = '';
        $state_list = '';
        $seller_list = '';
        
        $product = Product::with('desc','images', 'states', 'attributes')->find($id);

        $attributes = Attribute::where('category_id',$product->category_id)->where('subcategory_id',$product->sub_category_id)->orderBy('name', 'asc')->get();

        $subcategorys = SubCategory::where('category_id', $product->category_id)->get();

        if(!$this->categorys->isEmpty()){
            $category_list = '<option value="">Choose...</option>';
            foreach ($this->categorys as $category) {
                if($product->category_id == $category->id){
                    $category_list .= '<option value="'.$category->id.'" selected>'.$category->category_name.'</option>';
                }else{
                    $category_list .= '<option value="'.$category->id.'">'.$category->category_name.'</option>';
                }
            }
        }else{
            $category_list = '<option value="">No Category</option>';
        }

        if(!$subcategorys->isEmpty()){
            $sub_category_list = '<option value="">Choose...</option>';
            foreach ($subcategorys as $sub) {
                if($product->sub_category_id == $sub->id){
                    $sub_category_list .= '<option value="'.$sub->id.'" selected>'.$sub->sub_category_name.'</option>';
                }else{
                    $sub_category_list .= '<option value="'.$sub->id.'">'.$sub->sub_category_name.'</option>';
                }
            }
        }else{
            $sub_category_list = '<option value="">No Sub Category</option>';
        }

        if(!$this->states->isEmpty()){
            foreach ($this->states as $state) {
                $active = false;
                foreach ($product->states as $stat) {
                    if($stat->state_id == $state->id){
                        $active = true;
                    }
                }
                if($active == true){
                    $state_list .= '<option value="'.$state->id.'" selected>'.$state->name.'</option>';
                }else{
                    $state_list .= '<option value="'.$state->id.'">'.$state->name.'</option>';
                }
            }
        }else{
            $state_list = '<option value="">No Stare</option>';
        }

        if(!$this->sellers->isEmpty()){
            $seller_list = '<option value="">Choose...</option>';
            foreach ($this->sellers as $seller) {
                if($seller->id == $product->seller_id){
                    $seller_list .= '<option value="'.$seller->id.'" selected>'.$seller->name.'</option>';
                }else{
                    $seller_list .= '<option value="'.$seller->id.'">'.$seller->name.'</option>';
                }
            }
        }else{
            $seller_list = '<option value="">No Seller</option>';
        }
        $s = false;
        $w = false;
        foreach ($product->desc as $desc) {
            if ($desc->size != NULL) {
                $s = true;
            }
            if ($desc->weight != NULL) {
                $w = true;
            }
        }
        $proats = array();
        $at = 0;
        foreach ($product->attributes as $attterm) {
            $attr = false; $trm = false; $attr_key = '';                
            foreach ($proats as $key => $value) {
                if($value['attributes'] == $attterm->attribute_id){
                    $attr = true;
                    $attr_key = $key;
                    foreach ($value['terms'] as $k => $v) {
                        if($v == $attterm->term_id){
                            $trm = true;
                        }
                    }
                }
            }
            if($attr==false && $trm==false){
                $proats[$at]['attributes'] = $attterm->attribute_id;
                $proats[$at]['terms'][0] = $attterm->term_id;
                $at++;
            }elseif ($attr==true && $trm==false) {
                $tt = count($proats[$attr_key]['terms']);
                $proats[$attr_key]['terms'][$tt] = $attterm->term_id;
            }
        }
        
        $body = '<div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Category <span style="color:red;">*</span></label>
                        <input type="hidden" name="id" value="'.$id.'">
                        <select class="form-control" id="category_id" name="category_id" required>
                            '.$category_list.'
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Sub Category</label>
                        <select class="form-control" id="sub_category_id" name="sub_category_id">
                        '.$sub_category_list.'
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Name <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" placeholder="Separate by , (comma)" name="name" value="'.$product->name.'" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Images <span style="color:red;">*</span></label>
                        <div id="nexpre" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner" id="product_image_carousel'.$id.'">';
                            $i = 0;
                            foreach ($product->images as $image) {
                                if($i==0){
                                $body .= '<div class="carousel-item active">
                                    <img src="'.asset('images/'.$image->image).'" width="100%" height="100">
                                    <div class="overlay">';
                                        if($image->main_image != '1'){
                                            $body .= '<button type="button" class="btn btn-primary btn-sm" id="product_image_activ'.$image->id.'"  value="'.$image->id.'" onclick="proImgMain(\'product_image_carousel'.$id.'\',\'product_image_activ'.$image->id.'\')">Main</button>';
                                            $body .= '<button type="button" class="btn btn-danger btn-sm" onclick="delProImg(\'product_image_carousel'.$id.'\',\'product_image_activ'.$image->id.'\')">Delete</button>';
                                        }

                                    $body .= '</div>
                                    </div>';
                                }else{
                                    $body .= '<div class="carousel-item">
                                    <img src="'.asset('images/'.$image->image).'" width="100%" height="100">
                                    <div class="overlay">';
                                        if($image->main_image != '1'){
                                            $body .= '<button type="button" class="btn btn-primary btn-sm" id="product_image_activ'.$image->id.'"  value="'.$image->id.'" onclick="proImgMain(\'product_image_carousel'.$id.'\',\'product_image_activ'.$image->id.'\')">Main</button>';
                                            $body .= '<button type="button" class="btn btn-danger btn-sm" onclick="delProImg(\'product_image_carousel'.$id.'\',\'product_image_activ'.$image->id.'\')">Delete</button>';
                                        }

                                    $body .= '</div>
                                    </div>';
                                }
                                $i++;
                            }
                            $body .= '</div>

                        <a class="carousel-control-prev" href="#nexpre" data-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </a>
                        <a class="carousel-control-next" href="#nexpre" data-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </a>
                        </div>
                        <input id="photo" type="file" class="form-control" name="photo[]" multiple>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>State <span style="color:red;">*</span></label>
                        <select id="id_state" class="form-control multipleSelect" name="state_id[]" multiple required>
                            '.$state_list.'
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Seller</label>
                        <select class="form-control" name="seller_id">
                            '.$seller_list.'     
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Color</label>
                        <input type="color" class="form-control" name="color" value="'.$product->color.'">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Size/Weight <span style="color:red;">*</span></label>
                        <select id="size_wet" name="size_weight" class="form-control" required>
                            <option value="">Choose...</option>
                            <option '.($s == true ? "selected" : "").' value="size">Size</option>
                            <option '.($w == true ? "selected" : "").' value="weigth">Weight</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Description:</label>
                        <table class="table table-bordered table-sm" id="pro_more_desc">
                            <thead>
                                <tr>
                                <th width="25%">'.($s == true ? "Size" : ($w == true ? "Waight" : "Size/Waight")).'</th>
                                <th width="20%">Price</th>
                                <th width="20%">Discount</th>
                                <th width="20%">Stock</th>
                                <th width="15%">
                                    <button type="button" class="btn btn-link" id="pro_desc"><i class="fa fa-plus"></i></button>
                                </th>
                                </tr>
                            </thead>
                            <tbody>';
                            foreach($product->desc as $prodesc){
                                $body .= '<tr>
                                <td>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <input id="'.($s == true ? "size" : ($w == true ? "waight" : "")).'" type="text" class="form-control" name="'.($s == true ? "size" : ($w == true ? "waight" : "")).'[]" value="'.($s == true ? $prodesc->size : ($w == true ? $prodesc->weight : "")).'" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <select id="'.($s == true ? "size_unit" : ($w == true ? "weight_unit" : "")).'" class="form-control" name="'.($s == true ? "size_unit" : ($w == true ? "weight_unit" : "")).'[]" required>
                                            <option value=""></option>';
                                            if($s == true){
                                                $body .= '<option '.($prodesc->size_unit == "m" ? "selected" : "").' value="m">m</option>
                                                <option '.($prodesc->size_unit == "cm" ? "selected" : "").' value="cm">cm</option>
                                                <option '.($prodesc->size_unit == "ft" ? "selected" : "").' value="ft">ft</option>
                                                <option '.($prodesc->size_unit == "inch" ? "selected" : "").' value="inch">inch</option>';
                                            }
                                            if($w == true){
                                                $body .= '<option '.($prodesc->weight_unit == "mg" ? "selected" : "").' value="mg">mg</option>
                                                <option '.($prodesc->weight_unit == "gm" ? "selected" : "").' value="gm">gm</option>
                                                <option '.($prodesc->weight_unit == "kg" ? "selected" : "").' value="kg">kg</option>';
                                            }
                                        $body .= '</select>
                                    </div>
                                </div>
                                </td>
                                <td>
                                <div class="col-md-12">
                                    <input id="price" type="text" class="form-control" name="price[]" value="'.$prodesc->price.'" required>
                                </div>   
                                </td>
                                <td>
                                <div class="col-md-12">
                                    <input id="discount" type="text" class="form-control" name="discount[]" value="'.$prodesc->discount.'">
                                </div>
                                </td>
                                <td>
                                <div class="col-md-12">
                                    <input id="stock" type="text" class="form-control" name="stock[]" value="'.$prodesc->stock.'" required>
                                </div>
                                </td>
                                <td>
                                <div class="col-md-12">
                                <button type="button" class="btn btn-link remove-des"><i class="fa fa-minus-circle"></i></button>
                                </div>
                                </td>
                              </tr>';
                            }
                            $body .= '</tbody>
                        </table>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Total Attributes: <span class="badge badge-secondary" id="total-attribute">'.count($proats).'</span></label>
                        <table class="table table-bordered table-sm" id="moreAttribute">
                            <thead>
                                <tr>
                                    <th width="20%">Attribute</th>
                                    <th width="70%">Term</th>
                                    <th width="10%" id="add-more-attr"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>';
                            if(count($proats) == 0){
                                $body .= '<tr>
                                    <td>
                                        <select id="attribute_id1" class="form-control" name="attribute_id[]" onchange="showTerm(this.id,\'1term_td\')">';
                                            if(count($attributes) == 0){
                                                $body .= '<option value="">Attribute</option>';
                                            }else{
                                            $body .= '<option value="">Attributes</option>';
                                                foreach($attributes as $key => $attribute){
                                                    $body .= '<option value="'.$attribute->id.'">'.$attribute->name.'</option>';
                                                }
                                            }
                                        $body .= '</select>
                                    </td>
                                    <td id="1term_td">
                                        <select id="term_id1"  class="form-control multipleSelect" multiple name="term_id[]">
        
                                        </select>
                                    </td>
                                  </tr>';
                            }else{
                                $array = [];
                                foreach($proats as $a){
                                    array_push($array,$a['attributes']);
                                }
                                foreach ($proats as $k => $atr) {
                                    $body .= '<tr>
                                    <td>
                                        <select id="attribute_id'.($k+1).'" class="form-control" name="attribute_id[]" onchange="showTerm(this.id,\'1term_td\')">';
                                            if(count($attributes) == 0){
                                                $body .= '<option value="">Attribute</option>';
                                            }else{
                                            $body .= '<option value="">Attributes</option>';
                                                foreach($attributes as $key => $attribute){
                                                    if($attribute->id == $atr['attributes']){
                                                        $body .= '<option value="'.$attribute->id.'" selected>'.$attribute->name.'</option>';
                                                    }else{
                                                        if(!in_array($attribute->id, $array)){
                                                            $body .= '<option value="'.$attribute->id.'">'.$attribute->name.'</option>';
                                                        }
                                                    }
                                                }
                                            }
                                        $body .= '</select>
                                    </td>
                                    <td id="1term_td">';
                                    $terms = Term::where('attribute_id', $atr['attributes'])->orderBy('name', 'asc')->get();
                                    $body .= '<select id="term_id'.($k+1).'"  class="form-control multipleSelect" multiple name="term_id[]">';
                                    if(count($terms) != 0){
                                        foreach($terms as $term){
                                            $selected = false;
                                            foreach ($atr['terms'] as $trm) {
                                                if($term->id == $trm){
                                                    $selected = true;
                                                }
                                            }
                                            $body .= '<option '.( $selected == true ? "selected" : "").' value="'.$atr['attributes'].'-'.$term->id.'">'.$term->name.'</option>';
                                        }
                                    }
                                    $body .= '</select>
                                    </td>
                                  </tr>';
                                }
                            }
                              
                            $body .= '</tbody>
                        </table>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Details <span style="color:red;">*</span></label>
                        <textarea class="form-control" rows="5" name="details" required>'.$product->details.'</textarea>
                    </div>
                </div>
                <div class="form-row">

                    <div class="col-md-12 alert alert-danger alert-dismissible" role="alert" id="form-error">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <div class="alert-message">
                            <strong>Hi there!</strong> <span><span>!
                        </div>
                    </div>
                </div>';
        
        return response()->json(['title' => 'Edit Product Details', 'body' => $body, 'button_text' => 'Save Product']);
    }

    public function imgMain(Request $request)
    {
        $image = Image::where('id', $request->id)->get();
        foreach ($image as $imag) {
            $pro_id = $imag->product_id;
        }
        Image::where('product_id', $pro_id)->update(['main_image' => '0']);
        Image::where('id', $request->id)->update(['main_image' => '1']);
        $images = Image::where('product_id', $pro_id)->get();
        $i = 0;
        $all_img = '';
        foreach ($images as $img) {
            if($i==0){
              $all_img .= '<div class="carousel-item active">
                  <img src="'.asset('images/'.$img->image).'" width="100%" height="100">
                  <div class="overlay">';
                    if($img->main_image != '1'){
                        $all_img .= '<button type="button" class="btn btn-primary btn-sm" id="product_image_activ'.$img->id.'"  value="'.$img->id.'" onclick="proImgMain(\'product_image_carousel'.$pro_id.'\',this.id)">Main</button>';
                        $all_img .= '<button type="button" class="btn btn-danger btn-sm" onclick="delProImg(\'product_image_carousel'.$pro_id.'\',\'product_image_activ'.$img->id.'\')">Delete</button>';
                    }

                  $all_img .= '</div>
                </div>';
            }else{
                $all_img .= '<div class="carousel-item">
                  <img src="'.asset('images/'.$img->image).'" width="100%" height="100">
                  <div class="overlay">';
                    if($img->main_image != '1'){
                        $all_img .= '<button type="button" class="btn btn-primary btn-sm" id="product_image_activ'.$img->id.'"  value="'.$img->id.'" onclick="proImgMain(\'product_image_carousel'.$pro_id.'\',this.id)">Main</button>';
                        $all_img .= '<button type="button" class="btn btn-danger btn-sm" onclick="delProImg(\'product_image_carousel'.$pro_id.'\',\'product_image_activ'.$img->id.'\')">Delete</button>';
                    }

                  $all_img .= '</div>
                </div>';
            }
            $i++;
        }
        return Response::json(['proImg'=>$all_img]);
    }

    public function imgDelete(Request $request)
    {
        $image = Image::where('id', $request->id)->get();
        foreach ($image as $imag) {
            $pro_id = $imag->product_id;
            @unlink(public_path().'/images/'.$imag->image);
        }

        Image::where('id', $request->id)->delete();
        $images = Image::where('product_id', $pro_id)->get();
        $i = 0;
        $all_img = '';
        foreach ($images as $img) {
            if($i==0){
              $all_img .= '<div class="carousel-item active">
                  <img src="'.asset('images/'.$img->image).'" width="100%" height="100">
                  <div class="overlay">';
                    if($img->main_image != '1'){
                        $all_img .= '<button type="button" class="btn btn-primary btn-sm" id="product_image_activ'.$img->id.'"  value="'.$img->id.'" onclick="proImgMain(\'product_image_carousel'.$pro_id.'\',this.id)">Main</button>';
                        $all_img .= '<button type="button" class="btn btn-danger btn-sm" onclick="delProImg(\'product_image_carousel'.$pro_id.'\',\'product_image_activ'.$img->id.'\')">Delete</button>';
                    }

                  $all_img .= '</div>
                </div>';
            }else{
                $all_img .= '<div class="carousel-item">
                  <img src="'.asset('images/'.$img->image).'" width="100%" height="100">
                  <div class="overlay">';
                    if($img->main_image != '1'){
                        $all_img .= '<button type="button" class="btn btn-primary btn-sm" id="product_image_activ'.$img->id.'"  value="'.$img->id.'" onclick="proImgMain(\'product_image_carousel'.$pro_id.'\',this.id)">Main</button>';
                        $all_img .= '<button type="button" class="btn btn-danger btn-sm" onclick="delProImg(\'product_image_carousel'.$pro_id.'\',\'product_image_activ'.$img->id.'\')">Delete</button>';
                    }

                  $all_img .= '</div>
                </div>';
            }
            $i++;
        }
      return Response::json(['proImg'=>$all_img]);
    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'category_id' => 'required|numeric',
            'sub_category_id' => 'nullable|numeric',
            'name' => 'required|string',
            'photo.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'state_id' => 'required',
            'size_weight' => 'required',
            'size' => 'required_if:size_weight,==,size|array',
            'size_unit' => 'required_if:size_weight,==,size',
            'weight' => 'required_if:size_weight,==,weight|array',
            'weight_unit' => 'required_if:size_weight,==,weight',
            'price.*' => 'required',
            'price' => ['required', 'array'],
            'stock.*' => 'required',
            'stock' => ['required', 'array'],
            'details' => 'required|string'               
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()));
        }else{
            if($request->discount!=''){
                if($request->discount >= $request->price){
                    return Response::json(array('errors' => ['discount' => 'Discount must be smaller then price']));
                }
            }
            $i = Image::where('product_id', $id)->count()+1;
            if($request->color == '#000000'){
                $color = '';
            }else{
               $color = $request->color;
            }
            $product = Product::where('id', $id)->update([
                'category_id' => $request->input('category_id'),
                'sub_category_id' => $request->input('sub_category_id'),
                'name' => $request->input('name'),
                'seller_id' => $request->input('seller_id'),
                'color' => $color,
                'details' => $request->input('details')
            ]);

            if($request->hasFile('photo')){
                foreach($request->file('photo') as $file){
                    $path = public_path('images/');
                    $file_name = $id.'_'.time().'_'.$i.'.'.$file->getClientOriginalExtension();
                    $file->move($path, $file_name);
                    $active = '0';
                    if($i == 1){
                        $active = '1';
                    }
                    Image::create([
                        'product_id' => $id,
                        'package_id' => null,
                        'booking_id' => null,
                        'main_image' => $active,
                        'image' => $file_name,
                    ]);
                    $i++;
                }
            }
            $state_id = $request->state_id;
            BusinessLocation::where('product_id', $id)->delete();
            foreach($state_id as $ind => $state){
                BusinessLocation::create([
                    'product_id' => $id,
                    'state_id' => $state,
                ]);
            }

            $prices = $request->price;
            ProductDesc::where('product_id', $id)->delete();
            for($i =0; $i < count($prices); $i++){
                $productDesc = new ProductDesc;
                $productDesc->product_id = $id;
                if($request->weight){
                    $productDesc->weight = $request->weight[$i];
                }
                if($request->size){
                    $productDesc->size = $request->size[$i];
                }
                if($request->weight_unit){
                    $productDesc->weight_unit = $request->weight_unit[$i];
                }
                if($request->size_unit){
                    $productDesc->size_unit = $request->size_unit[$i]; 
                }
                $productDesc->price = $request->price[$i]; 
                $productDesc->discount = $request->discount[$i]; 
                $productDesc->stock = $request->stock[$i]; 
                $productDesc->save();
            }

            AttributeTerm::where('product_id', $id)->delete();
            $term_id = $request->term_id;
            for($i=0; $i < count($term_id); $i++){
                $term = explode('-',$term_id[$i]);
                $attribute_id = $term[0];
                $term  = $term[1];
                AttributeTerm::create([
                        'product_id' => $id,
                        'attribute_id' => $attribute_id,
                        'term_id' => $term,
                    ]);
            }

            return Response::json(['msg'=>'Product Update Successfully']);
        }
    }

    public function destroy($id)
    {
            $image = Image::where('product_id', $id)->get();
            foreach ($image as $img) {
                @unlink(public_path().'/images/'.$img->image);
                Image::where('id', $img->id)->delete();
            }

            BusinessLocation::where('product_id', $id)->delete();
            ProductDesc::where('product_id', $id)->delete();
            AttributeTerm::where('product_id', $id)->delete();
            Product::where('id', $id)->delete();

            return Response::json(['msg'=>'Product Delete Successfully']);

    }
}
