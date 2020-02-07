@extends('admin.layout.base')

@section('title', 'History')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
         
            <h3> @lang('admin.trans_history')</h3>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th> @lang('user.bank') </th>
                         <th> @lang('user.banks.code') </th>
                        <th>@lang('user.acc_no')</th>
                        <th>@lang('user.amount') </th>
                        <th>@lang('user.status')</th>
                        <th>@lang('user.time')</th>
                         <th> @lang('user.action') </th>         
                    </tr>
                </thead>
                <tbody>
                    @foreach($History as  $index => $trans)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $trans->bank->bank }}</td>
                                    <td>{{ $trans->bank->code }}</td>
                                    <td>{{ $trans->bank->acc_no }}</td>
                                    <td>${{ $trans->amount}}</td>
                                    <td>
                                        @if($trans->status == "PENDING")
                                            <span class="tag tag-warning">Pending</span>
                                        @elseif($trans->status == "SUCCESS")
                                            <span class="tag tag-success">Success</span>
                                        @else
                                            <span class="tag tag-danger">Failed</span>
                                        @endif
                                    </td>
                                    <td>{{ $trans->updated_at->toDayDateTimeString() }}</td>
                                    <td>

                                         @if($trans->status != "FAILED")
                                        <div class="input-group-btn">
                               
                                <button type="button" 
                                    class="btn btn-info btn-block dropdown-toggle"
                                    data-toggle="dropdown">Action
                                    <span class="caret"></span>
                                </button>

                                <ul class="dropdown-menu">

                                    <li>
                                        <a href="{{ route('admin.history.success', $trans->id ) }}" class="btn btn-default" style="color: #008000;"> Success </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.history.failed', $trans->id ) }}" class="btn btn-default" style="color: #FF0000;"> Failed </a>
                                    </li>
                                     
                                </ul>

                            </div>
                            @else
                                <span class="tag tag-danger">Failed</span>
                            @endif
                                    </td>
                                </tr>
                                @endforeach
                     
                </tbody>
                <tfoot>
                     <tr>
                            <th>@lang('admin.id')</th>
                            <th> @lang('user.bank') </th>
                            <th> @lang('user.banks.code') </th>
                            <th>@lang('user.acc_no')</th>
                            <th>@lang('user.amount') </th>
                            <th>@lang('user.status')</th>
                            <th>@lang('user.time')</th>
                            <th> @lang('user.action') </th>
                                   
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection   