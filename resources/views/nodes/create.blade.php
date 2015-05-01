@extends('app')

@section('title', 'nodes.create')

@section('content')
 <h1>nodes.create</h1>
 {!! Form::open(['id'    => 'form',
                 'url'   => 'nodes',
                 'class' => 'form-horizontal']) !!}
  @include('nodes.form', ['submitButtonText' => 'Add Node',
                          'cancelHref' => '/nodes',])
 {!! Form::close() !!}

 @include('errors.list')
@stop
