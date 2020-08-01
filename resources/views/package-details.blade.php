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
                            <a>Package Details</a>
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
                                    <ul class="slides" style="
                                        width: 1200%;
                                        transition-duration: 0.6s;
                                        transform: translate3d(-1665px, 0px, 0px);">
                                        
                                        <li data-thumb="images/bx-slider1.jpg" rel="adjustX:10, adjustY:" class="" style="width: 555px; float: left; display: block;">
                                            <img src="images/bx-slider1.jpg" alt="#" />
                                        </li>
                                        <li data-thumb="images/bx-slider2.jpg" style="width: 555px; float: left; display: block;" class="">
                                            <img src="images/bx-slider2.jpg" alt="#" />
                                        </li>
                                        <li data-thumb="images/bx-slider3.jpg" style="width: 555px; float: left; display: block;" class="flex-active-slide">
                                            <img src="images/bx-slider3.jpg" alt="#" />
                                        </li>
                                        <li data-thumb="images/bx-slider4.jpg" style="width: 555px; float: left; display: block;" class="">
                                            <img src="images/bx-slider4.jpg" alt="#" />
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="product-des">
                            <div class="short">
                                <h4>{{$package->name}}</h4>
                                <div class="rating-main">
                                    <ul class="rating">
                                        <li><i class="fa fa-star"></i> </li><li><i class="fa fa-star"></i> </li><li><i class="fa fa-star"></i> </li><li><i class="fa fa-star"></i> </li><li class="dark"><i class="fa fa-star-o"></i> </li>
                                    </ul>
                                    <a href="#" class="total-review">(1) Review</a>
                                </div>
                                <p class="price">
                                    <span class="discount">₹31.00</span><s>₹35.00</s>
                                </p>
                                <p class="description">
                                    The field under validation will be excluded from the request data returned by the validate and validated methods if the anotherfield field is equal to value.
                                </p>
                            </div>
                            <form action="http://localhost/puja_bazar_backend/public/add-to-cart-product" method="post">
                                <div class="size">
                                    <h4>Size</h4>
                                    <ul>
                                        <li>
                                            <a class="activev" href="javascript:void(0)" data-variation="2cm">2 cm</a>
                                            <input type="radio" name="product_size" value="2-cm" checked="">
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" data-variation="4ft">4 ft</a>
                                            <input type="radio" name="product_size" value="4-ft">
                                        </li>
                                    </ul>
                                    <input type="hidden" name="variation" value="size">
                                </div>

                                <div class="product-buy">
                                    <div class="quantity">
                                        <h6>Quantity :</h6>
                                        <div class="input-group">
                                            <div class="button minus">
                                                <button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quantity">
                                                    <i class="ti-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text" name="quantity" class="input-number" data-min="1" data-max="1000" value="1">
                                            <div class="button plus">
                                                <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quantity">
                                                    <i class="ti-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add-to-cart">
                                        <input type="hidden" name="_token" value="SpOfR1UOOr1ZzZ2UqqV7Eq4NZhGkGyuPsRftGLYC">                                        <input type="hidden" name="atcpid" value="eyJpdiI6ImdkbXRObjB6WHd1cHNuQmlzeURQdnc9PSIsInZhbHVlIjoibTNSeWpNN0NHQ0FUQlNlYnhkUXFFdz09IiwibWFjIjoiNWZlMzVjMWEwNWQwNTQ3YzkzODNjNmZhMjdjNTExNDI2MTk5ZDczNDBkMGViY2FjNDBkNTY5MjlmMmY4NGJlZSJ9">
                                        <input type="submit" class="btn" value="Add to cart">
                                    </div>
                                    <p class="cat">Category :
                                        <a href="http://localhost/puja_bazar_backend/public/category/puja-store">Puja Store</a>
                                        ,<a href="http://localhost/puja_bazar_backend/public/category/puja-store/vegetable">Vegetable,শাকসবজি</a>
                                    </p>
                                    <p class="availability">
                                        Availability : 10 Products In Stock
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
                                                    <p>The field under validation will be excluded from the request data returned by the validate and validated methods if the anotherfield field is equal to value.</p>
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
                                                        <h4> 
                                                            4.0
                                                            <span>(Overall)</span></h4>
                                                        <span>Based on 1 Comments</span>
                                                    </div>
                                                    <div class="single-rating">
                                                        <div class="rating-author">
                                                            <img src="http://localhost/puja_bazar_backend/public/images/user-pic.png" alt="User Pic">
                                                        </div>
                                                        <div class="rating-des">
                                                            <h6>Shawan Pal</h6>
                                                            <div class="ratings">
                                                                <ul class="rating">
                                                                    <li><i class="fa fa-star"></i></li>
                                                                    <li><i class="fa fa-star"></i></li>
                                                                    <li><i class="fa fa-star"></i></li>
                                                                    <li><i class="fa fa-star"></i></li>
                                                                    <li><i class="fa fa-star-o"></i></li>
                                                                </ul>
                                                                <div class="rate-count">
                                                                    (<span>4</span>)
                                                                </div>
                                                            </div>
                                                            <p>test</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="comment-review">
                                                    <div class="add-review">
                                                        <h5>Add A Review</h5>
                                                        <p>Your email address will not be published. Required fields are marked</p>
                                                    </div>
                                                </div>
                                                <form class="form" method="post" action="http://localhost/puja_bazar_backend/public/submit-review">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-12">
                                                            <label>Your Rating</label>
                                                            <fieldset class="input-rating">
                                                                <input type="radio" id="star5" name="rating" value="5" checked="">
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
                                                        </div>
                                                        <div class="col-lg-6 col-12">  
                                                            <div class="form-group">
                                                                <label>Your Name<span>*</span></label>
                                                                <input type="text" name="name" required="required" value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-12">
                                                            <div class="form-group">
                                                                <label>Your Email<span>*</span></label>
                                                                <input type="email" name="email" required="required" value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-12">
                                                            <div class="form-group">
                                                                <label>Your Phone No</label>
                                                                <input type="number" name="phone_no" required="required" placeholder="(Optional)" value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-12">
                                                            <div class="form-group">
                                                                <label>Write a review<span>*</span></label><textarea name="message" rows="6"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-12">
                                                            <div class="form-group button5">
                                                                <input type="hidden" name="_token" value="SpOfR1UOOr1ZzZ2UqqV7Eq4NZhGkGyuPsRftGLYC">                                                                <input type="hidden" name="encpid" value="eyJpdiI6IkZ2N3o1MmZJTlRKcEZxTEtGY3FwTkE9PSIsInZhbHVlIjoiMUNGQjhKMkxoUCttTVNxTGg2K3NqQT09IiwibWFjIjoiYmU0M2FkMmVlM2UwYzczZTJmYjFhOGExMmU4Mjc5ZmY2OTdkMjZkM2U0M2FlOWQ2YmE1ZTM5N2FhNWUzNWY1MSJ9">
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
@endsection