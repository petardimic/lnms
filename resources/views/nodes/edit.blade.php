@extends('app')

@section('title', 'nodes.edit')

@section('breadcrumb')
 <li><a href="/nodes">nodes</a></li>
 <li><a href="/nodes/{{ $node->id }}">{{ $node->name }}</a></li>
 <li class="active">edit</li>
@stop

@section('content')
 <h1>nodes.edit</h1>
 {!! Form::model($node, ['id'     => 'form',
                         'method' => 'PATCH',
                         'url'    => 'nodes/' . $node->id,
                         'class' => 'form-horizontal']) !!}
  @include('nodes.form', ['submitButtonText' => 'Update Node',
                          'cancelHref'       => '/nodes/' . $node->id ] )
 {!! Form::close() !!}

 @include('errors.list')
@stop
