@if (count($errors) > 0)
<div class="col-md-12">
   <div class="container" style="margin-top: 25px;">
       <div class="alert alert-danger" style="position: initial;">
           <button type="button" class="close" data-dismiss="alert">×</button>
           <ul>
               @foreach ($errors->all() as $error)
                   <li>{{ $error }}</li>
               @endforeach
           </ul>
       </div>
   </div>
</div>
@endif

@if(Session::has('flash_error'))
   <div class="col-md-12">
       <div class="container" style="margin-top: 25px;" style="position: initial;">
           <div class="alert alert-danger">
               <button type="button" class="close" data-dismiss="alert">×</button>
               {{ Session::get('flash_error') }}
           </div>
       </div>
   </div>
@endif


@if(Session::has('flash_success'))
   <div class="col-md-12">
       <div class="container" style="margin-top: 25px;" style="position: initial;">
           <div class="alert alert-success">
               <button type="button" class="close" data-dismiss="alert">×</button>
               {{ Session::get('flash_success') }}
           </div>
       </div>
   </div>
@endif

@if(Session::has('flash_warning'))
   <div class="col-md-12">
       <div class="container" style="margin-top: 25px;" style="position: initial;">
           <div class="alert alert-warning">
               <button type="button" class="close" data-dismiss="alert">×</button>
               {{ Session::get('flash_warning') }}
           </div>
       </div>
   </div>
@endif