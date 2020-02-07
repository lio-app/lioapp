@extends('admin.layout.base')

@section('content')
<div class="content-area py-1">
        <div class="container-fluid">
            
            <div class="box box-block bg-white">
           
                <h5 class="mb-1">Currency</h5>
                            <a href="{{url('/admin/currency/add')}}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> 
                                Add Currency</a>
                                
                           
                            <table class="table table-striped table-bordered dataTable" id="table-2">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Currency Name</th>
                                        <th>Coin Value</th> 
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Currency Name</th>
                                        <th>Coin Value</th> 
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($currency as $index=>$value)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td>{{$value->currency}}</td>
                                        <td>{{$value->coin_value}}</td>                                        
                                        <td>
                                            <a href="{{url('/admin/currency/edit/'.$value->id)}}" class="btn btn-primary"><i class="fa fa-pencil"></i> Edit</a>
                                        </td>
                                    </tr>    
                                    @endforeach                        
                                </tbody>
                            </table>
                        < </div>
            
        </div>
    </div>
@endsection
