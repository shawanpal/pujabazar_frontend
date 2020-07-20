@extends('layouts.app')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @if($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <strong>Error Alert!</strong> {{ $message }}
            </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="cart-left">
              <form method="POST" action="{{ action('BuyerController@addCustomer') }}">
                  @csrf
                  <h4><span id="step1" class="cir">1</span>Delivery Address</h4>
                  <div class="cart-sub">
                      <div class="cart-sub-area">
                          <div class="delivery">
                              <div class="del-sub">
                                  <div class="row">
                                      <div class="col">
                                         <div class="form-group">
                                              {{--<span class="drop">
                                                  <select id="buyer-titl">
                                                      <option value="">Title </option>
                                                      <option value="Mr.">Mr.</option>
                                                      <option value="Mrs.">Mrs.</option>
                                                      <option value="Miss">Miss</option>
                                                  </select>
                                              </span>--}}
                                              <label>Name</label>
                                              <input type="text" id="buyer-name" name="buyer_name" value="{{ $name }}" placeholder="First & last Name" class="form-control dels-fld" />
                                          </div>
                                      </div>
                                      <div class="col">
                                          <div class="form-group">
                                              <label class="l-small">Email</label>
                                              <input type="email" id="buyer-email" name="buyer_email" value="{{ $email }}" class="del-fld form-control" disabled placeholder="your@email.id">
                                          </div>
                                      </div>
                                      <div class="col">
                                          <div class="form-group">
                                              <label class="l-small">Pin No.</label>
                                              <input type="text" id="buyer-pin" name="buyer_pin" value="{{ $pin }}" class="del-fld form-control">
                                              <ul class="flipkart-navbar-input col-xs-10 pin_sugg" id="pin_suggation"></ul>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="row">
                                      <div class="col">
                                          <div class="form-group">
                                              <label class="l-small">Area / Locality</label>
                                              <input type="text" id="buyer-area" name="buyer_area" value="{{ $location }}" class="del-fld form-control" placeholder="E.g. Sector 32 or Kormangala or Park View Residency">
                                          </div>
                                      </div>
                                      <div class="col">
                                          <div class="form-group">
                                              <label class="l-small">Flat / House / Office No.</label>
                                              <input type="text" id="flt-hus-ofc-no" name="flt_hus_ofc_no" placeholder="Flat / House / Office No." value="{{ $office_no }}" class="form-control del-fld " />
                                          </div>
                                      </div>
                                      <div class="col">
                                         <div class="form-group">
                                              <label class="l-small">Street / Society / Office Name</label>
                                              <input type="text" id="str-soc-ofc-nam" name="str_soc_ofc_nam" placeholder="Street / Society / Office Name" value="{{ $office_nam }}" class="form-control del-fld " />
                                          </div>
                                      </div>
                                  </div>

                                  <div class="form-group radio-area">
                                      <input type="radio" name="buyer_address_type" id="Home" {{ $add_type=='Home' ? 'checked' : '' }} value="Home" onclick="myFunction();" />Home

                                      <input type="radio" name="buyer_address_type" id="Office" {{ $add_type=='Office' ? 'checked' : '' }} value="Office" onclick="myFunction();" />Office

                                      <input type="radio" name="buyer_address_type" id="other" {{ $add_type=='other' ? 'checked' : '' }} value="other" onclick="myFunction();" />Others

                                      <textarea class="form-control del-t  {{ $add_type=='other' ? '' : 'd-none' }}" id="bu-ot-add" name="buyer_address_other">{{ $other_address }}</textarea>
                                  </div>
                                  <button type="submit" class="btn btn-primary del-btn" id="secend-next">Next</button>
                                  <span class="edit d-none" id="edit-address">(Edit)</span>
                              </div>
                          </div>
                      </div>
                  </div>

                  <h4><span id="step2" class="cir">2</span>Choose Delivery Time</h4>
                  <div class="cart-sub">
                      <div class="cart-sub-area">
                          <div class="date_time">
                              <div class="date-sub">
                                  <div>
                                    <input type="date" id="deli-dat" value="{{ $date }}" min="{{ $date }}" onkeydown="return false" required>
                                  </div>

                                  <!--input type="hidden" id="deli-dat" value="{{ $date }}"-->
                                  <ul class="date-list list-unstyled">
                                      <li><span class="date-check"><input type="radio" name="deli-time" value="9 AM - 11 AM"></span>9 AM - 11 AM</li>
                                      <li><span class="date-check"><input type="radio" name="deli-time" value="11 PM - 4 PM"></span>11 PM - 4 PM</li>
                                      <li><span class="date-check"><input type="radio" name="deli-time" value="4 PM - 7 PM"></span>4 PM - 7 PM</li>
                                      <li><span class="date-check"><input type="radio" name="deli-time" value="7 PM - 9 PM"></span>7 PM - 9 PM</li>
                                  </ul>
                                  <button type="submit" class="btn btn-primary del-btn btn-block" id="third-next">Next</button>
                                  <span class="edit d-none" id="edit-time">(Edit)</span>
                              </div>
                          </div>
                      </div>
                  </div>

                  <h4><span id="step3" class="cir">3</span>Payment</h4>
                  <div class="cart-sub">
                      <div class="pay-sub-area">
                          <div class="pay_sub">
                              <div class="pay-box">
                                  <h5>Total Amount <span class="rupe">₹{{Cart::total()}}</span></h5>

                                  <h6 class="br-top"><b>Amount Payable</b> (incl. of all taxes)<span class="rupe ">₹{{Cart::total()}}</span></h6>
                              </div>
                              <div class="pay-box">
                                  <div class="tabbable">
                                      <ul class="nav nav-tabs">
                                          <li class="nav-item"><a href="#tab1" data-toggle="tab" class="nav-link active">Cash On Delivery</a> </li>
                                          <li class="nav-item"><a href="#tab2" data-toggle="tab" class="nav-link ">Credit Card/Debit Card/Net Banking</a>
                                          <li class="nav-item"><a href="#tab3" data-toggle="tab" class="nav-link">Pay with paypal</a> </li>
                                      </ul>
                                      <div class="tab-content">

                                          <div class="tab-pane active" id="tab1">
                                              <div class="cash-area">
                                                  <div class="row">
                                                      <div class="col-sm-3"></div>
                                                      <div class="col-sm-6">
                                                          <div class="inner-cash"> <img src="{{asset('images/cash.png')}}" class="img-fluid cash-img" />
                                                              <h5>Please pay ₹{{Cart::total()}} to the delivery executive when your order is delivered</h5>
                                                              <p class="or">or</p>
                                                              <h5>Pay using <b>Paytm/Mobikwik</b> at the time of delivery</h5>
                                                              <ul class="mobi-list list-inline">
                                                                  <li class="list-inline-item"><img src="{{asset('images/paytm.png')}}" class="mobi-img mobi1" /></li>
                                                                  <li class="list-inline-item"><img src="{{asset('images/mobikwik_wallet.png')}}" class="mobi-img mob2" /></li>
                                                              </ul>
                                                          </div>
                                                      </div>
                                                      <div class="col-sm-3"></div>
                                                      <button type="button" class="del-btn form-control" id="confurm-order">Confirm This Order</button>
                                                      {{--<form method="POST" action="{{ action('PaymentController@cashOnDelivery') }}">
                                                          @csrf

                                                          <input type="hidden" id="buyer_email" name="email" value="{{ $email }}">
                                                          <button type="submit" class="del-btn form-control" >Payment</button>
                                                      </form>--}}
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="tab-pane" id="tab2">
                                              <div class="card-area">
                                                  <div class="row">
                                                      <div class="col-md-12" id="card-pay-msg">

                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-sm-3"></div>
                                                      <div class="col-sm-6">
                                                          <div class="inner-cash"> <img src="{{asset('images/cash.png')}}" class="img-fluid cash-img" />
                                                              <h5>Please pay ₹{{Cart::total()}} by Credit Card/Debit Card/Net Banking</h5>

                                                              </ul>
                                                          </div>
                                                      </div>
                                                      <div class="col-sm-3"></div>
                                                      <button type="button" id="payWithCard" class="del-btn form-control" >Proceed to Payment</button>
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="tab-pane" id="tab3">
                                              <div class="net-area">

                                                  <div class="row">
                                                      <div class="col-sm-3"></div>
                                                      <div class="col-sm-6">
                                                          <div class="inner-cash"> <img src="{{asset('images/cash.png')}}" class="img-fluid cash-img" />
                                                              <h5>Please pay ₹{{Cart::total()}} by PayPal</h5>

                                                              </ul>
                                                          </div>
                                                      </div>
                                                      <div class="col-sm-3"></div>
                                                      {{--<button id="pay-with-paypal" class="del-btn form-control">Proceed to Payment</button>--}}
                                                      <a href="{{ route('payment.paypal') }}" class="del-btn form-control">Proceed to Payment</a>
                                                  </div>

                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </form>
            </div>
        </div>
            <div class="col-md-4">
    			<table class="table">
                <thead>
                    <tr>
                        <th class="table-image">Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                     @foreach($cart as $item)
                    <tr>
                        <td class="table-image">
                        	<a href="{{$item->options->url}}"><img src="{{asset('images/'.$item->options->image)}}" alt="{{$item->name}}" class="img-responsive cart-image"></a><br>
                        <a href="{{$item->options->url}}">{{$item->name}}</a>
                    </td>
                        <td><i class="fa fa-inr"> {{$item->price}}</td>
                        <td>
                            {{$item->qty}}
                        </td>

                        <td><i class="fa fa-inr"> {{$item->subtotal}}</td>

                    </tr>
                    @endforeach
                        <tr class="border-bottom">
                            <th colspan="3">Your Total</th>
                            <th><i class="fa fa-inr"> {{Cart::total()}}</th>
                        </tr>
                        <tr class="border-bottom">
                            <th colspan="3"></th>
                            <th class="float-right"><a href="{{route('cart')}}">Back&nbsp;to&nbsp;Cart</a></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@if($verify_phone == 1)


@endif



@endsection
