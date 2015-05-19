@extends('app')

@section('content')

<h3>Dashboard: by Location</h3>

<div class="container-fluid">
 <div class="row">
  <div class="col-sm-12 col-md-12 main">
   <div class="container-fluid">

@for ($row=0; $row < (ceil($locations->count() / 4)); $row++)
    <div class="row">

 @for ($col=0; $col < 4; $col++)

  @if ( isset($locations[($row*4) + $col]) )
     <div class="col-md-3">
      <div class="thumbnail">
       <div class="caption">

        <h4>{{ $locations[($row*4) + $col]->name }}</h4>

        Nodes
        <div class="progress">
         <div class="progress-bar progress-bar-success" style="width: {{ $locations[($row*4) + $col]->nodesUpPercent }}%;">
          <a style="color: white" href="/nodes?location_id={{ $locations[($row*4) + $col]->id }}&status=up">{{ $locations[($row*4) + $col]->nodesUp }}</a>
         </div>
         <div class="progress-bar progress-bar-danger" style="width: {{ $locations[($row*4) + $col]->nodesDownPercent }}%;">
          <a style="color: white" href="/nodes?location_id={{ $locations[($row*4) + $col]->id }}&status=down">{{ $locations[($row*4) + $col]->nodesDown }}</a>
         </div>
        </div>

       </div>
      </div>
     </div>
  @endif

 @endfor
    </div> <!-- end row -->
@endfor

   </div> <!-- end container -->
  </div>
 </div>
</div>

@stop
