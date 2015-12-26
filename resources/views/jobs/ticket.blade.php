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
			<form class="form-horizontal" role="form" method="POST" action="{{ url('/ticket/new') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<label for="">Email</label><br/>
				<input type="email" name="email" placeholder="email@domain.com" value="{{ old('email') }}" autocomplete="off"><br/>
				{!! $errors->first('email','<div class="error">:message</div><br/>') !!}
				<label for="">Invoice</label><br/>
				<input type="text" name="invoice" autocomplete="off" placeholder="Invoice number"><br/>
				{!! $errors->first('invoice','<div class="error">:message</div><br/>') !!}
				<label for="">LLR</label><br/>
				<input type="text" name="llr" autocomplete="off" placeholder="LLR number"><br/>
				{!! $errors->first('llr','<div class="error">:message</div><br/>') !!}
				<label for="">Issue</label><br/>
				<textarea type="text" cols="44" rows="10" name="issue" placeholder="describe issue"></textarea><br/><br/><br/>
				{!! $errors->first('issue','<div class="error">:message</div><br/>') !!}
				<center><input class="login_loginbtn" type="submit" value="Submit"></center>
				<div class="clearboth"></div>
			</form>    	 
		</div>
		<br/>
			<div class="clearboth"></div>
	</div>
</body>
</html>