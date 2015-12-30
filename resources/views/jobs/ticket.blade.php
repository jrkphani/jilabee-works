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
	<div class="indexLogin" style="width:765px">
		<h1>{{env('APP_NAME')}}</h1>
		<div class="indexLoginForm">
			<form class="form-horizontal" role="form" method="POST" action="{{ url('/ticket/new') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div style="float:left">
					<label for="">Email</label><br/>
					<input type="email" name="email" placeholder="email@domain.com" value="{{ old('email') }}" autocomplete="off">
					{!! $errors->first('email','<div class="error">:message</div>') !!}
				</div>
				<div style="float:left">
					<label for="">Invoice No</label><br/>
					<input type="text" name="invoice" autocomplete="off" placeholder="Invoice number" value="{{ old('invoice') }}">
					{!! $errors->first('invoice','<div class="error">:message</div>') !!}
				</div>
				<div style="float:left">
					<label for="">LR Number</label><br/>
					<input type="text" name="lrn" autocomplete="off" class="date" placeholder="LR Number" value="{{ old('lrd') }}">
					{!! $errors->first('lrn','<div class="error">:message</div>') !!}
				</div>
				<div style="float:left">
					<label for="">LR Date</label><br/>
					<input type="text" name="lrd" autocomplete="off" class="date" placeholder="LR Date" value="{{ old('lrd') }}">
					{!! $errors->first('lrd','<div class="error">:message</div>') !!}
				</div>
				<div style="float:left">
					<label for="">Transport</label><br/>
					<input type="text" name="transport" autocomplete="off" placeholder="Transport" value="{{ old('transport') }}">
					{!! $errors->first('transport','<div class="error">:message</div>') !!}
				</div>
				<div style="float:left">
					<p><label style="padding:10px">Manufacture</label></p><br/>
					<select autocomplete="off" name="location" style="width:220px; margin-left:20px;  font-family: Source Sans Pro,sans-serif; font-size: 1.3em;  height: 48px; margin-top: -5px;">
					<option value="">Select Location</option>
						<option value="Illalur">Illalur</option>
						<option value="Meghalaya">Meghalaya</option>
						<option value="Pondy">Pondy</option>
						<option value="TVM">TVM</option>
					</select>
					{!! $errors->first('location','<br/><br/><br/><div class="error">:message</div><br/>') !!}
				</div>
				<div style="float:left">
				<label>Describe Issue</label><br/><br/>
				<textarea type="text" rows="10" name="issue" placeholder="Description" style="width:720px;  margin-left: 20px; font-size: 1.3em;">{{ old('issue') }}</textarea><br/><br/><br/>
				{!! $errors->first('issue','<div class="error">:message</div>') !!}
				</div>
				<div style="float:left; margin-left: 20px;">
					{!! app('captcha')->display(); !!}
					{!! $errors->first('g-recaptcha-response','<br/><br/><br/><div class="error">:message</div>') !!}
				</div>
				<br/><br/><br/>
				<div style="float:left; width:100%; margin-left:220px"><input class="login_loginbtn" type="submit" value="Submit"></div>
				<div class="clearboth"></div>
				<br/>
				<center><a class="login_forgotpassword" href="{{ url('/ticket/view') }}" style="font-size: 20px;"><b>Have a ticket number ?</b></a></center>
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