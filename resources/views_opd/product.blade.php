@extends('layouts.main')
@section('contents')
	<div class="my_card">
    <div class="container">
        @foreach($product as $key => $value)
            @php ($id = $value->id)
            @php ($name = $value->name)
            @php ($price = $value->price)
            @php ($discount = $value->discount)
            @php ($color = $value->color)
            @php ($size = $value->size)
            @php ($size_unit = $value->size_unit)
            @php ($weight = $value->weight)
            @php ($weight_unit = $value->weight_unit)
            @php ($stock = $value->stock)
            @php ($details = $value->details)
        @endforeach
        <div class="row">
            <div class="col-md-12">
                <div class="wrapper row">
                    <div class="preview col-lg-6">
                        <div class="preview-pic tab-content">
                            @php ($i = 1)
                            @foreach($images as $img)
                                @if($i == 1)
                                    <div class="tab-pane active" id="pic-{{$i}}">
                                        <img src="{{asset('images/'.$img->image)}}">
                                    </div>
                                @else
                                    <div class="tab-pane" id="pic-{{$i}}">
                                        <img src="{{asset('images/'.$img->image)}}">
                                    </div>
                                @endif
                                @php ($i++)
                            @endforeach
                            
                        </div>
                        <ul class="preview-thumbnail nav nav-tabs">
                            @php ($i = 1)
                            @foreach($images as $img)
                                <li class="active nav-item">
                                    <a data-target="#pic-{{$i}}" data-toggle="tab" class="nav-link">
                                        <img src="{{asset('images/'.$img->image)}}">
                                    </a>
                                </li>
                                @php ($i++)
                            @endforeach
                            
                        </ul>
                    </div>
                    <div class="details col-lg-6">
                        <h3 class="product-title">{{ $name }}</h3>
                        <div class="rating">
                            <div class="stars">
                                {!! $reviews !!}
                            </div>
                            <span class="review-no"><button type="button" class="btn btn-outline-info btn-sm"  data-toggle="modal" data-target="#reviewshow">{!! $totalReviews !!}</button></span>
                            <button class="btn btn-outline-primary btn-sm rating-btn"  data-toggle="modal" data-target="#reviewModal">WRITE A REVIEW</button>
                        </div>
                        @if($value->discount != null)
                            <h4>Rs.{{ $price-$discount }}</h4>
                            <h5><strike>Rs.{{ $price }}</strike></h5>
                        @else
                            <h4>Rs.{{ $price }}</h4>
                        @endif


                        	
                        <ul class="socila list-inline">
                            <li class="fb list-inline-item"><a href="javascript:void(0)"><span>Total Stock <b>{{ $stock }}</b></a>
                            </li>
                            @if($color !='')
                            <li class="fb list-inline-item" style="background-color:{{ $color }};"><a href="javascript:void(0)"><span>Color</a>
                            </li>
                            @endif
                            @if($size !='')
                            <li class="fb list-inline-item"><a href="javascript:void(0)"><span>Dimensions </span>{{ $size }} {{ $size_unit }}</a>
                            </li>
                            @endif
                            @if($weight !='')
                            <li class="fb list-inline-item"><a href="javascript:void(0)"><span>Net Weight {{ $weight }} {{ $weight_unit }}</a>
                            </li>
                            @endif
                            {{--<li class="tw list-inline-item"><a href="javascript:void(0)"><span><i class="fa fa-twitter" aria-hidden="true"></i></span>Twitter</a>
                            </li>
                            <li class="gg list-inline-item"><a href="javascript:void(0)"><span><i class="fa fa-google-plus" aria-hidden="true"></i></span>Google+</a>
                            </li>
                            <li class="pin list-inline-item"><a href="javascript:void(0)"><span><i class="fa fa-pinterest" aria-hidden="true"></i></span>Pinterest</a>
                            </li>--}}
                        </ul>
                        <div style="margin-top: 89px;"></div>
                        <div class="product-btn-area text-center">
                            @if($stock > 0)
                            <form method="POST" action="{{url('add-cart')}}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{$id}}">
                                <button type="submit" class="product-btn">
                                    <i class="fa fa-shopping-cart"></i>
                                    ADD TO CART
                                </button>
                            </form>
                            @else
                            <button type="button" class="product-btn" disabled>
                                <i class="fa fa-ban"></i>
                                OUT OF STOCK
                            </button>
                            @endif
                            
                            {{--<a href="" class="product-btn">ADD TO CART</a>--}}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="product-description">
                    <h4>PRODUCT DESCRIPTION</h4>
                    <p>{{ $details }}</p>
                    {{--<p class="dim">Dimensions (in cm): L-9 W-9 H-3</p>
                    <p class="wei">Weight (in gm): 45</p>--}}
                </div>
            </div>
        </div>
        
        <ul id="flexisel1">
        @foreach($products as $product)
            <li>
                <div class="arrival-area">
                    <a href="{{ route('code') }}/{{ $product->code }}">
                    <img class="img-fluid" src="{{ asset('images/'.$product->image) }}">
                    <h4>{{ $product->name }}</h4>
                    </a>
                </div>
            </li>
        @endforeach
        </ul>    

        <div class="clearout"></div>

    </div>
</div>
@endsection