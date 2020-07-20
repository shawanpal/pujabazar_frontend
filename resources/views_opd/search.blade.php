@extends('layouts.main')
@section('contents')
<div class="catagory">
    <div class="container">
        
        @if($products == '' && $packages == '' && $bookings == '')
            <div class="col-md-12">
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Sorry!</strong> We can't find any Item.
                </div>
            </div>
        @else
        <div class="row">
            <div class="col-xl-1"></div>
            <div class="col-xl-10">
                <div class="right-side">
                    <div class="row">
                    @if($products != '')
                            {!! $products !!}
                    @endif
                    @if($packages != '')
                            {!! $packages !!}
                    @endif
                    @if($bookings != '')
                            {!! $bookings !!}
                    @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-1"></div>
        </div>
        @endif
    </div>
</div>
      
@endsection