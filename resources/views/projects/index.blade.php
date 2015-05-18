@extends('app')

@section('title', 'projects.index')

@section('breadcrumb')
 <li class="active">projects</li>
@stop

@section('content')
<h1>projects.index</h1>

@if (count($projects))
 <table class="table table-bordered table-hover">
 <thead>
  <tr>
   <th><a href="/projects?sort=name">Name</a></th>
   <th><a href="/projects?sort=name">#Nodes</a></th>
  </tr>
 </thead>
 <tbody>
  @foreach ($projects as $project)
   <tr>
    <td><a href="/projects/{{ $project->id }}">{{ $project->name }}</a></td>
    <td><a href="/nodes?project_id={{ $project->id }}">{{ $project->nodes()->count() }}</a></td>
   </tr>
  @endforeach
 </tbody>
  <caption style="caption-side: bottom; text-align: right;">
   {!! $projects->render() !!}
  </caption>
 </table>
@else
 no data<br>
 <br>
@endif

<a href="/projects/create" class="btn btn-primary" role="button">projects.create</a>

@stop
