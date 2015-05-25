@extends('app')

@section('title', 'ports.octets')

@section('breadcrumb')
 <li><a href="/nodes">nodes</a></li>
 <li><a href="/nodes/{{ $port->node->id }}/ports">{{ $port->node->name }}</a></li>
 <li class="active">{{ $port->ifDescr }}</li>
@stop

@section('content')
 <h1>ports.octets</h1>

 @include('ports.info')

 @if (count($octets))

@section('script')

<script type="text/javascript">
$(function() {
 var options = {
  xaxis: {
   mode: "time",
   minTickSize: [1, "hour"],
   timezone: "browser",
   twelveHourClock: true
  }
 };


 var data = [];

 // Fetch one series, adding to what we already have
 var alreadyFetched = {};

 // Find the URL in the link right next to us, then fetch the data
 var dataurl = "/ports/{{ $port->id }}/octets_data?date={{ $q_date }}";

 function onDataReceived(series) {

  if (!alreadyFetched[series.label]) {
   alreadyFetched[series.label] = true;
   data.push(series[0]);
   data.push(series[1]);
  }

  $.plot("#placeholder", data, options);
 }

 // ajax
 $.ajax({
  url: dataurl,
  type: "GET",
  dataType: "json",
  success: onDataReceived
 });

});
</script>

<script>
$(function() {
    $( "#datepicker" ).datepicker({
                        dateFormat: "yy-mm-dd"
                        });
});
</script>


@stop

 @endif
<h5>Traffic Utilization</h5>

<center>
 <form method="GET" action="/ports/{{ $port->id }}/octets/">
 Select Date: <input type="text" id="datepicker" name="date" value="{{ $q_date }}">
 <input type="submit" value="submit">
 </form>
</center>

<div class="demo-container">
 <div id="placeholder" class="demo-placeholder"></div>
</div>


 <a href="/ports/{{ $port->id }}" class="btn btn-default">Back</a>

@stop


