@extends('app')

@section('title', 'nodes.vlans')

@section('content')
 <h1>nodes.vlans</h1>

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

@if (count($vlans))

 <table class="table table-bordered table-hover">
  <thead>
   <tr>
    <th>vlanIndex</th>
    <th>vlanName</th>
   </tr>
  </thead>
  <caption style="caption-side: top; text-align: right;">
   {!! $vlans->render() !!}
  </caption>
  <tbody>
    @foreach ($vlans as $vlan)
     <tr>
      <td>{{ $vlan->vlanIndex }}</td>
      <td>{{ $vlan->vlanName }}</td>
     </tr>
    @endforeach
  </tbody>
 </table>
@else
 <p>no vlans data</p>
@endif
<a href="/nodes/{{ $node->id }}" class="btn btn-default">Back</a>

 {!! Form::close() !!}

@stop
