@extends('layouts.header')

@section('content')
  <div class="container-fluid">
  <h3>Security</h3>
  <div class="col-md-4 my_div">
    <i class="fa fa-lock"></i> 
    <p>Change Password</p> 
    <a href="{{url('password')}}" class="btn btn-primary prime-color">Change Password</a>
  </div> 
  <div class="col-md-4 my_div">
    <i class="fa fa-shield"></i> 
    <p>Your G2f Authenticator</p>
    @if (Auth::user()->google2fa_secret)
    <a href="{{ url('2fa/disable') }}" class="btn btn-warning">Disable 2FA</a>
    @else
    <a href="{{ url('2fa/enable') }}" class="btn btn-primary prime-color">Enable 2FA</a>
    @endif
    </div>


    <div class="col-md-4 my_div">
    <i class="fa fa-money"></i> 
    <p>Currency Change</p> 
    <a href="{{route('networkpage')}}" class="btn btn-primary prime-color">Change Currency</a>
  </div> 

  </div>
@endsection

