@extends('app')

@section('title', 'locations.edit')

@section('breadcrumb')
 <li><a href="/locations">locations</a></li>
 <li><a href="/locations/{{ $location->id }}">{{ $location->name }}</a></li>
 <li class="active">edit</li>
@stop

@section('content')
 <h1>locations.edit</h1>
 {!! Form::model($location, ['id'     => 'form',
                             'method' => 'PATCH',
                             'url'    => 'locations/' . $location->id,
                             'class' => 'form-horizontal']) !!}
  @include('locations.form', ['submitButtonText' => 'Update Node',
                          'cancelHref'       => '/locations/' . $location->id ] )
 {!! Form::close() !!}

 @include('errors.list')
@stop
