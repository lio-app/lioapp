@extends('layouts.header')

@section('content')
    <div class="container-fluid">
        <?php 
            //$tranfee_temp =  $coin - 0.000374; 
             $USDVALUE = number_format((float)$USD, 2, '.', ''); 


            $avb_amt=$coin;
            //$avb_amt=0.000373132;

            if(Auth::user()->network == 'BTC'){
                 $fee=0.000374;
            }

            if(Auth::user()->network == 'BCH'){
                  $fee=0.000374;
            }

            if(Auth::user()->network == 'LTC'){
                  $fee=0.000374;
            }

            if(Auth::user()->network == 'ETH' || Auth::user()->network == 'BNB' || Auth::user()->network == 'VEN' || Auth::user()->network == 'OMG' || Auth::user()->network == 'MKR' || Auth::user()->network == 'ZRX' || Auth::user()->network == 'ZIL' || Auth::user()->network == 'ICX'){
                  $fee=0.000374;
            }

            $calc_amt=\DB::select('select '.$avb_amt.'-'.$fee); 
           
            $arr = json_decode(json_encode($calc_amt[0]), true);
            $tranfee_temp=current($arr);

            if($tranfee_temp<=0){
                $tranfee =  0; 
            }else{
                $tranfee =  $tranfee_temp;                 
            }


            if(Auth::user()->network == 'BTC'){
                $value = $BTC;
            }

            if(Auth::user()->network == 'BCH'){
                 $value = $BCH;
            }

            if(Auth::user()->network == 'LTC'){
                 $value = $LTC;
            }

            if(Auth::user()->network == 'ETH'){
                 $value = $ETH;
            }

            if(Auth::user()->network == 'BNB'){
                 $value = $BNB;
            }

            if(Auth::user()->network == 'OMG'){
                 $value = $OMG;
            }

            if(Auth::user()->network == 'VEN'){
                 $value = $VEN;
            }

            if(Auth::user()->network == 'MKR'){
                 $value = $MKR;
            }

            if(Auth::user()->network == 'ZRX'){
                 $value = $ZRX;
            }

            if(Auth::user()->network == 'ZIL'){
                 $value = $ZIL;
            }

            if(Auth::user()->network == 'ICX'){
                 $value = $ICX;
            }
            

            
        ?>
        <!-- <div class="content"> -->
            
                {{--  <div class="row">
                    <div class="col-6 col-md-4 js-appear-enabled animated fadeIn" data-toggle="appear">
                        <a class="block block-rounded block-link-pop text-center" href="javascript:void(0)">
                            <div class="block-content block-content-full aspect-ratio-16-9 d-flex justify-content-center align-items-center">
                                <div>
                                    <div class="font-size-h1 font-w300 text-black">{{$coin}} {{Auth::user()->network}}</div>
                                    <div class="font-w600 mt-2 text-uppercase text-muted">Available Balance</div>
                                </div>
                            </div>
                        </a>
                    </div>
                  
                    <div class="col-6 col-md-4 js-appear-enabled animated fadeIn" data-toggle="appear" data-timeout="500">
                        <a class="block block-rounded block-link-pop text-center" href="javascript:void(0)">
                            <div class="block-content block-content-full aspect-ratio-16-9 d-flex justify-content-center align-items-center">
                                <div>
                                    <div class="font-size-h1 font-w300 text-black">1 {{Auth::user()->network}} = {{$value}}  {{Setting::get('einr_symbol')}}</div>
                                    <div class="font-w600 mt-2 text-uppercase text-muted">Current Rate</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-4 js-appear-enabled animated fadeIn" data-toggle="appear" data-timeout="500">
                        <a class="block block-rounded block-link-pop text-center" href="javascript:void(0)">
                            <div class="block-content block-content-full aspect-ratio-16-9 d-flex justify-content-center align-items-center"  style="padding: 14px;">
                                <div>
                                    <div class="font-size-h1 font-w300 text-black">                                    
                                        <table class="currency_table">
                                            <tr>    
                                                <td>    
                                                    <span> 1 {{Auth::user()->network}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>    
                                                    <span class="text-center">
                                                        @foreach($currency as $value)
                                                           <span class="currency-span">{{$value->coin_value}} {{$value->currency}}</span>
                                                        @endforeach
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>

                                    </div>

                                    <br>
                                    <div class="row"></div>
                                    <div class="font-w600 mt-2 text-uppercase text-muted">Current Rate</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-4 js-appear-enabled animated fadeIn" data-toggle="appear" data-timeout="750">
                        <a class="block block-rounded block-link-pop text-center" href="javascript:void(0)">
                            <div class="block-content block-content-full aspect-ratio-16-9 d-flex justify-content-center align-items-center">
                                <div>
                                    <div class="font-size-h1 font-w300 text-black">{{count($history)}}</div>
                                    <div class="font-w600 mt-2 text-uppercase text-muted">Transactions</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>--}}

                <div class="row">
                    
                    <div class="col-xs-12">
                        <div class="block-content block-content-full fill_block dashBoardBlck" data-toggle="appear">
                            <h2 class="pull-left">BE YOUR OWN BANK</h2>
                            <a href="{{route('networkpage')}}" title="Change Currency">
                            <h5 class="pull-right">
                              
                                {{Auth::user()->network}}
                            
                            </h5>
                            </a>  
                        </div>
                    </div>
                </div>

                <div class="row">
                        <div class="col-md-6 js-appear-enabled animated fadeIn" data-toggle="appear">
                            
                            <div class="block-rounded block-link-pop">
                                <h3>Your Balances</h3>
                                <div class="block-content block-content-full fill_block">
                                    <div class="row">
                                        <div class="col-sm-12 text-center" >
                                            <div class="progress" data-percentage="100">
                                                <span class="progress-left">
                                                    <span class="progress-bar"></span>
                                                </span>
                                                <span class="progress-right">
                                                    <span class="progress-bar"></span>
                                                </span>
                                                <div class="progress-value">
                                                    <div>
                                                        <b>USD</b> <br/> $ {{$USDVALUE}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="waletList">
                                        <li class="waletItem">
                                            <div class="walet_bx btcCoin">
                                                <h5>Bitcoin</h5>
                                                <h4>{{$data['BTC']}} BTC</h4>
                                                <h6> 1 BTC = {{$BTC}} USD</h6>
                                            </div>
                                        </li>
                                        <li class="waletItem">
                                            <div class="walet_bx ethCoin">
                                                <h5>Etherium</h5>
                                                <h4>{{$data['ETH']}} ETH</h4>
                                                <h6> 1 ETH = {{$ETH}} USD</h6>
                                            </div>
                                        </li>
                                        <li class="waletItem">
                                            <div class="walet_bx ltcCoin">
                                                <h5>Litecoin</h5>
                                                <h4>{{$data['LTC']}} LTC</h4>
                                                <h6> 1 LTC = {{$LTC}} USD</h6>
                                            </div>
                                        </li>
                                        <li class="waletItem">
                                            <div class="walet_bx bnbCoin">
                                                <h5>BNB</h5>
                                                <h4>{{$data['BNB']}} BNB</h4>
                                                <h6> 1 BNB = {{$BNB}} USD</h6>
                                            </div>
                                        </li>
                                        <li class="waletItem">
                                            <div class="walet_bx venCoin">
                                                <h5>Vechain</h5>
                                                <h4>{{$data['VEN']}} VEN</h4>
                                                <h6> 1 VEN = {{$VEN}} USD</h6>
                                            </div>
                                        </li>
                                        <li class="waletItem">
                                            <div class="walet_bx omgCoin">
                                                <h5>Omisego</h5>
                                                <h4>{{$data['OMG']}} OMG</h4>
                                                <h6> 1 OMG = {{$OMG}} USD</h6>
                                            </div>
                                        </li>

                                        <li class="waletItem">
                                            <div class="walet_bx mkrCoin">
                                                <h5>Maker</h5>
                                                <h4>{{$data['MKR']}} MKR</h4>
                                                <h6> 1 MKR = {{$MKR}} USD</h6>
                                            </div>
                                        </li>

                                        <li class="waletItem">
                                            <div class="walet_bx zrxCoin">
                                                <h5>ZRX</h5>
                                                <h4>{{$data['ZRX']}} ZRX</h4>
                                                <h6> 1 ZRX = {{$ZRX}} USD</h6>
                                            </div>
                                        </li>

                                        <li class="waletItem">
                                            <div class="walet_bx zilCoin">
                                                <h5>Zilliqa</h5>
                                                <h4>{{$data['ZIL']}} ZIL</h4>
                                                <h6> 1 ZIL = {{$ZIL}} USD</h6>
                                            </div>
                                        </li>

                                        <li class="waletItem">
                                            <div class="walet_bx icxCoin">
                                                <h5>ICON</h5>
                                                <h4>{{$data['ICX']}} ICX</h4>
                                                <h6> 1 ICX = {{$ICX}} USD</h6>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 js-appear-enabled animated fadeIn" data-toggle="appear">
                            <div class=" block-rounded block-link-pop">
                                <h3>Address Details</h3>
                                <div class="block-content block-content-full fill_block">
                                    
                                    <div class="row address_copy">
                                        <p id="block_hash" style="border: 1px solid #ccc;padding:20px;border-radius:25px"> {{Auth::user()->address}}</p> 
                                           
                                        <button class="btn yellow-btn my_btn" onclick="myFunction1()" style="padding: 8px 16px;"><i class="fa fa-copy" data-toggle="tooltip" title="Copy Address"></i> Copy</button>  
                                        <h6 class="copied" style="display: none; color: green; font-weight: bold;">Copied</h6>
                                    </div>
                                    
                                    <div class="text-center o_r">OR</div> 
                                    <h3 class="text-center">Scan QR Code</h3>
                                    
                                    <div class="row text-center">
                                        <div id="qrcode-CAMPUS" class="QRImage" ins="{{Auth::user()->address}}"></div>
                                    </div>
                                    

                                    <div class="row text-center">
                                        
                                        <a href="#0" class="btn yellow-btn cd-popup-trigger " >Send Payment</a>
                                        {{--@if($tranfee>0)
                                        @else
                                            <br>
                                            <a href="#" class="btn yellow-btn" style=" pointer-events: none;cursor: default;opacity: 0.5;">Send Payment</a>
                                            <p class="text-center" style="color: red; font-weight: 600;">You have no sufficient balance to make transaction</p>
                                        @endif--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--  <div class="col-md-6 js-appear-enabled animated fadeIn" data-toggle="appear">
                            <h3>Notifications</h3>
                            <a class="block-rounded block-link-pop" href="javascript:void(0)">
                                <ul class="list-group pmd-card-list pmd-list-avatar">
                                @forelse($history as $trans)
                                    <li class="list-group-item">
                                        <div class="media-left"> 
                                            <a href="javascript:void(0);" class="avatar-list-img" title="profile-link"> 
                                                <img alt="40x40" data-src="holder.js/40x40" class="img-responsive" src="{{ asset('img/2.png') }}" data-holder-rendered="true"> 
                                            </a> 
                                        </div>

                                        <div class="media-body media-middle">
                                            <h3 class="list-group-item-heading">


                                            @if(Auth::user()->network == 'BTC')

                                            <a href="https://www.blockchain.com/btc/tx/{{$trans['txid']}}" target="_blank">{{$trans['txid']}}</a>
             

                                            @elseif(Auth::user()->network == 'LTC')
                                            <a href="https://insight.litecore.io/tx/{{$trans['txid']}}" target="_blank">{{$trans['txid']}}</a>


                                            @elseif(Auth::user()->network == 'ETH')

                                            <a href="https://etherscan.io/tx/{{$trans['txid']}}" target="_blank">{{$trans['txid']}}</a>

                                            @elseif(Auth::user()->network == 'BNB')

                                            <a href="https://etherscan.io/tx/{{$trans['txid']}}" target="_blank">{{$trans['txid']}}</a>

                                            @elseif(Auth::user()->network == 'OMG')

                                            <a href="https://etherscan.io/tx/{{$trans['txid']}}" target="_blank">{{$trans['txid']}}</a>

                                            @elseif(Auth::user()->network == 'VEN')

                                            <a href="https://etherscan.io/tx/{{$trans['txid']}}" target="_blank">{{$trans['txid']}}</a>

                                            @else

                                            <a href="https://etherscan.io/tx/{{$trans['txid']}}" target="_blank">{{$trans['txid']}}</a>

                                            @endif

                                                <!-- <a href="" target="_blank">{{$trans['txid']}}</a> -->

                                            </h3>
                                            <span class="list-group-item-text"> {{$trans['amount']}} {{Auth::user()->network}}</span>
                                        </div>
                                        <div class="media-right post">
                                            <span class="post-time"> {{date('d M Y H:i:s', $trans['time'])}}</span>
                                        </div>
                                    </li>
                                @empty
                                    <div class="col-md-12"><center><h5>No transactions yet...</h5></center></div>
                                @endforelse
                                </ul>
                                <span class="btn-loader loader hidden">Loading...</span>
                            </a>
                        </div>--}}
                </div>
            

            @if($tranfee>0)
            @endif

            <div class="row" style="text-align: center;margin-top: 25px;margin-bottom: 25px;">
                <!-- <button class="btn yellow-btn cd-popup-trigger" id="test">Send Payments</button> -->
                <!-- <a href="#0" class="btn yellow-btn cd-popup-trigger">Send Payment</a> -->

                <div class="cd-popup" role="alert">
                    <div class="cd-popup-container login-form">
                        <p>Send {{Auth::user()->network}} Coin</p>
                        <form action="{{ url('/sendcoin') }}" method="POST" class="new-project" id="formid">
                            {{csrf_field()}}
                            <div class="field ">
                                <label for="text">To Address</label>
                                <div class="clearfix"></div>
                                <input type="text" id="address" name="to_address" value="" title="Please enter address" tabindex="1" autocomplete="off" placeholder="Address Here..." required>
                                  <span id="address_error" style="clear:both;color: red;display: none;">Please fill this field..</span> 
                                
                            </div>
                            <div class="field ">
                                <label for="number">{{Auth::user()->network}} Coin to Send</label>
                                <div class="clearfix"></div>
                                <input type="number" id="amount" name="amount" title="Please enter amount" tabindex="2" autocomplete="off" placeholder="Enter amount Here..." step="" required ><br>
                                 
                                <!--  <b>Transaction Fee : 0.000374 {{Auth::user()->network}}</b><br>
                                 <span>You can send upto <b>{{$tranfee}}</b> {{Auth::user()->network}}</span><br>  -->
                                 <span id="amount_max_error" style="clear:both;color: red;display: none;"></span>
                                 <span id="amount_error" style="clear:both;color: red;display: none;">Please fill this field...</span> 

                            </div>

                            {{--<div class="field ">
                                <label for="msg">Your Comment</label>
                                <div class="clearfix"></div>
                                <input type="text" id="comment" name="comment" title="Comment" tabindex="2" autocomplete="off" placeholder="Write something Here..." required>
                            </div>

                            <div class="field ">
                                <label for="msg">Comment To</label>
                                <div class="clearfix"></div>
                                <input type="text" id="to_comment" name="to_comment" title="Comment" tabindex="2" autocomplete="off" placeholder="Write something Here..." required>
                            </div>--}}
                            
                            <ul class="cd-buttons">
                                <li>
                                    <button type="button" class="btn yellow-btn" onclick="mytransction();">Send</button>
                                </li>
                               
                            </ul>
                        </form>
                        <a href="#0" class="cd-popup-close img-replace"></a>
                    </div> <!-- cd-popup-container -->
                </div> <!-- cd-popup -->
            </div>
            
                        </div>
  
                <!-- model -->

            </div>
        </div>
        
    </div>
<audio id="myAudio" src="{{asset('/sounds/note.mp3')}}"></audio>
@endsection

@section('scripts')       

    <script type="text/javascript">

        $(function() {

            $('#amount').on('keydown', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||(/65|67|86|88/.test(e.keyCode)&&(e.ctrlKey===true||e.metaKey===true))&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});

            function mycurrencysplit(step){

                var n1=0;
                var coin_value=$('#coin_value').val();
                var currency_type=$('#currency_type').val();
                var currency_value=$('#currency_value').val();
                var n=currency_type.split("-");
                var coin_value1=parseFloat(coin_value);
                var currency_type1=parseFloat(n[1]);
                var currency_value1=parseFloat(currency_value);
                
                if(coin_value==''){
                    n1=0;                
                }
                else if(currency_value==''){
                    n1=0;
                }else{
                    if(step==1 || step==2){
                        n1=currency_type1*coin_value1;
                    }
                    if(step==3){
                        n1=currency_value1/currency_type1;
                    }
                }
                                
                return n1;
            }


            $('#coin_value').keyup(function(){
                var n=mycurrencysplit(1)
                $('#currency_value').val(n);
                $('#amount').val($('#coin_value').val());
            });

            $('#currency_type').change(function(){
                var n=mycurrencysplit(2)
                $('#currency_value').val(n);
                $('#amount').val($('#coin_value').val());
            });

            $('#currency_value').keyup(function(){
                var n=mycurrencysplit(3)
                $('#coin_value').val(n);
                $('#amount').val($('#coin_value').val());
            });

        });

        function myFunction1(id) {

            var input_text="#block_hash";
            var id1 = $(input_text).attr('id');
            var el = document.getElementById(id1);
            var range = document.createRange();
            range.selectNodeContents(el);
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
            document.execCommand('copy');
            $(".copied").show().delay(5000).fadeOut();
            //alert("Contents copied to clipboard.");
            return false;
          // alert("Copied the text: " + copyText.value);
        }

        function mytransction() {

           
            var err=0;
            var address=$('#address').val();
            var amount=$('#amount').val();
            //var memo=$('#memo').val();

            if(address==''){
                $('#address_error').show().delay(3000);                
                err=1;
            }else{
                $('#address_error').hide();
            }

            if(amount==''){
                $('#amount_error').show().delay(3000);                
                err=1;
            }else{
                $('#amount_error').hide();
            }
          
            
            var amt_temp=1000;
            var tran = {{$tranfee}};
            
            var coined = "<?=Auth::user()->network?>";
            var coins = coined;
// console.log(coins);
            if(coins == "BTC" || coins == "ETH" || coins == "LTC"){
                if(amount!=''){

                        if(amount>1000){
                            var amt_txt="You can send upto 1000 coins per transactions...";
                            err=1;
                            $('#amount_max_error').html(amt_txt);
                            $('#amount_max_error').show().delay(3000);
                        }else if(tran<amount){
                            
                            err=1;
                            var amt_txt="You cannot send coin greater than your available balance...";
                            $('#amount_max_error').html(amt_txt);
                            $('#amount_max_error').show().delay(3000);

                        }else if(amount<=0){
                           
                            err=1;
                            var amt_txt="You cannot send 0 coin...";
                            $('#amount_max_error').html(amt_txt);
                            $('#amount_max_error').show().delay(3000);

                        }else{
                            $('#amount_max_error').hide();

                        }
                }

            }
            
            if(err == 0){

                $('#formid').submit();

            }                      
        }
    </script>
    <script type="text/javascript">
        /*var coin_value=$('#coin_value').val();
        var currency_type=$('#currency_type').val();
        var currency_value=$('#currency_value').val();*/

        function mycurrencysplit(step){

            var coin_value=$('#coin_value').val();
            var currency_type=$('#currency_type').val();
            var currency_type=$('#currency_value').val();

            var n=currency_type.split("-");

            var n1=0;
            
            if(step==1 || step==2){
                n1=n[1]*coin_value;
            }
            if(step==3){
                n1=n[1]*currency_type;
            }

            return n1;
        }


        $('#coin_value').keyup(function(){
            var n=mycurrencysplit(1)
            $('#currency_value').val(n);
        });

        $('#currency_type').change(function(){
            var n=mycurrencysplit(2)
            $('#currency_value').val(n);
        });

        $('#currency_value').keyup(function(){
            var n=mycurrencysplit(3)
            $('#coin_value').val(n);
        });
    </script>
@endsection

