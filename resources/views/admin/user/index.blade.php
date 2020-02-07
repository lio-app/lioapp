@extends('admin.layout.base')

@section('title', 'List Users ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
         
            <!-- <a href="{{ route('admin.cointype.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add New User</a> -->
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Balance</th>
                       <!--  <th>View</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($User as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->mobile }}</td>
                            <td>{{ $user->address }}</td>
                            <td>{{ $user->coin }}</td>
                            <!-- <td>
                                <a href="{{ route('admin.user.history', $user->id ) }}">View History</a>
                            </td> -->
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Balance</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection