@extends('app')

@section('title', 'roles.create')

@section('breadcrumb')
 <li><a href="/roles">roles</a></li>
 <li class="active">create</li>
@stop

@section('content')
 <h1>roles.create</h1>
 {!! Form::open(['id'    => 'form',
                 'url'   => 'roles',
                 'class' => 'form-horizontal']) !!}
  @include('roles.form', ['submitButtonText' => 'Add Role',
                          'cancelHref' => '/roles',])
 {!! Form::close() !!}

 @include('errors.list')
@stop
