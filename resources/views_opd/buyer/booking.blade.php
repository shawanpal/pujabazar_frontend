@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-2">
      @include('layouts.sidebarBuyer')
    </div>

    <div class="col-sm-10">
        <div class="justify-content-center">
          
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                        <div class="col">{{ __('All Bookings') }}</div>
                        <div class="col"></div>
                        <div class="col">
                            <button type="button" class="btn btn-outline-primary float-right" data-toggle="modal" data-target="#bookingAdd">{{ __('Add Booking') }}</button>
                        </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($bookings != '')
                            <table class="table table-bordered table-sm">
                            <thead>
                              <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Location</th>
                                <th>Language</th>
                                <th>Enlisted</th>
                                <th>Events</th>
                                <th>Place</th>
                                <th>Duration</th>
                                <th>Price</th>
                                <th>Fee</th>
                                <th>Status</th>
                                <th>Edit</th>
                                <th>Delete</th>
                              </tr>
                            </thead>
                            <tbody id="all_booking">
                                {!! $bookings !!}
                            </tbody>
                        </table>        
                    @else
                        <div class="alert alert-info alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert">&times;</button>
                          Booking is not available yet.
                        </div>
                    @endif 
                        
                    </div>
                </div>
        </div>
    </div>

  </div>
</div>
{{--Set Booking Form--}}
<div class="modal fade" id="bookingAdd">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Set Your Booking</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <form method="POST" action="" enctype='multipart/form-data'>
        @csrf
      <div class="modal-body">
        <div class="row">
            <div class="row row-margin row-padding">
                @if($role == 'Admin')    
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="category_id" class="col-md-4 col-form-label value-md-left">{{ __('Category') }}</label>
                        <div class="col-md-8">
                            <select id="category_id" class="form-control{{ $errors->has('category_id') ? ' is-invalid' : '' }}" name="category_id" required autofocus>
                                @if(count($categorys) == 0)
                                    <option value="">No Category</option>
                                @else
                                <option value="">Select any Category</option>
                                    @foreach($categorys as $key => $category)
                                        <option value="{{ $category->id }}" @if (old('category_id') == $category->id) selected @endif>{{ $category->category_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($errors->has('category_id'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('category_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="sub_category_id" class="col-md-4 col-form-label value-md-left">{{ __('Sub Category') }}</label>

                        <div class="col-md-8">
                            <select id="sub_category_id" class="form-control{{ $errors->has('sub_category_id') ? ' is-invalid' : '' }}" name="sub_category_id">
                                <option value="">Select Category first</option>
                                
                            </select>
                            @if ($errors->has('sub_category_id'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('sub_category_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @else
                    <input type="hidden" id="category_id" name="category_id" value="5">

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="sub_category_id" class="col-md-4 col-form-label value-md-left">{{ __('Category') }}</label>

                            <div class="col-md-8">
                                <select id="sub_category_id" class="form-control{{ $errors->has('sub_category_id') ? ' is-invalid' : '' }}" name="sub_category_id">
                                 @if(count($subCategorys) == 0)
                                    <option value="">No Category</option>
                                @else
                                <option value="">Select any Category</option>
                                    @foreach($subCategorys as $key => $sub)
                                        <option value="{{ $sub->id }}" @if (old('sub_category_id') == $sub->id) selected @endif>{{ $sub->sub_category_name }}</option>
                                    @endforeach
                                @endif
                                    
                                    
                                </select>
                                @if ($errors->has('sub_category_id'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('sub_category_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                
                   
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label value-md-left">{{ __('Name') }}</label>

                        <div class="col-md-8">
                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                            @if ($errors->has('name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="location" class="col-md-4 col-form-label value-md-left">{{ __('Location') }}</label>

                        <div class="col-md-8">
                            <input id="location" type="text" class="form-control{{ $errors->has('location') ? ' is-invalid' : '' }}" name="location" placeholder="Your Address" value="{{ old('location') }}" required autofocus>

                            @if ($errors->has('location'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('location') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="language" class="col-md-4 col-form-label value-md-left">{{ __('Language') }}</label>

                        <div class="col-md-8">
                            <input id="language" type="text" class="form-control{{ $errors->has('language') ? ' is-invalid' : '' }}" name="language" placeholder="Separate by , (comma)" value="{{ old('language') }}" required autofocus>

                            @if ($errors->has('language'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('language') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="enlisted_in" class="col-md-4 col-form-label value-md-left">{{ __('Enlisted In') }}</label>

                        <div class="col-md-8">
                            <input id="enlisted_in" type="text" class="form-control{{ $errors->has('enlisted_in') ? ' is-invalid' : '' }}" name="enlisted_in" placeholder="Separate by , (comma)" value="{{ old('enlisted_in') }}" required autofocus>

                            @if ($errors->has('enlisted_in'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('enlisted_in') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="preferable_events" class="col-md-4 col-form-label value-md-left">{{ __('Preferable Events') }}</label>

                        <div class="col-md-8">
                            <input id="preferable_events" type="text" class="form-control{{ $errors->has('preferable_events') ? ' is-invalid' : '' }}" name="preferable_events" placeholder="Separate by , (comma)" value="{{ old('preferable_events') }}" required autofocus>

                            @if ($errors->has('preferable_events'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('preferable_events') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="preferable_place" class="col-md-4 col-form-label value-md-left">{{ __('Preferable Place') }}</label>

                        <div class="col-md-8">
                            <input id="preferable_place" type="text" class="form-control{{ $errors->has('preferable_place') ? ' is-invalid' : '' }}" name="preferable_place" placeholder="Separate by , (comma)" value="{{ old('preferable_place') }}" required autofocus>

                            @if ($errors->has('preferable_place'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('preferable_place') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="performane_duration" class="col-md-4 col-form-label value-md-left">{{ __('Performane Duration') }}</label>

                        <div class="col-md-8">
                            <select id="performane_duration" class="form-control{{ $errors->has('performane_duration') ? ' is-invalid' : '' }}" name="performane_duration" required autofocus>
                                <option value=""></option>
                                @for($i=1; $i<=24; $i++)
                                <option value="{{$i}}" @if (old('performane_duration') == $i) selected @endif>{{ $i }} Hr (Approx)</option>
                                @endfor
                            </select>

                            @if ($errors->has('performane_duration'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('performane_duration') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="price" class="col-md-4 col-form-label value-md-left">{{ __('Contact Price') }}</label>

                        <div class="col-md-8">
                            <input id="price" type="text" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" name="price" value="{{ old('price') }}" required autofocus>

                            @if ($errors->has('price'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('price') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="performance_fee" class="col-md-4 col-form-label value-md-left">{{ __('Performance Fee') }}</label>

                        <div class="col-md-8">
                            <input id="performance_fee" type="text" class="form-control{{ $errors->has('performance_fee') ? ' is-invalid' : '' }}" name="performance_fee" value="{{ old('performance_fee') }}" required autofocus>

                            @if ($errors->has('performance_fee'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('performance_fee') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="photo" class="col-md-4 col-form-label value-md-left">{{ __('Photo') }}</label>

                        <div class="col-md-8">
                            <input id="photo" type="file" class="form-control{{ $errors->has('photo') ? ' is-invalid' : '' }}" name="photo[]" value="{{ old('photo') }}" multiple required autofocus>

                            @if ($errors->has('photo'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('photo') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="video" class="col-md-4 col-form-label value-md-left">{{ __('Youtube link') }}</label>

                        <div class="col-md-8">
                            <input id="video" type="text" class="form-control{{ $errors->has('video') ? ' is-invalid' : '' }}" name="video" placeholder="Separate by , (comma)" value="{{ old('video') }}">

                            @if ($errors->has('video'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('video') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="on_stage_team" class="col-md-4 col-form-label value-md-left">{{ __('On Stage Team') }}</label>

                        <div class="col-md-8">
                            <select id="on_stage_team" class="form-control{{ $errors->has('on_stage_team') ? ' is-invalid' : '' }}" name="on_stage_team">
                                <option value=""></option>
                                @for($i=1; $i<=10; $i++)
                                <option value="{{$i}}" @if (old('on_stage_team') == $i) selected @endif>
                                    {{ $i }} @if($i==1)
                                     Person
                                    @else
                                     Persons
                                    @endif
                                </option>
                                @endfor
                            </select>

                            @if ($errors->has('on_stage_team'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('on_stage_team') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="off_stage_team" class="col-md-4 col-form-label value-md-left">{{ __('Off Stage Team') }}</label>

                        <div class="col-md-8">
                            <select id="off_stage_team" class="form-control{{ $errors->has('off_stage_team') ? ' is-invalid' : '' }}" name="off_stage_team">
                                <option value=""></option>
                                @for($i=1; $i<=10; $i++)
                                <option value="{{$i}}" @if (old('off_stage_team') == $i) selected @endif>
                                    {{ $i }} @if($i==1)
                                     Person
                                    @else
                                     Persons
                                    @endif
                                </option>
                                @endfor
                            </select>

                            @if ($errors->has('off_stage_team'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('off_stage_team') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="off_stage_food" class="col-md-4 col-form-label value-md-left">{{ __('Off Stage Food') }}</label>

                        <div class="col-md-8">
                            <select id="off_stage_food" name="off_stage_food" class="form-control{{ $errors->has('off_stage_food') ? ' is-invalid' : '' }}">
                                <option value="0">No</option>
                                <option value="1" @if (old('off_stage_food') == '1') selected @endif>Yes</option>
                                
                            </select>

                            @if ($errors->has('off_stage_food'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('off_stage_food') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                
                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="details" class="col-md-4 col-form-label value-md-left">{{ __('About This Person') }}</label>

                        <div class="col-md-12">
                            <textarea id="details" class="form-control{{ $errors->has('details') ? ' is-invalid' : '' }}" rows="5" name="details">{{ old('details') }}</textarea>

                            @if ($errors->has('details'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('details') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12" id="booking_msg"></div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" id="add_booking" class="btn btn-primary">{{ __('Add New Booking') }}</button>
      </div>
      </form>
    </div>
  </div>
</div>   
{{--Set Booking Form--}}
{{--Edit Booking Form--}}
<div class="modal fade" id="bookingEdit">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Your Booking</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <form method="POST" action="{{ route('booking') }}" enctype='multipart/form-data'>
        @csrf
      <div class="modal-body">
        <div class="row">
            <div class="row row-margin row-padding" id="edit_booking">
                    
                
            </div>
            
            <div class="col-md-12" id="booking_edit_msg"></div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" id="save_booking" class="btn btn-primary">{{ __('Save Booking') }}</button>
      </div>
      </form>
    </div>
  </div>
</div>   
{{--Edit Booking Form--}}
@endsection
