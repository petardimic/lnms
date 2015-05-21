@extends('app')

@section('title', 'nodes.index')

@section('breadcrumb')
 <li class="active">nodes</li>
@stop

@section('content')
<h1>nodes.index</h1>

<form class="form-inline">
  <div class="form-group">
    <label class="sr-only" for="q">Name or IP Address</label>
    <input type="text" class="form-control" id="q" name="q" placeholder="Name or IP Address" value="{{ \Request::get('q') }}">

{!! Form::select('location_id', \App\Location::all_select(), \Request::get('location_id'), ['class' => 'form-control'] ) !!}

{!! Form::select('project_id',  \App\Project::all_select(),  \Request::get('project_id'),  ['class' => 'form-control'] ) !!}

<!--
{!! Form::select('nodegroup_id',  \App\Nodegroup::all_select(),  \Request::get('nodegroup_id'),  ['class' => 'form-control'] ) !!}
-->

{!! Form::select('poll_class',  \App\Node::poll_class_select(),  \Request::get('poll_class'),  ['class' => 'form-control'] ) !!}

{!! Form::select('sysObjectID',  \App\Node::sysObjectID_select(),  \Request::get('sysObjectID'),  ['class' => 'form-control'] ) !!}

&nbsp; &nbsp; Status:
{!! Form::select('status',  \App\Node::status_select(),  \Request::get('status'),  ['class' => 'form-control'] ) !!}

  </div>
  <button type="submit" class="btn btn-primary">search</button>
</form>
<br>

@if (count($nodes))
 <table class="table table-bordered table-hover">
 <thead>
  <tr>
   <th><a href="/nodes?q={{ $q }}&sort=name">Name</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=ip_address">IP Address</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=project_id">Project</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=nodegroup_id">Nodegroup</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=location_id">Location</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=poll_enabled">Poll Enabled</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=ping_success">Ping Success</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=sysName">sysName</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=sysObjectID">sysObjectID</a></th>
   <th><a href="/nodes?q={{ $q }}&sort=poll_class">Poll Class</a></th>
  </tr>
 </thead>
 <tbody>
  @foreach ($nodes as $node)
   <tr>
    <td><a href="/nodes/{{ $node->id }}">{{ $node->name }}</a></td>
    <td>{{ $node->ip_address }}</td>
    <td>{{ $node->nodegroup_id == '' ? '' : \App\Nodegroup::find($node->nodegroup_id)->name }}</td>
    <td>{{ $node->project_id == '' ? '' : \App\Project::find($node->project_id)->name }}</td>
    <td>{{ $node->location_id == '' ? '' : \App\Location::find($node->location_id)->name }}</td>
    <td>{{ $node->dsp_poll_enabled }}</td>
    <td>{{ $node->ping_success }}%</td>
    <td>{{ $node->sysName }}</td>
    <td>{{ $node->sysObjectID }}</td>
    <td>{{ $node->poll_class }}</td>
   </tr>
  @endforeach
 </tbody>
  <caption style="caption-side: bottom; text-align: right;">
   {!! $nodes->appends(['q' => $q,
                        'location_id' => \Request::get('location_id'),
                        'project_id'  => \Request::get('project_id'),
                        'nodegroup_id'  => \Request::get('nodegroup_id'),
                        'status'  => \Request::get('status'),
                        'poll_class'  => \Request::get('poll_class'),
                        'sysObjectID'  => \Request::get('sysObjectID'),
                       ])->render() !!}
  </caption>
 </table>
@else
 no data<br>
 <br>
@endif

<a href="/nodes/create" class="btn btn-primary" role="button">nodes.create</a>

@stop
