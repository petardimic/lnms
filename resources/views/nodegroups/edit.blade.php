@extends('app')

@section('title', 'nodegroups.edit')

@section('breadcrumb')
 <li><a href="/nodegroups">nodegroups</a></li>
 <li><a href="/nodegroups/{{ $nodegroup->id }}">{{ $nodegroup->name }}</a></li>
 <li class="active">edit</li>
@stop

@section('content')
 <h1>nodegroups.edit</h1>
 {!! Form::model($nodegroup, ['id'     => 'form',
                             'method' => 'PATCH',
                             'url'    => 'nodegroups/' . $nodegroup->id,
                             'class' => 'form-horizontal']) !!}
  @include('nodegroups.form', ['submitButtonText' => 'Update Node',
                          'cancelHref'       => '/nodegroups/' . $nodegroup->id ] )
 {!! Form::close() !!}

 @include('errors.list')
@stop
