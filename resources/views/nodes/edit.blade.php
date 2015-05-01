@extends('app')

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
