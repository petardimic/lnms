@extends('app')

@section('title', 'usergroups.create')

@section('breadcrumb')
 <li><a href="/usergroups">usergroups</a></li>
 <li class="active">create</li>
@stop

@section('content')
 <h1>usergroups.create</h1>
 {!! Form::open(['id'    => 'form',
                 'url'   => 'usergroups',
                 'class' => 'form-horizontal']) !!}
  @include('usergroups.form', ['submitButtonText' => 'Add Usergroup',
                          'cancelHref' => '/usergroups',])
 {!! Form::close() !!}

 @include('errors.list')
@stop
