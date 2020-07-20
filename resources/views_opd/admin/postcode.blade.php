@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-2 barCol">
      @include('layouts.sidebarAdmin')
    </div>
    <div class="col-sm-10">
        <div class="justify-content-center">
          
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                        <div class="col">{{ __('All Postcodes') }}</div>
                        <div class="col"></div>
                        <div class="col">
                            <button type="button" class="btn btn-outline-primary float-right" data-toggle="modal" data-target="#postcodeAdd">{{ __('Add Postcodes') }}</button>
                        </div>
                        </div>
                    </div>

                    <div class="card-body">
                    @if($postcodes!='')
                        <table class="table table-bordered table-sm">
                            <thead>
                              <tr>
                                <th>Pincode</th>
                                <th>Village/Locality name</th>
                                <th>Post Office ( BO/SO/HO)</th>
                                <th>Sub Distname</th>
                                <th>Distname</th>
                                <th>State</th>
                                <th>Status</th>
                                <th>Edit</th>
                                <th>Delete</th>
                              </tr>
                            </thead>
                            <tbody id="all_pincode">
                                {!! $postcodes !!}
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert">&times;</button>
                          Pin Code is not available yet.
                        </div>
                    @endif    
                    </div>
                </div>
        </div>
    </div>
  </div>
</div>

{{--Set Product Form--}}
<div class="modal fade" id="postcodeAdd">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Pin Code</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <form method="POST" action="{{ action('PostcodeController@store') }}" enctype='multipart/form-data'>
        @csrf
      <div class="modal-body">
        <div class="row">
            <div class="row row-margin row-padding">
                   
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="locality" class="col-md-4 col-form-label value-md-left">{{ __('Village/Locality name') }}</label>

                        <div class="col-md-8">
                            <input id="locality" type="text" class="form-control" name="locality" value="{{ old('locality') }}">

                            @if ($errors->has('locality'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('locality') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="postOffice" class="col-md-4 col-form-label value-md-left">{{ __('Post Office') }}</label>

                        <div class="col-md-8">
                            <input id="postOffice" type="text" class="form-control" name="postOffice" value="{{ old('postOffice') }}">

                            @if ($errors->has('postOffice'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('postOffice') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="pincode" class="col-md-4 col-form-label value-md-left">{{ __('Pincode') }}</label>

                        <div class="col-md-8">
                            <input id="pincode" type="text" class="form-control" name="pincode" value="{{ old('pincode') }}" required autofocus>
                            
                            @if ($errors->has('pincode'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('pincode') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="subDistrict" class="col-md-4 col-form-label value-md-left">{{ __('Sub District') }}</label>

                        <div class="col-md-8">
                            <input id="subDistrict" type="text" class="form-control" name="subDistrict" value="{{ old('subDistrict') }}">
                            @if ($errors->has('subDistrict'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('subDistrict') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="district" class="col-md-4 col-form-label value-md-left">{{ __('District') }}</label>

                        <div class="col-md-8">
                            <input id="district" type="text" class="form-control{{ $errors->has('district') ? ' is-invalid' : '' }}" name="district" value="{{ old('district') }}" required autofocus>

                            @if ($errors->has('district'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('district') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="state" class="col-md-4 col-form-label value-md-left">{{ __('State') }}</label>

                        <div class="col-md-8">
                            <select id="state" class="form-control" name="state" required autofocus>
                                <option value="">Select State</option>
                                @if(count($states) != 0)
                                    @foreach($states as $key => $state)
                                        <option value="{{ strtoupper($state->name) }}">{{ strtoupper($state->name) }}</option>
                                    @endforeach
                                @endif
                            </select>

                            @if ($errors->has('state'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('state') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6"></div>

                <div class="col-md-6 row">
                    <label for="status" class="col col-form-label value-md-left">{{ __('Status (Service available or not)') }}</label>
                    
                    <input id="status" type="checkbox" class="col form-control" name="status" checked value="1">
                    @if ($errors->has('status'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('status') }}</strong>
                        </span>
                    @endif
                </div>

            </div>
            
            <div class="col-md-12" id="pincode_msg"></div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="submit" id="add_pincode" class="btn btn-primary">{{ __('Add New Pincode') }}</button>
      </div>
      </form>
    </div>
  </div>
</div>
{{--Set Product Form--}}
{{--Edit Product Form--}}
<div class="modal fade" id="postcodeEdit">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Pincode</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <form method="POST" action="" enctype='multipart/form-data'>
        @csrf
      <div class="modal-body">
        <div class="row">
            <div class="row row-margin row-padding" id="edit_pincode">
                    
                
            </div>
            
            <div class="col-md-12" id="pincode_edit_msg"></div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" id="save_pincode" class="btn btn-primary">{{ __('Save Pincode') }}</button>
      </div>
      </form>
    </div>
  </div>
</div>
{{--Edit Product Form--}}

@endsection
