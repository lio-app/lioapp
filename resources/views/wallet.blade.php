@extends('layouts.header')

@section('content')
    <div class="container-fluid">
    
        <!-- <div class="content"> -->
            <h3>Your Balance</h3>
            <div class="row">
                <div class="col-6 col-md-4 js-appear-enabled animated fadeIn" data-toggle="appear">
                        <div class="block-content block-content-full aspect-ratio-16-9 d-flex justify-content-center align-items-center">
                            <div>
                                <div class="font-size-h1 font-w300 text-black">{{$data['BTC']}} BTC</div>
                                <div class="font-w600 mt-2 text-uppercase text-muted">Bitcoin Balance</div><hr>
                                <div class="font-size-h1 font-w300 text-black">1 BTC = {{$BTC}} USD</div>
                            </div>
                        </div>
                   
                </div>
              
               <div class="col-6 col-md-4 js-appear-enabled animated fadeIn" data-toggle="appear">
                   
                        <div class="block-content block-content-full aspect-ratio-16-9 d-flex justify-content-center align-items-center">
                            <div>
                                <div class="font-size-h1 font-w300 text-black">{{$data['ETH']}} ETH</div>
                                <div class="font-w600 mt-2 text-uppercase text-muted">Ethereum Balance</div><hr>
                                <div class="font-size-h1 font-w300 text-black">1 ETH = {{$ETH}} USD</div>
                            </div>
                        </div>
                   
                </div>


                <div class="col-6 col-md-4 js-appear-enabled animated fadeIn" data-toggle="appear">
                  
                        <div class="block-content block-content-full aspect-ratio-16-9 d-flex justify-content-center align-items-center">
                            <div>
                                <div class="font-size-h1 font-w300 text-black">{{$data['LTC']}} LTC</div>
                                <div class="font-w600 mt-2 text-uppercase text-muted">Litecoin Balance</div><hr>
                                <div class="font-size-h1 font-w300 text-black">1 LTC = {{$LTC}} USD</div>
                            </div>
                        </div>
                    
                </div>

                <div class="col-6 col-md-4 js-appear-enabled animated fadeIn" data-toggle="appear">
                  
                        <div class="block-content block-content-full aspect-ratio-16-9 d-flex justify-content-center align-items-center">
                            <div>
                                <div class="font-size-h1 font-w300 text-black">{{$data['BNB']}} BNB</div>
                                <div class="font-w600 mt-2 text-uppercase text-muted">BNB Balance</div><hr>
                                <div class="font-size-h1 font-w300 text-black">1 BNB = {{$BNB}} USD</div>
                            </div>
                        </div>
                    
                </div>

                <div class="col-6 col-md-4 js-appear-enabled animated fadeIn" data-toggle="appear">
                  
                        <div class="block-content block-content-full aspect-ratio-16-9 d-flex justify-content-center align-items-center">
                            <div>
                                <div class="font-size-h1 font-w300 text-black">{{$data['VEN']}} VEN</div>
                                <div class="font-w600 mt-2 text-uppercase text-muted">VeChain Balance</div><hr>
                                <div class="font-size-h1 font-w300 text-black">1 VEN = {{$VEN}} USD</div>
                            </div>
                        </div>
                    
                </div>

                <div class="col-6 col-md-4 js-appear-enabled animated fadeIn" data-toggle="appear">
                  
                        <div class="block-content block-content-full aspect-ratio-16-9 d-flex justify-content-center align-items-center">
                            <div>
                                <div class="font-size-h1 font-w300 text-black">{{$data['OMG']}} OMG</div>
                                <div class="font-w600 mt-2 text-uppercase text-muted">OmiseGO Balance</div><hr>
                                <div class="font-size-h1 font-w300 text-black">1 OMG = {{$OMG}} USD</div>
                            </div>
                        </div>
                    
                </div>
      
        </div>
        
    </div>

@endsection

