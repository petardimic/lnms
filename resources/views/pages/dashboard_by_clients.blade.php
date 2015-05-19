@extends('app')

@section('content')

<h3>Dashboard: Top 10 WiFi Clients Connected</h3>

@if (count($bssids))
<table class="table table-bordered table-hover">
 <thead>
  <tr>
   <th>Node</th>
   <th>SSID</a></th>
   <th>#Clients</th>
  </tr>
 </thead>
 <tbody>
  @foreach ($bssids as $bssid)
   <tr>
    <td><a href="/nodes/{{ $bssid->node->id }}">{{ $bssid->node->name }}</a></td>
    <td>{{ $bssid->bssidName }}</td>
    <td align="right">{{ $bssid->bssidClients_count }}</td>
   </tr>
  @endforeach
 </tbody>
</table>
@else
 no data<br>
 <br>
@endif

@stop
