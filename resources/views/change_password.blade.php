@extends('layouts.header')

@section('content')
<section class="login-form">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="section-title">Change Password</h2>
                    <form action="" method="POST" class="new-project">
                        <input type="hidden" name="csrfToken" value="">
                        <div class="field ">
                                <label for="password">Current Password</label>
                                <div class="clearfix"></div>
                                <input type="password" id="current_password" name="password" placeholder="Password" title="Please enter password" tabindex="2" autocomplete="off">
                            </div>
                        <div class="form-group field div-password">
                            <div class="field ">
                                <label for="password"> New Password</label>
                                <div class="clearfix"></div>
                                <input type="password" id="new_password" name="password" placeholder="Password" title="Please enter password" tabindex="2" autocomplete="off">
                            </div>
                        </div>
                        <div class="field ">
                            <label for="passwordRepeat">Confirm New password</label>
                            <div class="clearfix"></div>
                            <input type="password" id="conform_passwordRepeat" name="passwordRepeat" placeholder="Repeat password" autocomplete="off" title="Please repeat password" tabindex="3">
                        </div>
                        <!-- <div class="agree">
                            <span class="checkbox-options">
                                <div class="accept form-group ">
                                    <div class="">
                                        <label for="acceptTerms">
                                            <input type="checkbox" id="acceptTerms" name="acceptTerms" value="true" checked="checked" tabindex="5">
                                            <span></span>
                                            I read and accept the <a href="#" target="_blank">Terms &amp; Conditions</a></label>
                                        </div>
                                    </div>
                                </span>
                            </div> -->
                            <br>
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <button style="width: 100%; max-width: 500px;" class="btn yellow-btn" tabindex="16">Change password</button>
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
