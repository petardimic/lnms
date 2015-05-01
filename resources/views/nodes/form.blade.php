<div class="form-group">
 {!! Form::label('name', 'Name') !!}
 {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
 {!! Form::label('ip_address', 'IP Address') !!}
 {!! Form::text('ip_address', null, ['class' => 'form-control']) !!}
</div>

{!! Form::submit($submitButtonText, ['class' => 'btn btn-primary', 'id' => 'submit']) !!}
<a href="{{ $cancelHref }}" class="btn btn-default">Cancel</a>

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
