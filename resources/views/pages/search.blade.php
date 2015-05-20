@extends('app')

@section('content')
 <h1>Search : Node</h1>

 {!! Form::open(['id'    => 'form',
                 'url'   => 'nodes',
                 'method' => 'GET',
                 'class' => 'form-horizontal']) !!}

<div class="form-group">
 {!! Form::label('q', 'Node Name / IP Address', ['class' => 'control-label col-sm-2']) !!}
 <div class="col-sm-3">
  {!! Form::text('q', null, ['class' => 'form-control']) !!}
 </div>
</div>

<div class="form-group">
 <div class="col-sm-offset-2 col-sm-10">
  <input class="btn btn-primary" id="submit" type="submit" value="Search">
 </div>
</div>

 {!! Form::close() !!}

<br>
<br>
<br>

 <h1>Search : WiFi Clients</h1>

 {!! Form::open(['id'    => 'form',
                 'url'   => '/bssid_clients',
                 'method' => 'GET',
                 'class' => 'form-horizontal']) !!}

<div class="form-group">
 {!! Form::label('q', 'IP Address / MAC Address', ['class' => 'control-label col-sm-3']) !!}
 <div class="col-sm-3">
  {!! Form::text('q', null, ['class' => 'form-control']) !!}
 </div>
</div>

<div class="form-group">
 <div class="col-sm-offset-2 col-sm-10">
  <input class="btn btn-primary" id="submit" type="submit" value="Search">
 </div>
</div>

 {!! Form::close() !!}

@stop
