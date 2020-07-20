<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Category;

use App\SubCategory;

use App\Booking;

use App\Image;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    private $userId,$userRole,$allCategorys,$allBookings;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->userId = Auth::user()->id;
            $this->userRole = Auth::user()->role;
            return $next($request);
        });

        $this->allCategorys = Category::orderBy('category_name', 'asc')->get();
        $this->allBookings = Booking::join('categories', 'bookings.category_id', '=', 'categories.id')->leftJoin('sub_categories', 'bookings.sub_category_id', '=', 'sub_categories.id')->select('bookings.id', 'bookings.code', 'bookings.user_id', 'categories.category_name', 'sub_categories.sub_category_name', 'bookings.name', 'bookings.location', 'bookings.language', 'bookings.enlisted_in', 'bookings.preferable_events', 'bookings.preferable_place', 'bookings.performane_duration', 'bookings.price', 'bookings.performance_fee', 'bookings.video', 'bookings.on_stage_team', 'bookings.off_stage_team', 'bookings.off_stage_food', 'bookings.details', 'bookings.status', 'bookings.updated_at')->orderBy('bookings.id', 'desc')->get();
    }

    public static function limit_words($string, $word_limit)
    {
        $words = explode(" ",$string);
        return implode(" ", array_splice($words, 0, $word_limit));
    }

    public function index()
    {
        //dd($this->userId);
        //dd($this->userRole);
         $subCategorys = SubCategory::where('category_id', 5)->orderBy('sub_category_name', 'asc')->get();
        $book = '';
        foreach ($this->allBookings as $booking){
            if($this->userRole == 'Admin'){
                $book .= '<tr>
                        <td>'.$booking->code.'</td>
                        <td>'.$booking->name.'</td>
                        <td>'.$booking->category_name.'</td>
                        <td>'.$booking->sub_category_name.'</td>
                        <td>'.$booking->location.'</td>
                        <td>'.$booking->language.'</td>
                        <td>'.$booking->enlisted_in.'</td>
                        <td>'.$booking->preferable_events.'</td>
                        <td>'.$booking->preferable_place.'</td>
                        <td>'.$booking->performane_duration.'</td>
                        <td>'.$booking->price.'</td>
                        <td>'.$booking->performance_fee.'</td>';
                        $book .= '<td>';
                        $book .= '<button type="button" value="'.$booking->id.'/'.$booking->status.'" class="btn btn-primary btn-sm status_booking">';
                        if($booking->status==1){
                            $book .= 'Show';
                        }else{
                            $book .= 'Hide';
                        }
                        $book .= '</button>';
                        $book .= '</td>';
                        $book .= '<td><button type="button" value="'.$booking->id.'" class="btn btn-link edit_booking"><i class="fa fa-pencil"></i></button></td>
                        <td><button type="button" value="'.$booking->id.'" class="btn btn-link delete_booking"><i class="fa fa-trash-o"></i></button></td>
                    </tr>';
            }else{
                //$book .= $booking->user_id.'<br>';
                if($booking->user_id==$this->userId){
                    $book .= '<tr>
                        <td>'.$booking->code.'</td>
                        <td>'.$booking->name.'</td>
                        <td>'.$booking->category_name.'</td>
                        <td>'.$booking->sub_category_name.'</td>
                        <td>'.$booking->location.'</td>
                        <td>'.$booking->language.'</td>
                        <td>'.$booking->enlisted_in.'</td>
                        <td>'.$booking->preferable_events.'</td>
                        <td>'.$booking->preferable_place.'</td>
                        <td>'.$booking->performane_duration.'</td>
                        <td>'.$booking->price.'</td>
                        <td>'.$booking->performance_fee.'</td>';
                        $book .= '<td>';
                        if($booking->status==1){
                            $book .= 'Active';
                        }else{
                            $book .= 'Wait For Review';
                        }
                        $book .= '</td>';
                        $book .= '<td><button type="button" value="'.$booking->id.'" class="btn btn-link edit_booking"><i class="fa fa-pencil"></i></button></td>
                        <td><button type="button" value="'.$booking->id.'" class="btn btn-link delete_booking"><i class="fa fa-trash-o"></i></button></td>
                    </tr>';
                }
            }
        }
        if($this->userRole == 'Admin'){
            return view('admin/booking', ['categorys' => $this->allCategorys, 'bookings'=>$book, 'role' => $this->userRole]);
        }else{
            return view('buyer/booking', ['categorys' => $this->allCategorys, 'bookings'=>$book, 'role' => $this->userRole, 'subCategorys' => $subCategorys]);
        }
    }

    public function store(Request $request)
    {
        $rules = array(
            'category_id' => 'required|numeric',
            'sub_category_id' => 'nullable|numeric',
            'name' => 'required|string',
            'location' => 'required|string',
            'language' => 'required|string',
            'enlisted_in' => 'required|string',
            'preferable_events' => 'required|string',
            'preferable_place' => 'required|string',
            'performane_duration' => 'required|numeric',
            'price' => 'required|numeric',
            'performance_fee' => 'required|numeric',
            'photo' => 'required',
            'photo.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video' => 'nullable|string',
            'on_stage_team' => 'nullable|numeric',
            'off_stage_team' => 'nullable|numeric',
            'off_stage_food' => 'nullable|numeric',
            'details' => 'nullable|string'
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()));
        }else{
            $i = 1;
            $posible_text='ASDFGHJKLZXCVBNMQWERTYUP23456789';
            $code='';
            $p=0;
            while($p<8){
                $code .= substr($posible_text,mt_rand(0,strlen($posible_text)-1),1);
                $p++;
            }
            $user = Auth::user();
            $booking = Booking::create([
                'code' => $code,
                'user_id' => $this->userId,
                'category_id' => $request->input('category_id'),
                'sub_category_id' => $request->input('sub_category_id'),
                'name' => $request->input('name'),
                'location' => $request->input('location'),
                'language' => $request->input('language'),
                'enlisted_in' => $request->input('enlisted_in'),
                'preferable_events' => $request->input('preferable_events'),
                'preferable_place' => $request->input('preferable_place'),
                'performane_duration' => $request->input('performane_duration'),
                'price' => $request->input('price'),
                'performance_fee' => $request->input('performance_fee'),
                'video' => $request->input('video'),
                'on_stage_team' => $request->input('on_stage_team'),
                'off_stage_team' => $request->input('off_stage_team'),
                'off_stage_food' => $request->input('off_stage_food'),
                'preferable_events' => $request->input('preferable_events'),
                'details' => $request->input('details')
            ]);
            if($booking->id){
                if($request->hasFile('photo')){
                    foreach($request->file('photo') as $file){
                        $path = public_path('images/');
                        $file_name = $booking->id.'_'.time().'_'.$i.'.'.$file->getClientOriginalExtension();
                        $file->move($path, $file_name);
                        $active = '0';
                        if($i == 1){
                            $active = '1';
                        }
                        Image::create([
                            'product_id' => null,
                            'package_id' => null,
                            'booking_id' => $booking->id,
                            'main_image' => $active,
                            'image' => $file_name,
                        ]);
                        $i++;
                    }
                }
            }
            //dd($this->userId);
            //dd($this->userRole);

            $book = '';
            foreach ($this->allBookings as $booking){
                if($this->userRole == 'Admin'){
                    $book .= '<tr>
                            <td>'.$booking->code.'</td>
                            <td>'.$booking->name.'</td>
                            <td>'.$booking->category_name.'</td>
                            <td>'.$booking->sub_category_name.'</td>
                            <td>'.$booking->location.'</td>
                            <td>'.$booking->language.'</td>
                            <td>'.$booking->enlisted_in.'</td>
                            <td>'.$booking->preferable_events.'</td>
                            <td>'.$booking->preferable_place.'</td>
                            <td>'.$booking->performane_duration.'</td>
                            <td>'.$booking->price.'</td>
                            <td>'.$booking->performance_fee.'</td>';
                            $book .= '<td>';
                            $book .= '<button type="button" value="'.$booking->id.'/'.$booking->status.'" class="btn btn-primary btn-sm status_booking">';
                            if($booking->status==1){
                                $book .= 'Show';
                            }else{
                                $book .= 'Hide';
                            }
                            $book .= '</button>';
                            $book .= '</td>';
                            $book .= '<td><button type="button" value="'.$booking->id.'" class="btn btn-link edit_booking"><i class="fa fa-pencil"></i></button></td>
                            <td><button type="button" value="'.$booking->id.'" class="btn btn-link delete_booking"><i class="fa fa-trash-o"></i></button></td>
                        </tr>';
                }else{
                    if($booking->user_id==$this->userId){
                        $book .= '<tr>
                            <td>'.$booking->code.'</td>
                            <td>'.$booking->name.'</td>
                            <td>'.$booking->category_name.'</td>
                            <td>'.$booking->sub_category_name.'</td>
                            <td>'.$booking->location.'</td>
                            <td>'.$booking->language.'</td>
                            <td>'.$booking->enlisted_in.'</td>
                            <td>'.$booking->preferable_events.'</td>
                            <td>'.$booking->preferable_place.'</td>
                            <td>'.$booking->performane_duration.'</td>
                            <td>'.$booking->price.'</td>
                            <td>'.$booking->performance_fee.'</td>';
                            $book .= '<td>';
                            if($booking->status==1){
                                $book .= 'Active';
                            }else{
                                $book .= 'Wait For Review';
                            }
                            $book .= '</td>';
                            $book .= '<td><button type="button" value="'.$booking->id.'" class="btn btn-link edit_booking"><i class="fa fa-pencil"></i></button></td>
                            <td><button type="button" value="'.$booking->id.'" class="btn btn-link delete_booking"><i class="fa fa-trash-o"></i></button></td>
                        </tr>';
                    }
                }
            }

            return Response::json(['bookings' => $book, 'msg' => 'Booking Add Successfully']);
        }
    }

    public function edit(Request $request)
    {
        $categorys = Category::orderBy('category_name', 'asc')->get();
        $booking = Booking::where('id', $request->id)->get();
        $images = Image::where('booking_id', $request->id)->get();
        foreach($booking as $book){
            $id = $book->id;
            $category_id = $book->category_id;
            $sub_category_id = $book->sub_category_id;
            $name = $book->name;
            $location = $book->location;
            $language = $book->language;
            $enlisted_in = $book->enlisted_in;
            $preferable_events = $book->preferable_events;
            $preferable_place = $book->preferable_place;
            $performane_duration = $book->performane_duration;
            $price = $book->price;
            $performance_fee = $book->performance_fee;
            $video = $book->video;
            $on_stage_team = $book->on_stage_team;
            $off_stage_team = $book->off_stage_team;
            $off_stage_food = $book->off_stage_food;
            $details = $book->details;
        }

        $subCategorys = SubCategory::where('category_id', $category_id)->orderBy('sub_category_name', 'asc')->get();

        $edit_form = '';
        $edit_form .= '<input type="hidden" id="booking_id" value="'.$id.'">';
        if($this->userRole == 'Admin'){
            $edit_form .= '<div class="col-md-6">
                    <div class="form-group row">
                        <label for="category" class="col-md-4 col-form-label value-md-left">'.__('Category').'</label>';

                        $edit_form .= '<div class="col-md-8">
                            <select id="category" class="form-control" name="category_id" required autofocus>';
                                if(count($categorys) == 0){
                                    $edit_form .= '<option value="">No Category</option>';
                                }else{
                                $edit_form .= '<option value="">Select any Category</option>';
                                    foreach($categorys as $key => $category){
                                        if($category_id == $category->id){
                                            $edit_form .= '<option value="'.$category->id.'" selected>'.$category->category_name.'</option>';
                                        }else{
                                           $edit_form .= '<option value="'.$category->id.'">'.$category->category_name.'</option>';
                                        }

                                    }
                                }
                            $edit_form .= '</select>
                        </div>
                    </div>
            </div>';

            $edit_form .= '<div class="col-md-6">
                <div class="form-group row">
                <label for="sub_category" class="col-md-4 col-form-label value-md-left">'.__('Sub Category').'</label>

                <div class="col-md-8">
                    <select id="sub_category" class="form-control" name="sub_category_id">
                ';
                if(count($subCategorys) == 0){
                    $edit_form .= '<option value="">No Sub Category</option>';
                }else{
                $edit_form .= '<option value="">Select Category first</option>';
                    foreach($subCategorys as $key => $subCategory){
                        if($sub_category_id == $subCategory->id){
                            $edit_form .= '<option value="'.$subCategory->id.'" selected>'.$subCategory->sub_category_name.'</option>';
                        }else{
                           $edit_form .= '<option value="'.$subCategory->id.'">'.$subCategory->sub_category_name.'</option>';
                        }

                    }
                }
            $edit_form .= '</select>
                </div>
            </div>
        </div>';
        }else{
             $edit_form .= '<input type="hidden" id="category" name="category_id" value="'.$category_id.'">';

             $edit_form .= '<div class="col-md-6">
                <div class="form-group row">
                <label for="sub_category" class="col-md-4 col-form-label value-md-left">'.__('Category').'</label>

                <div class="col-md-8">
                    <select id="sub_category" class="form-control" name="sub_category_id">
                ';
                if(count($subCategorys) == 0){
                    $edit_form .= '<option value="">No Category</option>';
                }else{
                $edit_form .= '<option value="">Select Category first</option>';
                    foreach($subCategorys as $key => $subCategory){
                        if($sub_category_id == $subCategory->id){
                            $edit_form .= '<option value="'.$subCategory->id.'" selected>'.$subCategory->sub_category_name.'</option>';
                        }else{
                           $edit_form .= '<option value="'.$subCategory->id.'">'.$subCategory->sub_category_name.'</option>';
                        }

                    }
                }
            $edit_form .= '</select>
                </div>
            </div>
        </div>';
        }




        $edit_form .= '<div class="col-md-6">
            <div class="form-group row">
                <label for="book_name" class="col-md-4 col-form-label value-md-left">'.__('Name').'</label>

                <div class="col-md-8">
                    <input id="book_name" type="text" class="form-control" name="name" value="'.$name.'" required autofocus>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <label for="book_location" class="col-md-4 col-form-label value-md-left">'.__('Location').'</label>

                <div class="col-md-8">
                    <input id="book_location" type="text" class="form-control" name="location" placeholder="Your Address" value="'.$location.'" required autofocus>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <label for="book_language" class="col-md-4 col-form-label value-md-left">'.__('Language').'</label>

                <div class="col-md-8">
                    <input id="book_language" type="text" class="form-control" name="language" placeholder="Separate by , (comma)" value="'.$language.'" required autofocus>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <label for="enlisted" class="col-md-4 col-form-label value-md-left">'.__('Enlisted In').'</label>

                <div class="col-md-8">
                    <input id="enlisted" type="text" class="form-control" name="enlisted_in" placeholder="Separate by , (comma)" value="'.$enlisted_in.'" required autofocus>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <label for="events" class="col-md-4 col-form-label value-md-left">'.__('Preferable Events').'</label>

                <div class="col-md-8">
                    <input id="events" type="text" class="form-control" name="preferable_events" placeholder="Separate by , (comma)" value="'.$preferable_events.'" required autofocus>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <label for="place" class="col-md-4 col-form-label value-md-left">'.__('Preferable Place').'</label>

                <div class="col-md-8">
                    <input id="place" type="text" class="form-control" name="preferable_place" placeholder="Separate by , (comma)" value="'.$preferable_place.'" required autofocus>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <label for="duration" class="col-md-4 col-form-label value-md-left">'.__('Performane Duration').'</label>

                <div class="col-md-8">
                    <select id="duration" class="form-control" name="performane_duration" required autofocus>
                        <option value=""></option>';
                        for($pd=1; $pd<=24; $pd++){
                            if($performane_duration == $pd){
                                $edit_form .= '<option value="'.$pd.'" selected>'.$pd.'  Hr (Approx)</option>';
                            }else{
                               $edit_form .= '<option value="'.$pd.'">'.$pd.'  Hr (Approx)</option>';
                            }
                        }
            $edit_form .= '</select>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <label for="book_price" class="col-md-4 col-form-label value-md-left">'.__('Contact Price').'</label>

                <div class="col-md-8">
                    <input id="book_price" type="text" class="form-control" name="price" value="'.$price.'" required autofocus>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <label for="fee" class="col-md-4 col-form-label value-md-left">'.__('Performance Fee').'</label>

                <div class="col-md-8">
                    <input id="fee" type="text" class="form-control" name="performance_fee" value="'.$performance_fee.'" required autofocus>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="col">
                <div id="nexpre" class="carousel slide" data-ride="carousel">
                  <div class="carousel-inner" id="booking_image_carousel'.$id.'">';
                  $i = 0;
                  foreach ($images as $image) {
                    if($i==0){
                      $edit_form .= '<div class="carousel-item active">
                          <img src="'.asset('images/'.$image->image).'" width="100%" height="100">
                          <div class="overlay">';
                            if($image->main_image != '1'){
                                $edit_form .= '<button type="button" class="btn btn-primary btn-sm" id="booking_image_activ'.$image->id.'"  value="'.$image->id.'" onclick="bookImgMain(\'booking_image_carousel'.$id.'\',\'booking_image_activ'.$image->id.'\')">Main</button>';
                                $edit_form .= '<button type="button" class="btn btn-danger btn-sm" onclick="delBookImg(\'booking_image_carousel'.$id.'\',\'booking_image_activ'.$image->id.'\')">Delete</button>';
                            }

                          $edit_form .= '</div>
                        </div>';
                    }else{
                        $edit_form .= '<div class="carousel-item">
                          <img src="'.asset('images/'.$image->image).'" width="100%" height="100">
                          <div class="overlay">';
                            if($image->main_image != '1'){
                                $edit_form .= '<button type="button" class="btn btn-primary btn-sm" id="booking_image_activ'.$image->id.'"  value="'.$image->id.'" onclick="bookImgMain(\'booking_image_carousel'.$id.'\',\'booking_image_activ'.$image->id.'\')">Main</button>';
                                $edit_form .= '<button type="button" class="btn btn-danger btn-sm" onclick="delBookImg(\'booking_image_carousel'.$id.'\',\'booking_image_activ'.$image->id.'\')">Delete</button>';
                            }

                          $edit_form .= '</div>
                        </div>';
                    }
                    $i++;
                  }
                  $edit_form .= '</div>

                  <a class="carousel-control-prev" href="#nexpre" data-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                  </a>
                  <a class="carousel-control-next" href="#nexpre" data-slide="next">
                    <span class="carousel-control-next-icon"></span>
                  </a>
                </div>
                <input id="image" type="file" class="form-control" multiple required autofocus>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <label for="book_video" class="col-md-4 col-form-label value-md-left">'.__('Youtube link').'</label>

                <div class="col-md-8">
                    <input id="book_video" type="text" class="form-control" name="video" placeholder="Separate by , (comma)" value="'.$video.'">
                </div>
            </div>
        </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="on_stag_team" class="col-md-4 col-form-label value-md-left">'.__('On Stage Team').'</label>

                        <div class="col-md-8">
                            <select id="on_stag_team" class="form-control" name="on_stage_team">
                                <option value=""></option>';
                                for($i=1; $i<=10; $i++){
                                    if($on_stage_team == $i){
                                        if($i==1){
                                            $edit_form .= '<option value="'.$i.'" selected>'.$i.' Person</option>';
                                        }else{
                                            $edit_form .= '<option value="'.$i.'" selected>'.$i.' Persons</option>';
                                        }
                                    }else{
                                       if($i==1){
                                            $edit_form .= '<option value="'.$i.'">'.$i.' Person</option>';
                                        }else{
                                            $edit_form .= '<option value="'.$i.'">'.$i.' Persons</option>';
                                        }
                                    }
                                }
                    $edit_form .= '</select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="off_stag_team" class="col-md-4 col-form-label value-md-left">'.__('Off Stage Team').'</label>

                        <div class="col-md-8">
                            <select id="off_stag_team" class="form-control" name="off_stage_team">
                                <option value=""></option>';
                                for($i=1; $i<=10; $i++){
                                    if($off_stage_team == $i){
                                        if($i==1){
                                            $edit_form .= '<option value="'.$i.'" selected>'.$i.' Person</option>';
                                        }else{
                                            $edit_form .= '<option value="'.$i.'" selected>'.$i.' Persons</option>';
                                        }
                                    }else{
                                       if($i==1){
                                            $edit_form .= '<option value="'.$i.'">'.$i.' Person</option>';
                                        }else{
                                            $edit_form .= '<option value="'.$i.'">'.$i.' Persons</option>';
                                        }
                                    }
                                }
                    $edit_form .= '</select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="off_stag_food" class="col-md-4 col-form-label value-md-left">'.__('Off Stage Food').'</label>

                        <div class="col-md-8">
                            <select id="off_stag_food" name="off_stage_food" class="form-control">
                                <option value="0">No</option>';
                                if($off_stage_food == 1){
                                    $edit_form .= '<option value="1" selected>Yes</option>';
                                }else{
                                    $edit_form .= '<option value="1">Yes</option>';
                                }

                            $edit_form .= '</select>
                        </div>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="book_details" class="col-md-4 col-form-label value-md-left">'.__('About This Person').'</label>

                        <div class="col-md-12">
                            <textarea id="book_details" class="form-control" rows="5" name="details">'.$details.'</textarea>
                        </div>
                    </div>
                </div>';
        return Response::json(['editForm'=>$edit_form]);
    }
    public function imgMain(Request $request)
    {
        $image = Image::where('id', $request->id)->get();
        foreach ($image as $imag) {
            $booking_id = $imag->booking_id;
        }
        Image::where('booking_id', $booking_id)->update(['main_image' => '0']);
        Image::where('id', $request->id)->update(['main_image' => '1']);
        $images = Image::where('booking_id', $booking_id)->get();
        $i = 0;
        $all_img = '';
        foreach ($images as $img) {
            if($i==0){
              $all_img .= '<div class="carousel-item active">
                  <img src="'.asset('images/'.$img->image).'" width="100%" height="100">
                  <div class="overlay">';
                    if($img->main_image != '1'){
                        $all_img .= '<button type="button" class="btn btn-primary btn-sm" id="booking_image_activ'.$img->id.'"  value="'.$img->id.'" onclick="bookImgMain(\'booking_image_carousel'.$booking_id.'\',this.id)">Main</button>';
                        $all_img .= '<button type="button" class="btn btn-danger btn-sm" onclick="delBookImg(\'booking_image_carousel'.$booking_id.'\',\'booking_image_activ'.$img->id.'\')">Delete</button>';
                    }

                  $all_img .= '</div>
                </div>';
            }else{
                $all_img .= '<div class="carousel-item">
                  <img src="'.asset('images/'.$img->image).'" width="100%" height="100">
                  <div class="overlay">';
                    if($img->main_image != '1'){
                        $all_img .= '<button type="button" class="btn btn-primary btn-sm" id="booking_image_activ'.$img->id.'"  value="'.$img->id.'" onclick="bookImgMain(\'booking_image_carousel'.$booking_id.'\',this.id)">Main</button>';
                        $all_img .= '<button type="button" class="btn btn-danger btn-sm" onclick="delBookImg(\'booking_image_carousel'.$booking_id.'\',\'booking_image_activ'.$img->id.'\')">Delete</button>';
                    }

                  $all_img .= '</div>
                </div>';
            }
            $i++;
        }
      return Response::json(['bookIng'=>$all_img]);
    }

    public function imgDelete(Request $request)
    {
        $image = Image::where('id', $request->id)->get();
        foreach ($image as $imag) {
            $booking_id = $imag->booking_id;
            @unlink(public_path().'/images/'.$imag->image);
        }

        Image::where('id', $request->id)->delete();
        $images = Image::where('booking_id', $booking_id)->get();
        $i = 0;
        $all_img = '';
        foreach ($images as $img) {
            if($i==0){
              $all_img .= '<div class="carousel-item active">
                  <img src="'.asset('images/'.$img->image).'" width="100%" height="100">
                  <div class="overlay">';
                    if($img->main_image != '1'){
                        $all_img .= '<button type="button" class="btn btn-primary btn-sm" id="booking_image_activ'.$img->id.'"  value="'.$img->id.'" onclick="bookImgMain(\'booking_image_carousel'.$booking_id.'\',this.id)">Main</button>';
                        $all_img .= '<button type="button" class="btn btn-danger btn-sm" onclick="delBookImg(\'booking_image_carousel'.$booking_id.'\',\'booking_image_activ'.$img->id.'\')">Delete</button>';
                    }

                  $all_img .= '</div>
                </div>';
            }else{
                $all_img .= '<div class="carousel-item">
                  <img src="'.asset('images/'.$img->image).'" width="100%" height="100">
                  <div class="overlay">';
                    if($img->main_image != '1'){
                        $all_img .= '<button type="button" class="btn btn-primary btn-sm" id="booking_image_activ'.$img->id.'"  value="'.$img->id.'" onclick="bookImgMain(\'booking_image_carousel'.$booking_id.'\',this.id)">Main</button>';
                        $all_img .= '<button type="button" class="btn btn-danger btn-sm" onclick="delBookImg(\'booking_image_carousel'.$booking_id.'\',\'booking_image_activ'.$img->id.'\')">Delete</button>';
                    }

                  $all_img .= '</div>
                </div>';
            }
            $i++;
        }
      return Response::json(['bookIng'=>$all_img]);
    }

    public function status(Request $request)
    {
        $booking = Booking::where('id', $request->id)->get();
        foreach ($booking as $book) {
            $booking_name = $book->name;
        }
        if($request->status=='1'){
            Booking::where('id', $request->id)->update(['status' => '0']);
        }else{
            Booking::where('id', $request->id)->update(['status' => '1']);
        }

        $allBookings = Booking::join('categories', 'bookings.category_id', '=', 'categories.id')->leftJoin('sub_categories', 'bookings.sub_category_id', '=', 'sub_categories.id')->select('bookings.id', 'bookings.code', 'categories.category_name', 'sub_categories.sub_category_name', 'bookings.name', 'bookings.location', 'bookings.language', 'bookings.enlisted_in', 'bookings.preferable_events', 'bookings.preferable_place', 'bookings.performane_duration', 'bookings.price', 'bookings.performance_fee', 'bookings.video', 'bookings.on_stage_team', 'bookings.off_stage_team', 'bookings.off_stage_food', 'bookings.details', 'bookings.status', 'bookings.updated_at')->orderBy('bookings.id', 'desc')->get();
            $book = '';
            foreach ($allBookings as $booking){
                $book .= '<tr>
                    <td>'.$booking->code.'</td>
                    <td>'.$booking->name.'</td>
                    <td>'.$booking->category_name.'</td>
                    <td>'.$booking->sub_category_name.'</td>
                    <td>'.$booking->location.'</td>
                    <td>'.$booking->language.'</td>
                    <td>'.$booking->enlisted_in.'</td>
                    <td>'.$booking->preferable_events.'</td>
                    <td>'.$booking->preferable_place.'</td>
                    <td>'.$booking->performane_duration.'</td>
                    <td>'.$booking->price.'</td>
                    <td>'.$booking->performance_fee.'</td>
                    <td>
                    <button type="button" value="'.$booking->id.'/'.$booking->status.'" class="btn btn-primary btn-sm status_booking">';
                    if($booking->status==1){
                        $book .= 'Show';
                    }else{
                        $book .= 'Hide';
                    }
                    $book .= '</button></td>
                    <td><button type="button" value="'.$booking->id.'" class="btn btn-link edit_booking"><i class="fa fa-pencil"></i></button></td>
                    <td><button type="button" value="'.$booking->id.'" class="btn btn-link delete_booking"><i class="fa fa-trash-o"></i></button></td>
                </tr>';
            }

        return Response::json(['bookings'=>$book, 'msg'=>'"'.$booking_name.'\'s Status change Successfully']);
    }

    public function update(Request $request)
    {
        $rules = array(
            'category_id' => 'required|numeric',
            'sub_category_id' => 'nullable|numeric',
            'name' => 'required|string',
            'location' => 'required|string',
            'language' => 'required|string',
            'enlisted_in' => 'required|string',
            'preferable_events' => 'required|string',
            'preferable_place' => 'required|string',
            'performane_duration' => 'required|numeric',
            'price' => 'required|numeric',
            'performance_fee' => 'required|numeric',
            'photo' => 'nullable',
            'photo.*' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video' => 'nullable|string',
            'on_stage_team' => 'nullable|numeric',
            'off_stage_team' => 'nullable|numeric',
            'off_stage_food' => 'nullable|numeric',
            'details' => 'nullable|string'
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return Response::json(array('errors'=>$validator->getMessageBag()));
        }else{
            $i = Image::where('booking_id', $request->id)->count()+1;
            $booking = Booking::where('id', $request->id)->update([
                'category_id' => $request->input('category_id'),
                'sub_category_id' => $request->input('sub_category_id'),
                'name' => $request->input('name'),
                'location' => $request->input('location'),
                'language' => $request->input('language'),
                'enlisted_in' => $request->input('enlisted_in'),
                'preferable_events' => $request->input('preferable_events'),
                'preferable_place' => $request->input('preferable_place'),
                'performane_duration' => $request->input('performane_duration'),
                'price' => $request->input('price'),
                'performance_fee' => $request->input('performance_fee'),
                'video' => $request->input('video'),
                'on_stage_team' => $request->input('on_stage_team'),
                'off_stage_team' => $request->input('off_stage_team'),
                'off_stage_food' => $request->input('off_stage_food'),
                'preferable_events' => $request->input('preferable_events'),
                'details' => $request->input('details')
            ]);
            if($request->hasFile('photo')){
                foreach($request->file('photo') as $file){
                    $path = public_path('images/');
                    $file_name = $request->id.'_'.time().'_'.$i.'.'.$file->getClientOriginalExtension();
                    $file->move($path, $file_name);
                    $active = '0';
                    if($i == 1){
                        $active = '1';
                    }
                    Image::create([
                        'product_id' => null,
                        'package_id' => null,
                        'booking_id' => $request->id,
                        'main_image' => $active,
                        'image' => $file_name,
                    ]);
                    $i++;
                }
            }

            //dd($this->userId);
            //dd($this->userRole);

            $book = '';
            foreach ($this->allBookings as $booking){
                if($this->userRole == 'Admin'){
                    $book .= '<tr>
                            <td>'.$booking->code.'</td>
                            <td>'.$booking->name.'</td>
                            <td>'.$booking->category_name.'</td>
                            <td>'.$booking->sub_category_name.'</td>
                            <td>'.$booking->location.'</td>
                            <td>'.$booking->language.'</td>
                            <td>'.$booking->enlisted_in.'</td>
                            <td>'.$booking->preferable_events.'</td>
                            <td>'.$booking->preferable_place.'</td>
                            <td>'.$booking->performane_duration.'</td>
                            <td>'.$booking->price.'</td>
                            <td>'.$booking->performance_fee.'</td>';
                            $book .= '<td>';
                            $book .= '<button type="button" value="'.$booking->id.'/'.$booking->status.'" class="btn btn-primary btn-sm status_booking">';
                            if($booking->status==1){
                                $book .= 'Show';
                            }else{
                                $book .= 'Hide';
                            }
                            $book .= '</button>';
                            $book .= '</td>';
                            $book .= '<td><button type="button" value="'.$booking->id.'" class="btn btn-link edit_booking"><i class="fa fa-pencil"></i></button></td>
                            <td><button type="button" value="'.$booking->id.'" class="btn btn-link delete_booking"><i class="fa fa-trash-o"></i></button></td>
                        </tr>';
                }else{
                    if($booking->user_id==$this->userId){
                        $book .= '<tr>
                            <td>'.$booking->code.'</td>
                            <td>'.$booking->name.'</td>
                            <td>'.$booking->category_name.'</td>
                            <td>'.$booking->sub_category_name.'</td>
                            <td>'.$booking->location.'</td>
                            <td>'.$booking->language.'</td>
                            <td>'.$booking->enlisted_in.'</td>
                            <td>'.$booking->preferable_events.'</td>
                            <td>'.$booking->preferable_place.'</td>
                            <td>'.$booking->performane_duration.'</td>
                            <td>'.$booking->price.'</td>
                            <td>'.$booking->performance_fee.'</td>';
                            $book .= '<td>';
                            if($booking->status==1){
                                $book .= 'Active';
                            }else{
                                $book .= 'Wait For Review';
                            }
                            $book .= '</td>';
                            $book .= '<td><button type="button" value="'.$booking->id.'" class="btn btn-link edit_booking"><i class="fa fa-pencil"></i></button></td>
                            <td><button type="button" value="'.$booking->id.'" class="btn btn-link delete_booking"><i class="fa fa-trash-o"></i></button></td>
                        </tr>';
                    }
                }
            }

            return Response::json(['bookings' => $book, 'msg' => 'Booking Update Successfully']);
        }
    }

    public function destroy(Request $request)
    {
        $booking = Booking::where('id', $request->id)->get();
        foreach ($booking as $book) {
            $booking_name = $book->name;
            $booking_id = $book->id;
        }
        $image = Image::where('booking_id', $request->id)->get();
        foreach ($image as $img) {
            @unlink(public_path().'/images/'.$img->image);
            Image::where('id', $img->id)->delete();
        }
        Booking::where('id', $request->id)->delete();
        //dd($this->userId);
        //dd($this->userRole);

        $book = '';
        foreach ($this->allBookings as $booking){
            if($this->userRole == 'Admin'){
                $book .= '<tr>
                        <td>'.$booking->code.'</td>
                        <td>'.$booking->name.'</td>
                        <td>'.$booking->category_name.'</td>
                        <td>'.$booking->sub_category_name.'</td>
                        <td>'.$booking->location.'</td>
                        <td>'.$booking->language.'</td>
                        <td>'.$booking->enlisted_in.'</td>
                        <td>'.$booking->preferable_events.'</td>
                        <td>'.$booking->preferable_place.'</td>
                        <td>'.$booking->performane_duration.'</td>
                        <td>'.$booking->price.'</td>
                        <td>'.$booking->performance_fee.'</td>';
                        $book .= '<td>';
                        $book .= '<button type="button" value="'.$booking->id.'/'.$booking->status.'" class="btn btn-primary btn-sm status_booking">';
                        if($booking->status==1){
                            $book .= 'Show';
                        }else{
                            $book .= 'Hide';
                        }
                        $book .= '</button>';
                        $book .= '</td>';
                        $book .= '<td><button type="button" value="'.$booking->id.'" class="btn btn-link edit_booking"><i class="fa fa-pencil"></i></button></td>
                        <td><button type="button" value="'.$booking->id.'" class="btn btn-link delete_booking"><i class="fa fa-trash-o"></i></button></td>
                    </tr>';
            }else{
                if($booking->user_id==$this->userId){
                    $book .= '<tr>
                        <td>'.$booking->code.'</td>
                        <td>'.$booking->name.'</td>
                        <td>'.$booking->category_name.'</td>
                        <td>'.$booking->sub_category_name.'</td>
                        <td>'.$booking->location.'</td>
                        <td>'.$booking->language.'</td>
                        <td>'.$booking->enlisted_in.'</td>
                        <td>'.$booking->preferable_events.'</td>
                        <td>'.$booking->preferable_place.'</td>
                        <td>'.$booking->performane_duration.'</td>
                        <td>'.$booking->price.'</td>
                        <td>'.$booking->performance_fee.'</td>';
                        $book .= '<td>';
                        if($booking->status==1){
                            $book .= 'Active';
                        }else{
                            $book .= 'Wait For Review';
                        }
                        $book .= '</td>';
                        $book .= '<td><button type="button" value="'.$booking->id.'" class="btn btn-link edit_booking"><i class="fa fa-pencil"></i></button></td>
                        <td><button type="button" value="'.$booking->id.'" class="btn btn-link delete_booking"><i class="fa fa-trash-o"></i></button></td>
                    </tr>';
                }
            }
        }

        return Response::json(['bookings'=>$book, 'msg'=>$booking_name.' Delete Successfully']);
    }
}
