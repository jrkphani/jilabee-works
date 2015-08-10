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
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
</head>
<body>
	<header>
		<h1><a href="/">Jotter</a></h1>
	</header>
	<div class="adminContent">
			<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/auth/register') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="userDetailItem">
							<p>Organization Name</p>
							<input type="text" autocomplete="off" name="name" value="{{ old('name') }}">
							{!! $errors->first('name','<div class="error">:message</div>')!!}
						</div>
						<div class="userDetailItem">
							<p>Domain</p>
							<input type="text" autocomplete="off" name="domain" value="{{ old('domain') }}">
							{!! $errors->first('domain','<div class="error">:message</div>') !!}
						</div>

						<div class="userDetailItem">
							<p>Phone</p>
							<input type="text" autocomplete="off"  name="phone" value="{{ old('phone') }}">
							{!! $errors->first('phone','<div class="error">:message</div>') !!}
						</div>
						
						<div class="userDetailItem">
						<p>Phone1</p>
						<input type="text" autocomplete="off"  name="phone1" value="{{ old('phone1') }}">
						{!! $errors->first('phone1','<div class="error">:message</div>') !!}
						</div>
						<p>Admin User Details</p>
						<div class="userDetailItem">
							<p>Admin Name</p>
							<input type="text" autocomplete="off"  name="adminname" value="{{ old('name') }}">
							{!! $errors->first('adminname','<div class="error">:message</div>') !!}
						</div>
						<div class="userDetailItem">
							<p>E-Mail Address</p>
							<input type="email" autocomplete="off"  name="email" value="{{ old('email') }}">
							{!! $errors->first('email','<div class="error">:message</div>') !!}
						</div>
						<div class="userDetailItem">
							<p>Secondary E-Mail</p>
							<input type="email" autocomplete="off"  name="secondEmail" value="{{ old('secondEmail') }}">
							{!! $errors->first('secondEmail','<div class="error">:message</div>') !!}
						</div>
						<div class="userDetailItem">
							<p>Password</p>
							<input type="password" autocomplete="off"  name="password">
							{!! $errors->first('password','<div class="error">:message</div>') !!}
						</div>

						<div class="userDetailItem">
							<p>Confirm Password</p>
							<input type="password" autocomplete="off"  name="password_confirmation">
						</div>

						<div class="userDetailItem">
							<p>DOB</p>
							<input type="text" class="dateInput" name="dob" value="{{ old('dob') }}">
							{!! $errors->first('dob','<div class="error">:message</div>') !!}
						</div>

						<div class="userDetailItem">
							<p>Gender</p>
							{!!Form::radio('gender', 'M') !!} Male 
							{!!Form::radio('gender', 'F') !!} Female
							{!!Form::radio('gender', 'O') !!} Others
							{!! $errors->first('gender','<div class="error">:message</div>') !!}
						</div>

						<div class="userDetailItem">
                           		<a class="btn btn-primary" href="{{url('/auth/login')}}">Back</a> 
								<input type="submit" value="Register">
						</div>
					</form>
	</div>
</body>
</html>
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
	$(document).ready(function($)
		{
			 $('.dateInput').datepicker({dateFormat: "yy-mm-dd",maxDate: "-15y",autoclose: true});
    	});
</script>