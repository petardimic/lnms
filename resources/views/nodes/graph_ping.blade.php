@extends('app')

@section('title', 'nodes.graph_ping')

@section('content')
 <h1>nodes.graph_ping</h1>

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

 <a href="/nodes/{{ $node->id }}" class="btn btn-default">Back</a>

<hr>

@if (count($pings))

    <table class="table table-bordered table-hover">
    <thead>
     <th width="200">Date/Time</th>
     <th>Ping Success</th>
     <th>Response Time (ms.)</th>
    </thead>
    <tbody>
    @foreach ($pings as $ping)
        <tr>
         <td>{{ $ping->timestamp }}</td>
         <td>{{ $ping->success }}</td>
         <td>{{ $ping->microsec / 1000 }}</td>
        </tr>
    @endforeach
    </tbody>
    <caption style="caption-side: bottom; text-align: right;">
     {!! $pings->render() !!}
    </caption>
    </table>
@else
    no data
@endif

@stop

