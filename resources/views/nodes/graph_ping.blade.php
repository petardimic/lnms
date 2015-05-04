@extends('app')

@section('title', 'nodes.graph_ping')

@section('content')
 <h1>nodes.graph_ping</h1>

 <table class="table table-bordered table-hover">
  <tr>
   <th width="150">Name</th>
   <td>{{ $node->name }}</td>
  </tr>
  <tr>
   <th>IP Address</th>
   <td>{{ $node->ip_address }}</td>
  </tr>
  <tr>
   <th>Ping Success</th>
   <td><a href="/nodes/{{ $node->id }}/graph_ping">{{ $node->ping_success }}%</a></td>
  </tr>
 </table>

 <a href="/nodes/{{ $node->id }}" class="btn btn-default">Back</a>

<hr>

@if (count($node->pings))

    <table class="table table-bordered table-hover">
    <thead>
     <th width="200">Date/Time</th>
     <th>Ping Success</th>
    </thead>
    <tbody>
    @foreach ($node->pings as $ping)
        <tr>
         <td>{{ $ping->created_at }}</td>
         <td>{{ $ping->success }}</td>
        </tr>
    @endforeach
    </tbody>
    </table>
@else
    no data
@endif

@stop

