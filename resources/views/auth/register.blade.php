@extends('layouts.app')

@section('content')

 <section class="login-form">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                    <div class="regiBox">
                        <a href="{{ route('login') }}" class="logo">
                            <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name', '') }}">
                        </a>
                    
                        <h2 class="section-title">Register</h2>
                        <form action="{{ route('register') }}" method="POST" class="new-project">
                            {{ csrf_field() }}
                            <input type="hidden" name="csrfToken" value="">
                            <div class="field ">
                                <label for="name">Name</label>
                                <div class="clearfix"></div> 
                                <input type="text" id="name" name="name" value="" placeholder="" autocomplete="off" title="Please enter name" tabindex="1">
                            </div>
                            <div class="field ">
                                <label for="email">Email</label>
                                <div class="clearfix"></div> 
                                <input type="email" id="email" name="email" value="" placeholder="" autocomplete="off" title="Please choose a valid email address" tabindex="1">
                            </div>
                            <div class="form-group field div-password">
                                <div class="field ">
                                    <label for="password">Password</label>
                                    <div class="clearfix"></div>
                                    <input type="password" id="password" name="password" placeholder="" title="Please enter password" tabindex="2" autocomplete="off">
                                </div>
                            </div>
                            <div class="field ">
                                <label for="passwordRepeat">Repeat password</label>
                                <div class="clearfix"></div>
                                <input type="password" id="passwordRepeat" name="password_confirmation" placeholder="" autocomplete="off" title="Please repeat password" tabindex="3">
                            </div>
                            
                            <div class="field ">
                                <label for="">Mobile</label>
                                <div class="clearfix"></div>
                                <input type="text" id="mobile" name="mobile" placeholder="" autocomplete="off" title="Please enter mobile number" tabindex="3">
                            </div>
                            
                            <div class="agree">
                                <span class="checkbox-options">
                                    <div class="accept form-group">
                                        <div class="">
                                            <label for="acceptTerms">
                                            <input type="checkbox" id="acceptTerms" name="acceptTerms" value="true" tabindex="5" required>
                                            <span></span>
                                            I read and accept the <a href="{{url('/terms')}}" target="_blank">Terms &amp; Conditions</a></label>
                                        </div>
                                    </div>
                                </span>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <button style="width: 100%; max-width: 500px;" type="submit" class="button btn yellow-btn" tabindex="16">Register</button>
                                    <!-- <a href="{{ route('login') }}" title="Login">Log in instead</a> -->
                                    <a href="{{ route('login') }}" class="signup signupLnk"><span>Already Registered? </span> Login here </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection
