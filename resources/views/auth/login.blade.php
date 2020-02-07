@extends('layouts.app')

@section('content')
<section class="login-form">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                <div class="regiBox">
                    <a href="#" class="logo">
                        <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name', '') }}">
                        <!-- <span>{{Setting::get('site_title')}}</span> -->
                    </a>
                   {{-- <h2 class="section-title"><span>Login</span></h2>
                    <form  method="POST" action="{{ route('login') }}" class="new-project" id="form-login">
                        {{ csrf_field() }}
                        <div class="field ">
                            <label for="email">Email</label>
                            <div class="clearfix"></div>
                            <input type="email" id="email" name="email" value="" title="Please enter your email address" tabindex="1" autocomplete="off" placeholder="">
                        </div>
                        <div class="field ">
                            <label for="password">Password</label>
                            <div class="clearfix"></div>
                            <input type="password" id="password" name="password" title="Please enter password" tabindex="2" autocomplete="off" placeholder="">
                            <a class="forget forgetLink" tabindex="17" href="{{ route('password.request') }}">Lost your Password ?</a>
                        </div>
                        
                        <!-- <div class="field ">
                            <label>Please verify you are a human.</label>
                            <div class="g-recaptcha" data-sitekey="6LcipWcUAAAAAJRZTQZSaYMHSu9e0LMPgJyS11QJ"></div>
                            <h6 class="gcaptcha-error" style="display: none; color: red; text-align: left;"></h6>
                        </div> -->
                        
                        <button type="submit" class="button btn yellow-btn mbtn" style="float: left;" tabindex="16">Log In</button>
                        <a href="{{ route('register') }}" class="signup signupLnk"><span>Don't have an account yet ?</span> Create Account</a>
                        
                    </form> --}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script type="text/javascript">
    $("#form-login").submit(function(event) {

            var recaptcha = $("#g-recaptcha-response").val();
            if (recaptcha === "") {
                event.preventDefault();
                //alert("Please check the recaptcha");
                $(".gcaptcha-error").html("Please check the recaptcha");
                $(".gcaptcha-error").show().delay(5000).fadeOut();
            }
        });
</script>
@endsection
