@extends('app')

@section('title', 'roles.edit')

@section('breadcrumb')
 <li><a href="/roles">roles</a></li>
 <li><a href="/roles/{{ $role->id }}">{{ $role->name }}</a></li>
 <li class="active">edit</li>
@stop

@section('content')
 <h1>roles.edit</h1>
 {!! Form::model($role, ['id'     => 'form',
                             'method' => 'PATCH',
                             'url'    => 'roles/' . $role->id,
                             'class' => 'form-horizontal']) !!}
  @include('roles.form', ['submitButtonText' => 'Update Node',
                          'cancelHref'       => '/roles/' . $role->id ] )
 {!! Form::close() !!}

 @include('errors.list')
@stop
