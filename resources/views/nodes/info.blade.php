<div class="container-fluid">
 <div class="row">
  <div class="col-sm-6 col-md-6">

<table class="table table-bordered table-hover">
 <tr>
  <th width="150">Name</th>
  <td>{{ $node->name }}</td>
 </tr>
 <tr>
  <th>IP Address</th>
  <td>{{ $node->ip_address }}</td>
 </tr>
 <tr>
  <th>Location</th>
  <td>{{ $node->location_id == '' ? '' : \App\Location::find($node->location_id)->name }}</td>
 </tr>
 <tr>
  <th>Project</th>
  <td>{{ $node->project_id == '' ? '' : \App\Project::find($node->project_id)->name }}</td>
 </tr>
 <tr>
  <th>Poll Class</th>
  <td>{{ $node->poll_class }}</td>
 </tr>

</table>

  </div>

  <div class="col-sm-3 col-md-3">
   <div class="thumbnail" align="center">

    <table class="table">

    @if ( $node->ping_success == 100 )
     <tr class="success">
    @else
     <tr class="danger">
    @endif


      <td align="center">
      @if ( $node->ping_success == 100 )
       <h4>Ping Success</h4>
      @else
       <h4>Ping Fail</h4>
      @endif
      </td>
     </tr>
     <tr>
      <td align="center">
       Response Time
       @if ( $node->ping_success == 100 )
        <a href="/nodes/{{ $node->id }}/graph_ping"><h5>{{ $node->ping_microsec / 1000 }} ms.</h5></a>
       @else
        <a href="/nodes/{{ $node->id }}/graph_ping"><h5>-</h5></a>
       @endif
      </td>
     </tr>
    </table>
   Last updated: {{ $node->ping_updated }}

   </div>
  </div>

  <div class="col-sm-3 col-md-3">
   <div class="thumbnail">

    <table class="table">
    @if ( $node->snmp_success == 100 )
     <tr class="success">
    @else
     <tr class="danger">
    @endif
      <td align="center">
      @if ( $node->snmp_success == 100 )
       <h4>SNMP Success</h4>
      @else
       <h4>SNMP Fail</h4>
      @endif
      </td>
     </tr>
     <tr>
      <td align="center">
       sysUpTime
      @if ( $node->snmp_success == 100 )
       <h5>{{ $node->dsp_sysUpTime }}</h5>
      @else
       <h5>-</h5>
      @endif
      </td>
     </tr>
    </table>
   Last updated: 19 May 2015 21:23:33

   </div>
  </div>

 </div>
</div>
