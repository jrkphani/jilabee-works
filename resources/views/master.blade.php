<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ Config::get('site.title') }}</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/sticky-footer-navbar.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/jotter.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/colorpicker.css') }}" rel="stylesheet">
	@yield('css')

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				 
				<a href="/"><img src="{{ asset('/img/jotter.jpg') }}" class="img-circle" alt="Cinque Terre" width="100" height="50"></a>
				<!--<a class="navbar-brand" href="#">{{ Config::get('site.title') }}</a> -->
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="{{ base_url() }}">Home</a></li>
				</ul>

				<ul class="nav navbar-nav">
					<li><a href="{{ base_url() }}">Link1</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="{{ base_url() }}">Link2</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ app_url('/auth/login') }}">Login</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ app_url('/profile') }}">Profile</a></li>
								
								@if(Auth::user()->profile->role == '999')
									<li><a href="{{ app_url('/auth/register') }}">Add User</a></li>
									<li><a href="{{ app_url('/minute/add') }}">Add Minute</a></li>
									<li><a href="{{ app_url('/userlist') }}">Users</a></li>
								@endif
								<li><a href="{{ app_url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('guestcontent')
	@yield('usercontent')
	<footer class="footer">
      <div class="container">
      	<div class="row">
      		<div class="col-md-6">
		        <p class="text-muted">
		        	<span class="glyphicon glyphicon-copyright-mark"></span>{{date('Y')}}
		        	<span class="border_left">{{Config::get('site.title')}}</span>
		        	<span class="border_left">All Rights Reserved</span>
		        </p>
		    </div>
		    <div class="col-md-6">
		        <p class="text-muted text-right">
		        	<span>About Us</span>
		        	<span class="border_left">Private Policy</span>
		        </p>
		    </div>
    	</div>
      </div>
	</footer>
	
	<!-- Scripts -->
	<script type="text/javascript">
	$_token = "{{ csrf_token() }}";
	</script>
	<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	<script src="{{ asset('/js/notify-combined.min.js') }}"></script>
	<script src="{{ asset('/js/main.js') }}"></script>
	<script type="text/javascript">
	$(document).ready(function($) {
		$.notify("{!! Session::get('message')!!}",
    			{
				   className:'success',
				   globalPosition:'top center'
				});	
	});
	</script>
	@yield('javascript')
</body>

</html>
