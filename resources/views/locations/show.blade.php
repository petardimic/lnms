@extends('app')

@section('title', 'locations.show')

@section('breadcrumb')
 <li><a href="/locations">locations</a></li>
 <li class="active">{{ $location->name }}</li>
@stop

@section('content')
 <h1>locations.show</h1>

  @include('locations.info')

  <a href="/locations/{{ $location->id }}/edit" class="btn btn-primary">Edit Location</a>

  {!! Form::button('Delete', ['class' => 'btn btn-danger',
                              'data-toggle' => 'modal',
                              'data-target' => '#confirmModal']) !!}
  <a href="/locations" class="btn btn-default">Back</a>

<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="confirmModalLabel">Confirm?</h4>
   </div>
   <div class="modal-body">
    Confirm Delete <strong>{{ $location->name }}</strong>
   </div>
   <div class="modal-footer">

    {!! Form::open(['id'     => 'form',
                    'method' => 'DELETE',
                    'url'    => '/locations/' . $location->id]) !!}

     {!! Form::submit('Confirm Delete', ['id'    => 'submit',
                                         'class' => 'btn btn-danger']) !!}
     <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    {!! Form::close() !!}
   </div>
  </div>
 </div>
</div>

@stop

@section('footer')
<script>
$(document).ready(function() {
 $("#form").submit(function() {
  $("#submit").prop("disabled", true);
  $("#submit").val("Please wait...");
 });
});
</script>
@stop
