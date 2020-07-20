@extends('layouts.main')
@section('contents')
<div class="my_card">
    <div class="container">

        <h2>Your Cart</h2>

        <hr>
        @if(count($cart))
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




        <table class="table">
            <thead>
                <tr>
                    <th class="table-image"></th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th class="column-spacer"></th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                 @foreach($cart as $item)
                <tr>
                    <td class="table-image"><a href="{{$item->options->url}}"><img src="{{asset('images/'.$item->options->image)}}" alt="{{$item->name}}" class="img-responsive cart-image"></a></td>
                    <td><a href="{{$item->options->url}}">{{$item->name}}</a></td>
                    <td><i class="fa fa-inr"> {{number_format($item->price, 2)}}</td>
                    <td>
                        <form action="{{url('update-cart')}}" method="post">
                            @csrf
                            <input type="hidden" name="rowId" value="{{$item->rowId}}">
                            <input class="cart_quantity_input" type="text" name="quantity" value="{{$item->qty}}" autocomplete="off" size="2">
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </form>
                    </td>

                    <td><i class="fa fa-inr"> {{number_format($item->subtotal, 2)}}</td>
                    <td>
                        <form action="{{url('remove-cart')}}" method="POST" class="side-by-side">
                            @csrf
                            <input type="hidden" name="rowId" value="{{$item->rowId}}">
                            <input class="btn btn-danger btn-sm" value="Remove" type="submit">
                        </form>
                    </td>
                </tr>
                @endforeach
                    <tr class="border-bottom">
                        <td class="table-image"></td>
                        <td style="padding: 40px;"></td>
                        <td class="small-caps table-bg" style="text-align: right">Your Total</td>
                        <td class="table-bg"><i class="fa fa-inr"> {{Cart::total()}}</td>
                        <td class="column-spacer"></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <a href="{{ url('/') }}" class="btn btn-primary btn-lg">Continue Shopping</a> &nbsp;
            <a href="{{url('checkout')}}" class="btn btn-success btn-lg">Proceed to Checkout</a>
            <div style="float:right">
                <form action="{{url('clear-cart')}}" method="post">
                    @csrf
                    <input class="btn btn-danger btn-lg" value="Empty Cart" type="submit">
                </form>
            </div>


        <div class="spacer"></div>
    @else
        <p>You have no items in the shopping cart</p>
    @endif
</div>
</div> <!--/#cart_items-->


@endsection
