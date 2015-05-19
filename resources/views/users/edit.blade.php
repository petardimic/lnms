@extends('app')

@section('title', 'users.edit')

@section('breadcrumb')
 <li><a href="/users">users</a></li>
 <li><a href="/users/{{ $user->id }}">{{ $user->name }}</a></li>
 <li class="active">edit</li>
@stop

@section('content')
 <h1>users.edit</h1>
 {!! Form::model($user, ['id'     => 'form',
                             'method' => 'PATCH',
                             'url'    => 'users/' . $user->id,
                             'class' => 'form-horizontal']) !!}
  @include('users.form', ['submitButtonText' => 'Update Node',
                          'cancelHref'       => '/users/' . $user->id ] )
 {!! Form::close() !!}

 @include('errors.list')
@stop
