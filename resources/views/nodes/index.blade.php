@extends('app')

@section('content')
<h1>nodes.index</h1>

@if (count($nodes))
 <table class="table table-bordered table-hover">
 <thead>
  <tr>
   <th>Name</th>
   <th>IP Address</th>
  </tr>
 </thead>
 <tbody>
  @foreach ($nodes as $node)
   <tr>
    <td>{{ $node->name }}</td>
    <td>{{ $node->ip_address }}</td>
   </tr>
  @endforeach
 </tbody>
 </table>
@else
 no data<br>
@endif

<a href="/nodes/create" class="btn btn-primary" role="button">nodes.create</a>

@stop
