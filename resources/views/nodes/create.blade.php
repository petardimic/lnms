@extends('app')

@section('content')
 <h1>nodes.create</h1>
 {!! Form::open(['id'  => 'form',
                 'url' => 'nodes']) !!}
  @include('nodes.form', ['submitButtonText' => 'Add Node',
                          'cancelHref' => '/nodes',])
 {!! Form::close() !!}

 @include('errors.list')
@stop
