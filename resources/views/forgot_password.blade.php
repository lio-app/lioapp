@extends('layouts.header')

@section('content')

<section class="login-form">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="section-title">Forgot Password</h2>
                    <form action="" method="POST" class="new-project">
                        <input type="hidden" name="csrfToken" value="">
                        <div class="field ">
                                <label for="password">Email</label>
                                <div class="clearfix"></div>
                                <input type="text" id="current_password" name="password" placeholder="Email" title="Please enter password" tabindex="2" autocomplete="off">
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <button style="width: 100%; max-width: 500px;" class="btn yellow-btn" tabindex="16">Send Verification Link</button>
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
