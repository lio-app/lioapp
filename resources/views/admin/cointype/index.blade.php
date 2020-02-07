@extends('admin.layout.base')

@section('title', 'Coin Types ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
         
            <a href="{{ route('admin.cointype.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add New Coin Type</a>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                       <th>ID</th>
                        <th>Coin Name</th>
                        <th>Coin Symbol</th>
                        <!-- <th>Coin Address</th> -->
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($Coin as $index => $coin)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $coin->name }}</td>
                        <td>{{ $coin->symbol }}</td>
                        <!-- <td>{{ $coin->address }}</td> -->
                        
                         <td>    
                                @if($coin->status == '1')
                                <a class="btn btn-danger btn-block" href="{{ route('admin.cointype.disableStatus', $coin->id ) }}">@lang('Disable')</a>
                                @else
                                <a class="btn btn-success btn-block" href="{{ route('admin.cointype.enableStatus', $coin->id ) }}">@lang('Enable')</a>
                                @endif
                        
                        
                        </td>
                        <td>
                            <form action="{{ route('admin.cointype.destroy', $coin->id) }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                               
                                <a href="{{ route('admin.cointype.edit', $coin->id) }}" class="btn btn-info"><i class="fa fa-pencil"></i>Edit</a>
                                <button class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i>Delete</button>
                                
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Coin Name</th>
                        <th>Coin Symbol</th>
                        <!-- <th>Coin Address</th> -->
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection