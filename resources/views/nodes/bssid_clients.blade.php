@extends('app')

@section('title', 'nodes.clients')

@section('content')
 <h1>nodes.clients</h1>

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

@if (count($clients))

 <table class="table table-bordered table-hover">
  <thead>
   <tr>
    <th>clientMacAddress</th>
    <th>clientIpAddress</th>
    <th>clientSignalStrength</th>
    <th>clientBytesReceived</th>
    <th>clientBytesSent</th>
   </tr>
  </thead>
  <caption style="caption-side: top; text-align: right;">
   {!! $clients->render() !!}
  </caption>
  <tbody>
    @foreach ($clients as $client)
     <tr>
      <td>{{ $client->clientMacAddress }}</td>
      <td>{{ $client->clientIpAddress }}</td>
      <td>{{ $client->clientSignalStrength }}</td>
      <td>{{ $client->clientBytesReceived }}</td>
      <td>{{ $client->clientBytesSent }}</td>
     </tr>
    @endforeach
  </tbody>
 </table>
@else
 <p>no clients data</p>
@endif
<a href="/nodes/{{ $node->id }}" class="btn btn-default">Back</a>

 {!! Form::close() !!}

@stop
