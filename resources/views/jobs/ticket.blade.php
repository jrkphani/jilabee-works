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
	<link href="{{ asset('/css/jquery.datetimepicker.css') }}" rel="stylesheet">
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
				<label for="">Invoice No</label><br/>
				<input type="text" name="invoice" autocomplete="off" placeholder="Invoice number" value="{{ old('invoice') }}"><br/>
				{!! $errors->first('invoice','<div class="error">:message</div><br/>') !!}
				<label for="">LR No</label><br/>
				<input type="text" name="lrn" autocomplete="off" placeholder="LR number" value="{{ old('lrn') }}"><br/>
				{!! $errors->first('lrn','<div class="error">:message</div><br/>') !!}
				<label for="">LR Date</label><br/>
				<input type="text" name="lrd" autocomplete="off" class="date" placeholder="LR Date" value="{{ old('lrd') }}"><br/>
				{!! $errors->first('lrd','<div class="error">:message</div><br/>') !!}
				<label for="">Manufacturing Location</label><br/><br/>
				<p style="margin-left: 20px;"><select autocomplete="off" name="location">
				<option value="">Select Location</option>
					<option value="Illalur">Illalur</option>
					<option value="Meghalaya">Meghalaya</option>
					<option value="Pondy">Pondy</option>
					<option value="TVM">TVM</option>
				</select></p>

				{!! $errors->first('location','<br/><br/><br/><div class="error">:message</div><br/>') !!}
				<br/>
				<label for="">Transport</label><br/>
				<input type="text" name="transport" autocomplete="off" placeholder="Transport" value="{{ old('transport') }}"><br/>
				{!! $errors->first('transport','<div class="error">:message</div><br/>') !!}
				<label for="">Issue</label><br/><br/>
				<textarea type="text" cols="40" rows="10" name="issue" placeholder="describe issue" style="margin-left: 20px;">{{ old('issue') }}</textarea><br/><br/><br/>
				{!! $errors->first('issue','<div class="error">:message</div><br/>') !!}
				{!! app('captcha')->display(); !!}
				{!! $errors->first('g-recaptcha-response','<br/><br/><br/><div class="error">:message</div><br/>') !!}
				<center><input class="login_loginbtn" type="submit" value="Submit"></center>
				<div class="clearboth"></div>
				<a class="login_forgotpassword" href="{{ url('/') }}">Back</a>
			</form>    	 
		</div>
		<br/>
			<div class="clearboth"></div>
	</div>
</body>
</html>
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="{{ asset('/js/jquery.datetimepicker.full.js') }}"></script>
<script>
	$(document).ready(function($)
		{
			d= new Date();
			$('.date').datetimepicker({
				format:'Y-m-d',
				timepicker:false,
				maxDate:'28.12',formatDate:'d.m.Y',
			});
    	});
</script>