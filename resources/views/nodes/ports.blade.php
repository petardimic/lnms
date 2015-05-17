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

{!! Form::open(['id'     => 'form',
                'method' => 'PATCH' ]) !!}
@if (count($ports))

 <table class="table table-bordered table-hover">
  <thead>
   <tr>
    <th>Polling</th>
    <th>ifIndex</th>
    <th>portIndex</th>
    <th>Name</th>
    <th>Description</th>
    <th>IP Address</th>
    <th>Type</th>
    <th>Status</th>
    <th>Speed</th>
   </tr>
  </thead>
  <caption style="caption-side: top; text-align: right;">
   {!! $ports->render() !!}
  </caption>
  <tbody>
    @foreach ($ports as $port)
     <tr>
      <td>

       @if ($port['poll_enabled'] == 'Y')
        <input type="checkbox" id="poll_enabled_{{ $port['ifIndex'] }}" 
               name="poll_enabled[{{ $port['ifIndex'] }}]" checked >
       @else
        <input type="checkbox" id="poll_enabled_{{ $port['ifIndex'] }}" 
               name="poll_enabled[{{ $port['ifIndex'] }}]" >
       @endif
      </td>
      <td>{{ $port['ifIndex'] }}</td>
      <td>{{ $port['portIndex'] }}</td>
      <td><a href="/ports/{{ $port->id }}">{{ $port->dsp_ifDescr }}</a></td>
      <td>{{ $port['ifAlias'] }}</td>
      <td>{{ $port->ips->count() ? json_encode($port->ips->lists('ipAddress')) : '' }}</td>

      <td>{{ $port->dsp_ifType }}</td>
      <td>{{ $port['dsp_ifStatus'] }}</td>
      <td>{{ $port->dsp_ifSpeed }}</td>
     </tr>
    @endforeach
  </tbody>
 </table>
  {!! Form::submit('Update', ['class' => 'btn btn-primary', 'id' => 'submit']) !!}
@else
 <p>no ports data</p>
@endif
<a href="/nodes/{{ $node->id }}" class="btn btn-default">Back</a>

 {!! Form::close() !!}

@stop
