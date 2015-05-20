@extends('app')

@section('content')
 <h1>WiFi Clients Search</h1>

 {!! Form::open(['id'    => 'form',
                 'url'   => '/bssid_clients',
                 'method' => 'GET',
                 'class' => 'form-horizontal']) !!}

<div class="form-group">
 {!! Form::label('q', 'IP Address / MAC Address', ['class' => 'control-label col-sm-3']) !!}
 <div class="col-sm-3">
  <input name="q" type="text" id="q" value="{{ $q }}">
  <input class="btn btn-primary" id="submit" type="submit" value="Search">

 </div>
</div>

 {!! Form::close() !!}

@if (count($clients))
<table class="table table-bordered table-hover">
 <thead>
  <tr>
   <th>Node</th>
   <th>SSID</th>
   <th>MacAddress</th>
   <th>IpAddress</th>
   <th>UserAgent</th>
   <th>UserType</th>
  </tr>
 </thead>
  <caption style="caption-side: top; text-align: right;">
   {!! $clients->render() !!}
  </caption>
 <tbody>
   @foreach ($clients as $client)
    <tr>
     <td>{{ $client->bssid->node->name }}</td>
     <td>{{ $client->bssid->bssidName }}</td>
     <td>{{ $client->clientMacAddress }}</td>
     <td>{{ $client->clientIpAddress }}</td>
     <td>{{ $client->clientUserAgent }}</td>
     <td>{{ $client->clientUserType }}</td>
    </tr>
   @endforeach
 </tbody>
</table>

@else
 not found
@endif

@stop
