@extends('layouts.main')
@section('contents')       
<div class="my_card">
    <div class="container">
        <div class="well">
            <div class="row">
                <div class="col-sm-4"> 
                    <address> 
                        <h4>Corporate Information</h4>
                        <h5><strong>PUJA BAZAR</b></strong></h5>
                        @foreach($states as $state)
                            @if($state->name == Session::get('location'))
                                @if($state->address!='')
                                    <p><strong>Address:</strong> {{ $state->address }}</p>
                                @endif
                                @if($state->phone!='')
                                    <p><strong>Call Us:</strong> {{ $state->phone }}</p>
                                @endif
                            @endif
                        @endforeach
                       
                    </address> 
                </div>
                <div class="col-sm-8">
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
                        <h4 class="cont">Contact Us</h4> 
                        <form action="{{action('ViewController@sendEmail')}}" method="post" class="row">
                            @csrf
                            <div class="col-sm-4" data-wow-delay=".5s">
                                <div class="form-group">
                                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Name" class="form-control contact-form{{ $errors->has('name') ? ' is-invalid' : '' }}">
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="E-mail" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif 
                                </div>
                                <div class="form-group">
                                    <input type="text" name="mobile" value="{{ old('mobile') }}" placeholder="Mobile number" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}">
                                    @if ($errors->has('mobile'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                    @endif 
                                </div>
                                
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Address" name="address" rows="2">{{ old('address') }}</textarea>
                                    @if ($errors->has('address'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-8" data-wow-delay=".5s">
                                <div class="form-group">
                                    <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Message Subject" class="form-control{{ $errors->has('subject') ? ' is-invalid' : '' }}">
                                    @if ($errors->has('subject'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('subject') }}</strong>
                                        </span>
                                    @endif 
                                </div>
                                <div class="form-group">
                                    <textarea name="message" class="form-control{{ $errors->has('message') ? ' is-invalid' : '' }}"  placeholder="Describe yourself here..." rows="7">{{ old('message') }}</textarea>
                                    @if ($errors->has('message'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('message') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-danger btn-block">SEND</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div> <!--/#cart_items-->


@endsection
