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
                        <th>Id</th>
                        <th>From / To </th>
                        <th>Category</th>
                        <th>Transction ID</th>
                        <th>Amount</th>                        
                    </tr>
                </thead>
               <tbody> 
                        @foreach( $details as $index => $details)
                        <tr>
                           <td>{{ $index + 1 }}</td>
                            <td>{{$details['address'] }}</td>
                            <td>{{$details['category'] }}</td>
                            <td><a href="/tx/{{$details['txid']}}">{{$details['txid'] }}</a></td>
                            <td>{{$details['amount'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                <tfoot>
                    <tr>
                        <th>Id</th>
                         <th>From / To </th>
                            <th>Category</th>
                            <th>Transction ID</th>
                            <th>Amount</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
