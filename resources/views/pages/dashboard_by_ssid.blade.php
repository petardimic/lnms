@extends('app')

@section('content')

<h3>Dashboard: by SSID</h3>

@if (count($bssids))
<table class="table table-bordered table-hover">
 <thead>
  <tr>
   <th><a href="/bssids?sort=name">Name</a></th>
   <th><a href="/bssids?sort=name">#SSID</a></th>
   <th><a href="/bssids?sort=name">#Client</a></th>
  </tr>
 </thead>
 <tbody>
  @foreach ($bssids as $bssid)
   <tr>
    <td>{{ $bssid->bssidName }}</td>
    <td align="right">{{ $bssid->bssidCount }}</td>
    <td align="right">{{ $bssid->clientCount }}</td>
   </tr>
  @endforeach
 </tbody>
</table>
@else
 no data<br>
 <br>
@endif


@stop
