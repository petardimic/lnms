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
 {!! Form::label('poll_enabled', 'Poll Enabled', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::select('poll_enabled', ['yes' => 'yes', 'no' => 'no' ], $node->poll_enabled, ['class' => 'form-control'] ) !!}
 </div>
</div>

<div class="form-group">
 {!! Form::label('snmp_version', 'SNMP Version', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::select('snmp_version', ['disabled' => 'disabled', '1' => '1', '2c' => '2c' ], $node->snmp_version, ['class' => 'form-control']) !!}

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
