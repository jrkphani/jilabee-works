<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{{env('APP_NAME')}}</title>
	<meta name="author" content="Dexel Designs">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta name="description" content="">
	<meta name="keywords" content="Anabond, Jotter, ">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/base.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('css/sss.css') }}" />
</head>
<body class="login_body">
	<header>
	<!-- 	<h1>Jotter</h1> -->
	</header>
	<div class="indexLogin">
		<h1>{{env('APP_NAME')}}</h1>
		<div class="indexLoginForm">
			<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<label for="">Username</label><br/>
				<input type="email" name="email" placeholder="email@domain.com" value="{{ old('email') }}"><br/>
				{!! $errors->first('email','<div class="error">:message</div><br/>') !!}
				<label for="">Password</label><br/>
				<input type="password" name="password"><br/>
				{!! $errors->first('password','<div class="error">:message</div><br/>') !!}
				<input class="login_loginbtn" type="submit" value="Login">
				<div class="clearboth"></div>
			</form>
			<a class="login_forgotpassword" href="{{ url('/password/email') }}">Forgot Your Password?</a>
			
    	 
		</div>
		<br/>
			<h4 class="login_signuptext">Don't have a login?</h4>
			<div>
				{{-- <a href="{{ url('/auth/register') }}" class="login_signup">Single Signup</a> --}}
    			 <a href="{{ url('admin/auth/register') }}" class="login_signup">Signup</a>
			</div>
			<div class="clearboth"></div>
	</div>
</body>
</html>