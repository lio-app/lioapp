@extends('admin.layout.base')

@section('title', 'Add Provider ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
            <a href="{{ route('admin.user.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i>@lang('admin.back')</a>

			<h5 style="margin-bottom: 2em;">{{ $User->first_name.' '.$User->last_name }} : {{$Coin->coin}} )</h5>

            <form class="form-horizontal" action="{{route('admin.savecoin')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}


            	<input type="hidden" value="{{ $Coin->id }}" name="" >

				<div class="form-group row">
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{$Coin->value}}" name="amount" required id="amount" placeholder="Enter Amount">
						<input class="form-control" type="hidden" value="{{$Coin->id}}" name="id" required  >
					</div>
				</div>

			

				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">@lang('admin.update')</button>
						<a href="{{route('admin.user.index')}}"  class="btn btn-default">@lang('admin.cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection