<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>lnms</title>
 <link rel="stylesheet" href="/css/bootstrap.min.css">
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
   <a class="navbar-brand" href="/">lnms</a>
  </div>

  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
   <ul class="nav navbar-nav">
    <li><a href="/">Home</a></li>
    <li><a href="/nodes">Nodes</a></li>
   </ul>

   <ul class="nav navbar-nav navbar-right">
<!--
    @if (Auth::guest())
     <li><a href="{{ url('/auth/login') }}">Login</a></li>
     <li><a href="{{ url('/auth/register') }}">Register</a></li>
    @else
     <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
      <ul class="dropdown-menu" role="menu">
       <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
      </ul>
     </li>
    @endif
-->
   </ul>
  </div>
 </div>
</nav>

<div class="container">
 @yield('content')
</div>

<!-- Scripts -->
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

@yield('footer')
</body>
</html>
