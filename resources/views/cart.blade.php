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
<div class="shopping-cart section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Shopping Summery -->
                <table class="table shopping-summery">
                    <thead>
                        <tr class="main-hading">
                            <th>PRODUCT</th>
                            <th>NAME</th>
                            <th class="text-center">UNIT PRICE</th>
                            <th class="text-center">QUANTITY</th>
                            <th class="text-center">TOTAL</th> 
                            <th class="text-center"><i class="ti-trash remove-icon"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $cartItem)
                        <tr>
                            <td class="image" data-title="No"><img src="{{asset('images/'.$cartItem->options->image)}}"></td>
                            <td class="product-des" data-title="Description">
                                @if($cartItem->options->type == 'product')
                                    <p class="product-name"><a href="{{url('/product-details/'.$cartItem->options->code)}}">{{$cartItem->name}}</a></p>
                                    @if($cartItem->options->size != '')
                                    <p class="product-des">Size: {{$cartItem->options->size.$cartItem->options->size_unit}}</p>
                                    @endif
                                    @if($cartItem->options->weight != '')
                                    <p class="product-des">Weight: {{$cartItem->options->weight.$cartItem->options->weight_unit}}</p>
                                    @endif
                                @endif
                            </td>
                            <td class="price" data-title="Price"><span>₹{{number_format($cartItem->price,2)}} </span></td>
                            <td class="qty" data-title="Qty"><!-- Input Order -->
                                <div class="input-group">
                                    <div class="button minus">
                                        <button type="button" class="btn btn-primary btn-number" data-type="minus" data-field="quantity" disabled="disabled">
                                            <i class="ti-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" name="quantity" class="input-number" data-min="1" data-max="100" value="{{$cartItem->qty}}">
                                    <div class="button plus">
                                        <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quantity">
                                            <i class="ti-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!--/ End Input Order -->
                            </td>
                            <td class="total-amount" data-title="Total"><span>₹{{number_format($cartItem->subtotal,2)}} </span></td>
                            <td class="action" data-title="Remove"><a href="javascript:void(0)"><i class="ti-trash remove-icon"></i></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!--/ End Shopping Summery -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <!-- Total Amount -->
                <div class="total-amount">
                    <div class="row">
                        <div class="col-lg-8 col-md-5 col-12">
                            <div class="left">
                                <div class="coupon">
                                    <form action="#" target="_blank">
                                        <input name="Coupon" placeholder="Enter Your Coupon">
                                        <button class="btn">Apply</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-7 col-12">
                            <div class="right">
                                <ul>
                                    <li>Cart Subtotal<span>₹{{Cart::total()}}</span></li>
                                    <li>Shipping<span>Free</span></li>
                                    <li class="last">You Pay<span>₹{{Cart::total()}}</span></li>
                                </ul>
                                <div class="button5">
                                    <a href="{{url('/checkout')}}" class="btn">Checkout</a>
                                    <a href="{{url('/')}}" class="btn">Continue shopping</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ End Total Amount -->
            </div>
        </div>
    </div>
</div>

@endsection