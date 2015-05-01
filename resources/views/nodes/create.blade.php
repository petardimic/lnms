@extends('app')

@section('title', 'nodes.create')

@section('breadcrumb')
 <li><a href="/nodes">nodes</a></li>
 <li class="active">create</li>
@stop

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
