@extends('app')

@section('content')

@if (count($locations))
<table class="table table-bordered table-hover">
 <caption>
  Node Status by Location
 </caption>
 <thead>
  <tr>
   <th><a href="/locations?sort=name">Name</a></th>
   <th><a href="/locations?sort=name">#Nodes</a></th>
   <th><a href="/locations?sort=name">#Up</a></th>
   <th><a href="/locations?sort=name">#Down</a></th>
  </tr>
 </thead>
 <tbody>
  @foreach ($locations as $location)
   <tr>
    <td>{{ $location->name }}</td>
    <td align="right"><a href="/nodes?location_id={{ $location->id }}">{{ $location->nodes()->count() }}</a></td>
    <td align="right"><a href="/nodes?location_id={{ $location->id }}&status=up">{{ $location->nodesUp }}</a></td>
    <td align="right"><a href="/nodes?location_id={{ $location->id }}&status=down">{{ $location->nodesDown }}</a></td>

   </tr>
  @endforeach
 </tbody>
</table>
@else
 no data<br>
 <br>
@endif


@if (count($projects))
<table class="table table-bordered table-hover">
 <caption>
  Node Status by Project
 </caption>
 <thead>
  <tr>
   <th><a href="/projects?sort=name">Name</a></th>
   <th><a href="/projects?sort=name">#Nodes</a></th>
   <th><a href="/projects?sort=name">#Up</a></th>
   <th><a href="/projects?sort=name">#Down</a></th>
  </tr>
 </thead>
 <tbody>
  @foreach ($projects as $project)
   <tr>
    <td>{{ $project->name }}</td>
    <td align="right"><a href="/nodes?project_id={{ $project->id }}">{{ $project->nodes()->count() }}</a></td>
    <td align="right"><a href="/nodes?project_id={{ $project->id }}&status=up">{{ $project->nodesUp }}</a></td>
    <td align="right"><a href="/nodes?project_id={{ $project->id }}&status=down">{{ $project->nodesDown }}</a></td>

   </tr>
  @endforeach
 </tbody>
</table>
@else
 no data<br>
 <br>
@endif

@stop
