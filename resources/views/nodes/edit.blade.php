@extends('app')

@section('content')
 <h1>nodes.edit</h1>
 {!! Form::model($node, ['id'     => 'form',
                         'method' => 'PATCH',
                         'url'    => 'nodes/' . $node->id ]) !!}
  @include('nodes.form', ['submitButtonText' => 'Update Node',
                          'cancelHref'       => '/nodes/' . $node->id ] )
 {!! Form::close() !!}

 @include('errors.list')
@stop
