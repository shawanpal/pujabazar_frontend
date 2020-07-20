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
                  <form action="{{ action('BlogController@update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                        <div class="col">{{ __('Blogs') }}</div>
                        <div class="col"></div>
                        </div>
                    </div>

                    <div class="card-body">
                      <div class="row mb-3">
                        <div class="col-sm-4">
                            <select id="blog_id" name="id" class="form-control{{ $errors->has('id') ? ' is-invalid' : '' }}">
                              <option value="">Select Blog</option>
                              @if(count($blogs)>0)
                                @foreach($blogs as $blog)
                                  <option value="{{ $blog->id }}" @if (old('id') == $blog->id) selected @endif>{{ $blog->heading }}</option>
                                @endforeach
                              @endif
                            </select>
                            @if ($errors->has('id'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-sm-4" id="btn-blog">
                          
                        </div>
                        <div class="col-sm-4" id="show-img"></div>
                      </div>
                      
                      <div class="row mb-3">
                        <div class="col-sm-4">
                          <input type="text" class="form-control{{ $errors->has('heading') ? ' is-invalid' : '' }}" id="heading" name="heading" placeholder="Heading" value="{{ old('heading') }}">
                            @if ($errors->has('heading'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('heading') }}</strong>
                                </span>
                            @endif 
                        </div>
                        <div class="col-sm-4">
                          <input type="date" class="form-control{{ $errors->has('published') ? ' is-invalid' : '' }}" id="published" name="published" value="{{ old('published') }}">
                          @if ($errors->has('published'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('published') }}</strong>
                                </span>
                            @endif 
                        </div>
                        <div class="col-sm-4">
                          <input type="file" class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}" name="image" value="{{ old('image') }}">
                          @if ($errors->has('image'))
                              <span class="invalid-feedback">
                                  <strong>{{ $errors->first('image') }}</strong>
                              </span>
                          @endif
                        </div>
                      </div>


                        <textarea name="content" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" rows="10">{{ old('content') }}</textarea>
                        <span id="alert">
                        @if ($errors->has('content'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('content') }}</strong>
                            </span>
                        @endif
                        @if($message = Session::get('error'))
                            <div class="alert alert-warning alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <strong>Error Alert!</strong> {{ $message }}
                            </div>
                            {!! Session::forget('error') !!}
                        @elseif($message = Session::get('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <strong>Success Alert!</strong> {{ $message }}
                            </div>
                            {!! Session::forget('success') !!}
                        @endif
                      </span>
                        <button type="submit" class="btn btn-info mt-3 float-right">Save</button>
                    </div>
                  </form>
                </div>
        </div>
    </div>

  </div>
</div>
@endsection
