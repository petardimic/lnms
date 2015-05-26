@extends('app')

@section('title', 'permissions.edit')

@section('breadcrumb')
 <li><a href="/permissions">permissions</a></li>
 <li><a href="/permissions/{{ $permission->id }}">{{ $permission->name }}</a></li>
 <li class="active">edit</li>
@stop

@section('content')
 <h1>permissions.edit</h1>
 {!! Form::model($permission, ['id'     => 'form',
                             'method' => 'PATCH',
                             'url'    => 'permissions/' . $permission->id,
                             'class' => 'form-horizontal']) !!}
  @include('permissions.form', ['submitButtonText' => 'Update Node',
                          'cancelHref'       => '/permissions/' . $permission->id ] )
 {!! Form::close() !!}

 @include('errors.list')
@stop
