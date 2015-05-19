@extends('app')

@section('title', 'usergroups.edit')

@section('breadcrumb')
 <li><a href="/usergroups">usergroups</a></li>
 <li><a href="/usergroups/{{ $usergroup->id }}">{{ $usergroup->name }}</a></li>
 <li class="active">edit</li>
@stop

@section('content')
 <h1>usergroups.edit</h1>
 {!! Form::model($usergroup, ['id'     => 'form',
                             'method' => 'PATCH',
                             'url'    => 'usergroups/' . $usergroup->id,
                             'class' => 'form-horizontal']) !!}
  @include('usergroups.form', ['submitButtonText' => 'Update Node',
                          'cancelHref'       => '/usergroups/' . $usergroup->id ] )
 {!! Form::close() !!}

 @include('errors.list')
@stop
