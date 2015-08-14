<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Jotter</title>
	<meta name="author" content="Dexel Designs">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta name="description" content="">
	<meta name="keywords" content="Anabond, Jotter, ">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/base.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('css/sss.css') }}" />
</head>
<body>
	<header>
	<!-- 	<h1>Jotter</h1> -->
	</header>
	<div class="indexLogin">
		<h1>Jotter</h1>
		<div class="indexLoginForm">
			<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<label for="">Username</label><br/>
				<input type="email" name="email" placeholder="email@domain.com" value="{{ old('email') }}"><br/>
				{!! $errors->first('email','<div class="error">:message</div><br/>') !!}
				<label for="">Password</label><br/>
				<input type="password" name="password"><br/>
				{!! $errors->first('password','<div class="error">:message</div><br/>') !!}
				<input type="submit" value="Login">
			</form>
			<center><a href="{{ url('/password/email') }}">Forgot Your Password?</a></center>
			<center><h4>Don't have a login?</h4></center>
    	 {{-- <center><a href="{{ url('/auth/register') }}">Single Signup</a></center> --}}
    	 <center><a href="{{ url('admin/auth/register') }}">Org Signup</a></center>
		</div>
	</div>
	
</body>
</html>