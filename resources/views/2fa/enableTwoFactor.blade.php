    @extends('layouts.header')

    @section('content')

    <div class="row b_g m-0">
        <div class="col-md-6 col-md-offset-3">
            <form id="msform"  method="POST" action="{{url('/g2fotpcheckenable')}}">
                <!-- progressbar -->
                <ul id="progressbar">
                    <li class="active">Step 1</li>
                    <li>Step 2</li>
                    <li>Step 3</li>
                </ul>
                <!-- fieldsets -->
                <fieldset>
                    <h2 class="fs-title">Download App</h2>
                    <!-- <h3 class="fs-subtitle">Tell us something more about you</h3> -->
                    <p>
                    <!-- <img src="{{asset('img/logo.png')}}" width="50%"></p> -->
                    <img src="{{asset('img/google-2fa.png')}}" width="50%"></p>

                    <div class="col-md-6">
                        <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">
                            <img src="{{asset('img/play.png')}}" width="100%">
                        </a>
                    </div>
                    
                    <div class="col-md-6">
                        <a href="https://itunes.apple.com/in/app/google-authenticator/id388497605?platform=iphone&preserveScrollPosition=true&platform=iphone#platform/iphone&platform=iphone" target="_blank">
                            <img src="{{asset('img/app.png')}}" width="100%" style="margin-bottom: 30px;margin-top: 10px;">
                        </a>
                    </div>
                    
                    <input type="button" name="next" class="next action-button" value="Next"/>
                </fieldset>
                <fieldset>
                    <h2 class="fs-title">Scan QR Code</h2>
                    <h3 class="fs-subtitle">Your presence on the social network</h3>
                    <!-- <img src="{{asset('img/qr.png')}}" width="200"> -->
                    <img src="{{ $image }}" style="max-width:200px;margin:0px auto;" class="img-responsive">
                    <br>
                    <p class="man_code">Manual Code: <span>{{ $secret }}</span></p>
                    <p>If you have any problem with scanning the QR code enter this code manually into the APP.</p>
                    <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                    <input type="button" name="next" class="next action-button" value="Next"/>
                </fieldset>
                <fieldset>
                    <h2 class="fs-title">Backup Key</h2>                
                    <h3 class="fs-subtitle">Get in your credentials</h3>
                    <img src="{{asset('img/download.png')}}" style="max-width:200px;margin:0px auto;" class="img-responsive">
                    <p class="description">Please save this Key on paper. This Key will allow you to recover your Google Authentication in case of phone loss.</p>
                    <p class="red">Resetting your Google Authentication requires opening a support ticket and takes at least 7 days to process.</p>

                    <br>
                    <label>Enter your otp from the Google Authendicator App</label>
                    <br>
                    <input type="number" id="totp" name="totp" placeholder="Enter your otp" />
                    <br>
                    <p style="display: none;" id="otp_msg"></p>
                    <br>

                    <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                    <button type="button" class="action-button" onclick="myfuncgoogleotp();" >Submit</button>

                    <!-- <input type="submit" name="submit" class="submit action-button" value="Submit"/> -->
                   <!--  <a href="{{url('/')}}" class="action-button">Submit</a> -->
                </fieldset>
            </form>
            <!-- <form method="POST" action="{{url('/g2fotpcheckenable')}}">
                     <input type="number" name="totp" placeholder="Enter your otp" />
                    <button type="submit" class="action-button" >Submit</button>
            </form> -->
        </div>
    </div>

    <script type="text/javascript">
        function myfuncgoogleotp() {

            var otp=$('#totp').val();

            $.ajax({
                  url: "{{url('/g2fotpcheckenable')}}",
                  type: "POST",
                  data:{'totp':otp,'_token': '{{ csrf_token() }}'}
            }).done(function(response){
                if(response.status==1){
                    //$("#otp_msg").html('<span style="color:green;">'+response.message+'</span>');
                    //$("#otp_msg").show().delay(5000).fadeOut();                
                    window.location.href="{{url('/security')}}?google_succ=1";
                }else{
                    $("#otp_msg").html('<span style="color:red;">'+response.message+'</span>');
                    $("#otp_msg").show().delay(5000).fadeOut();
                }
                

            }).fail(function(jqXhr,status){

            });

        }
    </script>

    @endsection
