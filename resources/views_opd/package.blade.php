@extends('layouts.main')
@section('contents')
	<div class="my_card">
    <div class="container">
        @foreach($package as $key => $value)
            @php ($id = $value->id)
            @php ($name = $value->name)
            @php ($discount = $value->discount)
            @php ($stock = $value->stock)
            @php ($details = $value->details)
        @endforeach
        <form method="post" action="{{ action('CartController@storePackage') }}">
            @csrf
        <div class="wrapper row">
            <div class="preview col-md-5">
                <h2>{{ $name }}</h2>
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
            <div class="col-md-7">
                <div class="card-sub">
                    <div class="pack-pric">
                        <h4>Full Package Rs.{{ $price }}</h4>
                   {{-- @if($discount != null)
                        <h4>Full Package Rs.{{ $discountPrice }}</h4>
                        <h5><strike>Old Price Rs.{{ $price }}</strike></h5>
                    @else
                        <h4>Full Package Rs.{{ $price }}</h4>
                    @endif --}}
                    </div>
                    <span class="crd-btn">
                        <div class="rating">
                            <div class="stars">
                                {!! $reviews !!}
                            </div>
                            <span class="review-no"><button type="button" class="btn btn-outline-info btn-sm"  data-toggle="modal" data-target="#reviewshow">{!! $totalReviews !!}</button></span>
                            <button type="button" class="btn btn-outline-primary btn-sm rating-btn"  data-toggle="modal" data-target="#reviewModal">WRITE A REVIEW</button>
                        </div>
                    </span>
                    <div class="clearfix"></div>
                    <h5>Full Package Details <sub>(Total Stock: {{ $stock }})</sub><span class="custo">Customise the package</span></h5>
                    <div class="span5">
                        <table class="table ">
                            {!! $samogries !!}
                            
                        </table>
                        <div class="customise">
                            
                            <h4>Discount price 
                                <span class="custo">INR 
                                <span id="package_price">
                                @if($discount != null)
                                    {{ $discountPrice }}
                                @else
                                    {{ $price }}
                                @endif
                                </span>
                                </span>
                        </h4> 
                        </div>
                            @if($stock!='' && $price!=0)
                            <input type="hidden" id="pack_id" name="package_id" value="{{ $id }}">
                            <button type="submit" class="ad-cart" id="add-package-order">
                            <i class="fa fa-shopping-cart"></i> ADD TO CART
                            </button>
                            @else
                            <button type="button" class="ad-cart" id="add-package-order" disabled>
                                <i class="fa fa-ban"></i> OUT OF STOCK
                            </button>
                            @endif
                    </div>
                </div>
            </div>
        </div>
        </form>
        <div class="row">
            <div class="details">
                <h4>Details of {{ $name }}</h4>
                <div class="content-area">
                    <p>{{ $details }}</p>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        <ul id="flexisel1">
        @foreach($packages as $package)
            <li>
                <div class="arrival-area">
                    <a href="{{ route('packag') }}/{{ $package->code }}">
                    <img class="img-fluid" src="{{ asset('images/'.$package->image) }}">
                    <h4>{{ $package->name }}</h4>
                    </a>
                </div>
            </li>
        @endforeach
        </ul>    

        <div class="clearout"></div>
        
    </div>
</div>
@endsection