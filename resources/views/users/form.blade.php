<div class="form-group">
 {!! Form::label('username', 'Username', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::text('username', null, ['class' => 'form-control']) !!}
 </div>
</div>

<div class="form-group">
 {!! Form::label('usergroup_id', 'Usergroup', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::select('usergroup_id', \App\Usergroup::all_select(), $user->usergroup_id, ['class' => 'form-control'] ) !!}
 </div>
</div>

<div class="form-group">
 {!! Form::label('password', 'Password', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::password('password', ['class' => 'form-control']) !!}
 </div>
</div>

<div class="form-group">
 {!! Form::label('password_confirm', 'Confirm Password', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::password('password_confirm', ['class' => 'form-control']) !!}
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
