@extends('admin.layout.base')

@section('title', 'User Documents ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
        

        <div class="box box-block bg-white">
           
                      <!-- Account List Box Starts -->

                      <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>@lang('admin.coin')</th>
                        <th>@lang('admin.value')</th>
                        <th>@lang('admin.action')</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td>1</td>
                            <td><img src="{{asset('img/bit-1.png')}}" style="height: 50px; width: 50px;"></td>
                            <td>@if($BTC)
                                                            {{$BTC->value}} {{$BTC->coin}}
                                                        @else
                                                            0 BTC
                                                        @endif</td>
                            <td>
                                <form action="{{route('admin.editcoin')}}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="user_id" value="{{$User->id}}">
                                    <input type="hidden" name="type" value="BTC">
                                    <input type="hidden" name="coin_id" @if($BTC) value="{{$BTC->id}}"  @else value="0"  @endif>
                                    
                                    <button class="btn btn-success" ><i class="fa fa-edit"></i> Edit</button>
                                </form>
                            </td>
                           
                        </tr>

                         <tr>
                            <td>2</td>
                            <td><img src="{{asset('img/bit-2.png')}}" style="height: 50px; width: 50px;"></td>
                            <td>@if($BCH)
                                                            {{$BCH->value}} {{$BCH->coin}}
                                                        @else
                                                            0 BCH
                                                        @endif</td>
                           <td>
                                <form action="{{route('admin.editcoin')}}" method="POST">
                                    {{ csrf_field() }}

                                    <input type="hidden" name="user_id" value="{{$User->id}}">
                                    <input type="hidden" name="type" value="BCH">

                                    <input type="hidden" name="coin_id" @if($BCH) value="{{$BCH->id}}"  @else value="0"  @endif>
                                    
                                    <button class="btn btn-success" ><i class="fa fa-edit"></i> Edit</button>
                                </form>
                            </td>
                        </tr>

                         <tr>
                            <td>3</td>
                            <td><img src="{{asset('img/ethereum.png')}}" style="height: 50px; width: 50px;"></td>
                            <td>@if($ETH)
                                                            {{$ETH->value}} {{$ETH->coin}}
                                                        @else
                                                            0 ETH
                                                        @endif</td>
                            <td>
                                <form action="{{route('admin.editcoin')}}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="user_id" value="{{$User->id}}">
                                    <input type="hidden" name="type" value="ETH">

                                    <input type="hidden" name="coin_id" @if($ETH) value="{{$ETH->id}}"  @else value="0"  @endif>
                                    
                                    <button class="btn btn-success" ><i class="fa fa-edit"></i> Edit</button>
                                </form>
                            </td>
                           
                        </tr>

                         <tr>
                            <td>4</td>
                            <td><img src="{{asset('img/lite-coin.png')}}" style="height: 50px; width: 50px;"></td>
                            <td>@if($LTC)
                                                            {{$LTC->value}} {{$LTC->coin}}
                                                        @else
                                                            0 LTC
                                                        @endif</td>

                            <td>
                                <form action="{{route('admin.editcoin')}}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="user_id" value="{{$User->id}}">
                                    <input type="hidden" name="type" value="LTC">

                                    <input type="hidden" name="coin_id" @if($LTC) value="{{$LTC->id}}"  @else value="0"  @endif>
                                    
                                    <button class="btn btn-success" ><i class="fa fa-edit"></i> Edit</button>
                                </form>
                            </td>
                           
                        </tr>

                         <tr>
                            <td>5</td>
                            <td><img src="{{asset('img/ripple.png')}}" style="height: 50px; width: 50px;"></td>
                            <td>@if($XRP)
                                                            {{$XRP->value}} {{$XRP->coin}}
                                                        @else
                                                            0 XRP
                                                        @endif</td>
                            <td>
                                <form action="{{route('admin.editcoin')}}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="user_id" value="{{$User->id}}">
                                    <input type="hidden" name="type" value="XRP">

                                    <input type="hidden" name="coin_id"  @if($XRP) value="{{$XRP->id}}"  @else value="0"  @endif>
                                    
                                    <button class="btn btn-success" ><i class="fa fa-edit"></i> Edit</button>
                                </form>
                            </td>
                           
                        </tr>

                      <!--   <tr>
                            <td>6</td>
                            <td><img src="{{asset('img/bit.png')}}" style="height: 50px; width: 50px;"></td>
                            <td>{{$User->wallet}} FIAT</td>
                           <td>
                                <form action="{{route('admin.editcoin')}}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="user_id" value="{{$User->id}}">
                                    <input type="hidden" name="type" value="FIAT">

                                    
                                    <button class="btn btn-success" ><i class="fa fa-edit"></i> Edit</button>
                                </form>
                            </td>
                        </tr> -->


                </tbody>
                <tfoot>
                     <tr>
                         <th>@lang('admin.id')</th>
                        <th>@lang('admin.coin')</th>
                        <th>@lang('admin.value')</th>
                        <th>@lang('admin.action')</th>
                    </tr>
                </tfoot>
            </table>
                               

                             
                             
        </div>
    </div>
</div>
@endsection