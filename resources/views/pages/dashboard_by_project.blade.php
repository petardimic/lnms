@extends('app')

@section('content')

<h3>Dashboard: by Project</h3>

<div class="container-fluid">
 <div class="row">
  <div class="col-sm-12 col-md-12 main">
   <div class="container-fluid">

@for ($row=0; $row < (ceil($projects->count() / 4)); $row++)
    <div class="row">

 @for ($col=0; $col < 4; $col++)

  @if ( isset($projects[($row*4) + $col]) )
     <div class="col-md-3">
      <div class="thumbnail">
       <div class="caption">

        <h5>
         @if ( $projects[($row*4) + $col]->logo_file_name  <> '' )
          <img height="50" src="/projects/{{ $projects[($row*4) + $col]->id }}/logo" />
         @endif

        {{ $projects[($row*4) + $col]->name }}
        </h5>

        Node Status
        <div class="progress">
         <div class="progress-bar progress-bar-success" style="width: {{ $projects[($row*4) + $col]->nodesUpPercent }}%;">
          <a style="color: white" href="/nodes?project_id={{ $projects[($row*4) + $col]->id }}&status=up">{{ $projects[($row*4) + $col]->nodesUp }}</a>
         </div>
         <div class="progress-bar progress-bar-danger" style="width: {{ $projects[($row*4) + $col]->nodesDownPercent }}%;">
          <a style="color: white" href="/nodes?project_id={{ $projects[($row*4) + $col]->id }}&status=down">{{ $projects[($row*4) + $col]->nodesDown }}</a>
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
