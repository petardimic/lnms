@extends('app')

@section('title', 'locations.create')

@section('breadcrumb')
 <li><a href="/locations">locations</a></li>
 <li class="active">create</li>
@stop

@section('content')
 <h1>locations.create</h1>
 {!! Form::open(['id'    => 'form',
                 'url'   => 'locations',
                 'class' => 'form-horizontal']) !!}
  @include('locations.form', ['submitButtonText' => 'Add Location',
                          'cancelHref' => '/locations',])
 {!! Form::close() !!}

 @include('errors.list')
@stop
