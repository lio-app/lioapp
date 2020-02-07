@extends('admin.layout.base')

@section('title', 'Users ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
          
            <h5 class="mb-1">
                @lang('admin.users.Users')
                
            </h5>
          <!--   <a href="{{ route('admin.user.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add New User</a> -->
          <a href="{{url('/admin/userallblock/')}}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> Over All User Block</a>
          <br>
          <br>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>@lang('admin.name')</th>
                        <th>@lang('admin.email')</th>
                        <th>Phrase</th>
                        <th>@lang('admin.address')</th>
                        <th>@lang('admin.mobile')</th>
                        <th>Balance</th>
                        <th>View History</th>
                        <th>Action</th>
                       
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phrase_word }}</td>
                        <td>{{ $user->address }}</td>
                        <td>{{ $user->mobile }}</td>
                        <td>{{ $user->wallet }}</td>
                        <td><a href="{{ route('admin.user.history', $user->id ) }}">View History</a></td>
                        <td>@if($user->block_status==1)
                                <a href="{{url('/admin/userblock/'.$user->id)}}" class="btn btn-info"><i class="fa fa-pencil"></i> Block</a>
                            @else
                                <a href="{{url('/admin/userblock/'.$user->id)}}" class="btn btn-danger"><i class="fa fa-pencil"></i> Un Block</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>@lang('admin.name')</th>
                        <th>@lang('admin.email')</th>
                        <th>Phrase</th>
                        <th>@lang('admin.address')</th>
                        <th>@lang('admin.mobile')</th>
                        <th>View History</th>
                        <th>Action</th>
                        
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection