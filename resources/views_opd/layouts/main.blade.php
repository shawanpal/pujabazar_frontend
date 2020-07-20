<!DOCTYPE html>
<html  lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="../../favicon.ico">
    <title>Puja Bazar</title>
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
</head>

<body>
    <div class="head-search">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="row">
                        <div class="logo">
                        <a href="{{ route('home') }}">
                            <img src="{{asset('images/logo.png')}}" class="img-responsive" alt="puja_bazar">
                        </a>
                        </div>
                    </div>
                </div>
                {{--<div class="col-md-2">
                    <div class="row">
                        <div class="dropdown">
                            <button class="dropbtn"><span><i class="fa fa-map-marker"></i></span>&#xA0;
                            {!! $locations !!}

                             <span><i class="fa fa-angle-down"></i></span> </button>
                            <div class="dropdown-content">
                                <div class="white-area">
                                    <h6>Where do you want the delivery?</h6>
                                </div>
                                <div class="grey-area row">
                                    <form method="post" action="action('ViewController@index')">
                                        @csrf
                                    @foreach($states as $state)
                                        @if($state->name == Session::get('location'))
                                            <button type="button" class="btn btn-outline-success disabled m-1">{{ $state->name }}</button>
                                        @else
                                            <button type="button" class="btn btn-outline-dark m-1" id="set-state{{ $state->name }}" value="{{ $state->name }}">{{ $state->name }}</button>
                                        @endif
                                    @endforeach
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="head-lang">
                        <div id="google_translate_element"></div>
                    </div>
                </div>--}}
                <div class="col-md-6">
                    <div id="imaginary_container">
                    <form method="GET" action="{{ route('search') }}">
                        <div class="input-group stylish-input-group">
                            <input type="text" class="flipkart-navbar-input col-xs-10" id="look_for" name="term" placeholder="Search by Name, Details or Code">
                            <input type="text" class="flipkart-navbar-input col-xs-10" id="look_for_auto" disabled="disabled" value="" />
                            <button type="submit" class="flipkart-navbar-button col-xs-2">
                                   <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                            <ul class="flipkart-navbar-input col-xs-10 Suggestion" id="Suggestion"></ul>
                        </div>
                    </form>
                    </div>
                </div>
                <div class="col-md-3">

                    <div class="">

                        <div class="sign-up">
                            <div class="col-md-6">
                                <div class="">
                                    @guest
                                    <div class="hd-acnt bord" data-toggle="modal" data-target="#loginModal">
                                        <p>My Account</p>
                                        <p class="smin">
                                            Login / Sign Up
                                            <span>
                                                <a href="javascript:void(0)"><i class="fa fa-angle-down"></i></a>
                                            </span>
                                        </p>
                                    </div>
                                    @else
                                    <div class="hd-acnt bord nav-item dropdown">
                                      @if(Auth::user()->role == 'Buyer')
                                          <a href="{{ route('buyer') }}" id="navbarDropdown">
                                      @elseif(Auth::user()->role == 'Admin')
                                          <a href="{{ route('admin') }}" id="navbarDropdown">
                                      @elseif(Auth::user()->role == 'Seller')
                                          <a href="{{ route('seller') }}" id="navbarDropdown">
                                      @endif

                                            <p class="smin">
                                                {{ Auth::user()->name }}
                                                <span>
                                                    <i class="fa fa-angle-down"></i>
                                                </span>
                                            </p>
                                        </a>




                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>


                                    </div>
                                    @endguest
                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="">
                                @if(Cart::count()!='')
                                    <div class="hd-acnt">
                                        <a href="{{ route('cart') }}">
                                        <p class="cart">
                                        <span class="no-items">{{ Cart::count() }}</span>
                                        <span><img src="{{asset('images/cart_2x-1657d3d.png')}}" class="img-fluid cart-icon"/>
                                        </span> <span class="price-denote"><i class="fa fa-inr" aria-hidden="true"></i><span class="price-counter">{{ Cart::total() }}</span></span>
                                        </p>
                                        </a>
                                    </div>
                                @else
                                    <div class="hd-acnt">
                                      <a href="{{ route('cart') }}">
                                        <p class="cart">
                                            <span>

                                        <img src="{{asset('images/cart_2x-1657d3d.png')}}" class="img-fluid cart-icon"/>

                                    </span>&#xA0; My Cart</p></a>
                                    </div>
                                @endif
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-dark bg-dark navbar-expand-md">
        <div class="container">
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#myNavbar">&#x2630;</button>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav wow fadeInRight" data-wow-delay="0.4s">
                    <li class="hvr-underline-from-left nav-item">
                        <a href="{{ route('home') }}" class="nav-link">Home</a>
                    </li>
                    @foreach($categorys as $category)
                        <li class="hvr-underline-from-left nav-item dropdown">
                                <a href="{{ route('itemsShowByCategory') }}/{{ $category->category_url }}" class="nav-link">{{ $category->category_name }}</a>
                        <ul class="dropdown-menu">
                        @foreach($subcategorys as $subcategory)
                            @if($subcategory->category_id == $category->id)

                                <li><a class="dropdown-item" href="{{ route('itemsShowBySubCategory') }}/{{ $category->category_url }}/{{ $subcategory->sub_category_url }}">{{ $subcategory->sub_category_name }}</a></li>

                            @endif
                        @endforeach
                        </ul>
                        </li>
                    @endforeach


                </ul>
            </div>
        </div>
    </nav>


    @yield('contents')

    <section id="nwslttr">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <h3>Book Your Total Religious Event Here</h3>
                    <p>We offer you the most suitable package</p>
                </div>
                <div class="col-md-6">
                    <div class="nws">
                        <div class="input-group">
                            <select class="form-control nws-eml" id="footer-category">
                                <option value="">Select Any</option>
                                <option value="{{ route('home') }}">Home</option>
                                @if(count($subcategorys) != 0)
                                    @foreach ($subcategorys as $key => $subcategory)
                                        @if($subcategory->image == '')
                                        <option style="background-image:url({{asset('images/noPhoto.jpg')}});" value="{{ route('itemsShowBySubCategory') }}/{{ $subcategory->category_url }}/{{ $subcategory->sub_category_url }}">{{ $subcategory->sub_category_name }}</option>
                                        @else
                                        <option style="background-image:url({{asset('images/'.$subcategory->image)}});" value="{{ route('itemsShowBySubCategory') }}/{{ $subcategory->category_url }}/{{ $subcategory->sub_category_url }}">{{ $subcategory->sub_category_name }}</option>
                                        @endif

                                    @endforeach
                                @endif
                            </select>
                            {{--<input type="email" class="form-control nws-eml" placeholder="Enter your email">
                            <span class="input-group-btn">
                                <button class="btn btn-theme nws-btn" type="submit">Subscribe</button>
                            </span>--}}
                        </div>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </section>
    <section id="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="fot-logo">
                        <a href="{{ route('home') }}">
                            <img src="{{asset('images/invoice-logo.png')}}" class="img-fluid" alt="Puja Bazar">
                        </a>
                    </div>
                    <ul class="list-unstyled addrs">
                         @foreach($states as $state)
                            @if($state->name == Session::get('location'))
                                @if($state->address!='')
                                <li>
                                    <img src="{{asset('images/map.png')}}" class="img-fluid pay-icon add-icon" alt="">&#xA0;
                                    {{ $state->address }}
                                </li>
                                @endif
                                @if($state->phone!='')
                                <li>
                                    <img src="{{asset('images/headphn.png')}}" class="img-fluid pay-icon" alt="">&#xA0;
                                    {{ $state->phone }}
                                </li>
                                @endif
                            @endif
                        @endforeach

                        {{--<li> <img src="{{asset('images/emil.png')}}" class="img-fluid pay-icon" alt="">&#xA0;info@pujabazar.com</li>--}}
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="map-area map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d117776.3405477344!2d88.4715!3d22.709254!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39f898ad23caba2d%3A0x580ab1dc553a5a18!2sKolkata%2C+West+Bengal+700127!5e0!3m2!1sen!2sin!4v1520856711568" width="100%" height="190" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                    <ul class="foot-social list-inline">
                        <li class="list-inline-item"><a href="https://wwww.facebook.com/pujabazar/" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a> </li>
                        <li class="list-inline-item"><a href="https://twitter.com/BazarPuja/" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a> </li>
                        {{--<li class="list-inline-item"><a href=""><i class="fa fa-google-plus" aria-hidden="true"></i></a> </li>
                        <li class="list-inline-item"><a href=""><i class="fa fa-instagram" aria-hidden="true"></i></a> </li>
                        <li class="list-inline-item"><a href=""><i class="fa fa-pinterest" aria-hidden="true"></i></a> </li>--}}
                    </ul>
                </div>
                <div class="col-md-3 col-6">
                    <h4>Information</h4>
                    <ul class="list-unstyled imprnt">
                        <li><i class="fa fa-circle-o" aria-hidden="true"></i>&#xA0;<a href="{{ route('home') }}">Home</a> </li>
                        <li><i class="fa fa-circle-o" aria-hidden="true"></i>&#xA0;<a href="{{ route('about') }}">About Us</a> </li>
                        <li><i class="fa fa-circle-o" aria-hidden="true"></i>&#xA0;<a href="{{ route('blogs') }}">Blog</a> </li>
                        <li><i class="fa fa-circle-o" aria-hidden="true"></i>&#xA0;<a href="{{ route('contact') }}">Contact</a> </li>
                        <li><i class="fa fa-circle-o" aria-hidden="true"></i>&#xA0;<a href="{{ route('privacy') }}">Privacy Policy</a> </li>
                        <li><i class="fa fa-circle-o" aria-hidden="true"></i>&#xA0;<a href="{{ route('terms') }}">Terms and Conditions</a> </li>
                        <li><i class="fa fa-circle-o" aria-hidden="true"></i>&#xA0;<a href="{{ route('return') }}">Warranties & Return</a> </li>
                        <li><i class="fa fa-circle-o" aria-hidden="true"></i>&#xA0;<a href="{{ route('contact') }}">Support 24/7 page</a> </li>
                    </ul>
                </div>
                <div class="col-md-2 col-6">
                    <h4>My Account</h4>
                    <ul class="list-unstyled imprnt">

                        <!-- Authentication Links -->
                        @guest
                            <li><i class="fa fa-circle-o" aria-hidden="true"></i>&#xA0;<a href="{{ route('login') }}">{{ __('Login') }}</a></li>
                            <li><i class="fa fa-circle-o" aria-hidden="true"></i>&#xA0;<a href="{{ route('register') }}">{{ __('Register') }}</a></li>

                        @else
                            <li class="nav-item dropdown">
                              @if(Auth::user()->role == 'Buyer')
                                  <a href="{{ route('buyer') }}" id="navbarDropdown">
                              @elseif(Auth::user()->role == 'Admin')
                                  <a href="{{ route('admin') }}" id="navbarDropdown">
                              @elseif(Auth::user()->role == 'Seller')
                                  <a href="{{ route('seller') }}" id="navbarDropdown">
                              @endif
                                    <p class="smin">
                                        {{ Auth::user()->name }}
                                        <span>
                                            <i class="fa fa-angle-down"></i>
                                        </span>
                                    </p>
                                </a>
                                {{--<a id="navbarDropdown" class="nav-link dropdown-toggle" href="{{ route('admin') }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>--}}

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest





                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section id="copy">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="cpy-txt">&#xA9; <?php echo date("Y"); ?> Puja Bazar. All Rights Reserved.</p>
                </div>
                <div class="col-md-6">
                    <div class="paymnt">
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <a href=""><img src="{{asset('images/pay1.png')}}" class="img-fluid"></a>
                            </li>
                            <li class="list-inline-item">
                                <a href=""><img src="{{asset('images/pay2.png')}}" class="img-fluid"></a>
                            </li>
                            <li class="list-inline-item">
                                <a href=""><img src="{{asset('images/pay3.png')}}" class="img-fluid"></a>
                            </li>
                            <li class="list-inline-item">
                                <a href=""><img src="{{asset('images/pay4.png')}}" class="img-fluid"></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="coming">
        <img src="{{asset('images/comming soon.png')}}" class="img-fluid coming-soon" alt="">
    </div>


{{-- Login modal Start --}}
<div class="modal" id="loginModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Login') }}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form id="login-form" method="POST" action="{{ route('custom-login') }}">
                @csrf

                <div class="form-group row">
                    <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                        <script> $("#modal-open").click(); </script>
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                        @if ($errors->has('password'))
                        <script> $("#modal-open").click(); </script>
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Login') }}
                        </button>

                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <div id="login_error"></div>
            <input type="hidden" id="open-modal" data-toggle="modal" data-target="#registerModal">
            <a class="btn btn-link" href="{{ route('register') }}">
                {{ __('Register') }}
            </a>
            {{--<button type="button" class="btn btn-outline-light text-dark" id="register">Register</button>--}}
        </div>

      </div>
    </div>
</div>
{{-- Register modal Start --}}
<div class="modal" id="registerModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Register') }}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form id="register-form" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <input type="hidden" id="role" name="role" value="Buyer">
                {{--<div class="form-group row">
                    <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('Role') }}</label>

                    <div class="col-md-6">
                        <select id="role" class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role" required autofocus disabled>
                            <option value="">Select any role</option>
                            <option value="Buyer" {{ old('role')=='Buyer' ? 'selected' : '' }} selected>User</option>
                            <option value="Seller" {{ old('role')=='Seller' ? 'selected' : '' }}>Seller</option>
                        </select>
                        @if ($errors->has('role'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('role') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>--}}

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>

                    <div class="col-md-6">
                        <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}">

                        @if ($errors->has('phone'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Register') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <div class="col" id="register_error"></div>
            <input type="hidden" id="modal-open" data-toggle="modal" data-target="#loginModal">
            <button type="button" class="btn btn-outline-light text-dark" id='login'>Login</button>
        </div>

      </div>
    </div>
</div>


{{-- FrontEnd modal Start --}}
@isset($id)
<div class="modal fade" id="bookingModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Book me now</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <form method="POST" action="{{ action('ViewController@takeBooking') }}" enctype='multipart/form-data'>
            @csrf
            <div class="modal-body">
                <input type="hidden" id="book_id" name="booking_id" value="{{ $id }}">
              <div class="row">
                <div class="col">
                    <label for="usr">Name:</label>
                    <input type="text" class="form-control" id="my_nm" name="name">
                </div>
                <div class="col">
                    <label for="usr">Email:</label>
                    <input type="text" class="form-control" id="my_eml" name="email">
                </div>
                <div class="col">
                    <label for="usr">Phone:</label>
                    <input type="text" class="form-control" id="my_pho" name="phone">
                </div>
              </div>
              <div class="clearout"></div>

              <div class="row">
                <div class="col">
                    <label for="my_qry">Have you any query ? (<span id="txtleng">400</span>):</label>
                    <textarea class="form-control" rows="5" id="my_text" name="customer_qry" maxlength="400" onkeyup="textCounter('my_text','txtleng',400);"></textarea>
                </div>
              </div>
              <div class="clearout"></div>
              <div class="row">
                <div class="col" id="booking_alert">

                </div>
              </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" id="submitBooking">SUBMIT BOOKING</button>
            </div>
        </form>
      </div>
    </div>
</div>

<div class="modal fade" id="videoModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">MY ALL VIDEOS</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" id="all_video">

        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="reviewModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">WRITE A REVIEW</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <form method="POST" action="" enctype='multipart/form-data'>
            @csrf
        <div class="modal-body">
            <input type="hidden" id="item_id" name="review_for" value="{{ $id }}">
          <div class="row">
            <div class="col">
                <label for="usr">Name:</label>
                <input type="text" class="form-control" id="my_nam" name="name">
            </div>
            <div class="col">
                <label for="usr">Email:</label>
                <input type="text" class="form-control" id="my_em" name="email">
            </div>
            <div class="col">
                <label for="usr">Phone:</label>
                <input type="text" class="form-control" id="my_ph" name="phone">
            </div>
          </div>
          <div class="clearout"></div>
          <div class="row">
             <div class="col">
                <label>Rating:</label><br>
                <fieldset class="rating">
                    <input type="radio" id="star5" name="rating" value="5" />
                    <label class="full" for="star5" title="5 stars"></label>

                    <input type="radio" id="star4" name="rating" value="4" />
                    <label class = "full" for="star4" title="4 stars"></label>

                    <input type="radio" id="star3" name="rating" value="3" />
                    <label class="full" for="star3" title="3 stars"></label>

                    <input type="radio" id="star2" name="rating" value="2" />
                    <label class="full" for="star2" title="2 stars"></label>

                    <input type="radio" id="star1" name="rating" value="1" />
                    <label class="full" for="star1" title="1 star"></label>
                </fieldset>
            </div>
          </div>
          <div class="row">
            <div class="col">
                <label for="my_comm">Post Review Below (<span id="qlength">400</span>):</label>
                <textarea class="form-control" rows="5" id="my_comm" name="review" maxlength="400" onkeyup="textCounter('my_comm','qlength',400);"></textarea>
            </div>
          </div>
          <div class="clearout"></div>
          <div class="row">
            <div class="col" id="review_alert">

            </div>
          </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="submitReview">SUBMIT REVIEW</button>
        </div>
        </form>
      </div>
    </div>
</div>
<div class="modal fade" id="reviewshow">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">All REVIEW</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body" id="all_review">

          {!! $allReviews !!}


        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <!-- <button type="submit" class="btn btn-primary" id="submitProductReview">SUBMIT REVIEW</button> -->
        </div>
      </div>
    </div>
</div>
@endisset
{{-- FrontEnd modal End --}}

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>

<!--Start of Tawk.to Script-->
{{--<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/5b4ee6d291379020b95ef7fa/default';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();

</script>--}}

</body>

</html>
