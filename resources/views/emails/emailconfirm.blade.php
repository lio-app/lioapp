@extends('layouts.header')

@section('content')

<div>
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">
      <div class="modal-header">
        <div class="icon-box">
          <i class="material-icons">&#xE876;</i>
        </div>
        
      </div>
      <div class="modal-body text-center">
        <h4>Success!</h4> 
        <p>Your account has been created successfully.</p>
        Your Email is successfully verified. Click here to <a href="{{url('/login')}}">login</a>
      </div>
    </div>
  </div>
</div>
@endsection