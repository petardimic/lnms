@extends('app')

@section('title', 'ports.octets')

@section('breadcrumb')
 <li><a href="/nodes">nodes</a></li>
 <li><a href="/nodes/{{ $port->node->id }}/ports">{{ $port->node->name }}</a></li>
 <li class="active">{{ $port->ifDescr }}</li>
@stop

@section('content')
 <h1>ports.octets</h1>

 @include('ports.info')

 @if (count($octets))

    <table class="table table-bordered table-hover">
    <thead>
     <th width="200">Date/Time</th>
     <th>Input</th>
     <th>Output</th>
    </thead>
    <tbody>
    @foreach ($octets as $octet)
        <tr>
         <td>{{ $octet->timestamp }}</td>
         <td>{{ $octet->input }}</td>
         <td>{{ $octet->output }}</td>
        </tr>
    @endforeach
    </tbody>
    <caption style="caption-side: bottom; text-align: right;">
     {!! $octets->render() !!}
    </caption>
    </table>
@else
    no data
@endif

 <a href="/ports/{{ $port->id }}" class="btn btn-default">Back</a>

@stop
