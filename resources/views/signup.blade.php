@extends('layout.app')
@section('content')
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-inner">
                    <ul class="bread-list">
                        <li>
                            <a href="{{url('/')}}">Home<i class="ti-arrow-right"></i></a>
                        </li>
                        <li class="active">
                            <a>Sign Up</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="shop login section">
    <div class="container">
        <div class="row"> 
            <div class="col-lg-6 offset-lg-3 col-12">
                <div class="login-form">
                    <h2>Register</h2>
                    <p>Please register in order to checkout more quickly</p>
                    <!-- Form -->
                    <form class="form" method="post" action="{{url('/user-register')}}">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Your Name<span>*</span></label>
                                    <input type="text" name="name" required="required" autocomplete="off" value="{{ old('name') }}">
                                    @if($errors->has('name'))
                                    <p class="error">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Your Email<span>*</span></label>
                                    <input type="email" name="email" required="required" autocomplete="off" value="{{ old('email') }}">
                                    @if($errors->has('email'))
                                    <p class="error">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Your Contact No<span>*</span></label>
                                    <input type="tel" name="phone" required="required" autocomplete="off" value="{{ old('phone') }}">
                                    @if($errors->has('phone'))
                                    <p class="error">{{ $errors->first('phone') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Your Password<span>*</span></label>
                                    <input type="password" name="password" required="required">
                                    @if($errors->has('password'))
                                    <p class="error">{{ $errors->first('password') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group login-btn">
                                    @csrf
                                    <input class="btn" type="submit" value="Sign Up">
                                    <a href="{{url('/signin')}}" class="btn regbtn">Sign In</a>
                                </div>
                                <div class="checkbox">
                                    <label class="checkbox-inline" for="2"><input name="tnc" id="2" type="checkbox" checked required>Accept <a href="{{url('/terms-and-conditions')}}">terms & conditions</a></label>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--/ End Form -->
                </div>
            </div>
        </div>
    </div>
</section>
@endsection