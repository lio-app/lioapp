<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{Setting::get('site_title')}}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body style="background-color: #f8f8f8;" >
    <div class="loginLayout"></div>
    <div class="main"></div>
    <div id="app">
        <!-- <header id="header" class="header">
            <a href="#" class="logo">
                <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name', '') }}">
                 <span>{{Setting::get('site_title')}}</span>
            </a>
            <nav id="nav-primary" class="nav-primary">
                <ul class="nav-menu my_navbar">
                    <li class="my_coin_box">
                        <a href="{{ route('login') }}" title="Login">
                            Login
                        </a>
                    </li>
                    <li class="my_coin_box">
                        <a href="{{ route('register') }}" title="Register">
                            Register
                        </a>
                    </li>
                </ul>
            </nav>

            <nav class="mobile_nav">
                <div class="nav-xbootstrap">
                    <div class="over-lay-pro">
                         <div class="over-lay"></div>
                    </div>
                   
                    <ul>
                        <li><a href="{{ url('/home') }}">Home</a></li>
                        <li><a href="{{ route('login') }}">Login</a>
                            
                        </li>
                        <li><a href="{{ route('register') }}" >Sign Up</a>
                        </li>
                    </ul>
                </div>
                <div class="nav-bg-xbootstrap">
                    <div class="navbar-xbootstrap"> <span></span> <span></span> <span></span> </div>
                    <a href="#" class="title-mobile"><img src="{{asset('img/logo.png')}}" alt="logo" class="img-responsive text-center"></a>
                </div>
            </nav>

        </header> -->
        @include('common.notify')
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>

    <script src="{{ asset('js/bundle.js') }}"></script>

    <script src="{{ asset('js/index.js') }}"></script>
    
    <script src='https://www.google.com/recaptcha/api.js'></script>
<script>
    jQuery(document).ready(function($){
    //open popup
    $('.cd-popup-trigger').on('click', function(event){
        event.preventDefault();
        $('.cd-popup').addClass('is-visible');
    });
    
    //close popup
    $('.cd-popup').on('click', function(event){
        if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup') ) {
            event.preventDefault();
            $(this).removeClass('is-visible');
        }
    });
    //close popup when clicking the esc keyboard button
    $(document).keyup(function(event){
        if(event.which=='27'){
            $('.cd-popup').removeClass('is-visible');
        }
    });
});
</script>

<script>
        
    $('.navbar-xbootstrap').click(function(){
    
        $('.nav-xbootstrap').toggleClass('visible');
        $('.main').toggleClass('cover-bg');
    });

    $(".main").click(function(){

       $('.main').toggleClass('cover-bg');
        $(".nav-xbootstrap").removeClass('visible');
    });

</script>
@yield('scripts')
</body>
</html>
