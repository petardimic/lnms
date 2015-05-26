@extends('app')

@section('title', 'roles.index')

@section('breadcrumb')
 <li class="active">roles</li>
@stop

@section('content')
<h1>roles.index</h1>

<form class="form-inline">
  <div class="form-group">
    <label class="sr-only" for="q">Name</label>
    <input type="text" class="form-control" id="q" name="q" placeholder="Name" value="{{ $q }}">
  </div>
  <button type="submit" class="btn btn-primary">search</button>
</form>
<br>

@if (count($roles))
 <table class="table table-bordered table-hover">
 <thead>
  <tr>
   <th><a href="/roles?q={{ $q }}&sort=id">Id</a></th>
   <th><a href="/roles?q={{ $q }}&sort=user">User</a></th>
   <th><a href="/roles?q={{ $q }}&sort=usergroup">Usergroup</a></th>
   <th><a href="/roles?q={{ $q }}&sort=location">Location</a></th>
   <th><a href="/roles?q={{ $q }}&sort=project">Project</a></th>
  </tr>
 </thead>
 <tbody>
  @foreach ($roles as $role)
   <tr>
    <td><a href="/roles/{{ $role->id }}">{{ $role->id }}</a></td>
    <td>{{ $role->user_id }}</td>
    <td>{{ $role->usergroup_id }}</td>
    <td>{{ $role->location_id }}</td>
    <td>{{ $role->project_id }}</td>
   </tr>
  @endforeach
 </tbody>
  <caption style="caption-side: bottom; text-align: right;">
   {!! $roles->appends(['q' => $q])->render() !!}
  </caption>
 </table>
@else
 no data<br>
 <br>
@endif

<a href="/roles/create" class="btn btn-primary" role="button">roles.create</a>

@stop
