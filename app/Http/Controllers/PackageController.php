<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Category;

use App\SubCategory;

use App\State;

use App\Seller;

use App\Package;

use App\Item;

use App\Samogri;

use App\Image;

use App\Attribute;

use App\Term;
use App\PackageDesc;

use App\AttributeTerm;

use App\BusinessLocation;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    private $categorys,$attributes,$states,$sellers,$items, $quality;
    public function __construct()
    {
        $this->middleware('auth');
        $this->categorys = Category::orderBy('category_name', 'asc')->get();
        $this->attributes = Attribute::orderBy('name', 'asc')->get();
        $this->states = State::orderBy('name', 'asc')->get();
        $this->sellers = Seller::orderBy('name', 'asc')->get();
        $this->items = Item::orderBy('name', 'asc')->get();
        $this->quality = array(['val' => 'B', 'name' => 'Basic'], ['val' => 'E', 'name' => 'Economic'], ['val' => 'S', 'name' => 'Standard'], ['val' => 'G', 'name' => 'Gold'], ['val' => 'P', 'name' => 'Premium']);
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
            return view('admin/package');
        }else if($user->role == 'Seller'){
            return view('seller/package');
        }else{
            return redirect('buyer/booking');
        }
    }

    public function showPackage(Request $request)
    {
        $user = Auth::user();
        $page_no = $request->page_no;
        $table_body = '';

        if($user->role == 'Admin'){
            $packages = Package::join('categories', 'packages.category_id', '=', 'categories.id')->leftJoin('sub_categories', 'packages.sub_category_id', '=', 'sub_categories.id')->select('packages.id', 'sub_categories.sub_category_name', 'categories.category_name', 'packages.name', 'packages.code', 'packages.details', 'packages.updated_at')->orderBy('packages.id', 'desc')->simplePaginate(3);
        }else if($user->role == 'Seller'){
            $packages = Package::join('categories', 'packages.category_id', '=', 'categories.id')->leftJoin('sub_categories', 'packages.sub_category_id', '=', 'sub_categories.id')->select('packages.id', 'sub_categories.sub_category_name', 'categories.category_name', 'packages.name', 'packages.code', 'packages.details', 'packages.updated_at')->where('user_id', $user->id)->orderBy('packages.id', 'desc')->simplePaginate(3);
        }

        

        if($packages->isEmpty()){
            $table_body = '<tr>
            <td class="text-center" colspan="'.$request->length.'">
                Product is not available yet.
            </td>
            </tr>';
        }else{
            foreach ($packages as $package) {
                $states = State::orderBy('name', 'asc')->get();
                $stat = '';
                $stock = true;

                foreach ($states as $state) {
                    $business_locations = BusinessLocation::where('package_id', $package->id)->where('state_id', $state->id)->get();
                    if(count($business_locations)!=0){
                       $stat .= $state->name.', ';
                    }
                }
                $packageDesc = PackageDesc::where('package_id', $package->id)->get();
                if(count($packageDesc) > 0){
                    foreach ($packageDesc as $desc) {
                        if($desc->stock < 1){
                            $stock = false;
                        }
                    }
                }else{
                    $stock = false; 
                }
                $table_body .= '<tr class='.($stock == false ? "table-danger" : "").'>
                    <td>'.$package->code.'</td>
                    <td>
                        <a href="javascript:void(0)" data-toggle="tooltip" title="'.$user->name.'">
                        '.$package->name.'
                        </a>
                    </td>
                    <td>'.$package->category_name.'</td>
                    <td>'.$package->sub_category_name.'</td>
                    <td>'.$stat.'</td>
                    <td>
                        '.PackageController::limit_words($package->details,5).' ...
                    </td>
                    <td><button type="button" value="'.$page_no.'-'.$package->id.'" class="btn btn-link edit-package"><i class="fa fa-pencil"></i></button></td>
                    <td><button type="button" value="'.$page_no.'-'.$package->id.'" class="btn btn-link delete-package"><i class="fa fa-trash-o"></i></button></td>
                </tr>'; 
            }
        }

        return response()->json(['page_no' => $page_no, 'table_body' => $table_body, 'pagination' => $packages]);
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        $page_no = $request->page_no;
        $search = $request->content;
        $table_body = '';

        if($user->role == 'Admin'){
            $packages = Package::join('categories', 'packages.category_id', '=', 'categories.id')->leftJoin('sub_categories', 'packages.sub_category_id', '=', 'sub_categories.id')->select('packages.id', 'sub_categories.sub_category_name', 'categories.category_name', 'packages.name', 'packages.code', 'packages.details', 'packages.updated_at')->where('packages.name', 'LIKE', '%'.$search.'%')->orWhere('packages.code', 'LIKE', '%' . $search . '%')->orderBy('packages.id', 'desc')->simplePaginate(3);
        }else if($user->role == 'Seller'){
            $packages = Package::join('categories', 'packages.category_id', '=', 'categories.id')->leftJoin('sub_categories', 'packages.sub_category_id', '=', 'sub_categories.id')->select('packages.id', 'sub_categories.sub_category_name', 'categories.category_name', 'packages.name', 'packages.code', 'packages.details', 'packages.updated_at')->where('packages.name', 'LIKE', '%'.$search.'%')->orWhere('packages.code', 'LIKE', '%' . $search . '%')->where('user_id', $user->id)->orderBy('packages.id', 'desc')->simplePaginate(3);
        }

        
        
        if($packages->isEmpty()){
            $table_body = '<tr>
            <td class="text-center" colspan="'.$request->length.'">
                Product is not available yet.
            </td>
            </tr>';
        }else{
            foreach ($packages as $package) {
                $states = State::orderBy('name', 'asc')->get();
                $stat = '';
                $stock = true;

                foreach ($states as $state) {
                    $business_locations = BusinessLocation::where('package_id', $package->id)->where('state_id', $state->id)->get();
                    if(count($business_locations)!=0){
                       $stat .= $state->name.', ';
                    }
                }
                $packageDesc = PackageDesc::where('package_id', $package->id)->get();
                if(count($packageDesc) > 0){
                    foreach ($packageDesc as $desc) {
                        if($desc->stock < 1){
                            $stock = false;
                        }
                    }
                }else{
                    $stock = false; 
                }
                $table_body .= '<tr class='.($stock == false ? "table-danger" : "").'>
                    <td>'.$package->code.'</td>
                    <td>
                        <a href="javascript:void(0)" data-toggle="tooltip" title="'.$user->name.'">
                        '.$package->name.'
                        </a>
                    </td>
                    <td>'.$package->category_name.'</td>
                    <td>'.$package->sub_category_name.'</td>
                    <td>'.$stat.'</td>
                    <td>
                        '.PackageController::limit_words($package->details,5).' ...
                    </td>
                    <td><button type="button" value="'.$page_no.'-'.$package->id.'" class="btn btn-link edit-package"><i class="fa fa-pencil"></i></button></td>
                    <td><button type="button" value="'.$page_no.'-'.$package->id.'" class="btn btn-link delete-package"><i class="fa fa-trash-o"></i></button></td>
                </tr>'; 
            }
        }
        
        return response()->json(['table_body' => $table_body, 'pagination' => $packages]);
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
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-12">
                        
                        <label>Add Items: <span class="badge badge-secondary" id="total-itm-row">0</span></label>
                        
                        <table class="table table-bordered table-sm" id="pac-pro-tbl">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-center" width="70%">Products</th>
                                    <th class="text-center" width="20%">Prices</th>
                                    <th class="text-center" width="10%" id="add-more-pac-pro">
                                        <i class="fa fa-plus"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                        
                    </div>
                    <div class="form-group col-md-12 text-right">
                        <label>Total: <span id="pro_tot_pri">0</span></label>
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
        return response()->json(['title' => 'Add Package Details', 'body' => $body, 'button_text' => 'Add New Package']);
    }

    public function showPackageToSeller()
    {
        $user = Auth::user();

        $allPackages = Package::join('categories', 'packages.category_id', '=', 'categories.id')->leftJoin('sub_categories', 'packages.sub_category_id', '=', 'sub_categories.id')->select('packages.id', 'sub_categories.sub_category_name', 'categories.category_name', 'packages.name', 'packages.code', 'packages.quality', 'packages.discount', 'packages.stock', 'packages.details', 'packages.updated_at')->where('user_id', $user->id)->orderBy('packages.id', 'desc')->get();
            $packages = '';
            $samogri = '';
            $price = 0;
            foreach ($allPackages as $package) {
                $samogris = Samogri::where('package_id', $package->id)->get();
                foreach ($samogris as $samg) {
                   $prds = Item::where('id', $samg->item_id)->get();
                   foreach ($prds as $prd) {
                        $price += $prd->price;
                        $samogri .= $prd->name.', ';
                   }
                }

                $states = State::orderBy('name', 'asc')->get();
                $stat = '';
                foreach ($states as $state) {
                    $business_locations = BusinessLocation::where('package_id', $package->id)->where('state_id', $state->id)->get();
                    if(count($business_locations)!=0){
                       $stat .= $state->name.', ';
                    }
                }

                if($package->stock == 0){
                    $packages .= '<tr class="table-danger">
                                    <td>'.$package->code.'</td>
                                    <td>
                                        <a href="javascript:void(0)" data-toggle="tooltip" title="'.$user->name.'">
                                        '.$package->name.'
                                        </a>
                                    </td>
                                    <td>'.$package->category_name.'</td>
                                    <td>'.$package->sub_category_name.'</td>
                                    <td>'.PackageController::limit_words(rtrim($samogri,', '),4).' ...</td>
                                    <td>'.$stat.'</td>
                                    <td>'.$package->stock.'</td>
                                    <td>';
                                    if($package->quality=='A'){
                                        $packages .= 'Deluxe';
                                    }else if($package->quality=='B'){
                                        $packages .= 'Premium';
                                    }else if($package->quality=='C'){
                                        $packages .= 'Standard';
                                    }else if($package->quality=='D'){
                                        $packages .= 'Basic';
                                    }
                    $packages .= '</td>
                                    <td>'.$price.'</td>
                                    <td>'.$package->discount.'</td>
                                    <td>
                                        '.PackageController::limit_words($package->details,5).' ...
                                    </td>
                                    <td>'.$package->updated_at.'</td>
                                    <td><button type="button" value="'.$package->id.'" class="btn btn-link edit-package"><i class="fa fa-pencil"></i></button></td>
                                    <td><button type="button" value="'.$package->id.'" class="btn btn-link delete-package"><i class="fa fa-trash-o"></i></button></td>
                                </tr>';
                }else{
                    $packages .= '<tr>
                                    <td>'.$package->code.'</td>
                                    <td>
                                        <a href="javascript:void(0)" data-toggle="tooltip" title="'.$user->name.'">
                                        '.$package->name.'
                                        </a>
                                    </td>
                                    <td>'.$package->category_name.'</td>
                                    <td>'.$package->sub_category_name.'</td>
                                    <td>'.PackageController::limit_words(rtrim($samogri,', '),4).' ...</td>
                                    <td>'.$stat.'</td>
                                    <td>'.$package->stock.'</td>
                                    <td>';
                                    if($package->quality=='A'){
                                        $packages .= 'Deluxe';
                                    }else if($package->quality=='B'){
                                        $packages .= 'Premium';
                                    }else if($package->quality=='C'){
                                        $packages .= 'Standard';
                                    }else if($package->quality=='D'){
                                        $packages .= 'Basic';
                                    }
                    $packages .= '</td>
                                    <td>'.$price.'</td>
                                    <td>'.$package->discount.'</td>
                                    <td>
                                        '.PackageController::limit_words($package->details,5).' ...
                                    </td>
                                    <td>'.$package->updated_at.'</td>
                                    <td><button type="button" value="'.$package->id.'" class="btn btn-link edit-package"><i class="fa fa-pencil"></i></button></td>
                                    <td><button type="button" value="'.$package->id.'" class="btn btn-link delete-package"><i class="fa fa-trash-o"></i></button></td>
                                </tr>';
                }
            }

        return view('seller/package', ['categorys' => $this->allCategorys, 'states' => $this->allStates, 'sellers' => $this->allSellers, 'attributes' => $this->allAttributes, 'items'=>$this->allItems, 'packages'=>$packages]);
    }

    public function addMorProRow(Request $request)
    {
        
        $total_itm_row = $request->total_itm_row + 1;

        $array_no = $request->total_itm_row;
        $quality = [];
        $list = explode(',',$request->listQly);
        // return $list;
        foreach ($this->quality as $qly) {
            if(!in_array($qly['val'], $list)){
                array_push($quality, $qly);
            }
        }
       
        $more = '<tr>
                <td>
                <select id="quality'.$total_itm_row.'" type="text" class="form-control" name="quality['.$array_no.']" placeholder="Quality">';
                $more .= '<option value="">Choose...</option>';
                foreach ($quality as $qly){
                    $more .= '<option value="'.$qly['val'].'">'.$qly['name'].'</option>';
                }
                $more .= '</select>
                </td>
                <td><input id="people'.$total_itm_row.'" type="text" class="form-control" name="people['.$array_no.']" placeholder="People"></td>
                <td><input id="discount'.$total_itm_row.'" type="text" class="form-control" name="discount['.$array_no.']" placeholder="Discount %"></td>
                <td><input id="stock'.$total_itm_row.'" type="text" class="form-control" name="stock['.$array_no.']" placeholder="Stock"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <select class="form-control multipleSelect" multiple id="package_product'.$total_itm_row.'" name="product['.$array_no.'][]">';
                    foreach ($this->items as $item){
                        $more .= '<option value="'.$item->id.'">'.$item->name.'</option>';
                    }
                    
                    $more .= '</select>
                </td>
                <td id="pro_pri'.$total_itm_row.'"></td>
                <td class="text-center remove-pec-pro"><i class="fa fa-minus-circle"></i></td>
            </tr>';
        

        return Response::json(['totalRow' =>$total_itm_row, 'more'=>$more]);
    }

    public function store(Request $request)
    {
        
        $rules = array(
            'category_id' => 'required|numeric',
            'sub_category_id' => 'nullable|numeric',
            'name' => 'required|string',
            'photo' => 'required',
            'photo.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'state_id' => 'required|array|min:1',
            'seller_id' => 'nullable|numeric',
            'quality.*' => 'required',
            'quality' => ['required', 'array'],
            'people.*' => 'nullable|numeric',
            'discount.*' => 'nullable|numeric',
            'stock.*' => 'required|numeric',
            'stock' => ['required', 'array'],
            'product.*' => 'required|array|min:1',
            'details' => 'required|string',
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()));
        }else{
            $i = 1;
            $posible_text='123456789';
            $code='';
            $p=0;
            while($p<8){
                $code .= substr($posible_text,mt_rand(0,strlen($posible_text)-1),1);
                $p++;
            }
            $code = 'PG-'.$code;
            $user = Auth::user();
            $package = Package::create([
                'code' => $code,
                'user_id' => $user->id,
                'category_id' => $request->input('category_id'),
                'sub_category_id' => $request->input('sub_category_id'),
                'name' => $request->input('name'),
                'seller_id' => $request->input('seller_id'),
                'details' => $request->input('details')
            ]);
            if($package->id){
                if($request->hasFile('photo')){
                    foreach($request->file('photo') as $file){
                        $path = public_path('images/');
                        $file_name = $package->id.'_'.time().'_'.$i.'.'.$file->getClientOriginalExtension();
                        $file->move($path, $file_name);
                        $active = '0';
                        if($i == 1){
                            $active = '1';
                        }
                        Image::create([
                            'product_id' => null,
                            'package_id' => $package->id,
                            'booking_id' => null,
                            'main_image' => $active,
                            'image' => $file_name,
                        ]);
                        $i++;
                    }
                }

                foreach($request->state_id as $ind => $state){
                    BusinessLocation::create([
                        'package_id' => $package->id,
                        'state_id' => $state,
                    ]);
                }

                foreach ($request->quality as $key => $qly) {
                    if ($qly !== null) {
                        $pds = PackageDesc::create([
                            'package_id' => $package->id,
                            'quality' => $qly,
                            'people' => $request->people[$key],
                            'discount' => $request->discount[$key],
                            'stock' => $request->stock[$key],
                        ]);
                        if ($pds->id) {
                            foreach($request->product[$key] as $item){
                                Samogri::create([
                                    'package_id' => $package->id,
                                    'package_desc_id' => $pds->id,
                                    'item_id' => $item,
                                ]);
                            }
                        }
                    }
                }

                if (isset($request->term_id)) {
                    $term_id = $request->term_id;
                    for($i=0; $i < count($term_id); $i++){
                        $term = explode('-',$term_id[$i]);
                        $attribute_id = $term[0];
                        $term  = $term[1];
                        AttributeTerm::create([
                            'package_id' => $package->id,
                            'attribute_id' => $attribute_id,
                            'term_id' => $term,
                        ]);
                    }
                }
            }

        
            return Response::json(['msg'=>'Package Add Successfully']);
        }
    }
   
    public function showPrices(Request $request)
    {
        $price = '';
        $total = 0; 
        if($request->action == 'remove'){
            $total = $request->total;
        }
        
        if($request->id!=''){
            foreach ($request->id as $id) {
                $items = Item::where('id', $id)->get();
                foreach ($items as $item) {
                    if($request->action == 'remove'){
                        $total -= $item->price;
                    }
                    $price .= '<span class="badge badge-dark">'.$item->price.'</span> ';
                }
            }
            
        }
        if($request->action == 'add' && $request->all_id != ''){
            foreach ($request->all_id as $d) {
                $itms = Item::where('id', $d)->get();
                foreach ($itms as $itm) {
                    $total += $itm->price;
                }
            }
        }
        return Response::json(['proPrice' =>$price, 'proPriTot' => $total]);
    }
    /*public function moreAdd(Request $request)
    {
        $total_item = $request->input('item_total')+1;

        $allProducts = Product::orderBy('id', 'desc')->get();
       $more = '<tr>
                    <td>
                    <input id="samogri_id'.$total_item.'" name="id[]" value="" type="hidden">
                        <select id="prd_id'.$total_item.'"  name="product_id[]">
                            <option value="">Select Product</option>';
                        foreach($allProducts as $key => $Product)
                            $more .= '<option value="'.$Product->id.'">'.$Product->name.'</option>';
                        $more .= '</select>
                    </td>
                    <td>
                    <div class="row">
                        <div class="col">
                            <input id="weight_size'.$total_item.'" name="size_weight[]" type="number" placeholder="Size/Weight">
                        </div>
                        <div class="col">
                            <select id="item_unit'.$total_item.'" name="unit[]">
                                <option value="">Unit</option>
                                <optgroup label="Size Unit">
                                    <option value="Metre">Metre</option>
                                    <option value="Centimetre">Centimetre</option>
                                    <option value="Millimetre">Millimetre</option>
                                    <option value="Micrometre">Micrometre</option>
                                    <option value="Nanometre">Nanometre</option>
                                    <option value="Foot">Foot</option>
                                    <option value="Inch">Inch</option>
                                </optgroup>
                                <optgroup label="Weight Unit">
                                    <option value="Kilogram">Kilogram</option>
                                    <option value="Gram">Gram</option>
                                    <option value="Milligram">Milligram</option>
                                    <option value="Microgram">Microgram</option>
                                    <option value="Pound">Pound</option>
                                    <option value="Ounce">Ounce</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    </td>
                  </tr>';
        return Response::json(['totalItem' =>$total_item, 'more'=>$more]);
    }*/

    /*public function delProductFromPackage(Request $request)
    {
        $samogri = Samogri::where('id', $request->id)->get();
        foreach ($samogri as $samog) {
            $package_id = $samog->package_id;
        }

        Samogri::where('id', $request->id)->delete();
        $samogris = Samogri::where('package_id', $package_id)->get();



        $total_item = count($samogris);

        $packages = Product::orderBy('id', 'desc')->get();
        $more = '';
        $i = 1;
        foreach ($samogris as $samg) {
            $more .= '<tr>
                <td>
                <button type="button" value="'.$samg->id.'" class="btn btn-link delete_pack_pro" title="Delete This Item"><i class="fa fa-trash-o"></i></button>

                    <input id="samogri_id'.$i.'" name="id[]" type="hidden" value="'.$samg->id.'">
                    <select id="prd_id'.$i.'" name="product_id[]">
                        <option value="">Select Product</option>';
                      foreach ($packages as $product){
                        if($samg->item_id == $product->id){
                            $more .= '<option value="'.$product->id.'" selected>'.$product->name.'</option>';
                        }else{
                            $more .= '<option value="'.$product->id.'">'.$product->name.'</option>';
                        }
                      }
                $more .= '</select>
                </td>
                <td>
                <div class="row">
                    <div class="col">
                        <input id="weight_size'.$i.'" name="size_weight[]" type="number" value="'.$samg->size_weight.'" placeholder="Size/Weight">
                    </div>
                    <div class="col">
                        <select id="item_unit'.$i.'" name="unit[]">
                            <option value="">Unit</option>
                            <optgroup label="Size Unit">';
                            if($samg->unit == 'Metre'){
                                $more .= '<option value="Metre" selected>Metre</option>';
                            }else{
                                $more .= '<option value="Metre">Metre</option>';
                            }
                            if($samg->unit == 'Centimetre'){
                                $more .= '<option value="Centimetre" selected>Centimetre</option>';
                            }else{
                                $more .= '<option value="Centimetre">Centimetre</option>';
                            }
                            if($samg->unit == 'Millimetre'){
                                $more .= '<option value="Millimetre" selected>Millimetre</option>';
                            }else{
                                $more .= '<option value="Millimetre">Millimetre</option>';
                            }
                            if($samg->unit == 'Micrometre'){
                                $more .= '<option value="Micrometre" selected>Micrometre</option>';
                            }else{
                                $more .= '<option value="Micrometre">Micrometre</option>';
                            }
                            if($samg->unit == 'Nanometre'){
                                $more .= '<option value="Nanometre" selected>Nanometre</option>';
                            }else{
                                $more .= '<option value="Nanometre">Nanometre</option>';
                            }
                            if($samg->unit == 'Foot'){
                                $more .= '<option value="Foot" selected>Foot</option>';
                            }else{
                                $more .= '<option value="Foot">Foot</option>';
                            }
                            if($samg->unit == 'Inch'){
                                $more .= '<option value="Inch" selected>Inch</option>';
                            }else{
                                $more .= '<option value="Inch">Inch</option>';
                            }
                            $more .= '</optgroup>
                            <optgroup label="Weight Unit">';
                            if($samg->unit == 'Kilogram'){
                                $more .= '<option value="Kilogram" selected>Kilogram</option>';
                            }else{
                                $more .= '<option value="Kilogram">Kilogram</option>';
                            }
                            if($samg->unit == 'Gram'){
                                $more .= '<option value="Gram" selected>Gram</option>';
                            }else{
                                $more .= '<option value="Gram">Gram</option>';
                            }
                            if($samg->unit == 'Milligram'){
                                $more .= '<option value="Milligram" selected>Milligram</option>';
                            }else{
                                $more .= '<option value="Milligram">Milligram</option>';
                            }
                            if($samg->unit == 'Microgram'){
                                $more .= '<option value="Microgram" selected>Microgram</option>';
                            }else{
                                $more .= '<option value="Microgram">Microgram</option>';
                            }
                            if($samg->unit == 'Pound'){
                                $more .= '<option value="Pound" selected>Pound</option>';
                            }else{
                                $more .= '<option value="Pound">Pound</option>';
                            }
                            if($samg->unit == 'Ounce'){
                                $more .= '<option value="Ounce" selected>Ounce</option>';
                            }else{
                                $more .= '<option value="Ounce">Ounce</option>';
                            }
                            $more .= '</optgroup>
                        </select>
                    </div>
                </div>
                </td>
              </tr>';
        }
        return Response::json(['totalItem' =>$total_item, 'more'=>$more]);
    }*/

    public function edit($id)
    {
        $category_list = '';
        $sub_category_list = '';
        $state_list = '';
        $seller_list = '';

        $package = Package::with('desc','images', 'states', 'attributes')->find($id);

        $attributes = Attribute::where('category_id',$package->category_id)->where('subcategory_id',$package->sub_category_id)->orderBy('name', 'asc')->get();

        $subcategorys = SubCategory::where('category_id', $package->category_id)->get();

        if(!$this->categorys->isEmpty()){
            $category_list = '<option value="">Choose...</option>';
            foreach ($this->categorys as $category) {
                if($package->category_id == $category->id){
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
                if($package->sub_category_id == $sub->id){
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
                foreach ($package->states as $stat) {
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
                if($seller->id == $package->seller_id){
                    $seller_list .= '<option value="'.$seller->id.'" selected>'.$seller->name.'</option>';
                }else{
                    $seller_list .= '<option value="'.$seller->id.'">'.$seller->name.'</option>';
                }
            }
        }else{
            $seller_list = '<option value="">No Seller</option>';
        }

        $proats = array();
        $at = 0;
        foreach ($package->attributes as $attterm) {
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
                        <input type="text" class="form-control" placeholder="Separate by , (comma)" name="name" value="'.$package->name.'" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Images <span style="color:red;">*</span></label>
                        <div id="nexpre" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner" id="package_image_carousel'.$id.'">';
                            $i = 0;
                            foreach ($package->images as $image) {
                                if($i==0){
                                $body .= '<div class="carousel-item active">
                                    <img src="'.asset('images/'.$image->image).'" width="100%" height="100">
                                    <div class="overlay">';
                                        if($image->main_image != '1'){
                                            $body .= '<button type="button" class="btn btn-primary btn-sm" id="package_image_activ'.$image->id.'"  value="'.$image->id.'" onclick="packImgMain(\'package_image_carousel'.$id.'\',\'package_image_activ'.$image->id.'\')">Main</button>';
                                            $body .= '<button type="button" class="btn btn-danger btn-sm" onclick="delPackImg(\'package_image_carousel'.$id.'\',\'package_image_activ'.$image->id.'\')">Delete</button>';
                                        }

                                    $body .= '</div>
                                    </div>';
                                }else{
                                    $body .= '<div class="carousel-item">
                                    <img src="'.asset('images/'.$image->image).'" width="100%" height="100">
                                    <div class="overlay">';
                                        if($image->main_image != '1'){
                                            $body .= '<button type="button" class="btn btn-primary btn-sm" id="package_image_activ'.$image->id.'"  value="'.$image->id.'" onclick="packImgMain(\'package_image_carousel'.$id.'\',\'package_image_activ'.$image->id.'\')">Main</button>';
                                            $body .= '<button type="button" class="btn btn-danger btn-sm" onclick="delPackImg(\'package_image_carousel'.$id.'\',\'package_image_activ'.$image->id.'\')">Delete</button>';
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
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-12">
                        
                        <label>Add Items: <span class="badge badge-secondary" id="total-itm-row">'.count($package->desc).'</span></label>
                        
                        <table class="table table-bordered table-sm" id="pac-pro-tbl">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-center" width="70%">Products</th>
                                    <th class="text-center" width="20%">Prices</th>
                                    <th class="text-center" width="10%" id="add-more-pac-pro">
                                        <i class="fa fa-plus"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>';
                                $arr = [];
                                foreach($package->desc as $q){
                                    array_push($arr,$q->quality);
                                }
                                $total = 0;
                                foreach ($package->desc as $k => $desc) {
                                    $body .= '<tr>
                                    <td>
                                    <input type="hidden" name="desc_id['.$k.']" value="'.$desc->id.'">
                                    <select id="quality'.($k+1).'" type="text" class="form-control" name="quality['.$k.']" placeholder="Quality">';
                                    $body .= '<option value="">Choose...</option>';
                                    foreach ($this->quality as $qly){
                                        if($desc->quality == $qly['val']){
                                            $body .= '<option selected value="'.$qly['val'].'">'.$qly['name'].'</option>';
                                        }else{
                                            if(!in_array($qly['val'], $arr)){
                                                $body .= '<option value="'.$qly['val'].'">'.$qly['name'].'</option>';
                                            }
                                        }
                                        
                                    }
                                    $body .= '</select>
                                    </td>
                                    <td><input id="people'.($k+1).'" type="text" class="form-control" name="people['.$k.']" placeholder="People" value="'.$desc->people.'"></td>
                                    <td><input id="discount'.($k+1).'" type="text" class="form-control" name="discount['.$k.']" placeholder="Discount %" value="'.$desc->discount.'"></td>
                                    <td><input id="stock'.($k+1).'" type="text" class="form-control" name="stock['.$k.']" placeholder="Stock" value="'.$desc->stock.'"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <select class="form-control multipleSelect" multiple id="package_product'.($k+1).'" name="product['.$k.'][]">';
                                        $price = '';
                                        
                                        foreach ($this->items as $item){
                                            $samogris = Samogri::where('package_id', $id)->where('package_desc_id', $desc->id)->where('item_id', $item->id)->get();
                                            if(count($samogris) != 0){
                                                $total += $item->price;
                                                $body .= '<option value="'.$item->id.'" selected>'.$item->name.'</option>';
                                                $price .= '<span class="badge badge-dark">'.$item->price.'</span> ';
                                            }else{
                                            $body .= '<option value="'.$item->id.'">'.$item->name.'</option>';
                                            }
                                        }
                                        
                                        $body .= '</select>
                                    </td>
                                    <td id="pro_pri'.($k+1).'">'.$price.'</td>
                                    <td class="text-center remove-pec-pro"><i class="fa fa-minus-circle"></i></td>
                                </tr>';
                              }  
                            $body .= '</tbody>
                        </table>
                        
                    </div>
                    <div class="form-group col-md-12 text-right">
                        <label>Total: <span id="pro_tot_pri">'.$total.'</span></label>
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
                            if(count($proats) > 0){
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
                        <textarea class="form-control" rows="5" name="details" required>'.$package->details.'</textarea>
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
        
        return response()->json(['title' => 'Edit Package Details', 'body' => $body, 'button_text' => 'Save Package']);
    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'category_id' => 'required|numeric',
            'sub_category_id' => 'nullable|numeric',
            'name' => 'required|string',
            'photo.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'state_id' => 'required|array|min:1',
            'seller_id' => 'nullable|numeric',
            'quality.*' => 'required',
            'quality' => ['required', 'array'],
            'people.*' => 'nullable|numeric',
            'discount.*' => 'nullable|numeric',
            'stock.*' => 'required|numeric',
            'stock' => ['required', 'array'],
            'product.*' => 'required|array|min:1',
            'details' => 'required|string',
        );

        $present_desc = [];
        $desc_id = [];
        $exter_list = [];
        
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()));
        }else{
            if(isset($request->desc_id)){
                $desc_id = $request->desc_id;	
            }
    
            $desc = PackageDesc::where('package_id', $id)->get();
            foreach ($desc as $des) {
                array_push($present_desc, $des->id);
            }

            $i = Image::where('package_id', $id)->count()+1;

            $package = Package::where('id', $id)->update([
                'category_id' => $request->input('category_id'),
                'sub_category_id' => $request->input('sub_category_id'),
                'name' => $request->input('name'),
                'seller_id' => $request->input('seller_id'),
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
                        'product_id' => null,
                        'package_id' => $id,
                        'booking_id' => null,
                        'main_image' => $active,
                        'image' => $file_name,
                    ]);
                    $i++;
                }
            }
            $state_id = $request->state_id;
            BusinessLocation::where('package_id', $id)->delete();
            foreach($state_id as $ind => $state){
                BusinessLocation::create([
                    'package_id' => $id,
                    'state_id' => $state,
                ]);
            }

			$exter_list = array_diff($desc_id,$present_desc);
			Samogri::where('package_id', $id)->delete();

            foreach ($exter_list as $ext) {
                PackageDesc::where('id', $ext)->delete();
            }

			foreach ($request->quality as $key => $qly) {
				if(isset($request->desc_id[$key])){
					PackageDesc::where('id', $request->desc_id[$key])->update([
		                'quality' => $qly,
                        'people' => $request->people[$key],
                        'discount' => $request->discount[$key],
                        'stock' => $request->stock[$key],
		            ]);
		            foreach($request->product[$key] as $item){
                        Samogri::create([
                            'package_id' => $id,
                            'package_desc_id' => $request->desc_id[$key],
                            'item_id' => $item,
                        ]);
                    }
				}else{
					$pds = PackageDesc::create([
                        'package_id' => $id,
                        'quality' => $qly,
                        'people' => $request->people[$key],
                        'discount' => $request->discount[$key],
                        'stock' => $request->stock[$key],
                    ]);
                    if ($pds->id) {
                        foreach($request->product[$key] as $item){
                            Samogri::create([
                                'package_id' => $id,
                                'package_desc_id' => $pds->id,
                                'item_id' => $item,
                            ]);
                        }
                    }
				}
			}

            AttributeTerm::where('package_id', $id)->delete();
            if (isset($request->term_id)) {
            	$term_id = $request->term_id;
	            for($i=0; $i < count($term_id); $i++){
	                $term = explode('-',$term_id[$i]);
	                $attribute_id = $term[0];
	                $term  = $term[1];
	                AttributeTerm::create([
	                    'package_id' => $id,
	                    'attribute_id' => $attribute_id,
	                    'term_id' => $term,
	                ]);
	            }
            }
            

            return Response::json(['msg'=>'Package Update Successfully']);
        }
    }

    public function imgMain(Request $request)
    {
        $image = Image::where('id', $request->id)->get();
        foreach ($image as $imag) {
            $pack_id = $imag->package_id;
        }
        Image::where('package_id', $pack_id)->update(['main_image' => '0']);
        Image::where('id', $request->id)->update(['main_image' => '1']);
        $images = Image::where('package_id', $pack_id)->get();
        $i = 0;
        $all_img = '';
        foreach ($images as $img) {
            if($i==0){
              $all_img .= '<div class="carousel-item active">
                  <img src="'.asset('images/'.$img->image).'" width="100%" height="100">
                  <div class="overlay">';
                    if($img->main_image != '1'){
                        $all_img .= '<button type="button" class="btn btn-primary btn-sm" id="package_image_activ'.$img->id.'"  value="'.$img->id.'" onclick="packImgMain(\'package_image_carousel'.$pack_id.'\',this.id)">Main</button>';
                        $all_img .= '<button type="button" class="btn btn-danger btn-sm" onclick="delPackImg(\'package_image_carousel'.$pack_id.'\',\'package_image_activ'.$img->id.'\')">Delete</button>';
                    }

                  $all_img .= '</div>
                </div>';
            }else{
                $all_img .= '<div class="carousel-item">
                  <img src="'.asset('images/'.$img->image).'" width="100%" height="100">
                  <div class="overlay">';
                    if($img->main_image != '1'){
                        $all_img .= '<button type="button" class="btn btn-primary btn-sm" id="package_image_activ'.$img->id.'"  value="'.$img->id.'" onclick="packImgMain(\'package_image_carousel'.$pack_id.'\',this.id)">Main</button>';
                        $all_img .= '<button type="button" class="btn btn-danger btn-sm" onclick="delPackImg(\'package_image_carousel'.$pack_id.'\',\'package_image_activ'.$img->id.'\')">Delete</button>';
                    }

                  $all_img .= '</div>
                </div>';
            }
            $i++;
        }
      return Response::json(['packImg'=>$all_img]);
    }

    public function imgDelete(Request $request)
    {
        $image = Image::where('id', $request->id)->get();
        foreach ($image as $imag) {
            $pack_id = $imag->package_id;
            @unlink(public_path().'/images/'.$imag->image);
        }

        Image::where('id', $request->id)->delete();
        $images = Image::where('package_id', $pack_id)->get();
        $i = 0;
        $all_img = '';
        foreach ($images as $img) {
            if($i==0){
              $all_img .= '<div class="carousel-item active">
                  <img src="'.asset('images/'.$img->image).'" width="100%" height="100">
                  <div class="overlay">';
                    if($img->main_image != '1'){
                        $all_img .= '<button type="button" class="btn btn-primary btn-sm" id="package_image_activ'.$img->id.'"  value="'.$img->id.'" onclick="packImgMain(\'package_image_carousel'.$pack_id.'\',this.id)">Main</button>';
                        $all_img .= '<button type="button" class="btn btn-danger btn-sm" onclick="delPackImg(\'package_image_carousel'.$pack_id.'\',\'package_image_activ'.$img->id.'\')">Delete</button>';
                    }

                  $all_img .= '</div>
                </div>';
            }else{
                $all_img .= '<div class="carousel-item">
                  <img src="'.asset('images/'.$img->image).'" width="100%" height="100">
                  <div class="overlay">';
                    if($img->main_image != '1'){
                        $all_img .= '<button type="button" class="btn btn-primary btn-sm" id="package_image_activ'.$img->id.'"  value="'.$img->id.'" onclick="packImgMain(\'package_image_carousel'.$pro_id.'\',this.id)">Main</button>';
                        $all_img .= '<button type="button" class="btn btn-danger btn-sm" onclick="delPackImg(\'package_image_carousel'.$pro_id.'\',\'package_image_activ'.$img->id.'\')">Delete</button>';
                    }

                  $all_img .= '</div>
                </div>';
            }
            $i++;
        }
      return Response::json(['packImg'=>$all_img]);
    }

    public function destroy($id)
    {
        $image = Image::where('package_id', $id)->get();
        foreach ($image as $img) {
            @unlink(public_path().'/images/'.$img->image);
            Image::where('id', $img->id)->delete();
        }
        BusinessLocation::where('package_id', $id)->delete();
        Samogri::where('package_id', $id)->delete();
        PackageDesc::where('package_id', $id)->delete();
        AttributeTerm::where('package_id', $id)->delete();
        Package::where('id', $id)->delete();
        
        return Response::json(['msg'=>'Package Delete Successfully']);
    }
}
