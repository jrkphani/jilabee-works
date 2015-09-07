<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Jotter</title>
	<meta name="author" content="Jotter">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta name="description" content="">
	<meta name="keywords" content="Jotter">
	<link href="{{ asset('/css/base.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/sss.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/jotter.css') }}" rel="stylesheet">
	@yield('css')
</head>
<body>
	{!! Form::hidden('_token', csrf_token(),['id'=>'_token']) !!}
	<header>
		<h1><a href="/">Jotter</a></h1>
			<nav>
				@if(Request::segment(2) == '')
					<a class="navHightlight">Notifications</a>
				@else
					<a href="{{url('admin')}}">Notifications</a>
				@endif
				@if(Request::segment(2) == 'user')
					<a class="navHightlight">Users</a>
				@else
					<a href="{{url('admin/user/list')}}">Users</a>
				@endif
				@if(Request::segment(2) == 'meetings')
					<a class="navHightlight">Meetings</a>
				@else
					<a href="{{url('admin/meetings')}}">Meetings</a>
				@endif				
				<div class="clearboth"></div>
			</nav>
		
			<div class="headerRight">
				@if(Auth::guest())
				@else
				<button class="notificationBtn"  id="notifications" onclick="$('#notifyDiv').toggle();"></button>
				<div class="notification" id="notifyDiv">No Notifications
				</div>
				<button class="usernameBtn"  onclick="$('#nameMenu').toggle();"> {{Auth::user()->profile()->first()->name}}<span></span></button>
				<div class="nameMenu" id="nameMenu">
					<a href="{{ url('/profile') }}">My Profile</a>
					<a href="{{ url('/jobs') }}">My Jobs</a>
					<a href="{{ url('auth/logout') }}">Logout</a>
				</div>
				@endif
			</div>
			<div class="clearboth"></div>

			<!--================ Toast message ==================== -->
			<div class="toast">
				<div class="toast_inner">	
					<p>	Place your message here ...</p>
					<span class="btn_close_small">	</span>
				</div>
			</div>
	</header>
		<div class="adminContent" id="adminContent">
			@yield('content')
		</div>
	<footer>
		<div class="footerColumn fcFirst">
			<a href="">Jobs</a>
			<a href="">My Taks</a>
			<a href="">Follow ups</a>
		</div>
		<div class="footerColumn">
			<a href="">Minutes</a>
			<a href="">Meetings</a>
		</div>
		<div class="footerColumn">
			<a href="">Plan</a>
			<a href="">Projects</a>
		</div>
		<div class="footerColumn">
			<a href="">Terms</a>
			<a href="">Pivacy policy</a>
			<a href="">Usage rights</a>
			<a href="">Service level</a>
		</div>
		<div class="footerColumn">
			<a href="">Contact</a>
			<a href="">Location</a>
			<a href="">Mail us</a>
			<a href="">Cal us</a>
		</div>
		<div class="footerColumn">
			<a href="">Help</a>
			<a href="">FAQs</a>
		</div>
		<div class="clearboth"></div>
	</footer>
	<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
	<script src="{{ asset('/js/notify.min.js') }}"></script>
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