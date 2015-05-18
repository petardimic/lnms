@extends('app')

@section('title', 'nodegroups.index')

@section('breadcrumb')
 <li class="active">nodegroups</li>
@stop

@section('content')
<h1>nodegroups.index</h1>

@if (count($nodegroups))
 <table class="table table-bordered table-hover">
 <thead>
  <tr>
   <th><a href="/nodegroups?sort=name">Name</a></th>
   <th><a href="/nodegroups?sort=name">#Nodes</a></th>
  </tr>
 </thead>
 <tbody>
  @foreach ($nodegroups as $nodegroup)
   <tr>
    <td><a href="/nodegroups/{{ $nodegroup->id }}">{{ $nodegroup->name }}</a></td>
    <td><a href="/nodes?nodegroup_id={{ $nodegroup->id }}">{{ $nodegroup->nodes()->count() }}</a></td>
   </tr>
  @endforeach
 </tbody>
  <caption style="caption-side: bottom; text-align: right;">
   {!! $nodegroups->render() !!}
  </caption>
 </table>
@else
 no data<br>
 <br>
@endif

<a href="/nodegroups/create" class="btn btn-primary" role="button">nodegroups.create</a>

@stop
