<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{Setting::get('site_title')}}</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link href="{{ asset('css/cloud.fontawesome.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,600" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="{{ asset('css/dataTables.bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/responsive.bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <style>
      /*form styles*/
            #msform {
                text-align: center;
                position: relative;
                margin-top: 80px;
                margin-bottom: 80px;
            }
            .b_g{
                background: #585050;
                min-height: 100vh;
            }

            #msform fieldset {
                background: white;
                border: 0 none;
                border-radius: 0px;
                box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
                padding: 20px 30px;
                box-sizing: border-box;
                width: 80%;
                margin: 0 10%;
                z-index: 1;

                /*stacking fieldsets above each other*/
                position: relative;
            }

            /*Hide all except first fieldset*/
            #msform fieldset:not(:first-of-type) {
                display: none;
            }

            /*inputs*/
            #msform input, #msform textarea {
                padding: 15px;
                border: 1px solid #ccc;
                border-radius: 0px;
                margin-bottom: 10px;
                width: 100%;
                box-sizing: border-box;
                font-family: 'Work Sans', sans-serif;
                color: #2C3E50;
                font-size: 13px;
            }

            #msform input:focus, #msform textarea:focus {
                -moz-box-shadow: none !important;
                -webkit-box-shadow: none !important;
                box-shadow: none !important;
                border: 1px solid #fff;
                outline-width: 0;
                transition: All 0.5s ease-in;
                -webkit-transition: All 0.5s ease-in;
                -moz-transition: All 0.5s ease-in;
                -o-transition: All 0.5s ease-in;
            }

            /*buttons*/
            #msform .action-button {
                width: 100px;
                background:#1d429d;
                font-weight: bold;
                color: white;
                border: 0 none;
                border-radius: 25px;
                cursor: pointer;
                padding: 10px 5px;
                margin: 10px 5px;
            }

            #msform .action-button:hover, #msform .action-button:focus {
                transition:all 0.2s ease-in;
                background:#1d429d;
            }

            #msform .action-button-previous {
                width: 100px;
                background: #C5C5F1;
                font-weight: bold;
                color: white;
                border: 0 none;
                border-radius: 25px;
                cursor: pointer;
                padding: 10px 5px;
                margin: 10px 5px;
            }

            #msform .action-button-previous:hover, #msform .action-button-previous:focus {
                /*box-shadow: 0 0 0 2px white, 0 0 0 3px #C5C5F1;*/
                transition:all 0.2s ease-in;
            }

            /*headings*/
            .fs-title {
                font-size: 18px;
                text-transform: uppercase;
                color: #f5bd2f -webkit-gradient(linear, left top, left bottom, from(#f5bd2f), to(#ae8913)) no-repeat;
                margin-bottom: 10px;
                letter-spacing: 2px;
                font-weight: bold;
            }

            .fs-subtitle {
                font-weight: normal;
                font-size: 13px;
                color: #666;
                margin-bottom: 20px;
            }

            /*progressbar*/
            #progressbar {
                margin-bottom: 30px;
                overflow: hidden;
                /*CSS counters to number the steps*/
                counter-reset: step;
            }
            p.description {
                text-align: left;
            }
            p.red {
                text-align: left;
                color: red;
            }
            #progressbar li {
                list-style-type: none;
                color: #fff;
                text-transform: uppercase;
                font-size: 12px;
                width: 33.33%;
                float: left;
                position: relative;
                letter-spacing: 1px;
                font-weight:500;
            }

            #progressbar li:before {
                content: counter(step);
                counter-increment: step;
                width: 24px;
                height: 24px;
                line-height: 26px;
                display: block;
                font-size: 12px;
                color: #333;
                background: white;
                border-radius: 25px;
                margin: 0 auto 10px auto;
            }
              .modal-confirm {    
                color: #434e65;
                width: 525px;
              }
              .modal-confirm .modal-content {
                padding: 20px;
                font-size: 16px;
                border-radius: 5px;
                border: none;
              }
              .modal .modal-dialog .modal-content {
                padding: 0 18px;
                border-radius: 0;
                border: 2px solid #84bce0;
            }
            .modal-confirm .modal-header {
                background: #47c9a2;
                border-bottom: none !important;
                position: relative;
                text-align: center;
                margin: -20px -20px 0;
                border-radius: 0px;
                padding: 35px !important;
            }
              .modal-confirm h4 {
                text-align: center;
                font-size: 36px;
                margin: 10px 0;
              }
              .modal-confirm .form-control, .modal-confirm .btn {
                min-height: 40px;
                border-radius: 3px; 
              }
              .modal-confirm .close {
                    position: absolute;
                top: 15px;
                right: 15px;
                color: #fff;
                text-shadow: none;
                opacity: 0.5;
              }
              .modal-confirm .close:hover {
                opacity: 0.8;
              }
              .modal-confirm .icon-box {
                color: #fff;    
                width: 95px;
                height: 95px;
                display: inline-block;
                border-radius: 50%;
                z-index: 9;
                border: 5px solid #fff;
                padding: 15px;
                text-align: center;
              }
              .modal-confirm .icon-box i {
                font-size: 64px;
                margin: -4px 0 0 -4px;
              }
              .modal-confirm.modal-dialog {
                margin-top: 80px;
              }
                .modal-confirm .btn {
                    color: #fff;
                    border-radius: 4px;
                background: #eeb711;
                text-decoration: none;
                transition: all 0.4s;
                    line-height: normal;
                border-radius: 30px;
                margin-top: 10px;
                padding: 6px 20px;
                    border: none;
                }
              .modal-confirm .btn:hover, .modal-confirm .btn:focus {
                background: #eda645;
                outline: none;
              }
              .modal-confirm .btn span {
                margin: 1px 3px 0;
                float: left;
              }
              .modal-confirm .btn i {
                margin-left: 1px;
                font-size: 20px;
                float: right;
              }
              .trigger-btn {
                display: inline-block;
                margin: 100px auto;
              }
            /*progressbar connectors*/
            #progressbar li:after {
                content: '';
                width: 100%;
                height: 2px;
                background: white;
                position: absolute;
                left: -50%;
                top: 9px;
                z-index: -1; /*put it behind the numbers*/
            }

            #progressbar li:first-child:after {
                /*connector not needed before the first step*/
                content: none;
            }

            /*marking active/completed steps green*/
            /*The number of the step and the connector before it = green*/
            #progressbar li.active:before, #progressbar li.active:after {
                background:#1d429d;
                color: white;
            }


            /* Not relevant to this form */
            .dme_link {
                margin-top: 30px;
                text-align: center;
            }
              .message {
               position: absolute;
                top: 200px !important;
                left: 50%;
                transform: translate(-50%, 0%);
                width: 450px;
                background: white;
                border-radius: 8px;
                padding: 30px;
                text-align: center;
                font-weight: 300;
                color: #2c2928;
                box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.1);
                transition: top 0.3s cubic-bezier(0.31, 0.25, 0.5, 1.5), opacity 0.2s ease-in-out;
              }
              .message .check {
                position: absolute;
                top: 0;
                left: 50%;
                transform: translate(-50%, -50%) scale(4);
                width: 120px;
                height: 120px;
                background: #71c341;
                color: white;
                font-size: 3.8rem;
                padding-top: 34px;
                border-radius: 50%;
                opacity: 0;
                transition: transform 0.2s 0.25s cubic-bezier(0.31, 0.25, 0.5, 1.5), opacity 0.1s 0.25s ease-in-out;
              }
              .message .scaledown {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
              }
              .message p {
                font-size: 18px;
                margin: 25px 0px;
                padding: 0;
              }
              .message p:nth-child(2) {
                font-size: 2.3rem;
                margin: 40px 0px 0px 0px;
              }
              .message #ok {
                position: relative;
                color: white;
                border: 0;
                background: #71c341;
                width: 100%;
                height: 50px;
                border-radius: 6px;
                font-size: 1.2rem;
                transition: background 0.2s ease;
                outline: none;
              }
              .message #ok:hover {
                background: #8ecf68;
              }
              .message #ok:active {
                background: #5a9f32;
              }

              .comein {
                top: 150px;
                opacity: 1;
              }
            .dme_link a {
                background: #FFF;
                font-weight: bold;
                color: #ee0979;
                border: 0 none;
                border-radius: 25px;
                cursor: pointer;
                padding: 5px 25px;
                font-size: 12px;
            }
            .message{
              display: none;
            }
            .message.comein{
              display: block;
            }
            .dme_link a:hover, .dme_link a:focus {
                background: #C5C5F1;
                text-decoration: none;
            }


            table.currency_table > tbody > tr > td { font-size:2.25rem !important; }

            span.currency-span {
                border: 1px solid;
                border-radius: 4px;
                padding: 8px;
                font-size: 15px;
                font-weight: 600;
            }

    </style>

</head>
<body>
    <div class="main"></div>
    <div id="app">
            <header id="header" class="header">
                <a href="#" class="logo">
                    <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name', 'Cointronix') }}">
                    <!-- <span>{{Setting::get('site_title')}}</span> -->
                </a>
                <nav id="nav-primary" class="nav-primary my_nav">
                    <ul class="nav-menu">
                        <!-- <li class="">
                            <a href="{{url('/home')}}" title="Home">
                                Dashboard
                            </a>
                        </li>
                        <li class="">
                            <a href="{{url('/transactions')}}" title="Transactions">
                                Transactions
                            </a>
                        </li>
                        <li class="">
                            <a href="{{route('networkpage')}}" title="Change Currency">
                                Change Currency
                            </a>
                        </li> -->
                        <!-- <li class="">
                            <a href="{{url('wallet')}}" title="Wallet">
                                Wallet
                            </a>
                        </li> -->
                        <!-- <li class="">
                            <a href="{{url('/security')}}" title="Security Center">
                                Security Center
                            </a>
                        </li>
                        <li class="">
                            <a href="{{url('/support')}}" title="Support">
                                Support
                            </a>
                        </li> -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" style="text-transform: capitalize;">
                                @if(Auth::check()) @if(Auth::user() && isset(Auth::user()->name)) {{ Auth::user()->name }} @endif @endif <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                       
                        <!-- <li class="">
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li> -->
                    </ul>
                </nav>

                <nav class="mobile_nav">
                    <div class="nav-xbootstrap">
                        <ul>
                            <!-- <li><a href="{{url('/home')}}">Home</a></li>
                            <li><a href="{{url('/transactions')}}">Transactions</a>

                            </li>
                            <li >
                                <a href="{{route('networkpage')}}" >
                                    Change Currency
                                </a>
                            </li>
                            <li >
                                <a href="{{url('wallet')}}" >
                                    Wallet
                                </a>
                            </li>
                            <li><a href="{{url('/security')}}">Security Center</a>
                            </li>
                            <li><a href="{{url('/support')}}">Support</a>
                            </li>
                        
                            <li>
                              <a href="{{ route('logout') }}"
                              onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                                  Logout
                              </a>

                              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                  {{ csrf_field() }}
                              </form>
                            </li> -->
                            <li><a href="{{url('/home')}}" title="Dashboard"><i class="fa fa-tachometer" aria-hidden="true"></i>&nbsp;Dashboard</a></li>
                            <li><a href="{{url('/transactions')}}" title="Transactions"><i class="fa fa-exchange" aria-hidden="true"></i>&nbsp;Transaction</a></li>
                            <li><a href="{{route('networkpage')}}" title="Change Currency"><i class="fa fa-usd" aria-hidden="true"></i>&nbsp;Change currency</a></li>
                            <li><a href="{{url('/security')}}" title="Security Center"><i class="fa fa-shield" aria-hidden="true"></i>&nbsp;Security center</a></li>
                            <li><a href="{{url('/support')}}" title="Support"><i class="fa fa-ticket" aria-hidden="true"></i>&nbsp;Support</a></li>
                            <li>
                                <a href="{{ route('logout') }}"
                                  onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="nav-bg-xbootstrap">
                        <div class="navbar-xbootstrap"> <span></span> <span></span> <span></span> </div>
                        <a href="#" class="title-mobile"><img src="{{asset('img/logo.png')}}" alt="logo" class="img-responsive text-center"></a>
                    </div>
                </nav>

            </header>
            <main class="contentBlock">
                <aside class="sidebar">
                    <ul class="sideMenu">
                        <li><a href="{{url('/home')}}" title="Dashboard"><i class="fa fa-tachometer" aria-hidden="true"></i>&nbsp;Dashboard</a></li>
                        <li><a href="{{url('/transactions')}}" title="Transactions"><i class="fa fa-exchange" aria-hidden="true"></i>&nbsp;Transaction</a></li>
                        <li><a href="{{route('networkpage')}}" title="Change Currency"><i class="fa fa-usd" aria-hidden="true"></i>&nbsp;Change currency</a></li>
                        <li><a href="{{url('/security')}}" title="Security Center"><i class="fa fa-shield" aria-hidden="true"></i>&nbsp;Security center</a></li>
                        <li><a href="{{url('/support')}}" title="Support"><i class="fa fa-ticket" aria-hidden="true"></i>&nbsp;Support</a></li>
                        <li>
                            <a href="{{ route('logout') }}"
                              onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                        
                    </ul>
                </aside>
                <main class="contentBox">
                    @include('common.notify')
                    @yield('content')
                    
                </main>
            </main>

            <footer>

                <div class="copyright">
                  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 text-center">
                      <p>© 2018 <a href="./">{{Setting::get('site_title')}}</a></p>
                  </div>
                </div>

            </footer>
            
    </div>
    

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>

    <script src="{{ asset('js/bundle.js') }}"></script>

    <script src="{{ asset('js/index.js') }}"></script>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
     
     <!-- mine -->
     <!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
     <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
     <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script> -->
    <script src="{{ asset('js/cloudfare.jquery.min.js') }}"></script>
     
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/cloudfare-ease.jquery.min.js') }}"></script>


    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/responsive.bootstrap.min.js') }}"></script>
     <!-- <script src="{{ asset('js/app.js') }}"></script>
     <script src="{{ asset('js/app.js') }}"></script> -->
    <script type="text/javascript" src="{{asset('js/jquery.qrcode.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/qrcode.js')}}"></script>

    <script type="text/javascript">
      $(document).ready(function() {
        $('#qrcode-CAMPUS').qrcode($('#qrcode-CAMPUS').attr('ins'));
      });
    </script>

    @if(Session::has('flash_error') || Session::has('flash_success') || Session::has('flash_warning'))
    <script type="text/javascript">
      $(document).ready(function() {
            document.getElementById('myAudio').play();
      });
    </script>
    @endif

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
        $('#go').click(function(){go(50)});
        $('#ok').click(function(){go(500)});

        // // setTimeout(function(){go(50)},700);
        // // setTimeout(function(){go(500)},2000);

        function go(nr) {
          $('.bb').fadeToggle(200);
          $('.message').toggleClass('comein');
          $('.check').toggleClass('scaledown');
          $('#go').fadeToggle(nr);
        }
    </script>

    <script>
    /*function myFunction() {
      var copyText = document.getElementById("myInput");
      copyText.select();
      document.execCommand("copy");
      // alert("Copied the text: " + copyText.value);
    }*/
    </script>
    <script type="text/javascript">
      window.Clipboard = (function(window, document, navigator) {
        var textArea,
            copy;

        function isOS() {
            return navigator.userAgent.match(/ipad|iphone/i);
        }

        function createTextArea(text) {
            textArea = document.createElement('textArea');
            textArea.value = text;
            document.body.appendChild(textArea);
        }

        function selectText() {
            var range,
                selection;

            if (isOS()) {
                range = document.createRange();
                range.selectNodeContents(textArea);
                selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
                textArea.setSelectionRange(0, 999999);
            } else {
                textArea.select();
            }
        }

        function copyToClipboard() {        
            document.execCommand('copy');
            document.body.removeChild(textArea);
        }

        copy = function(text) {
            createTextArea(text);
            selectText();
            copyToClipboard();
        };

        return {
            copy: copy
        };
    })(window, document, navigator);

    Clipboard.copy('text to be copied');

    </script>
    <script>
          
        //jQuery time
        var current_fs, next_fs, previous_fs; //fieldsets
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches

        $(".next").click(function(){
          if(animating) return false;
          animating = true;
          
          current_fs = $(this).parent();
          next_fs = $(this).parent().next();
          
          //activate next step on progressbar using the index of next_fs
          $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
          
          //show the next fieldset
          next_fs.show(); 
          //hide the current fieldset with style
          current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
              //as the opacity of current_fs reduces to 0 - stored in "now"
              //1. scale current_fs down to 80%
              scale = 1 - (1 - now) * 0.2;
              //2. bring next_fs from the right(50%)
              left = (now * 50)+"%";
              //3. increase opacity of next_fs to 1 as it moves in
              opacity = 1 - now;
              current_fs.css({
                'transform': 'scale('+scale+')',
                // 'position': 'absolute'
              });
              next_fs.css({'left': left, 'opacity': opacity});
            }, 
            duration: 800, 
            complete: function(){
              current_fs.hide();
              animating = false;
            }, 
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
          });
        });

        $(".previous").click(function(){
          if(animating) return false;
          animating = true;
          
          current_fs = $(this).parent();
          previous_fs = $(this).parent().prev();
          
          //de-activate current step on progressbar
          $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
          
          //show the previous fieldset
          previous_fs.show(); 
          //hide the current fieldset with style
          current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
              //as the opacity of current_fs reduces to 0 - stored in "now"
              //1. scale previous_fs from 80% to 100%
              scale = 0.8 + (1 - now) * 0.2;
              //2. take current_fs to the right(50%) - from 0%
              left = ((1-now) * 50)+"%";
              //3. increase opacity of previous_fs to 1 as it moves in
              opacity = 1 - now;
              current_fs.css({'left': left});
              previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
            }, 
            duration: 800, 
            complete: function(){
              current_fs.hide();
              animating = false;
            }, 
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
          });
        });

        $(".submit").click(function(){
          return false;
        })
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
