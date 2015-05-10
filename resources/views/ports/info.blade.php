<table class="table table-bordered table-hover">
 <tr>
  <th width="150">Name</th>
  <td>{{ $port->node->name }}</td>
 </tr>
 <tr>
  <th>IP Address</th>
  <td>{{ $port->node->ip_address }}</td>
 </tr>

 <tr>
  <th>ifDescr</th>
  <td>{{ $port->ifDescr }}</td>
 </tr>
 <tr>
  <th>ifType</th>
  <td>{{ $port->dsp_ifType }}</td>
 </tr>
 <tr>
  <th>ifSpeed</th>
  <td>{{ $port->dsp_ifSpeed }}</td>
 </tr>
 <tr>
  <th>Status</th>
  <td>{{ $port->dsp_ifStatus }}</td>
 </tr>
</table>
