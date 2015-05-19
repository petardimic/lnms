@extends('app')

@section('title', 'nodes.show')

@section('breadcrumb')
 <li><a href="/nodes">nodes</a></li>
 <li class="active">{{ $node->name }}</li>
@stop

@section('content')
 <h1>nodes.show</h1>

  @include('nodes.info')

@if (count($ports))

 <table class="table table-bordered table-hover">
  <caption>Ports</caption>
  <thead>
   <tr>
    <th>Name</th>
    <th>Description</th>
    <th>IP Address</th>
    <th>Type</th>
    <th>Status</th>
    <th>Speed</th>
   </tr>
  </thead>
  <caption style="caption-side: top; text-align: right;">
   {!! $ports->render() !!}
  </caption>
  <tbody>
    @foreach ($ports as $port)
     <tr>
      <td><a href="/ports/{{ $port->id }}">{{ $port->dsp_ifDescr }}</a></td>
      <td>{{ $port['ifAlias'] }}</td>
      <td>{{ $port->ips->count() ? json_encode($port->ips->lists('ipAddress')) : '' }}</td>

      <td>{{ $port->dsp_ifType }}</td>
      <td>{{ $port['dsp_ifStatus'] }}</td>
      <td>{{ $port->dsp_ifSpeed }}</td>
     </tr>
    @endforeach
  </tbody>
 </table>
@endif

@if (count($bssids))

 <table class="table table-bordered table-hover">
  <caption>SSID</caption>
  <thead>
   <tr>
    <th>Index</th>
    <th>MacAddress</th>
    <th>Name</th>
    <th>Spec</th>
    <th>MaxRate</th>
    <th>CurrentChannel</th>
   </tr>
  </thead>
  <caption style="caption-side: top; text-align: right;">
   {!! $bssids->render() !!}
  </caption>
  <tbody>
    @foreach ($bssids as $bssid)
     <tr>
      <td>{{ $bssid->bssidIndex }}</td>
      <td>{{ $bssid->bssidMacAddress }}</td>
      <td>{{ $bssid->bssidName }}</td>
      <td>{{ $bssid->dsp_bssidSpec }}</td>
      <td>{{ $bssid->bssidMaxRate }}</td>
      <td>{{ $bssid->bssidCurrentChannel }}</td>
     </tr>
    @endforeach
  </tbody>
 </table>
@endif


@if (count($clients))

 <table class="table table-bordered table-hover">
  <caption>WiFi Clients</caption>
  <thead>
   <tr>
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
      <td>{{ $client->clientMacAddress }}</td>
      <td>{{ $client->clientIpAddress }}</td>
      <td>{{ $client->clientUserAgent }}</td>
      <td>{{ $client->clientUserType }}</td>
     </tr>
    @endforeach
  </tbody>
 </table>
@endif

<!--
  <a href="/nodes/{{ $node->id }}/bssid_clients" class="btn btn-primary">Clients</a>

  <a href="/nodes/{{ $node->id }}/bssids" class="btn btn-primary">BSSIDs</a>

  <a href="/nodes/{{ $node->id }}/routes" class="btn btn-primary">Routes</a>

  <a href="/nodes/{{ $node->id }}/arps" class="btn btn-primary">ARPs</a>

  <a href="/nodes/{{ $node->id }}/ips" class="btn btn-primary">IPs</a>

  <a href="/nodes/{{ $node->id }}/macs" class="btn btn-primary">MACs</a>

  <a href="/nodes/{{ $node->id }}/vlans" class="btn btn-primary">VLANs</a>

  <a href="/nodes/{{ $node->id }}/ports" class="btn btn-primary">Ports</a>

-->

  <a href="/nodes/{{ $node->id }}/discover" class="btn btn-primary">Discover Node</a>

  <a href="/nodes/{{ $node->id }}/test" class="btn btn-primary">Test Node</a>

  <a href="/nodes/{{ $node->id }}/edit" class="btn btn-primary">Edit Node</a>
  {!! Form::button('Delete', ['class' => 'btn btn-danger',
                              'data-toggle' => 'modal',
                              'data-target' => '#confirmModal']) !!}
  <a href="/nodes" class="btn btn-default">Back</a>

<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="confirmModalLabel">Confirm?</h4>
   </div>
   <div class="modal-body">
    Confirm Delete <strong>{{ $node->name }}</strong>
   </div>
   <div class="modal-footer">
    {!! Form::open(['id'     => 'form',
                    'method' => 'DELETE',
                    'url'    => '/nodes/' . $node->id]) !!}

     {!! Form::submit('Confirm Delete', ['id'    => 'submit',
                                         'class' => 'btn btn-danger']) !!}
     <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    {!! Form::close() !!}
   </div>
  </div>
 </div>
</div>

@stop

@section('footer')
<script>
$(document).ready(function() {
 $("#form").submit(function() {
  $("#submit").prop("disabled", true);
  $("#submit").val("Please wait...");
 });
});
</script>
@stop
