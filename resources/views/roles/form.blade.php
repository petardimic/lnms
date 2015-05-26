<div class="form-group">
 {!! Form::label('user_id', 'User', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::select('user_id', \App\User::all_select(), null, ['class' => 'form-control'] ) !!}
 </div>
</div>

<div class="form-group">
 {!! Form::label('usergroup_id', 'Usergroup', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::select('usergroup_id', \App\Usergroup::all_select(), null, ['class' => 'form-control'] ) !!}
 </div>
</div>

<div class="form-group">
 {!! Form::label('location_id', 'Location', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::select('location_id', \App\Location::all_select(), null, ['class' => 'form-control'] ) !!}
 </div>
</div>

<div class="form-group">
 {!! Form::label('project_id', 'Project', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::select('project_id', \App\Project::all_select(), null, ['class' => 'form-control'] ) !!}
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
