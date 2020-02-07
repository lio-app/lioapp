@extends('layouts.header')

@section('content')
<div class="container-fluid">
        <h3>Support</h3>
        <div class="col-md-4 col-sm-6 col-xs-12 my_div">
            <i class="fa fa-phone"></i> 
            <p>Phone</p> 
            <a href="tel:{{Setting::get('contact_no')}}" class="btn btn-primary prime-color">{{Setting::get('contact_no')}}</a>
        </div> 
        <div class="col-md-4 col-sm-6 col-xs-12 my_div">
            <i class="fa fa-envelope"></i> 
            <p>Email</p>
             <a href="mailto:{{Setting::get('contact_email')}}" class="btn btn-primary prime-color">{{Setting::get('contact_email')}}</a>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 my_div">
            <i class="fa fa-globe"></i> 
            <p>Website</p>
             <a href="{{Setting::get('contact_website')}}" target="_blank" class="btn btn-primary prime-color">{{Setting::get('contact_website')}}</a>
            </div>
        </div>

@endsection
