@extends('admin.layout.base')

@section('title', 'Dashboard ')

@section('styles')
    <link rel="stylesheet" href="{{asset('main/vendor/jvectormap/jquery-jvectormap-2.0.3.css')}}">
@endsection

@section('content')
<div class="content-area py-1">
<div class="container-fluid">
    <div class="row row-md">
		<div class="col-lg-3 col-md-6 col-xs-12">
			<div class="box box-block bg-white tile tile-1 mb-2">
				<div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
				<div class="t-content">
					<h6 class="text-uppercase mb-1">TOTAL NO. OF BLOCKS</h6>
					<h1 class="mb-1">{{$getinfo['blocks']}}</h1>
					
				</div>
			</div>
		</div>

		<div class="col-lg-3 col-md-6 col-xs-12">
			<div class="box box-block bg-white tile tile-1 mb-2">
				<div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
				<div class="t-content">
					<h6 class="text-uppercase mb-1">TOTAL NO. OF difficulty</h6>
					<h1 class="mb-1">{{$getinfo['difficulty']}}</h1>
					
				</div>
			</div>
		</div>

		{{--<div class="col-lg-3 col-md-6 col-xs-12">
			<div class="box box-block bg-white tile tile-1 mb-2">
				<div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
				<div class="t-content">
					<h6 class="text-uppercase mb-1">balance</h6>
					<h1 class="mb-1">{{$getinfo['balance']}}</h1>
					
				</div>
			</div>
		</div>--}}

		<!--{{--<div class="col-lg-3 col-md-6 col-xs-12">
			<div class="box box-block bg-white tile tile-1 mb-2">
				<div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
				<div class="t-content">
					<h6 class="text-uppercase mb-1">TOTAL NO. OF connections</h6>
					<h1 class="mb-1">{{$getinfo['connections']}}</h1>
					
				</div>
			</div>
		</div>

		<div class="col-lg-3 col-md-6 col-xs-12">
			<div class="box box-block bg-white tile tile-1 mb-2">
				<div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
				<div class="t-content">
					<h6 class="text-uppercase mb-1">TOTAL NO. OF walletversion</h6>
					<h1 class="mb-1">{{$getwalletinfo['walletversion']}}</h1>
					
				</div>
			</div>
		</div>

		<div class="col-lg-3 col-md-6 col-xs-12">
			<div class="box box-block bg-white tile tile-1 mb-2">
				<div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
				<div class="t-content">
					<h6 class="text-uppercase mb-1">TOTAL NO. OF UNCONFIRMED BALANCE</h6>
					<h1 class="mb-1">{{$getwalletinfo['unconfirmed_balance']}}</h1>
					
				</div>
			</div>
		</div>

		<div class="col-lg-3 col-md-6 col-xs-12">
			<div class="box box-block bg-white tile tile-1 mb-2">
				<div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
				<div class="t-content">
					<h6 class="text-uppercase mb-1">TOTAL NO. OF immature balance</h6>
					<h1 class="mb-1">{{$getwalletinfo['immature_balance']}}</h1>
					
				</div>
			</div>
		</div>--}} -->

		<div class="col-lg-3 col-md-6 col-xs-12">
			<div class="box box-block bg-white tile tile-1 mb-2">
				<div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
				<div class="t-content">
					<h6 class="text-uppercase mb-1">TOTAL NO. OF TX COUNT</h6>
					<h1 class="mb-1">{{$getwalletinfo['txcount']}}</h1>					
				</div>
			</div>
		</div>

		<div class="col-lg-3 col-md-6 col-xs-12">
			<div class="box box-block bg-white tile tile-1 mb-2">
				<div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
				<div class="t-content">
					<h6 class="text-uppercase mb-1">TOTAL NO. OF USERS</h6>
					<h1 class="mb-1">{{$user}}</h1>					
				</div>
			</div>
		</div>

		<div class="col-lg-3 col-md-6 col-xs-12">
			<div class="box box-block bg-white tile tile-1 mb-2">
				<div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
				<div class="t-content">
					<h6 class="text-uppercase mb-1">Total Supply</h6>
					<h1 class="mb-1">{{$supply}}</h1>					
				</div>
			</div>
		</div>

		<div class="col-lg-3 col-md-6 col-xs-12">
			<div class="box box-block bg-white tile tile-1 mb-2">
				<div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
				<div class="t-content">
					<h6 class="text-uppercase mb-1">Total User Supply</h6>
					<h1 class="mb-1">{{$user_total_balance}}</h1>					
				</div>
			</div>
		</div>

		<!-- <div class="col-lg-3 col-md-6 col-xs-12">
			<div class="box box-block bg-white tile tile-1 mb-2">
				<div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
				<div class="t-content">
					<h6 class="text-uppercase mb-1">Coin Value</h6>
					<h1 class="mb-1">{{$coin_value}}</h1>
					
				</div>
			</div>
		</div> -->	
	</div>
	</div>
</div>
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
         
            <h3> @lang('admin.trans_history')</h3>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
	                    	<th>Id</th>
	                        <th>From / To </th>
                            <th>Transction ID</th>
                            <th>Amount</th>
                            <th>Category</th>
                        
                    </tr>
                </thead>
               <tbody> @if($details != "")
                        @foreach($details as $index => $detail )
                        <tr>
                           	<td>{{ $index + 1 }}</td>
                            <td>{{$detail['address'] }}</td>
                            <td><a href="https://foxbtc.info/tx/{{$detail['txid']}}">{{$detail['txid'] }}</a></td>
                            <td>{{$detail['amount'] }}</td>
                             <td>{{$detail['category'] }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                        	<td>-</td>
                        	<td>-</td>
                        	<td>-</td>
                        	<td>-</td>
                        </tr>
                        @endif
                    </tbody>
                <tfoot>
                    <tr>
                    	<th>Id</th>
                         	<th>From / To </th>
                            <th>Transction ID</th>
                            <th>Amount</th>
                            <th>Category</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
