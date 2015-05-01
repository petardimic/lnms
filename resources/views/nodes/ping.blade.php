@extends('app')

@section('title', 'nodes.ping')

@section('breadcrumb')
 <li><a href="/nodes">nodes</a></li>
 <li><a href="/nodes/{{ $node->id }}">{{ $node->name }}</a></li>
 <li class="active">ping</li>
@stop

@section('content')
 ping node {{ $node->ip_address }}<br>
 <br>
 {{ $ping_result }}<br>
 <br>

  <a href="/nodes/{{ $node->id }}" class="btn btn-default">Back</a>
@stop
