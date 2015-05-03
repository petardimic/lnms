@extends('app')

@section('title', 'nodes.discver')

@section('content')
 <h1>nodes.discover</h1>

 <table class="table table-bordered table-hover">
  <tr>
   <th width="150">Name</th>
   <td>{{ $node->name }}</td>
  <tr>
  <tr>
   <th>IP Address</th>
   <td>{{ $node->ip_address }}</td>
  <tr>
 </table>

 <table class="table table-bordered table-hover">
  <tr>
   <th width="150">sysDescr</th>
   <td>{{ $snmp_system['sysDescr'] }}</td>
  </tr>
  <tr>
   <th>sysObjectID</th>
   <td>{{ $snmp_system['sysObjectID'] }}</td>
  </tr>
  <tr>
   <th>sysUpTime</th>
   <td>{{ $snmp_system['sysUpTime'] }}</td>
  </tr>
  <tr>
   <th>sysContact</th>
   <td>{{ $snmp_system['sysContact'] }}</td>
  </tr>
  <tr>
   <th>sysName</th>
   <td>{{ $snmp_system['sysName'] }}</td>
  </tr>
  <tr>
   <th>sysLocation</th>
   <td>{{ $snmp_system['sysLocation'] }}</td>
  </tr>
 </table>

 {!! Form::open(['id'     => 'form',
                 'method' => 'PATCH' ]) !!}
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
    @foreach ($snmp_interfaces as $snmp_interface)
     <tr>
      <td>

       @if ($snmp_interface['poll_enabled'] == 'Y')
        <input type="checkbox" id="poll_enabled_{{ $snmp_interface['ifIndex'] }}" 
               name="poll_enabled[{{ $snmp_interface['ifIndex'] }}]" checked>
       @else
        <input type="checkbox" id="poll_enabled_{{ $snmp_interface['ifIndex'] }}" 
               name="poll_enabled[{{ $snmp_interface['ifIndex'] }}]">
       @endif
      </td>
      <td>{{ $snmp_interface['ifIndex'] }}</td>
      <td>{{ $snmp_interface['ifDescr'] }}</td>
      <td>{{ $snmp_interface['ifType'] }}</td>
      <td>{{ $snmp_interface['ifSpeed'] }}</td>
      <td>{{ $snmp_interface['ifPhysAddress'] }}</td>
      <td>{{ $snmp_interface['ifAdminStatus'] }}</td>
      <td>{{ $snmp_interface['ifOperStatus'] }}</td>
      <td>{{ $snmp_interface['ifName'] }}</td>
      <td>{{ $snmp_interface['ifHighSpeed'] }}</td>
      <td>{{ $snmp_interface['ifAlias'] }}</td>
     </tr>
    @endforeach
  </tbody>
 </table>
  {!! Form::submit('Update Polling Status', ['class' => 'btn btn-primary', 'id' => 'submit']) !!}
<a href="/nodes/{{ $node->id }}" class="btn btn-default">Back</a>
 {!! Form::close() !!}

@stop
