<table class="table table-bordered table-hover">
 <tr>
  <th width="150">Name</th>
  <td>{{ $usergroup->name }}</td>
 </tr>
 <tr>
  <th>Permissions</th>
  <td>
   @if (count($usergroup->permissions) > 0)
    @foreach ($usergroup->permissions as $permission)
     <p>{{ $permission->name }}</p>
    @endforeach
   @else
    -
   @endif
  </td>
 </tr>
</table>
