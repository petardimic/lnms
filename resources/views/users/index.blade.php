@extends('app')

@section('title', 'users.index')

@section('breadcrumb')
 <li class="active">users</li>
@stop

@section('content')
<h1>users.index</h1>

@if (count($users))
 <table class="table table-bordered table-hover">
 <thead>
  <tr>
   <th><a href="/users?sort=name">Name</a></th>
   <th><a href="/users?sort=name">Roles</a></th>
  </tr>
 </thead>
 <tbody>
  @foreach ($users as $user)
  <?php
    $roles = \App\Role::where('user_id', $user->id)
                      ->get();
    $user_roles = '';
    if ($roles->count() > 0) {
        foreach ($roles as $role) {
            $user_roles .= \App\Usergroup::find($role->usergroup_id)->name . ',';
        }
    }
  ?>
   <tr>
    <td><a href="/users/{{ $user->id }}">{{ $user->username }}</a></td>
    <td>{{ $user_roles }}</td>
    <td></td>
   </tr>
  @endforeach
 </tbody>
  <caption style="caption-side: bottom; text-align: right;">
   {!! $users->render() !!}
  </caption>
 </table>
@else
 no data<br>
 <br>
@endif

<a href="/users/create" class="btn btn-primary" role="button">users.create</a>

@stop
