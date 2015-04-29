@extends('app')

@section('content')
 <h1>nodes.create</h1>
 {!! Form::open(['url' => 'nodes']) !!}
  @include('nodes.form', ['submitButtonText' => 'Add Node',
                          'cancelHref' => '/nodes',])
 {!! Form::close() !!}

 @include('errors.list')
@stop
