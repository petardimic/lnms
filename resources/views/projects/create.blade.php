@extends('app')

@section('title', 'projects.create')

@section('breadcrumb')
 <li><a href="/projects">projects</a></li>
 <li class="active">create</li>
@stop

@section('content')
 <h1>projects.create</h1>
 {!! Form::open(['id'    => 'form',
                 'url'   => 'projects',
                 'files' => 'true',
                 'class' => 'form-horizontal']) !!}
  @include('projects.form', ['submitButtonText' => 'Add Project',
                          'cancelHref' => '/projects',])
 {!! Form::close() !!}

 @include('errors.list')
@stop
