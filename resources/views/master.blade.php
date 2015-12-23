<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Anabond Ticketing System</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<link href="{{ asset('/css/base.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/sss.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/jotter.css') }}" rel="stylesheet">
	@yield('css')
</head>
<body>
<div class="wrapper">
	{!! Form::hidden('_token', csrf_token(),['id'=>'_token']) !!}
	<header>
		<h1><a href="/">Anabond Ticketing System</a></h1>
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
				{{-- 	<a class="navHightlight">Meetings</a> --}}
				@else
					{{-- <a href="{{ url('meetings') }}">Meetings</a> --}}
				@endif
				<div class="clearboth"></div>
			</nav>
		
			<div class="headerRight">
				@if (Auth::guest())
				@else
				<button class="notificationBtn"  id="notifications" onclick="$('#notifyDiv').toggle();"></button>
				<div class="notification" id="notifyDiv">No Notifications
				</div>
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
			
	</header>

	<div id="centralViewer" class="centralViewer {{$colorClass}} {{$addtionalClass}}">
		<div class="centralContainer" id="centralContainer">
			@yield('content')
		</div>
	</div>
	<div class="breadcrumbBar breadcrumbBarFix">
		{!! Breadcrumbs::render(Request::segment(1)) !!}
		{{-- <div class="pagination">
			<button></button>
			<button></button>
			<button></button>
		</div> --}}
		<div class="clearboth"></div>
	</div>
	
<!-- style="display:none;"  --> 
	<!--========================================= Notification popup start ===================================================-->
	<div id="notificationDiv" class="popupnotificationOverlay"style="display:none;" >
		<div class="popupnotification">
			<div class="popupHeader">
				<h2>Your Notifications</h2>
				<span class="popupClose" onclick="$('#notificationDiv').hide();" ></span>
			</div>
			<div id="notification_content" class="popupnotification_content">
			</div>
		</div>
	</div>
	<!--========================================= Notification popup end ===================================================-->
	{{-- <div class="push"></div> --}}
	</div>
	{{-- <div class="footer">
		<div class="footerColumn fcFirst">
			<a href="{{url('jobs')}}">Jobs</a>
			<a href="{{url('jobs?&history=yes')}}">History</a>
			<a href="{{url('jobs')}}">Current</a>
		</div>
		<div class="footerColumn">
			<a href="{{url('followups')}}">Follow Ups</a>
			<a href="{{url('followups?&history=yes')}}">History</a>
			<a href="{{url('followups')}}">Current</a>
		</div>
		<div class="footerColumn">
			<a href="{{url('meetings')}}">Meetings</a>
			<a href="{{url('meetings?&history=yes')}}">History</a>
			<a href="{{url('meetings')}}">Current</a>
		</div>
		<div class="footerColumn">
			<a href="">Misc</a>
			<a href="{{url('followups')}}">New Task</a>
			<a href="{{url('meetings')}}">New Meeting </a>
			<a href="">FAQ </a>
		</div>
		<div class="clearboth"></div>
	</div> --}}
	<!--================ Toast message ==================== -->
			<div class="toast" id="toastDiv" style="display:none;">
				<div class="toast_inner">	
					<p id="toastmsg">loading...</p>
					<span class="btn_close_small" id="toastClose">	</span>
				</div>
			</div>
	<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
	<script src="{{ asset('/js/main.js') }}"></script>
	@yield('javascript')
	@if($message = Session::pull('message', null))
	<script>
	$(document).ready(function($)
	{
		toast("{{$message}}");
	});
	</script>
	@endif
</body>

</html>