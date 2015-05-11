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
   <th><a href="/nodes?q={{ $q }}&sort=poll_class">Poll Class</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=ping_success">Ping Success</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=snmp_success">SNMP Success</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=ports">#Ports</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=vlans">#VLANs</a></th>
  </tr>
 </thead>
 <tbody>
  @foreach ($nodes as $node)
   <tr>
    <td><a href="/nodes/{{ $node->id }}">{{ $node->name }}</a></td>
    <td>{{ $node->ip_address }}</td>
    <td>{{ $node->location_id == '' ? '' : \App\Location::find($node->location_id)->name }}</td>
    <td>{{ $node->dsp_poll_enabled }}</td>
    <td>{{ $node->poll_class }}</td>
    <td>{{ $node->ping_success }}%</td>
    <td>{{ $node->snmp_success }}%</td>
    <td><a href="/nodes/{{ $node->id }}/ports">{{ $node->ports->count() }}</a></td>
    <td><a href="/nodes/{{ $node->id }}/vlans">{{ $node->vlans->count() }}</a></td>
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
