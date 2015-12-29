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
			<form class="form-horizontal" role="form" method="POST" action="{{ url('/ticket/view/') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<label for="">Email</label><br/>
				<input type="email" name="email" placeholder="email@domain.com" value="{{ old('email') }}" autocomplete="off"><br/>
				{!! $errors->first('email','<div class="error">:message</div><br/>') !!}
				<label for="">Ticket Number</label><br/>
				<input type="text" name="ticket" autocomplete="off" placeholder="Invoice number" value="{{ old('ticket') }}"><br/>
				@if($error)
				<div class="error">{{$error}}</div>
				@endif
				{!! $errors->first('invoice','<div class="error">:message</div><br/>') !!}
				<center><input class="login_loginbtn" type="submit" value="Track"></center>
				<div class="clearboth"></div>
				<a class="login_forgotpassword" href="{{ url('/') }}">Back</a>
			</form>    	 
		</div>
		<br/>
			<div class="clearboth"></div>
	</div>
</body>
</html>