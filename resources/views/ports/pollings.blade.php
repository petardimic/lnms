@extends('app')

@section('title', 'ports.pollings')

@section('breadcrumb')
 <li><a href="/nodes">nodes</a></li>
 <li><a href="/nodes/{{ $port->node->id }}/ports">{{ $port->node->name }}</a></li>
 <li class="active">{{ $port->ifDescr }}</li>
@stop

@section('content')
 <h1>ports.pollings</h1>

 @include('ports.info')

 {!! Form::open(['id'     => 'form',
                 'method' => 'PATCH' ]) !!}
 @if (count($pollings))

    <table class="table table-bordered table-hover">
    <thead>
     <th width="200">status</th>
     <th width="200">poll_method</th>
     <th width="200">interval</th>
    </thead>
    <tbody>
    @foreach ($pollings as $polling)
        <tr>
         <td>
         @if ($polling['status'] == 'Y')
          <input type="checkbox" id="status_{{ $polling['id'] }}" 
                 name="status[{{ $polling['id'] }}]" checked >
         @else
          <input type="checkbox" id="status_{{ $polling['id'] }}" 
                 name="status[{{ $polling['id'] }}]" >
         @endif
         </td>
         <td>{{ $polling->poll_method }}</td>
         <td>{{ $polling->interval }}</td>
        </tr>
    @endforeach
    </tbody>
    </table>
  {!! Form::submit('Update', ['class' => 'btn btn-primary', 'id' => 'submit']) !!}
@else
    no data
@endif

 <a href="/ports/{{ $port->id }}" class="btn btn-default">Back</a>
 {!! Form::close() !!}

@stop
