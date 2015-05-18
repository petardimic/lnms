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
  <th>Nodegroup</th>
  <td>{{ $node->nodegroup_id == '' ? '' : \App\Nodegroup::find($node->nodegroup_id)->name }}</td>
 </tr>
 <tr>
  <th>Poll Enabled</th>
  <td>{{ $node->dsp_poll_enabled }}</td>
 </tr>
 <tr>
  <th>SNMP Version</th>
  <td>{{ $node->dsp_snmp_version }}</td>
 </tr>

 <tr>
  <th>Ping Success</th>
  <td><a href="/nodes/{{ $node->id }}/graph_ping">{{ $node->ping_success }}%</a></td>
 </tr>
 <tr>
  <th>SNMP Success</th>
  <td><a href="/nodes/{{ $node->id }}/graph_snmp">{{ $node->snmp_success }}%</a></td>
 </tr>
</table>
