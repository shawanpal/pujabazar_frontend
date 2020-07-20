@extends('layouts.main')

@foreach($booking as $key => $value)
    @php ($id = $value->id)
    @php ($name = $value->name)
    @php ($location = $value->location)
    @php ($language = $value->language)
    @php ($enlisted_in = $value->enlisted_in)
    @php ($preferable_events = $value->preferable_events)
    @php ($preferable_place = $value->preferable_place)
    @php ($performane_duration = $value->performane_duration)
    @php ($contact_price = $value->price)
    @php ($performance_fee = $value->performance_fee)
    @php ($on_stage_team = $value->on_stage_team)
    @php ($off_stage_team = $value->off_stage_team)
    @php ($off_stage_food = $value->off_stage_food)
    @php ($details = $value->details)    
@endforeach

@section('contents')
<div class="profile-area">
        <div class="container">
            <div class="profile-head">
                <div class="row">
                    <div class="col-md-4">
                        <div id="demo" class="carousel slide" data-ride="carousel">

                          <!-- Indicators -->
                          <ul class="carousel-indicators">
                            @php ($i = 1)
                            @foreach($images as $img)
                                @if($i == 1)
                                    <li data-target="#demo" data-slide-to="{{$i}}" class="active"></li>
                                @else
                                    <li data-target="#demo" data-slide-to="{{$i}}"></li>
                                @endif
                                @php ($i++)
                            @endforeach
                            
                          </ul>
  
                            <!-- The slideshow -->
                            <div class="carousel-inner">
                                @php ($i = 1)
                                @foreach($images as $img)
                                @if($i == 1)
                                    <div class="carousel-item active">
                                      <img src="{{asset('images/'.$img->image)}}">
                                    </div>
                                @else
                                    <div class="carousel-item">
                                      <img src="{{asset('images/'.$img->image)}}">
                                    </div>
                                @endif
                                    
                                @php ($i++)
                                @endforeach
                                
                            </div>
  
                            <!-- Left and right controls -->
                            <a class="carousel-control-prev" href="#demo" data-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </a>
                            <a class="carousel-control-next" href="#demo" data-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="profile-text">
                            <h4 class="name">{{ $name }}</h4>
                            <span class="crd-btn">
                        <div class="rating">
                            <div class="stars">
                                {!! $reviews !!}
                            </div>
                            <span class="review-no">
                                <button type="button" class="btn btn-outline-info btn-sm"  data-toggle="modal" data-target="#reviewshow">{!! $totalReviews !!}</button>
                            </span>
                            <button class="btn btn-outline-primary btn-sm rating-btn"  data-toggle="modal" data-target="#reviewModal">WRITE A REVIEW</button>
                        </div>
                    </span>
                            <h6><b>Location:</b> {{ $location }}</h6>
                            <h6><b>Language known:</b> {{ $language }}</h6>
                            <h6><b>Entitled In:</b> {{ $enlisted_in }}</h6>

                    <div class="clearfix"></div>

                            <button class="tube" value="{{ $id }}">Youtube</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="event">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <h5>EVENT DETAILS</h5>
                    <p><b>Preferable Events</b>: {{ $preferable_events }}</p>
                    <p><b>Preferable Place</b>: {{ $preferable_place }}</p>
                    <p><b>Performance Duration</b>: {{ $performane_duration }} Hr (Approx)</p>
                    <p><b>Contact Price</b>:Rs {{ $contact_price }}</p>
                    <p><b>Performance Fee</b>:Rs {{ $performance_fee }}</p>
                </div>
                <div class="col-sm-6">
                    <div class="product-btn-area text-center">
                        <button class="product-btn" data-toggle="modal" data-target="#bookingModal">
                            BOOK
                        </button>
                    </div>
                </div>
               
                <div class="col-sm-12">
                    <h5>ABOUT</h5>
                    <p>{{ $details }}</p>
                </div>
            </div>
        </div>
    </div>

<ul id="flexisel1">
@foreach($bookings as $booking)
    <li>
        <div class="arrival-area">
            <a href="{{ route('bookin') }}/{{ $booking->code }}">
            <img class="img-fluid" src="{{ asset('images/'.$booking->image) }}">
            <h4>{{ $booking->name }}</h4>
            </a>
        </div>
    </li>
@endforeach
</ul>    

<div class="clearout"></div>





@endsection