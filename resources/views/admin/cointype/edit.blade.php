@extends('admin.layout.base')

@section('title', 'Add cointype Type ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <a href="{{ route('admin.cointype.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Back</a>

            <h5 style="margin-bottom: 2em;">Add Coin Type</h5>

            <form class="form-horizontal" action="{{route('admin.cointype.update', $Coin->id )}}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}
                <input type="hidden" name="_method" value="PATCH">
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Coin Name</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" value="{{ $Coin->name }}" name="name" required id="name" placeholder="Coin Name">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="symbol" class="col-sm-2 col-form-label">Coin Symbol</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" value="{{ $Coin->symbol }}" name="symbol" required id="symbol" placeholder="Coin Symbol">
                    </div>
                </div>

                <!-- <div class="form-group row">
                    <label for="address" class="col-sm-2 col-form-label">Coin Address</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" value="{{ $Coin->address }}" name="address" required id="address" placeholder="Address">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="qr_code" class="col-sm-2 col-form-label">
                   QR Code</label>
                    <div class="col-sm-10">
                        <input type="file" accept="image/*" name="qr_code" class="dropify form-control-file" id="qr_code" aria-describedby="fileHelp">
                    </div>
                </div> -->

            
                <div class="form-group row">
                    <div class="col-xs-10">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <a href="{{ route('admin.cointype.index') }}" class="btn btn-danger btn-block">Cancel</a>
                            </div>
                            <div class="col-xs-12 col-sm-6 offset-md-6 col-md-3">
                                <button type="submit" class="btn btn-primary btn-block">
                                Update Coin Type</button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>
@endsection
