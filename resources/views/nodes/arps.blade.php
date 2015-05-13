@extends('app')

@section('title', 'nodes.arps')

@section('content')
 <h1>nodes.arps</h1>

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

@if (count($arps))

 <table class="table table-bordered table-hover">
  <thead>
   <tr>
    <th>IP Address</th>
    <th>MAC Address</th>
    <th>Port</th>
   </tr>
  </thead>
  <caption style="caption-side: top; text-align: right;">
   {!! $arps->render() !!}
  </caption>
  <tbody>
    @foreach ($arps as $arp)
     <tr>
      <td>{{ $arp->ipAddress }}</td>
      <td>{{ $arp->macAddress }}</td>
      <td>{{ $arp->port->dsp_ifDescr }}</td>
     </tr>
    @endforeach
  </tbody>
 </table>
@else
 <p>no arps data</p>
@endif
<a href="/nodes/{{ $node->id }}" class="btn btn-default">Back</a>

 {!! Form::close() !!}

@stop
