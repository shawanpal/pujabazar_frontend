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
                        <div class="col">{{ __('All Banner Images') }}</div>
                        <div class="col"></div>
                        <div class="col">
                            <button type="button" class="btn btn-outline-primary float-right" data-toggle="modal" data-target="#bannerAdd">{{ __('Add Banner Image') }}</button>
                        </div>
                        </div>
                    </div>

                    <div class="card-body" id="all_banners">
                        {!! $banners !!}
                    </div>
                </div>
        </div>
    </div>
  </div>
</div>
{{--Set image Form--}}
<div class="modal fade" id="bannerAdd">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Set Banner Image</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <form method="POST" action="" enctype='multipart/form-data' id="uploadformbanner">
        @csrf
      <div class="modal-body">
        <div class="row">
            <div class="row row-margin row-padding">
                <div class="col">
                    <input id="banner_img" name="photo[]" type="file" class="form-control" multiple required autofocus>
                </div>
                <div class="col">
                  <select id="category_id" name="category_id" class="form-control">
                      @if(count($categorys) == 0)
                          <option value="">No Category</option>
                      @else
                      <option value="">Select any Category</option>
                          @foreach($categorys as $key => $category)
                              <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                          @endforeach
                      @endif
                  </select>
                </div>
                <div class="col">
                  <select id="sub_category_id" name="sub_category_id" class="form-control{{ $errors->has('sub_category_id') ? ' is-invalid' : '' }}" name="sub_category_id">
                      <option value="">Select Category first</option>

                  </select>
                </div>
            </div>
            <div class="col-md-12" id="msg_banner"></div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">{{ __('Add New Image') }}</button>
      </div>
      </form>
    </div>
  </div>
</div>
{{--Set image Form--}}

@endsection
