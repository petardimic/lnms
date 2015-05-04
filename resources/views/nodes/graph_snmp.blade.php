@extends('app')

@section('title', 'nodes.graph_snmp')

@section('content')
 <h1>nodes.graph_snmp</h1>

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

@if (count($snmps))

    <table class="table table-bordered table-hover">
    <thead>
     <th width="200">Date/Time</th>
     <th>SNMP sysUpTime</th>
    </thead>
    <tbody>
    @foreach ($snmps as $snmp)
        <tr>
         <td>{{ $snmp->timestamp }}</td>
         <td>{{ $snmp->sysUpTime }}</td>
        </tr>
    @endforeach
    </tbody>
    <caption style="caption-side: bottom; text-align: right;">
     {!! $snmps->render() !!}
    </caption>
    </table>
@else
    no data
@endif

@stop

