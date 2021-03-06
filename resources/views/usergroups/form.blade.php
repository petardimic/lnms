<div class="form-group">
 {!! Form::label('name', 'Name', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::text('name', null, ['class' => 'form-control']) !!}
 </div>
</div>

<div class="form-group">
 <div class="col-sm-offset-2 col-sm-10">
 @if (count($permissions))

    <?php
        if ( isset($usergroup->id) ) {
            foreach ($usergroup->permissions as $perm) {
                $permissionChecked[$perm->id] = 'checked';
            }
        }

    ?>
    @foreach ($permissions as $permission)
        <p><input type="checkbox" id="permission_{{ $permission->id }}"
                  name="permissions[{{ $permission->id }}]"
                  {{ isset($permissionChecked[$permission->id]) ? 'checked' : '' }}
                  > {{ $permission->name }}</p>
    @endforeach
 @endif
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
