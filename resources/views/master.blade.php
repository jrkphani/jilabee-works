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
		<button class="showHeaderMenu" id="showHeaderMenu" onclick="$('#headerNav').toggle();" ></button>	
			<nav id="headerNav" class="headerNav">
				<?php $colorClass = $addtionalClass =''; ?>
				@if(Request::segment(1) == 'jobs' || Request::segment(1) == NULL)
					<a class="navHightlight jobsHeaderColor" id="jobs">Jobs</a>
					<?php $colorClass = 'jobsColor'; ?>
				@else
					<a href="{{ url('jobs') }}" id="jobs">Jobs</a>
				@endif
				@if(Request::segment(1) == 'followups')
					<a class="navHightlight followUpsHeaderColor">Followups</a>
					<?php $colorClass = 'followUpsColor'; ?>
				@else
					<a href="{{ url('followups') }}">Follow Ups</a>
				@endif
				@if(Request::segment(1) == 'meetings')
				<?php $addtionalClass = 'meetingsPage'; ?>
					<a class="navHightlight">Meetings</a>
				@else
					<a href="{{ url('meetings') }}">Meetings</a>
				@endif
				<div class="clearboth"></div>
			</nav>
		
			<div class="headerRight">
				@if (Auth::guest())
				@else
				<button class="notificationBtn"  onclick="">2</button>
				<button class="usernameBtn"  onclick="$('#nameMenu').toggle();"> {{Auth::user()->profile()->first()->name}}<span></span></button>
				<div class="nameMenu" id="nameMenu">
					<a href="{{ url('profile') }}">My Profile</a>
					@if(Auth::user()->isAdmin)
						<a href="{{ url('admin') }}">Admin Dashboard</a>
					@endif
					<a href="{{ url('auth/logout') }}">Logout</a>
				</div>
				@endif
			</div>
			<div class="clearboth"></div>
			<!--================ Toast message ==================== -->
			<div class="toast">
				<div class="toast_inner">	
					<p>	Place your message ...</p>
					<span class="btn_close_small">	</span>
				</div>
			</div>
	</header>

	<div id="centralViewer" class="centralViewer {{$colorClass}} {{$addtionalClass}}">
		<div class="centralContainer" id="centralContainer">
			@yield('content')
		</div>
	</div>
	<div class="breadcrumbBar breadcrumbBarFix">
		<div class="breadcrumbs">
				<a href="">Home</a> &nbsp;/&nbsp;
				<a href="">Jobs</a>
			</div>
			<div class="pagination">
				<button></button>
				<button></button>
				<button></button>
			</div>
			<div class="clearboth"></div>
	</div>

	<footer>
		<div class="footerColumn fcFirst">
			<a href="">Jobs</a>
			<a href="">History</a>
			<a href="">Current</a>
		</div>
		<div class="footerColumn">
			<a href="">Follow Ups</a>
			<a href="">History</a>
			<a href="">Current</a>
		</div>
		<div class="footerColumn">
			<a href="">Meetings</a>
			<a href="">History</a>
			<a href="">Current</a>
		</div>
		<div class="footerColumn">
			<a href="">Misc</a>
			<a href="">New Task</a>
			<a href="">New Meeting </a>
			<a href="">FAQ </a>
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
