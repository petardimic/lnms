@extends('app')

@section('title', 'projects.edit')

@section('breadcrumb')
 <li><a href="/projects">projects</a></li>
 <li><a href="/projects/{{ $project->id }}">{{ $project->name }}</a></li>
 <li class="active">edit</li>
@stop

@section('content')
 <h1>projects.edit</h1>
 {!! Form::model($project, ['id'     => 'form',
                             'method' => 'PATCH',
                             'url'    => 'projects/' . $project->id,
                             'class' => 'form-horizontal']) !!}
  @include('projects.form', ['submitButtonText' => 'Update Node',
                          'cancelHref'       => '/projects/' . $project->id ] )
 {!! Form::close() !!}

 @include('errors.list')
@stop
