@extends('app')

@section('title', 'nodes.ips')

@section('content')
 <h1>nodes.ips</h1>

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

@if (count($ips))

 <table class="table table-bordered table-hover">
  <thead>
   <tr>
    <th>IP Address</th>
    <th>Port</th>
   </tr>
  </thead>
  <caption style="caption-side: top; text-align: right;">
   {!! $ips->render() !!}
  </caption>
  <tbody>
    @foreach ($ips as $ip)
     <tr>
      <td>{{ $ip->ipAddress }}</td>
      <td>{{ $ip->port->dsp_ifDescr }}</td>
     </tr>
    @endforeach
  </tbody>
 </table>
@else
 <p>no ips data</p>
@endif
<a href="/nodes/{{ $node->id }}" class="btn btn-default">Back</a>

 {!! Form::close() !!}

@stop
