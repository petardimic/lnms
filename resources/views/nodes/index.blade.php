@extends('app')

@section('content')
<h1>nodes.index</h1>

<form class="form-inline">
  <div class="form-group">
    <label class="sr-only" for="q">Name or IP Address</label>
    <input type="text" class="form-control" id="q" name="q" placeholder="Name or IP Address" value="{{ $q }}">
  </div>
  <button type="submit" class="btn btn-primary">search</button>
</form>
<br>

@if (count($nodes))
 <table class="table table-bordered table-hover">
 <thead>
  <tr>
   <th>Name</th>
   <th>IP Address</th>
  </tr>
 </thead>
 <tbody>
  @foreach ($nodes as $node)
   <tr>
    <td><a href="/nodes/{{ $node->id }}">{{ $node->name }}</a></td>
    <td>{{ $node->ip_address }}</td>
   </tr>
  @endforeach
 </tbody>
 </table>
@else
 no data<br>
 <br>
@endif

<a href="/nodes/create" class="btn btn-primary" role="button">nodes.create</a>

@stop
