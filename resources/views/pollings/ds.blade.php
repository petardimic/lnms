@extends('app')

@section('title', 'pollings.ds')

@section('content')
 <h1>pollings.ds</h1>

 <a href="/ports/{{ $polling->table_id }}/pollings" class="btn btn-default">Back</a>

<hr>

@if (count($pds))

    <table class="table table-bordered table-hover">
    <thead>
     <th width="200">Date/Time</th>
     <th>Input</th>
     <th>Output</th>
    </thead>
    <tbody>
    @foreach ($pds as $pd)
        <tr>
         <td>{{ $pd->timestamp }}</td>
         <td>{{ $pd->input }}</td>
         <td>{{ $pd->output }}</td>
        </tr>
    @endforeach
    </tbody>
    <caption style="caption-side: bottom; text-align: right;">
     {!! $pds->render() !!}
    </caption>
    </table>
@else
    no data
@endif

@stop
