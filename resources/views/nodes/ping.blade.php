@extends('app')

@section('content')
 ping node {{ $node->ip_address }}<br>
 <br>
 {{ $ping_result }}<br>
 <br>

  <a href="/nodes/{{ $node->id }}" class="btn btn-default">Back</a>
@stop
