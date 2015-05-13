@extends('app')

@section('title', 'nodes.routes')

@section('content')
 <h1>nodes.routes</h1>

 <table class="table table-bordered table-hover">
  <tr>
   <th width="150">Name</th>
   <td>{{ $node->name }}</td>
  </tr>
  <tr>
   <th>IP Address</th>
   <td>{{ $node->ip_address }}</td>
  </tr>
 </table>

@if (count($routes))

 <table class="table table-bordered table-hover">
  <thead>
   <tr>
    <th>Destination</th>
    <th>Masks</th>
    <th>NextHop</th>
    <th>Port</th>
   </tr>
  </thead>
  <caption style="caption-side: top; text-align: right;">
   {!! $routes->render() !!}
  </caption>
  <tbody>
    @foreach ($routes as $route)
     <tr>
      <td>{{ $route->routeDest }}</td>
      <td>{{ $route->routeMasks }}</td>
      <td>{{ $route->routeNextHop }}</td>
      <td>{{ $route->port->dsp_ifDescr }}</td>
     </tr>
    @endforeach
  </tbody>
 </table>
@else
 <p>no routes data</p>
@endif
<a href="/nodes/{{ $node->id }}" class="btn btn-default">Back</a>

 {!! Form::close() !!}

@stop
