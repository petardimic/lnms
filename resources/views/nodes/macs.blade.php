@extends('app')

@section('title', 'nodes.macs')

@section('content')
 <h1>nodes.macs</h1>

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

@if (count($macs))

 <table class="table table-bordered table-hover">
  <thead>
   <tr>
    <th>macAddress</th>
    <th>Port</th>
    <th>VLAN</th>
   </tr>
  </thead>
  <caption style="caption-side: top; text-align: right;">
   {!! $macs->render() !!}
  </caption>
  <tbody>
    @foreach ($macs as $mac)
     <tr>
      <td>{{ $mac->macAddress }}</td>
      <td>{{ $mac->port->dsp_ifDescr }}</td>
      <td>{{ $mac->vlan->vlanIndex }}</td>
     </tr>
    @endforeach
  </tbody>
 </table>
@else
 <p>no macs data</p>
@endif
<a href="/nodes/{{ $node->id }}" class="btn btn-default">Back</a>

 {!! Form::close() !!}

@stop
