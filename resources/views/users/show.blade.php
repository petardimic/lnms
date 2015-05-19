@extends('app')

@section('title', 'users.show')

@section('breadcrumb')
 <li><a href="/users">users</a></li>
 <li class="active">{{ $user->username }}</li>
@stop

@section('content')
 <h1>users.show</h1>

  @include('users.info')

  <a href="/users/{{ $user->id }}/edit" class="btn btn-primary">Edit User</a>

  {!! Form::button('Delete', ['class' => 'btn btn-danger',
                              'data-toggle' => 'modal',
                              'data-target' => '#confirmModal']) !!}
  <a href="/users" class="btn btn-default">Back</a>

<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="confirmModalLabel">Confirm?</h4>
   </div>
   <div class="modal-body">
    Confirm Delete <strong>{{ $user->username }}</strong>
   </div>
   <div class="modal-footer">

    {!! Form::open(['id'     => 'form',
                    'method' => 'DELETE',
                    'url'    => '/users/' . $user->id]) !!}

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
