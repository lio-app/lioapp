<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{Setting::get('site_title')}}</title>

        <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,600" rel="stylesheet">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Work Sans', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
                color:#fff;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #fff;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .intro-bg {
                /* background: url(../img/b-1.jpg) no-repeat scroll center center; */
                background: #1d429d;
                background-size: cover;
                width: 100%;
                display: inline-block;
                top: 0;
                z-index: 1;
                position: relative;
            }
            .welcome-bg {
                position: absolute;
                top: 0px;
                bottom: 0px;
                left: 0px;
                right: 0px;
                background-color: rgba(0,0,0,.5);
            }
        </style>
    </head>
    <body>
        <div class="intro-bg">
            <div class="welcome-bg"></div>
            <div class="flex-center position-ref full-height">
                @if (Route::has('login'))
                    <div class="top-right links">
                        @if (Auth::check())
                            <a href="{{ url('/home') }}">Home</a>
                        @else
                            <a href="{{ url('/login') }}">Login</a>
                            <a href="{{ url('/register') }}">Register</a>
                        @endif
                    </div>
                @endif

                <div class="content">
                    <div class="title m-b-md">
                        {{Setting::get('site_title')}} WALLET
                    </div>

                    <div class="links">
                       
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
