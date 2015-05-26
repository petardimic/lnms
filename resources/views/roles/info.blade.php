<table class="table table-bordered table-hover">
 <tr>
  <th width="150">Id</th>
  <td>{{ $role->id }}</td>
 </tr>
 <tr>
  <th>User</th>
  <td>{{ \App\User::find($role->user_id)->username }}</td>
 </tr>
 <tr>
  <th>Usergroup</th>
  <td>{{ \App\Usergroup::find($role->usergroup_id)->name }}</td>
 </tr>
 <tr>
  <th>Location</th>
  <td>{{ \App\Location::find($role->location_id)->name }}</td>
 </tr>
 <tr>
  <th>Project</th>
  <td>{{ \App\Project::find($role->project_id)->name }}</td>
 </tr>
</table>
