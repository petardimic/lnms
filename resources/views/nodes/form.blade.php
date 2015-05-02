<div class="form-group">
 {!! Form::label('name', 'Name', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::text('name', null, ['class' => 'form-control']) !!}
 </div>
</div>

<div class="form-group">
 {!! Form::label('ip_address', 'IP Address', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::text('ip_address', null, ['class' => 'form-control']) !!}
 </div>
</div>

<div class="form-group">
 {!! Form::label('snmp_comm_ro', 'SNMP Community (ro)', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::text('snmp_comm_ro', null, ['class' => 'form-control']) !!}
 </div>
</div>

<div class="form-group">
 <div class="col-sm-offset-2 col-sm-10">
  {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary', 'id' => 'submit']) !!}
  <a href="{{ $cancelHref }}" class="btn btn-default">Cancel</a>
 </div>
</div>

@section('footer')
<script>
$(document).ready(function() {
    $("#form").submit(function(event) {
        $("#submit").val("Please wait...");
        $("#submit").prop("disabled", true);
    });
});
</script>
@stop
