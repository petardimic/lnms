@extends('app')

@section('title', 'nodes.show')

@section('breadcrumb')
 <li><a href="/nodes">nodes</a></li>
 <li class="active">{{ $node->name }}</li>
@stop

@section('content')
 <h1>nodes.show</h1>

 <table class="table table-bordered table-hover">
  <tr>
   <th width="150">Name</th>
   <td>{{ $node->name }}</td>
  </tr>
  <tr>
   <th>IP Address</th>
   <td>{{ $node->ip_address }}</td>
  </tr>
  <tr>
   <th>Ping Success</th>
   <td><a href="/nodes/{{ $node->id }}/graph_ping">{{ $node->ping_success }}%</a></td>
  </tr>
  <tr>
   <th>SNMP Success</th>
   <td><a href="/nodes/{{ $node->id }}/graph_snmp">{{ $node->snmp_success }}%</a></td>
  </tr>
 </table>


  <a href="/nodes/{{ $node->id }}/ports" class="btn btn-primary">Ports</a>

  <a href="/nodes/{{ $node->id }}/discover" class="btn btn-primary">Discover Node</a>

  <a href="/nodes/{{ $node->id }}/test" class="btn btn-primary">Test Node</a>

  <a href="/nodes/{{ $node->id }}/edit" class="btn btn-primary">Edit Node</a>
  {!! Form::button('Delete', ['class' => 'btn btn-danger',
                              'data-toggle' => 'modal',
                              'data-target' => '#confirmModal']) !!}
  <a href="/nodes" class="btn btn-default">Back</a>

<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="confirmModalLabel">Confirm?</h4>
   </div>
   <div class="modal-body">
    Confirm Delete <strong>{{ $node->name }}</strong>
   </div>
   <div class="modal-footer">
    {!! Form::open(['id'     => 'form',
                    'method' => 'DELETE',
                    'url'    => '/nodes/' . $node->id]) !!}

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
