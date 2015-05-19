@extends('app')

@section('title', 'users.create')

@section('breadcrumb')
 <li><a href="/users">users</a></li>
 <li class="active">create</li>
@stop

@section('content')
 <h1>users.create</h1>
 {!! Form::open(['id'    => 'form',
                 'url'   => 'users',
                 'class' => 'form-horizontal']) !!}
  @include('users.form', ['submitButtonText' => 'Add User',
                          'cancelHref' => '/users',])
 {!! Form::close() !!}

 @include('errors.list')
@stop
