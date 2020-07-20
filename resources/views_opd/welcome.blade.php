@extends('layouts.main')
@section('contents')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @if($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <strong>Success Alert!</strong> {{ $message }}
            </div>
            @endif
            {!! Session::forget('success') !!}
        </div>
    </div>
    @foreach($categorys as $category)
        @if($category->category_name=='Puja Store')
        <div class="arrival">
            <ul id="flexisel1">
                @if(count($subcategorys) != 0)
                    @foreach ($subcategorys as $key => $subcategory)
                    @if($subcategory->category_id == $category->id)
                        @if($subcategory->image == '')
                            {{!! $img = 'noPhoto.jpg' !!}}
                        @else
                            {{!! $img = $subcategory->image !!}}
                        @endif
                        <li>
                            <div class="arrival-area">
                                <a href="{{ route('itemsShowBySubCategory') }}/{{ $category->category_url }}/{{ $subcategory->sub_category_url }}">
                                <img class="img-fluid" src="{{asset('images/'.$img)}}">
                                <h4>
                                    @php ($sub_name = explode(',',$subcategory->sub_category_name))
                                    @foreach($sub_name as $sub)
                                        {{ $sub }}
                                        @if(count($sub_name)>1)
                                            <br>
                                        @endif
                                    @endforeach

                                </h4>
                                </a>
                            </div>
                        </li>

                    @endif
                    @endforeach
                @endif
            </ul>
        </div>
        <div class="clearout"></div>
        @endif
    @endforeach
</div>

{{-- Banner Section --}}
<div class="container">
    <div class="banner">

        <div id="demo" class="carousel slide " data-ride="carousel">
            <!-- The slideshow -->
            <div class="carousel-inner">
                @if(count($banners)!=0)
                @php ($i=1)
                @foreach($banners as $banner)
                <?php
                  $url = explode('_', $banner->banner_image);
                  if(isset($url[1]) && $url[1] !== ''){
                    $uu = explode('.', $url[1]);
                    if(isset($uu[1]) && $uu[1] !== ''){
                      $url[1] = '';
                    }
                  }

                ?>
                    @if($i==1)
                        <div class="carousel-item active">
                          @if(isset($url[2]) && $url[2] !== '')
                              <a href="{{ url('/') }}/items/{{ $url[1] }}/{{ $url[2] }}">
                          @elseif(isset($url[1]) && $url[1] !== '')
                              <a href="{{ url('/') }}/items/{{ $url[1] }}">
                          @endif
                            <img src="{{asset('images/'.$banner->banner_image)}}" alt="" class="img-fluid">
                            @if((isset($url[1]) && $url[1] !== '') || (isset($url[2]) && $url[2] !== ''))
                              </a>
                            @endif
                        </div>
                    @else
                        <div class="carousel-item">
                          @if(isset($url[2]) && $url[2] !== '')
                              <a href="{{ url('/') }}/items/{{ $url[1] }}/{{ $url[2] }}">
                          @elseif(isset($url[1]) && $url[1] !== '')
                              <a href="{{ url('/') }}/items/{{ $url[1] }}">
                          @endif
                            <img src="{{asset('images/'.$banner->banner_image)}}" alt="" class="img-fluid">
                            @if((isset($url[1]) && $url[1] !== '') || (isset($url[2]) && $url[2] !== ''))
                              </a>
                            @endif
                        </div>
                    @endif
                    @php ($i++)
                @endforeach
                @else
                    <div class="carousel-item active">
                        <img src="{{asset('images/Header.png')}}" alt="" class="img-fluid">
                    </div>
                @endif
            </div>
            <!-- Left and right controls -->
            <a class="left carousel-control" href="#demo" data-slide="prev">
                <i class="fa fa-chevron-left" aria-hidden="true"></i>
            </a>
            <a class="right carousel-control" href="#demo" data-slide="next">
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</div>
<div class="container">
    @php($i=2)
    @foreach($categorys as $category)
        @if($category->category_name!='Puja Store')
        <h4 class="arri-head"><a href="{{ route('itemsShowByCategory') }}/{{ $category->category_url }}">{{ $category->category_name }}</a></h4>
        <div class="arrival">
            <ul id="flexisel{{$i}}">
                @if(count($subcategorys) != 0)
                    @foreach ($subcategorys as $key => $subcategory)
                    @if($subcategory->category_id == $category->id)
                        @if($subcategory->image == '')
                            {{!! $img = 'noPhoto.jpg' !!}}
                        @else
                            {{!! $img = $subcategory->image !!}}
                        @endif
                        <li>
                            <div class="arrival-area">
                                <a href="{{ route('itemsShowBySubCategory') }}/{{ $category->category_url }}/{{ $subcategory->sub_category_url }}">
                                <img class="img-fluid" src="{{asset('images/'.$img)}}">
                                <h4>
                                    @php ($sub_name = explode(',',$subcategory->sub_category_name))
                                    @foreach($sub_name as $sub)
                                        {{ $sub }}
                                        @if(count($sub_name)>1)
                                            <br>
                                        @endif
                                    @endforeach
                                </h4>
                                </a>
                            </div>
                        </li>

                    @endif
                    @endforeach
                @endif
            </ul>
        </div>
        <div class="clearout"></div>
        @php($i++)
        @endif

    @endforeach
</div>
@endsection
