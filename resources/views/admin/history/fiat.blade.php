@extends('admin.layout.base')

@section('title', 'History')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
         
            <h3>@lang('admin.trans_history')</h3>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                       <th>@lang('admin.id')</th>
                        <th>@lang('admin.from') </th>
                        <th>@lang('admin.to') </th>
                        <th>@lang('admin.txn')</th>
                        <th>@lang('admin.payment.payment_method')</th>
                        <th>{{ ico() }} @lang('admin.price')</th>
                        <th>@lang('admin.date_time')</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($History as $index => $history)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $history->fromuser->first_name }} {{ $history->fromuser->last_name }}</td>
                        <td>
                           @if($history->to_user != NULL)
                           {{$history->user->first_name }} {{$history->user->last_name}}
                           @else
                            {{ $history->fromuser->first_name }} {{ $history->fromuser->last_name }}
                           @endif

                       </td>
                        <td>TXN{{ $history->id }}</td>
                        <td>{{ $history->via }}</td>
                        <td>{{ currency($history->amount) }}</td>
                        <td>{{ $history->created_at->toDayDateTimeString() }}</td>
                    </tr>
                    @endforeach
                     
                </tbody>
                <tfoot>
                    <tr>
                       <th>@lang('admin.id')</th>
                        <th>@lang('admin.from') </th>
                        <th>@lang('admin.to') </th>
                        <th>@lang('admin.txn')</th>
                        <th>@lang('admin.payment.payment_method')</th>
                        <th>{{ ico() }} @lang('admin.price')</th>
                        <th>@lang('admin.date_time')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection