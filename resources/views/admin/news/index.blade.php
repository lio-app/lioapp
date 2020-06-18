@extends('admin.layout.base')
@section('title', 'News ')
@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">

            <a href="{{ route('admin.news.add') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add News Item</a>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                       <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($news as $index => $coin)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $coin->title }}</td>
                        <td style="width:50%;">{{ $coin->description }}</td>
                        <!-- <td>{{ $coin->address }}</td> -->

                         <td>
                                @if($coin->status == '1')
                                <a class="btn btn-danger btn-block" href="{{ route('admin.news.disableStatus', $coin->id ) }}">@lang('Disable')</a>
                                @else
                                <a class="btn btn-success btn-block" href="{{ route('admin.news.enableStatus', $coin->id ) }}">@lang('Enable')</a>
                                @endif


                        </td>
                        <td>
                            <form action="{{ url('admin/news/removeNews') }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{$coin->id}}">
                                <a href="{{ route('admin.news.edit', $coin->id) }}" class="btn btn-info"><i class="fa fa-pencil"></i>Edit</a>
                                <button class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i>Delete</button>

                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                  <tr>
                     <th>ID</th>
                      <th>Title</th>
                      <th>Description</th>
                      <th>Status</th>
                      <th>Action</th>
                  </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
