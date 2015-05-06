@extends('app')

@section('title', 'nodes.ports')

@section('content')
 <h1>nodes.ports</h1>

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

@if (count($ports))
 <table class="table table-bordered table-hover">
  <thead>
   <tr>
    <th>Polling</th>
    <th>ifIndex</th>
    <th>ifDescr</th>
    <th>ifType</th>
    <th>ifSpeed</th>
    <th>ifPhysAddress</th>
    <th>ifAdminStatus</th>
    <th>ifOperStatus</th>
    <th>ifName</th>
    <th>ifHighSpeed</th>
    <th>ifAlias</th>
   </tr>
  </thead>
  <tbody>
    @foreach ($ports as $port)
     <tr>
      <td>

       @if ($port['poll_enabled'] == 'Y')
        <input type="checkbox" id="poll_enabled_{{ $port['ifIndex'] }}" 
               name="poll_enabled[{{ $port['ifIndex'] }}]" checked disabled>
       @else
        <input type="checkbox" id="poll_enabled_{{ $port['ifIndex'] }}" 
               name="poll_enabled[{{ $port['ifIndex'] }}]" disabled>
       @endif
      </td>
      <td>{{ $port['ifIndex'] }}</td>
      <td><a href="/ports/{{ $port->id }}">{{ $port['ifDescr'] }}</a></td>
      <td>{{ $port['ifType'] }}</td>
      <td>{{ $port['ifSpeed'] }}</td>
      <td>{{ $port['ifPhysAddress'] }}</td>
      <td>{{ $port['ifAdminStatus'] }}</td>
      <td>{{ $port['ifOperStatus'] }}</td>
      <td>{{ $port['ifName'] }}</td>
      <td>{{ $port['ifHighSpeed'] }}</td>
      <td>{{ $port['ifAlias'] }}</td>
     </tr>
    @endforeach
  </tbody>
 </table>
@else
 <p>no ports data</p>
@endif
<a href="/nodes/{{ $node->id }}" class="btn btn-default">Back</a>


@stop
