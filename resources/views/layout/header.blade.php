
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <!-- Meta Tag -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name='copyright' content=''>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Title Tag  -->
        <title>Puja Bazar</title>
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="images/favicon.png">
        <!-- Web Font -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    </head>
    <body class="js">

        <!-- Preloader -->
        <!--              <div class="preloader">
                            <div class="preloader-inner">
                                <div class="preloader-icon">
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </div>-->
        <!--   End Preloader-->


        <!-- Header -->
        <header class="header shop">
            <!-- Topbar -->
            <div class="topbar">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-12">
                            <!-- Top Left -->
                            <div class="top-left">
                                <ul class="list-main">
                                    <li><i class="ti-headphone-alt"></i> {{$state->phone}}</li>
                                </ul>
                            </div>
                            <!--/ End Top Left -->
                        </div>
                        <div class="col-lg-8 col-md-12 col-12">
                            <!-- Top Right -->
                            <div class="right-content">
                                <ul class="list-main">
                                    <li><i class="ti-location-pin"></i> <a href="{{url('/store-location')}}">Store location</a></li>
                                    @auth
                                    <li><i class="ti-user"></i> <a href="{{url('/my-account')}}">My account</a></li>
                                    <li><i class="ti-export"></i> <a href="{{url('/signout')}}">Sign Out</a></li>
                                    @endauth
                                    @guest
                                    <li><i class="ti-power-off"></i><a href="{{url('/signin')}}">Sign In</a></li>
                                    <li><i class="ti-check-box"></i><a href="{{url('/signup')}}">Sign Up</a></li>
                                    @endguest
                                </ul>
                            </div>
                            <!-- End Top Right -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Topbar -->
            <div class="middle-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-12">
                            <!-- Logo -->
                            <div class="logo">
                                <a href="{{url('/')}}"><img src="{{asset('images/logo.png')}}" alt="logo"></a>
                            </div>
                            <!--/ End Logo -->
                            <!-- Search Form -->
                            <div class="search-top">
                                <div class="top-search"><a href="#0"><i class="ti-search"></i></a></div>
                                <!-- Search Form -->
                                <div class="search-top">
                                    <form class="search-form">
                                        <input type="text" placeholder="Search here..." name="search">
                                        <button value="search" type="submit"><i class="ti-search"></i></button>
                                    </form>
                                </div>
                                <!--/ End Search Form -->
                            </div>
                            <!--/ End Search Form -->
                            <div class="mobile-nav"></div>
                        </div>
                        <div class="col-lg-8 col-md-7 col-12">
                            <div class="search-bar-top">
                                <div class="search-bar">
                                    <select>
                                        <option selected="selected">All Category</option>
                                        <option>watch</option>
                                        <option>mobile</option>
                                        <option>kid’s item</option>
                                    </select>
                                    <form>
                                        <input name="search" placeholder="Search Products Here....." type="search">
                                        <button class="btnn"><i class="ti-search"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-3 col-12">
                            <div class="right-bar">
                                <!-- Search Form -->
                                <div class="sinlge-bar shopping">
                                    <a href="javascript:void(0)" class="single-icon"><i class="ti-bag"></i> <span class="total-count">{{Cart::count()}}</span></a>
                                    @if(Cart::count() > 0)
                                    <!-- Shopping Item -->
                                    <div class="shopping-item">
                                        <div class="dropdown-cart-header">
                                            <span>{{Cart::count()}} Items</span>
                                            <a href="{{url('/cart')}}">View Cart</a>
                                        </div>
                                        @if(Cart::count() > 0)
                                        @php $items = json_decode(Cart::content()); @endphp
                                        <ul class="shopping-list">
                                            @foreach ($items as $item)
                                            <li>
                                                <a href="javascript:void(0)" class="remove" title="Remove this item"><i class="fa fa-remove"></i></a>
                                                @if($item->options->type == 'product')
                                                <a class="cart-img" href="{{url('/product-details/'.$item->options->code)}}"><img src="{{asset('images/'.$item->options->image)}}"></a>
                                                <h4><a href="{{url('/product-details/'.$item->options->code)}}">{{$item->name}} - {{$item->options->size.' '.$item->options->size_unit}}{{$item->options->weight.' '.$item->options->weight_unit}}</a></h4>
                                                @endif
                                                <p class="quantity">{{$item->qty}}x - <span class="amount">₹{{number_format($item->price,2)}}</span></p>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                        <div class="bottom">
                                            <div class="total">
                                                <span>Total</span>
                                                <span class="total-amount">₹{{Cart::total()}}</span>
                                            </div>
                                            <a href="{{url('/checkout')}}" class="btn animate">Checkout</a>
                                        </div>
                                    </div>
                                    <!--/ End Shopping Item -->
                                    @else
                                    <div class="shopping-item">
                                        <p>No items in your cart.</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Header Inner -->
            <div class="header-inner">
                <div class="container">
                    <div class="cat-nav-head">
                        <div class="row">
                            @if (Request::is('/'))
                            <div class="col-lg-3">
                                <div class="all-category">
                                    <h3 class="cat-heading"><i class="fa fa-bars" aria-hidden="true"></i>CATEGORIES</h3>
                                    <ul class="main-category">
                                        <li><a href="#">New Arrivals <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                            <ul class="sub-category">
                                                <li><a href="#">accessories</a></li>
                                                <li><a href="#">best selling</a></li>
                                                <li><a href="#">top 100 offer</a></li>
                                                <li><a href="#">sunglass</a></li>
                                                <li><a href="#">watch</a></li>
                                                <li><a href="#">man’s product</a></li>
                                                <li><a href="#">ladies</a></li>
                                                <li><a href="#">westrn dress</a></li>
                                                <li><a href="#">denim </a></li>
                                            </ul>
                                        </li>
                                        <li class="main-mega"><a href="#">best selling <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                            <ul class="mega-menu">
                                                <li class="single-menu">
                                                    <a href="#" class="title-link">Shop Kid's</a>
                                                    <div class="image">
                                                        <img src="https://via.placeholder.com/225x155" alt="#">
                                                    </div>
                                                    <div class="inner-link">
                                                        <a href="#">Kids Toys</a>
                                                        <a href="#">Kids Travel Car</a>
                                                        <a href="#">Kids Color Shape</a>
                                                        <a href="#">Kids Tent</a>
                                                    </div>
                                                </li>
                                                <li class="single-menu">
                                                    <a href="#" class="title-link">Shop Men's</a>
                                                    <div class="image">
                                                        <img src="https://via.placeholder.com/225x155" alt="#">
                                                    </div>
                                                    <div class="inner-link">
                                                        <a href="#">Watch</a>
                                                        <a href="#">T-shirt</a>
                                                        <a href="#">Hoodies</a>
                                                        <a href="#">Formal Pant</a>
                                                    </div>
                                                </li>
                                                <li class="single-menu">
                                                    <a href="#" class="title-link">Shop Women's</a>
                                                    <div class="image">
                                                        <img src="https://via.placeholder.com/225x155" alt="#">
                                                    </div>
                                                    <div class="inner-link">
                                                        <a href="#">Ladies Shirt</a>
                                                        <a href="#">Ladies Frog</a>
                                                        <a href="#">Ladies Sun Glass</a>
                                                        <a href="#">Ladies Watch</a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a href="#">accessories</a></li>
                                        <li><a href="#">top 100 offer</a></li>
                                        <li><a href="#">sunglass</a></li>
                                        <li><a href="#">watch</a></li>
                                        <li><a href="#">man’s product</a></li>
                                        <li><a href="#">ladies</a></li>
                                        <li><a href="#">westrn dress</a></li>
                                        <li><a href="#">denim </a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-9 col-12">
                                @else
                                <div class="col-lg-12 col-12">
                                    @endif
                                    <div class="menu-area">
                                        <!-- Main Menu -->
                                        <nav class="navbar navbar-expand-lg">
                                            <div class="navbar-collapse">	
                                                <div class="nav-inner">	
                                                    <ul class="nav main-menu menu navbar-nav">
                                                        <li class="active"><a href="#">Home</a></li>
                                                        <li><a href="#">Product</a></li>												
                                                        <li><a href="#">Service</a></li>
                                                        <li><a href="#">Shop<i class="ti-angle-down"></i><span class="new">New</span></a>
                                                            <ul class="dropdown">
                                                                <li><a href="shop-grid.html">Shop Grid</a></li>
                                                                <li><a href="cart.html">Cart</a></li>
                                                                <li><a href="checkout.html">Checkout</a></li>
                                                            </ul>
                                                        </li>
                                                        <li><a href="#">Pages</a></li>									
                                                        <li><a href="#">Blog<i class="ti-angle-down"></i></a>
                                                            <ul class="dropdown">
                                                                <li><a href="blog-single-sidebar.html">Blog Single Sidebar</a></li>
                                                            </ul>
                                                        </li>
                                                        <li><a href="contact.html">Contact Us</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </nav>
                                        <!--/ End Main Menu -->	
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ End Header Inner -->

        </header>
        <!--/ End Header -->