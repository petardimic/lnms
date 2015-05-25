@extends('app')

@section('title', 'bssids.index')

@section('content')
<h1>bssids.index</h1>

@if (count($bssids))
 <table class="table table-bordered table-hover">
 <thead>
  <tr>
   <th>BSSID Name</th>
   <th>Node Name</th>
   <th>Node IP Address</th>
  </tr>
 </thead>
 <tbody>
  @foreach ($bssids as $bssid)
   <tr>
    <td>{{ $bssid->bssidName }} </td>
    <td><a href="/nodes/{{ $bssid->node->id }}">{{ $bssid->node->name }}</a></td>
    <td>{{ $bssid->node->ip_address }} </td>
   </tr>
  @endforeach
 </tbody>
  <caption style="caption-side: bottom; text-align: right;">
   {!! $bssids->render() !!}
  </caption>
 </table>
@else
 no data<br>
 <br>
@endif

@stop
