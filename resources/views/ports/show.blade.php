@extends('app')

@section('title', 'ports.show')

@section('breadcrumb')
 <li><a href="/nodes">nodes</a></li>
 <li><a href="/nodes/{{ $port->node->id }}/ports">{{ $port->node->name }}</a></li>
 <li class="active">{{ $port->ifDescr }}</li>
@stop

@section('content')
 <h1>ports.show</h1>

 @include('ports.info')

 <a href="/ports/{{ $port->id }}/octets" class="btn btn-primary">Port Utilization</a>
 <a href="/nodes/{{ $port->node->id }}/ports" class="btn btn-default">Back</a>
@stop
