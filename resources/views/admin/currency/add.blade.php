@extends('admin.layout.base')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <a href="{{url('/admin/currency/index')}}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.back') </a>

            <h5 style="margin-bottom: 2em;">Currency Add</h5>                          
                           
            <form action="{{route('admin.currency.store')}}" method="POST" class="form-horizontal"  role="form">
                {{csrf_field()}}
                <div class="form-group row">
                    <div class="col-md-4">
                        <label>Currency Name</label>
                        <input type="text" name="currency" class="form-control"  required />
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label>Currency Coin Value</label>
                        <input type="text" name="coin_value" class="form-control" required />
                    </div>
                </div>  
                <div class="form-group row">
                    <div class="col-md-4">
                        <button class="btn btn-success" type="submit"> Submit </button>
                    </div>
                </div>                               
            </form>
        </div>
    </div>
</div>

@endsection
