@extends('admin.layout.base')

@section('title', 'User Documents ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
        

        <div class="box box-block bg-white">
            <h5 class="mb-1">@lang('admin.user_doc')</h5>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('admin.doc_type')</th>
                        <th>@lang('admin.doc')</th>
                        <th>@lang('admin.status')</th>
                        <th>@lang('admin.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($Doc as $Index => $Document)
                    <tr>
                        <td>{{ $Index + 1 }}</td>
                        <td>{{ $Document->document->name }}</td>
                        <td><a href="{{ $Document->url }}" target="_blank">view</a></td>
                        <td>{{ $Document->status}}</td>
                        <td>
                            @if($Document->status=="REJECTED"||$Document->status=="PENDING")
                            <div class="col-xs-6">
                            <form action="{{ route('admin.userdocument.approve')}}" method="POST">
                                {{ csrf_field() }}
                                
                                <input type="hidden" name="status" value="APPROVED">
                                <input type="hidden" name="user_id" value="{{$Document->user->id}}">
                                <input type="hidden" name="doc_id" value="{{$Document->document_id}}">
                                <button class="btn btn-block btn-success" type="submit">Approve</button>
                            </form>
                            </div>
                            @elseif($Document->status=="APPROVED")

                            <div class="col-xs-6">
                                <form action="{{ route('admin.userdocument.approve')}}" method="POST">
                                {{ csrf_field() }}
                                
                                <input type="hidden" name="status" value="REJECTED">
                                <input type="hidden" name="user_id" value="{{$Document->user->id}}">
                                <input type="hidden" name="doc_id" value="{{$Document->document_id}}">
                                <button class="btn btn-block btn-danger" type="submit">Reject</button>
                            </form>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                      <tr>
                        <th>#</th>
                       <th>@lang('admin.doc_type')</th>
                        <th>@lang('admin.doc')</th>
                        <th>@lang('admin.status')</th>
                        <th>@lang('admin.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection