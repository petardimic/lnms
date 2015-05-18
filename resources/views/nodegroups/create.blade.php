@extends('app')

@section('title', 'nodegroups.create')

@section('breadcrumb')
 <li><a href="/nodegroups">nodegroups</a></li>
 <li class="active">create</li>
@stop

@section('content')
 <h1>nodegroups.create</h1>
 {!! Form::open(['id'    => 'form',
                 'url'   => 'nodegroups',
                 'class' => 'form-horizontal']) !!}
  @include('nodegroups.form', ['submitButtonText' => 'Add Nodegroup',
                          'cancelHref' => '/nodegroups',])
 {!! Form::close() !!}

 @include('errors.list')
@stop
