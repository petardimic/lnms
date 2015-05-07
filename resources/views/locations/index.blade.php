@extends('app')

@section('title', 'locations.index')

@section('breadcrumb')
 <li class="active">locations</li>
@stop

@section('content')
<h1>locations.index</h1>

<form class="form-inline">
  <div class="form-group">
    <label class="sr-only" for="q">Name</label>
    <input type="text" class="form-control" id="q" name="q" placeholder="Name" value="{{ $q }}">
  </div>
  <button type="submit" class="btn btn-primary">search</button>
</form>
<br>

@if (count($locations))
 <table class="table table-bordered table-hover">
 <thead>
  <tr>
   <th><a href="/locations?q={{ $q }}&sort=name">Name</a></th>
  </tr>
 </thead>
 <tbody>
  @foreach ($locations as $location)
   <tr>
    <td><a href="/locations/{{ $location->id }}">{{ $location->name }}</a></td>
   </tr>
  @endforeach
 </tbody>
  <caption style="caption-side: bottom; text-align: right;">
   {!! $locations->appends(['q' => $q])->render() !!}
  </caption>
 </table>
@else
 no data<br>
 <br>
@endif

<a href="/locations/create" class="btn btn-primary" role="button">locations.create</a>

@stop
