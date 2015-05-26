@extends('app')

@section('title', 'permissions.create')

@section('breadcrumb')
 <li><a href="/permissions">permissions</a></li>
 <li class="active">create</li>
@stop

@section('content')
 <h1>permissions.create</h1>
 {!! Form::open(['id'    => 'form',
                 'url'   => 'permissions',
                 'class' => 'form-horizontal']) !!}
  @include('permissions.form', ['submitButtonText' => 'Add Permission',
                          'cancelHref' => '/permissions',])
 {!! Form::close() !!}

 @include('errors.list')
@stop
