@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Google Authentication</div>

                <div class="panel-body">
                    Google Authentication Disabled
                    <br /><br />
                    <a href="{{ url('/home') }}">Go Home</a>
                </div>
            </div>
        </div>
    </div>    
</div>
@endsection