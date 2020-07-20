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
            <div class="col-sm-3">
            @if(!empty($attrs))
                {!! $attrs !!}
            @endif
            @if(!empty($packageAttr))
                {!! $packageAttr !!}
            @endif
            @if(!empty($productAttr))
                {!! $productAttr !!}
            @endif
            

            </div>
            <div class="col-sm-9">
                <div class="right-side">
                    <div class="row">

                        <div class="col-sm-9">
                        @if(!empty($currentCategory))
                            {{ Breadcrumbs::render('category', $currentCategory[0]->category_name) }}
                        @endif
                        </div>

                        <div class="col-sm-3">
                            <div class=" sort">
                                @if($bookings != '')
                                <select id="short-by" name="short" onchange="shortBy(this.id)">
                                    <option value="">Default</option>
                                    <option value="rating">Users Rating</option>
                                    <option value="price">Fees - Lowest First</option>
                                    <option value="-price">Fees - Highest First</option>
                                    <option value="title">Person Name A-Z</option>
                                    <option value="-title">Person Name Z-A</option>
                                </select>
                                @else
                                <select id="short-by" name="short" onchange="shortBy(this.id)">
                                    <option value="">Default</option>
                                    <option value="rating">Customer Rating</option>
                                    <option value="price">Price - Lowest First</option>
                                    <option value="-price">Price - Highest First</option>
                                    <option value="title">Product Name A-Z</option>
                                    <option value="-title">Product Name Z-A</option>
                                    <option value="discount">Highest % Discount</option>
                                </select>
                                @endif
                            </div>
                        </div>
                    </div>
            @if($packages != '')
                <div class="row" id="filter-package">
                    {!! $packages !!}
                </div>
            @endif
            @if($products != '')
                <div class="row" id="filter-product">
                    {!! $products !!}
                </div>
            @endif
            
            @if($bookings != '')
                <div class="row" id="filter-booking">
                    {!! $bookings !!}
                </div>
            @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
