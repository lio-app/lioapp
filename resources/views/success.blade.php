@extends('layouts.app')

@section('content')

<!-- Modal HTML -->
<div>
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">
      <div class="modal-header">
        <div class="icon-box">
          <i class="material-icons">&#xE876;</i>
        </div>
        
      </div>
      <div class="modal-body text-center">
        <h4>Success!</h4> 
        <p>Your account has been created successfully.</p>
        <button class="btn btn-success" data-dismiss="modal"><span>Start Exploring</span> <i class="material-icons">&#xE5C8;</i></button>
      </div>
    </div>
  </div>
</div>

@endsection
