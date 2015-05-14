@extends('app')

@section('title', 'nodes.bssids')

@section('content')
 <h1>nodes.bssids</h1>

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

@if (count($bssids))

 <table class="table table-bordered table-hover">
  <thead>
   <tr>
    <th>bssidIndex</th>
    <th>ssidName</th>
    <th>Port</th>
   </tr>
  </thead>
  <caption style="caption-side: top; text-align: right;">
   {!! $bssids->render() !!}
  </caption>
  <tbody>
    @foreach ($bssids as $bssid)
     <tr>
      <td>{{ $bssid->bssidIndex }}</td>
      <td>{{ $bssid->ssidName }}</td>
      <td>{{ $bssid->port->dsp_ifDescr }}</td>
     </tr>
    @endforeach
  </tbody>
 </table>
@else
 <p>no bssids data</p>
@endif
<a href="/nodes/{{ $node->id }}" class="btn btn-default">Back</a>

 {!! Form::close() !!}

@stop
