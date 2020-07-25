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
                            <a>Cart</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Start Checkout -->
<section class="shop checkout section">
    <div class="container">
        <form class="form" method="post" action="{{url('/place-order')}}">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <div class="checkout-form">
                        @if (session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ session('error') }}
                        </div>
                        @endif
                        <h2>Make Your Checkout Here</h2>
                        <p>Please register in order to checkout more quickly</p>
                        <!-- Form -->
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Full Name<span>*</span></label>
                                    <input type="text" name="name" class="form-control" required="required"/>
                                    @if($errors->has('name'))
                                    <p class="error">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Email<span>*</span></label>
                                    <input type="email" name="email" class="form-control" required="required"/>
                                    @if($errors->has('email'))
                                    <p class="error">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Flat / House / Office No.<span>*</span></label>
                                    <input type="text" name="flat_house_office_no" class="form-control" required="required"/>
                                    @if($errors->has('flat_house_office_no'))
                                    <p class="error">{{ $errors->first('flat_house_office_no') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Street / Society / Office Name.<span>*</span></label>
                                    <input type="text" name="street_society_office_name" class="form-control" required="required"/>
                                    @if($errors->has('street_society_office_name'))
                                    <p class="error">{{ $errors->first('street_society_office_name') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Area / Locality<span>*</span></label>
                                    <input type="text" name="location" class="form-control" required="required"/>
                                    @if($errors->has('location'))
                                    <p class="error">{{ $errors->first('location') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Country<span>*</span></label>
                                    <select name="country_name" class="form-control" required>
                                        <option value="">Select Country</option>
                                        <option value="IN">India</option>
                                    </select>
                                    @if($errors->has('country_name'))
                                    <p class="error">{{ $errors->first('country_name') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>State<span>*</span></label>
                                    <select name="state" class="form-control" required>
                                        <option value="">Select State</option>
                                        @foreach ($indianStates as $indianStateId => $indianStateName)
                                        <option value="{{$indianStateId}}">{{$indianStateName}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('state'))
                                    <p class="error">{{ $errors->first('state') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Postal Code<span>*</span></label>
                                    <input type="text" name="pin" class="form-control" required="required"/>
                                    @if($errors->has('pin'))
                                    <p class="error">{{ $errors->first('pin') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Address Type<span>*</span></label>
                                    <p><input type="radio" name="address_type" value="Home" checked /> Home
                                        <input type="radio" name="address_type" value="Office" /> Office
                                        <input type="radio" name="address_type" value="Other"/> Others</p>
                                    @if($errors->has('address_type'))
                                    <p class="error">{{ $errors->first('address_type') }}</p>
                                    @endif
                                    <div id="oth-addr" style="display:none"><input type="text" class="form-control" name="address_other"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Choose Delivery Time<span>*</span></label>
                                    <p><input type="radio" name="deli_time" value="9 AM - 11 AM" checked> 9 AM - 11 AM
                                        <input type="radio" name="deli_time" value="11 PM - 4 PM"> 11 PM - 4 PM
                                        <input type="radio" name="deli_time" value="4 PM - 7 PM"> 4 PM - 7 PM
                                        <input type="radio" name="deli_time" value="7 PM - 9 PM"> 7 PM - 9 PM
                                    </p>
                                    @if($errors->has('deli_time'))
                                    <p class="error">{{ $errors->first('deli_time') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!--/ End Form -->
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="order-details">
                        <!-- Order Widget -->
                        <div class="single-widget">
                            <h2>CART TOTALS</h2>
                            <div class="content">
                                <ul>
                                    <li>Sub Total<span>₹{{Cart::total()}}</span></li>
                                    <li>Shipping<span>₹00.00</span></li>
                                    <li class="last">Total<span>₹{{Cart::total()}}</span></li>
                                </ul>
                            </div>
                        </div>
                        <!--/ End Order Widget -->
                        <!-- Order Widget -->
                        <div class="single-widget">
                            <h2>Payments</h2>
                            <div class="content">
                                <div class="radio">
                                    <label>
                                        <input name="pay_method" type="radio" value="pay_on_delivery" checked/> 
                                        Pay On Delivery</label>
                                    <label>
                                        <input name="pay_method" type="radio" value="pay_by_card"/>
                                        Credit Card/Debit Card/Net Banking
                                    </label>
                                    @if($errors->has('pay_method'))
                                    <p class="error">{{ $errors->first('pay_method') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!--/ End Order Widget -->
                        <!-- Payment Method Widget -->
                        <div class="single-widget payement">
                            <div class="content">
                                <img src="{{asset('images/pay1.png')}}" alt="visa" />
                                <img src="{{asset('images/pay2.png')}}" alt="mastercard" />
                                <img src="{{asset('images/pay3.png')}}" alt="mestro" />
                                <img src="{{asset('images/pay4.png')}}" alt="netbanking" />
                            </div>
                        </div>
                        <!--/ End Payment Method Widget -->
                        <!-- Button Widget -->
                        <div class="single-widget get-button">
                            <div class="content">
                                <div class="button">
                                    @csrf
                                    <input type="submit" class="btn" value="proceed to checkout">
                                </div>
                            </div>
                        </div>
                        <!--/ End Button Widget -->
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<!--/ End Checkout -->

@endsection