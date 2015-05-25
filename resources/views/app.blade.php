<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>@yield('title')</title>
 <link rel="stylesheet" href="/css/bootstrap.min.css">
 <link rel="stylesheet" href="/css/jquery-ui.min.css">
 <link rel="stylesheet" href="/css/flot.css">
</head>
<body>

<nav class="navbar navbar-default">
 <div class="container-fluid">
  <div class="navbar-header">
   <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
    <span class="sr-only">Toggle Navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
   </button>
   <a class="navbar-brand" href="/">{{ env('APP_NAME', 'lnms') }}</a>
  </div>

  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
   <ul class="nav navbar-nav">
    <li id="home" class="dropdown">
     <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dashboard<span class="caret"></span></a>
     <ul class="dropdown-menu" role="menu">
      <li><a href="/dashboard_by_location">by Location</a></li>
      <li><a href="/dashboard_by_project">by Project</a></li>
      <li><a href="/dashboard_by_ssid">by SSID</a></li>
      <li><a href="/dashboard_by_clients">Top WiFi Clients</a></li>
     </ul>
    </li>

    <li id="nodes"><a href="/nodes">Nodes</a></li>
    <li id="locations"><a href="/locations">Locations</a></li>
    <li id="projects"><a href="/projects">Projects</a></li>
    <li id="users"><a href="/users">Users</a></li>
    <li id="search"><a href="/search">Search</a></li>
    <!--
    <li id="nodegroups"><a href="/nodegroups">Nodegroups</a></li>
    <li id="usergroups"><a href="/usergroups">Usergroups</a></li>
    -->
   </ul>

   <ul class="nav navbar-nav navbar-right">
    @if (Auth::guest())
     <li><a href="{{ url('/auth/login') }}">Login</a></li>
    @else
     <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->username }} <span class="caret"></span></a>
      <ul class="dropdown-menu" role="menu">
       <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
      </ul>
     </li>
    @endif
   </ul>
  </div>
 </div>
</nav>

<div class="container">

 <ol class="breadcrumb">
  @yield('breadcrumb')
 </ol>

 @if (Session::has('flash_message'))
    <div class="alert alert-success">
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>  
     {{ Session::get('flash_message') }}
    </div>
 @endif

 @yield('content')
</div>

<hr>
<!-- Scripts -->
<script src="/js/jquery.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/jquery.flot.min.js"></script>
<script src="/js/jquery.flot.time.min.js"></script>

@yield('script')

@yield('footer')

<?php
if ( ! isset($activeNav) ) {
    $activeNav = \Request::route()->uri();
    //$activeNav = \Request::route()->parameterNames();
}
?>
<script>
$(document).ready(function() {
// $("#{{ $activeNav }}").attr('class', 'active');
});
</script>

<script>
 $("div.alert").delay(3000).slideUp(300);
</script>

</body>
</html>
