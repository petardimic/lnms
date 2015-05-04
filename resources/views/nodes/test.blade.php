@extends('app')

@section('title', 'nodes.test')

@section('breadcrumb')
 <li><a href="/nodes">nodes</a></li>
 <li><a href="/nodes/{{ $node->id }}">{{ $node->name }}</a></li>
 <li class="active">test</li>
@stop

@section('content')
 <h1>nodes.test</h1>

 <table class="table table-bordered table-hover">
  <tr>
   <th width="150">Name</th>
   <td>{{ $node->name }}</td>
  </tr>
  <tr>
   <th>IP Address</th>
   <td>{{ $node->ip_address }}</td>
  </tr>
 </table>

<p>Test Node from NMS Server.</p>
<button id="pingButton" type="button" class="btn btn-primary">Ping Node</button>
<button id="snmpButton" type="button" class="btn btn-primary">SNMP Node</button>
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
    $("#testResult").html("");

    $.getJSON("/api/v1/nodes/{{ $node->id }}/ping", function(result){
        $.each(result, function(i, field){
            $("#testResult").html("ping success = " + field + "%");
        });

        $("#pingButton").html("Ping Node");
        $("#pingButton").prop("disabled", false);    
    });
});

$("#snmpButton").click(function(){
    $("#snmpButton").html("Waiting...");
    $("#snmpButton").prop("disabled", true);    
    $("#testResult").html("");

    $.getJSON("/api/v1/nodes/{{ $node->id }}/snmp", function(result){
        $.each(result, function(i, field){
            $("#testResult").html("snmp success = " + field);
        });

        $("#snmpButton").html("SNMP Node");
        $("#snmpButton").prop("disabled", false);    
    });
});
</script>
@stop
