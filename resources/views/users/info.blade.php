<table class="table table-bordered table-hover">
 <tr>
  <th width="150">Username</th>
  <td>{{ $user->username }}</td>
 </tr>
 <tr>
  <th>Usergroup</th>
  <td>{{ $user->usergroup_id == '' ? '' : \App\Usergroup::find($user->usergroup_id)->name }}</td>
 </tr>
</table>
