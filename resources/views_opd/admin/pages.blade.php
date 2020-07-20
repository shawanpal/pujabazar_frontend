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
                  <form action="{{ action('PagesController@update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                        <div class="col">{{ __('Pages') }}</div>
                        <div class="col">
                          <select id="page_nam" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}">
                            <option value="">Select Page</option>
                            <option value="about" @if (old('name') == 'about') selected @endif>About Us</option>
                            <option value="return" @if (old('name') == 'return') selected @endif>Warranties & Return</option>
                            <option value="privacy" @if (old('name') == 'privacy') selected @endif>Privacy Policy</option>
                            <option value="terms" @if (old('name') == 'terms') selected @endif>Terms and Conditions</option>

                          </select>
                          @if ($errors->has('name'))
                              <span class="invalid-feedback">
                                  <strong>{{ $errors->first('name') }}</strong>
                              </span>
                          @endif
                        </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <textarea name="content" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" rows="10">{{ old('content') }}</textarea>
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
                        <button type="submit" id="pag_btn" class="btn btn-info mt-3 float-right">Save</button>
                    </div>
                  </form>
                </div>
        </div>
    </div>

  </div>
</div>
@endsection
