<div class="form-group">
 {!! Form::label('name', 'Name') !!}
 {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
 {!! Form::label('ip_address', 'IP Address') !!}
 {!! Form::text('ip_address', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
 {!! Form::submit($submitButtonText, ['class' => 'form-control btn btn-primary']) !!}
 <a href="{{ $cancelHref }}" class="form-control btn btn-default">Cancel</a>
</div>
