@extends('layouts.header')

@section('content')

    <section class="login-form">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="section-title">Change Password</h2>
                    <form action="{{url('change/password')}}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="field ">
                            <label for="current_password">Current Password</label>
                            <div class="clearfix"></div>
                            <input type="password" id="current_password" name="current_password" placeholder="Password" title="Please enter current password" tabindex="2" autocomplete="off">
                        </div>
                        <div class="form-group field div-password">
                            <div class="field ">
                                <label for="password"> New Password</label>
                                <div class="clearfix"></div>
                                <input type="password" id="new_password" name="password" placeholder="Password" title="Please enter password" tabindex="2" autocomplete="off">
                            </div>
                        </div>
                        <div class="field ">
                            <label for="password_confirmation">Confirm New password</label>
                            <div class="clearfix"></div>
                            <input type="password" id="conform_passwordRepeat" name="password_confirmation" placeholder="Repeat password" autocomplete="off" title="Please repeat password" tabindex="3">
                        </div>

                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <button type="submit" style="width: 100%; max-width: 500px;" class="btn yellow-btn" tabindex="16">Change password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <script>
            
            $(document).ready(function(){
                $('.navbar-xbootstrap').click(function(){
                    $('.nav-xbootstrap').toggleClass('visible');
                    $('body').toggleClass('cover-bg');
                });
            });
        </script>
@endsection