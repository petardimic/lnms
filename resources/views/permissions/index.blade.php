@extends('app')

@section('title', 'permissions.index')

@section('breadcrumb')
 <li class="active">permissions</li>
@stop

@section('content')
<h1>permissions.index</h1>

<form class="form-inline">
  <div class="form-group">
    <label class="sr-only" for="q">Name</label>
    <input type="text" class="form-control" id="q" name="q" placeholder="Name" value="{{ $q }}">
  </div>
  <button type="submit" class="btn btn-primary">search</button>
</form>
<br>

@if (count($permissions))
 <table class="table table-bordered table-hover">
 <thead>
  <tr>
   <th><a href="/permissions?q={{ $q }}&sort=name">Name</a></th>
  </tr>
 </thead>
 <tbody>
  @foreach ($permissions as $permission)
   <tr>
    <td><a href="/permissions/{{ $permission->id }}">{{ $permission->name }}</a></td>
   </tr>
  @endforeach
 </tbody>
  <caption style="caption-side: bottom; text-align: right;">
   {!! $permissions->appends(['q' => $q])->render() !!}
  </caption>
 </table>
@else
 no data<br>
 <br>
@endif

<a href="/permissions/create" class="btn btn-primary" role="button">permissions.create</a>

@stop
