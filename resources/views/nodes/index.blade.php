@extends('app')

@section('title', 'nodes.index')

@section('breadcrumb')
 <li class="active">nodes</li>
@stop

@section('content')
<h1>nodes.index</h1>

<form class="form-inline">
  <div class="form-group">
    <label class="sr-only" for="q">Name or IP Address</label>
    <input type="text" class="form-control" id="q" name="q" placeholder="Name or IP Address" value="{{ $q }}">
  </div>
  <button type="submit" class="btn btn-primary">search</button>
</form>
<br>

@if (count($nodes))
 <table class="table table-bordered table-hover">
 <thead>
  <tr>
   <th><a href="/nodes?q={{ $q }}&sort=name">Name</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=ip_address">IP Address</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=location_id">Location</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=poll_enabled">Poll Enabled</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=snmp_success">SNMP Version</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=ping_success">Ping Success</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=snmp_success">SNMP Success</a></th>
  </tr>
 </thead>
 <tbody>
  @foreach ($nodes as $node)
   <tr>
    <td><a href="/nodes/{{ $node->id }}">{{ $node->name }}</a></td>
    <td>{{ $node->ip_address }}</td>
    <td>{{ $node->location_id }}</td>
    <td>{{ $node->poll_enabled }}</td>
    <td>{{ $node->snmp_version }}</td>
    <td>{{ $node->ping_success }}%</td>
    <td>{{ $node->snmp_success }}%</td>
   </tr>
  @endforeach
 </tbody>
  <caption style="caption-side: bottom; text-align: right;">
   {!! $nodes->appends(['q' => $q])->render() !!}
  </caption>
 </table>
@else
 no data<br>
 <br>
@endif

<a href="/nodes/create" class="btn btn-primary" role="button">nodes.create</a>

@stop
