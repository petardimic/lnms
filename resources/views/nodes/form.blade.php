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
 {!! Form::label('location_id', 'Location', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">


<select class="form-control" name="location_id">
<?php
// {!! Form::select('location_id', \App\Location::all_select(), $node->location_id, ['class' => 'form-control'] ) !!}

$locations = \App\Location::where('id', '>', 1)
                          ->get();
foreach ($locations as $location) {
    print '<option ';
    if ($location->id == $node->location_id) {
        print 'selected';
    }

    print ' value="' . $location->id  . '">';

    if ($location->parent_id > 0) {
        print ' &nbsp;&middot; ';
    }

    print $location->name . '</option>';
}

?>
</select>


 </div>
</div>

<div class="form-group">
 {!! Form::label('project_id', 'Project', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::select('project_id', \App\Project::all_select(), $node->project_id, ['class' => 'form-control'] ) !!}
 </div>
</div>

<div class="form-group">
 {!! Form::label('nodegroup_id', 'Nodegroup', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::select('nodegroup_id', \App\Nodegroup::all_select(), $node->nodegroup_id, ['class' => 'form-control'] ) !!}
 </div>
</div>

<div class="form-group">
 {!! Form::label('poll_enabled', 'Poll Enabled', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::select('poll_enabled', ['Y' => 'yes', 'N' => 'no' ], $node->poll_enabled, ['class' => 'form-control'] ) !!}
 </div>
</div>

<div class="form-group">
 {!! Form::label('snmp_version', 'SNMP Version', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::select('snmp_version', ['0' => 'disabled', '1' => '1', '2' => '2c' ], $node->snmp_version, ['class' => 'form-control']) !!}

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
