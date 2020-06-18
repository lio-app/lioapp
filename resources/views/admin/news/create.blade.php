@extends('admin.layout.base')

@section('title', 'Add News Item ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <a href="{{ route('admin.news.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Back</a>

            <h5 style="margin-bottom: 2em;">Add News Item</h5>

            <form class="form-horizontal" action="{{route('admin.news.store')}}" method="POST"  role="form">
                {{ csrf_field() }}

                <div class="form-group row">
                    <label for="name" class="col-xs-12 col-form-label">Title</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ old('title') }}" name="title" required id="title" placeholder="Title">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="symbol" class="col-xs-12 col-form-label">Description</label>
                    <div class="col-xs-10">
                      <textarea rows="4" cols="50" name="description" id="description" class="form-control ckeditor"></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-xs-10">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <a href="{{ route('admin.cointype.index') }}" class="btn btn-danger btn-block">Cancel</a>
                            </div>
                            <div class="col-xs-12 col-sm-6 offset-md-6 col-md-3">
                                <button type="submit" class="btn btn-primary btn-block">Add News Item</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
