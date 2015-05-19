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
   <th><a href="/users?sort=name">Usergroup</a></th>
  </tr>
 </thead>
 <tbody>
  @foreach ($users as $user)
   <tr>
    <td><a href="/users/{{ $user->id }}">{{ $user->username }}</a></td>
    <td>{{ $user->usergroup_id == '' ? '' : \App\Usergroup::find($user->usergroup_id)->name }}</td>
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
