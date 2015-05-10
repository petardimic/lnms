@extends('app')

@section('title', 'nodes.discover')

@section('content')
 <h1>nodes.discover</h1>

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

 <p>Discover Result: {{ $discover_result }}</p>
<a href="/nodes/{{ $node->id }}" class="btn btn-default">Back</a>

@stop
