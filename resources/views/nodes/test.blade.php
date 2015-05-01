@extends('app')

@section('title', 'nodes.test')

@section('breadcrumb')
 <li><a href="/nodes">nodes</a></li>
 <li><a href="/nodes/{{ $node->id }}">{{ $node->name }}</a></li>
 <li class="active">test</li>
@stop

@section('content')
<h1>nodes.test</h1>
<p>Test Node from NMS Server.</p>
<button id="pingButton" type="button" class="btn btn-primary">Ping Node</button>
<a href="/nodes/{{ $node->id }}" class="btn btn-default">Back</a>

<p>
 <div id="testResult"></div>
</p>
@stop

@section('footer')
<script>
$("#pingButton").click(function(){
    $("#pingButton").html("Waiting...");
    $("#pingButton").prop("disabled", true);    

    $.getJSON("/api/v1/nodes/{{ $node->id }}/ping", function(result){
        $.each(result, function(i, field){
            $("#testResult").html("ping success = " + field + "%");
        });

        $("#pingButton").html("Ping Node");
        $("#pingButton").prop("disabled", false);    
    });
});
</script>
@stop
