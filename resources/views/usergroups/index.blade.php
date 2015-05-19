@extends('app')

@section('title', 'usergroups.index')

@section('breadcrumb')
 <li class="active">usergroups</li>
@stop

@section('content')
<h1>usergroups.index</h1>

@if (count($usergroups))
 <table class="table table-bordered table-hover">
 <thead>
  <tr>
   <th><a href="/usergroups?sort=name">Name</a></th>
  </tr>
 </thead>
 <tbody>
  @foreach ($usergroups as $usergroup)
   <tr>
    <td><a href="/usergroups/{{ $usergroup->id }}">{{ $usergroup->name }}</a></td>
   </tr>
  @endforeach
 </tbody>
  <caption style="caption-side: bottom; text-align: right;">
   {!! $usergroups->render() !!}
  </caption>
 </table>
@else
 no data<br>
 <br>
@endif

<a href="/usergroups/create" class="btn btn-primary" role="button">usergroups.create</a>

@stop
