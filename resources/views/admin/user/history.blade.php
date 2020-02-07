@extends('admin.layout.base')

@section('title', 'User details ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
          
            <a href="{{ route('admin.user.index') }}" class="btn btn-default pull-right">
                <i class="fa fa-angle-left"></i> Back
            </a>
            <div class="row">
              
                <div class="col-md-12">
		            <h3>@lang('admin.trans_history')</h3>
		            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>@lang('admin.user')</th>
                        <th>@lang('admin.via')</th>
                        <th>@lang('user.amount')</th>
                        <th>@lang('admin.crypto_value')</th>
                        <th>@lang('user.status')</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($History as $index=>$history)
                    <tr>
                        
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $history->user->first_name }} {{ $history->user->last_name }}</td>
                        <td>{{ $history->coin }}</td>
                        <!--  <td>
                                        @if($history->to_user == "")
                                            {{Auth::user()->first_name}} {{Auth::user()->last_name}}
                                        @else
                                            {{ $history->from->first_name }} {{ $history->from->last_name }}

                                        @endif
                                    </td> -->
                        <td>{{ currency($history->amount) }}</td>
                        <td>{{ $history->crypto_value }}{{ $history->coin }}</td>
                        <td>
                            @if($history->status == "CREDITED")
                            <p style="color: green;">{{ $history->status }}</p>
                            @else($history->status == "DEBITED")
                            <p style="color: blue;">{{ $history->status }}</p>
                            @endif
                        </td>
                    </tr>
                     @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>@lang('admin.user')</th>
                        <th>@lang('admin.via')</th>
                        <th>@lang('user.amount')</th>
                        <th>@lang('admin.crypto_value')</th>
                        <th>@lang('user.status')</th>
                    </tr>
                </tfoot>
            </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection