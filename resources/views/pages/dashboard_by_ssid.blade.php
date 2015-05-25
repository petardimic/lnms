@extends('app')

@section('content')

<h3>Dashboard: by SSID</h3>

@if (count($bssids))
<?php $counter = 1; ?>
<table class="table table-bordered table-hover">
 <thead>
  <tr>
   <th><a href="/bssids?sort=name">#</a></th>
   <th><a href="/bssids?sort=name">Name</a></th>
   <th><a href="/bssids?sort=name">Count</a></th>
  </tr>
 </thead>
 <tbody>
  @foreach ($bssids as $bssid)
   <tr>
    <td>{{ $counter++ }}</td>
    <td><a href="/bssids?bssidName={{ $bssid->bssidName }}">{{ $bssid->bssidName }}</a></td>
    <td align="right">{{ $bssid->bssidCount }}</td>
   </tr>
  @endforeach
 </tbody>
</table>
@else
 no data<br>
 <br>
@endif


@stop
