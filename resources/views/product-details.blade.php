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
                            <a>Shop Details</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="shop single section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="product-gallery">
                            <div class="flexslider-thumbnails">
                                <div class="flex-viewport" style="overflow: hidden; position: relative;">
                                    <ul class="slides" style="width: 1200%; transition-duration: 0.6s; transform: translate3d(-1665px, 0px, 0px);">
                                        @foreach($images as $image)
                                        <li data-thumb="{{asset('/images/'.$image->image)}}" class="clone flex-active-slide" style="width: 555px; float: left; display: block;">
                                            <img src="{{asset('/images/'.$image->image)}}" alt="#" />
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="product-des">
                            <div class="short">
                                <h4>{{$product->name}}</h4>
                                <div class="rating-main">
                                    <ul class="rating">
                                        {!! $stars !!}
                                    </ul>
                                    <a href="#" class="total-review">({{count($reviews)}}) Review</a>
                                </div>
                                <p class="price">
                                    @if($desces[0]->discount != '')
                                    <span class="discount">₹{{number_format($desces[0]->price - $desces[0]->discount, 2)}}</span><s>₹{{number_format($desces[0]->price,2)}}</s>
                                    @else
                                    <span>₹{{number_format($desces[0]->price,2)}}</span>
                                    @endif
                                </p>
                                <p class="description">
                                    {{$product->details}}
                                </p>
                            </div>
                            <form action="{{url('/add-to-cart-product')}}" method="post">
                                @if($desces[0]->size != '')
                                <div class="size">
                                    <h4>Size</h4>
                                    <ul>
                                        @foreach ($desces as $k => $desc)
                                        @if($k == 0)
                                        <li>
                                            <a class="activev" href="javascript:void(0)" data-variation="{{$desc->size.''.$desc->size_unit }}">{{$desc->size.' '.$desc->size_unit }}</a>
                                            <input type="radio" name="product_size" value="{{$desc->size.'-'.$desc->size_unit }}" checked>
                                        </li>
                                        @else
                                        <li>
                                            <a href="javascript:void(0)" data-variation="{{$desc->size.''.$desc->size_unit }}">{{$desc->size.' '.$desc->size_unit }}</a>
                                            <input type="radio" name="product_size" value="{{$desc->size.'-'.$desc->size_unit }}">
                                        </li>
                                        @endif
                                        @endforeach
                                    </ul>
                                    <input type="hidden" name="variation" value="size">
                                </div>
                                @endif
                                @if($desces[0]->weight != '')
                                <div class="size">
                                    <h4>Weight</h4>
                                    <ul>
                                        @foreach ($desces as $k => $desc)
                                        @if($k == 0)
                                        <li>
                                            <a class="activev" href="javascript:void(0)" data-variation="{{$desc->weight.''.$desc->weight_unit }}">{{$desc->weight.' '.$desc->weight_unit }}</a>
                                            <input type="radio" name="product_weight" value="{{$desc->weight.'-'.$desc->weight_unit }}" checked>
                                        </li>
                                        @else
                                        <li>
                                            <a href="javascript:void(0)" data-variation="{{$desc->weight.''.$desc->weight_unit }}">{{$desc->weight.' '.$desc->weight_unit }}</a>
                                            <input type="radio" name="product_weight" value="{{$desc->weight.'-'.$desc->weight_unit }}">
                                        </li>
                                        @endif
                                        @endforeach
                                    </ul>
                                    <input type="hidden" name="variation" value="weight">
                                </div>
                                @endif

                                <div class="product-buy">
                                    <div class="quantity">
                                        <h6>Quantity :</h6>
                                        <div class="input-group">
                                            <div class="button minus">
                                                <button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quant[1]">
                                                    <i class="ti-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text" name="quantity" class="input-number" data-min="1" data-max="1000" value="1"/>
                                            <div class="button plus">
                                                <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[1]">
                                                    <i class="ti-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add-to-cart">
                                        @csrf
                                        <input type="hidden" name="atcpid" value="{{ Crypt::encryptString($product->id) }}">
                                        <input type="submit" class="btn" value="Add to cart">
                                    </div>
                                    <p class="cat">Category :
                                        <a href="{{url('category/'.$category->category_url)}}">{{$category->category_name}}</a>
                                        @if($product->sub_category_id != '')
                                        ,<a href="{{url('category/'.$category->category_url.'/'.$subcategory->sub_category_url)}}">{{$subcategory->sub_category_name}}</a>
                                        @endif
                                    </p>
                                    <p class="availability">
                                        Availability : {{$desces[0]->stock}} Products In Stock
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="product-info">
                            <div class="nav-main">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#description" role="tab">Description</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Reviews</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="description" role="tabpanel">
                                    <div class="tab-single">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="single-des">
                                                    <p>{!! $product->details !!}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="reviews" role="tabpanel">
                                    <div class="tab-single review-panel">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="ratting-main">
                                                    <div class="avg-ratting">
                                                        <h4>@if($average == '') 
                                                            {{'0.0'}}
                                                            @else 
                                                            {{number_format($average,1)}}
                                                            @endif  <span>(Overall)</span></h4>
                                                        <span>Based on {{count($reviews)}} Comments</span>
                                                    </div>
                                                    @if(count($reviews) != 0)
                                                    @foreach ($reviews as $review)
                                                    <div class="single-rating">
                                                        <div class="rating-author">
                                                            <img src="{{asset('images/user-pic.png')}}" alt="User Pic" />
                                                        </div>
                                                        <div class="rating-des">
                                                            <h6>{{$review->name}}</h6>
                                                            <div class="ratings">
                                                                <ul class="rating">
                                                                    @for ($i=1; $i <= 5; $i++)
                                                                    @if($i <= $review->rating)
                                                                    <li><i class="fa fa-star"></i></li>
                                                                    @else
                                                                    <li><i class="fa fa-star-o"></i></li>
                                                                    @endif
                                                                    @endfor
                                                                </ul>
                                                                <div class="rate-count">
                                                                    (<span>{{$review->rating}}</span>)
                                                                </div>
                                                            </div>
                                                            <p>{{$review->review}}</p>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @else
                                                    <p>No review yet!</p>
                                                    @endif
                                                </div>
                                                <div class="comment-review">
                                                    <div class="add-review">
                                                        <h5>Add A Review</h5>
                                                        <p>Your email address will not be published. Required fields are marked</p>
                                                    </div>
                                                    @if (session('success'))
                                                    <div class="alert alert-success alert-dismissible">
                                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                        {{ session('success') }}
                                                    </div>
                                                    @endif
                                                </div>
                                                <form class="form" method="post" action="{{url('/submit-review')}}">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-12">
                                                            <label>Your Rating</label>
                                                            <fieldset class="input-rating">
                                                                <input type="radio" id="star5" name="rating" value="5" checked>
                                                                <label class="full" for="star5" title="5 stars"></label>

                                                                <input type="radio" id="star4" name="rating" value="4">
                                                                <label class="full" for="star4" title="4 stars"></label>

                                                                <input type="radio" id="star3" name="rating" value="3">
                                                                <label class="full" for="star3" title="3 stars"></label>

                                                                <input type="radio" id="star2" name="rating" value="2">
                                                                <label class="full" for="star2" title="2 stars"></label>

                                                                <input type="radio" id="star1" name="rating" value="1">
                                                                <label class="full" for="star1" title="1 star"></label>
                                                            </fieldset>
                                                            @if($errors->has('rating'))
                                                            <p class="error">{{ $errors->first('rating') }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-6 col-12">  
                                                            <div class="form-group">
                                                                <label>Your Name<span>*</span></label>
                                                                <input type="text" name="name" required="required" value="{{ old('name') }}"/>
                                                                @if($errors->has('name'))
                                                                <p class="error">{{ $errors->first('name') }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-12">
                                                            <div class="form-group">
                                                                <label>Your Email<span>*</span></label>
                                                                <input type="email" name="email" required="required" value="{{ old('email') }}"/>
                                                                @if($errors->has('email'))
                                                                <p class="error">{{ $errors->first('email') }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-12">
                                                            <div class="form-group">
                                                                <label>Your Phone No</label>
                                                                <input type="number" name="phone_no" required="required" placeholder="(Optional)" value="{{ old('phone_no') }}"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-12">
                                                            <div class="form-group">
                                                                <label>Write a review<span>*</span></label><textarea name="message" rows="6">{{ old('message') }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-12">
                                                            <div class="form-group button5">
                                                                @csrf
                                                                <input type="hidden" name="encpid" value="{{ Crypt::encryptString($product->id) }}">
                                                                <input type="submit" class="btn" value="Submit">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="product-area most-popular related-product section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2>Related Products</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="owl-carousel popular-slider">
                    @foreach ($related_products as $related_product)
                    <div class="single-product">
                        <div class="product-img">
                            <a href="{{url('/product-details/'.$related_product->code)}}">
                                @php
                                $images = getProductImages($related_product->id);
                                $rdesc = getProductDesc($related_product->id);
                                @endphp
                                @if(count($images) > 0)
                                <img class="default-img" src="{{asset('images/'.$images[0]->image)}}" alt="Front Image"/>
                                @endif
                                @if(count($images) > 1)
                                <img class="hover-img" src="{{asset('images/'.$images[1]->image)}}" alt="Back Image"/>
                                @endif
                            </a>
                            <div class="button-head">
                                <div class="product-action-2">
                                    <a title="Add to cart" href="#">Add to cart</a>
                                </div>
                            </div>
                        </div>
                        <div class="product-content">
                            <h3>
                                <a href="{{url('/product-details/'.$related_product->code)}}">{{$related_product->name}}</a>
                            </h3>
                            <div class="product-price">
                                @if(count($rdesc) > 0)
                                @if($rdesc[0]->discount != '')
                                <span class="old">₹{{number_format($rdesc[0]->price,2)}}</span>
                                <span>₹{{number_format($rdesc[0]->price - $rdesc[0]->discount, 2)}}</span>
                                @else
                                <span>₹{{number_format($rdesc[0]->price,2)}}</span>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection