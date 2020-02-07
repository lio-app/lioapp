@extends('layouts.header')

@section('content')

    <section class="login-form">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="section-title">Change Crypto Currency</h2>
                    <form action="{{url('network')}}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="field ">
                            <label for="current_password">Current Crypto Currency : 

                            @if(Auth::user()->network == 'BTC')
                                <b>Bitcoin</b>
                            @elseif(Auth::user()->network == 'BCH')

                                <b>Bitcoin Cash</b>
                            @elseif(Auth::user()->network == 'LTC')
                                <b>Litecoin</b>
                            @elseif(Auth::user()->network == 'ETH')
                                <b>Ethereum</b>
                            @elseif(Auth::user()->network == 'BNB')
                                <b>BNB</b>
                            @elseif(Auth::user()->network == 'OMG')
                                <b>OmiseGO</b>
                            @elseif(Auth::user()->network == 'VEN')
                                <b>VeChain</b>

                            @elseif(Auth::user()->network == 'MKR')
                                <b>Maker</b>
                            @elseif(Auth::user()->network == 'ZRX')
                                <b>ZRX</b>
                            @elseif(Auth::user()->network == 'ZIL')
                                <b>Zilliqa</b>
                            @elseif(Auth::user()->network == 'ICX')
                                <b>ICON</b>

                            @else
                                <b>Not Found</b>
                            @endif

                            </label>

                           
                           
                            <select name="network" class="btn yellow-btn my_btn" style="width: 100%; max-width: 500px;">
                                @if(Auth::user()->network == 'BTC')
                                    <option value="BTC" class="field">Bitcoin</option>
                                    <option value="ETH" class="field">Ethereum</option>
                                    <option value="LTC" class="field">Litecoin</option>
                                    <option value="BNB" class="field">BNB</option>
                                    <option value="VEN" class="field">VeChain</option>
                                    <option value="OMG" class="field">OmiseGO</option>

                                    <option value="MKR" class="field">Maker</option>
                                    <option value="ZRX" class="field">ZRX</option>
                                    <option value="ZIL" class="field">Zilliqa</option>
                                    <option value="ICX" class="field">ICON</option>


                                @elseif(Auth::user()->network == 'ETH')
                                    <option value="ETH" class="field">Ethereum</option>
                                    <option value="BTC" class="field">Bitcoin</option>
                                    <option value="LTC" class="field">Litecoin</option>
                                     <option value="BNB" class="field">BNB</option>
                                    <option value="VEN" class="field">VeChain</option>
                                    <option value="OMG" class="field">OmiseGO</option>

                                    <option value="MKR" class="field">Maker</option>
                                    <option value="ZRX" class="field">ZRX</option>
                                    <option value="ZIL" class="field">Zilliqa</option>
                                    <option value="ICX" class="field">ICON</option>


                                @elseif(Auth::user()->network == 'LTC')
                                    <option value="LTC" class="field">Litecoin</option>
                                    <option value="BTC" class="field">Bitcoin</option>
                                    <option value="ETH" class="field">Ethereum</option>
                                     <option value="BNB" class="field">BNB</option>
                                    <option value="VEN" class="field">VeChain</option>
                                    <option value="OMG" class="field">OmiseGO</option>

                                    <option value="MKR" class="field">Maker</option>
                                    <option value="ZRX" class="field">ZRX</option>
                                    <option value="ZIL" class="field">Zilliqa</option>
                                    <option value="ICX" class="field">ICON</option>


                                @elseif(Auth::user()->network == 'BCH')
                                    <option value="BCH" class="field">Bitcoincash</option>
                                    <option value="BTC" class="field">Bitcoin</option>
                                    <option value="ETH" class="field">Ethereum</option>
                                    <option value="LTC" class="field">Litecoin</option>
                                    <option value="BNB" class="field">BNB</option>
                                    <option value="VEN" class="field">VeChain</option>
                                    <option value="OMG" class="field">OmiseGO</option>
                                    <option value="MKR" class="field">Maker</option>
                                    <option value="ZRX" class="field">ZRX</option>
                                    <option value="ZIL" class="field">Zilliqa</option>
                                    <option value="ICX" class="field">ICON</option>


                                @elseif(Auth::user()->network == 'BNB')
                                    <option value="BNB" class="field">BNB</option>
                                    <option value="ETH" class="field">Ethereum</option>
                                    <option value="BTC" class="field">Bitcoin</option>
                                    <option value="LTC" class="field">Litecoin</option>
                                    <option value="VEN" class="field">VeChain</option>
                                    <option value="OMG" class="field">OmiseGO</option>
                                    <option value="MKR" class="field">Maker</option>
                                    <option value="ZRX" class="field">ZRX</option>
                                    <option value="ZIL" class="field">Zilliqa</option>
                                    <option value="ICX" class="field">ICON</option>


                                

                                @elseif(Auth::user()->network == 'VEN')
                                    <option value="VEN" class="field">VeChain</option>
                                    <option value="ETH" class="field">Ethereum</option>
                                    <option value="BTC" class="field">Bitcoin</option>
                                    <option value="LTC" class="field">Litecoin</option>
                                    <option value="BNB" class="field">BNB</option>
                                    <option value="OMG" class="field">OmiseGO</option>
                                    <option value="MKR" class="field">Maker</option>
                                    <option value="ZRX" class="field">ZRX</option>
                                    <option value="ZIL" class="field">Zilliqa</option>
                                    <option value="ICX" class="field">ICON</option>


                                @elseif(Auth::user()->network == 'OMG')
                                    <option value="OMG" class="field">OmiseGO</option>
                                    <option value="ETH" class="field">Ethereum</option>
                                    <option value="BTC" class="field">Bitcoin</option>
                                    <option value="LTC" class="field">Litecoin</option>
                                    <option value="BNB" class="field">BNB</option>
                                    <option value="VEN" class="field">VeChain</option>
                                    <option value="MKR" class="field">Maker</option>
                                    <option value="ZRX" class="field">ZRX</option>
                                    <option value="ZIL" class="field">Zilliqa</option>
                                    <option value="ICX" class="field">ICON</option>

                                @elseif(Auth::user()->network == 'MKR')
                                    <option value="MKR" class="field">Maker</option>
                                    <option value="BTC" class="field">Bitcoin</option>
                                    <option value="ETH" class="field">Ethereum</option>
                                    <option value="LTC" class="field">Litecoin</option>
                                     <option value="BNB" class="field">BNB</option>
                                    <option value="VEN" class="field">VeChain</option>
                                    <option value="OMG" class="field">OmiseGO</option>
                                    <option value="ZRX" class="field">ZRX</option>
                                    <option value="ZIL" class="field">Zilliqa</option>
                                    <option value="ICX" class="field">ICON</option>

                                @elseif(Auth::user()->network == 'ZRX')
                                    <option value="ZRX" class="field">ZRX</option>

                                    <option value="BTC" class="field">Bitcoin</option>
                                    <option value="ETH" class="field">Ethereum</option>
                                    <option value="LTC" class="field">Litecoin</option>
                                     <option value="BNB" class="field">BNB</option>
                                    <option value="VEN" class="field">VeChain</option>
                                    <option value="OMG" class="field">OmiseGO</option>
                                    <option value="MKR" class="field">Maker</option>
                                    <option value="ZIL" class="field">Zilliqa</option>
                                    <option value="ICX" class="field">ICON</option>

                                @elseif(Auth::user()->network == 'ZIL')
                                    <option value="ZIL" class="field">Zilliqa</option>

                                    <option value="BTC" class="field">Bitcoin</option>
                                    <option value="ETH" class="field">Ethereum</option>
                                    <option value="LTC" class="field">Litecoin</option>
                                     <option value="BNB" class="field">BNB</option>
                                    <option value="VEN" class="field">VeChain</option>
                                    <option value="OMG" class="field">OmiseGO</option>
                                    <option value="MKR" class="field">Maker</option>
                                    <option value="ZRX" class="field">ZRX</option>
                                    <option value="ICX" class="field">ICON</option>

                                @elseif(Auth::user()->network == 'ICX')
                                    <option value="ICX" class="field">ICON</option>

                                    <option value="BTC" class="field">Bitcoin</option>
                                    <option value="ETH" class="field">Ethereum</option>
                                    <option value="LTC" class="field">Litecoin</option>
                                     <option value="BNB" class="field">BNB</option>
                                    <option value="VEN" class="field">VeChain</option>
                                    <option value="OMG" class="field">OmiseGO</option>
                                    <option value="MKR" class="field">Maker</option>
                                    <option value="ZRX" class="field">ZRX</option>
                                    <option value="ZIL" class="field">Zilliqa</option>


                                @else
                                    <option value="BTC" class="field">Bitcoin</option>
                                    <option value="ETH" class="field">Ethereum</option>
                                    <option value="LTC" class="field">Litecoin</option>
                                     <option value="BNB" class="field">BNB</option>
                                    <option value="VEN" class="field">VeChain</option>
                                    <option value="OMG" class="field">OmiseGO</option>
                                    <option value="MKR" class="field">Maker</option>
                                    <option value="ZRX" class="field">ZRX</option>
                                    <option value="ZIL" class="field">Zilliqa</option>
                                    <option value="ICX" class="field">ICON</option>

                                @endif

                            </select>
                        </div>

                        

                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <button type="submit" style="width: 50%; max-width: 250px;" class="btn yellow-btn" tabindex="16">Change Currency</button>
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