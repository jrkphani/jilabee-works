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
	<link href="{{ asset('/css/jquery.datetimepicker.css') }}" rel="stylesheet">
</head>
<?php
if(session('isOrg') == 'yes')
{
	$display1="display:none";
	$display2="";
	$select1 = '';
	$select2 = 'true';
}
else
{
	$display1="";
	$display2="display:none";
	$select1 = 'true';
	$select2 = '';
}
?>
<body class="login_body">
	<header>
		<!-- <h1><a href="/">Jotter</a></h1> -->
	</header>
	<!-- <div class="adminContent"> -->
		<div class="indexLogin">
			<h1>Jotter</h1>
			<div class="registerForm">
				<div class="userDetailItem regFormGender">
					<p>Registration Type:</p>
					{!!Form::radio('regType', 'S',$select1,['class'=>'regType','autocomplete'=>'off']) !!} Single
					{!!Form::radio('regType', 'O',$select2,['class'=>'regType','autocomplete'=>'off']) !!} Organization
				</div>
			<form class="form-horizontal" id="singleForm" role="form" style="{{$display1}}" method="POST" action="{{ url('/auth/register') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="userDetailItem">
							<p>Name</p>
							<input type="text" autocomplete="off"  name="name" value="{{ old('name') }}">
							{!! $errors->first('name','<div class="error">:message</div>') !!}
						</div>
						<div class="userDetailItem">
							<p>E-Mail Address</p>
							<input type="email" autocomplete="off"  name="email" value="{{ old('email') }}">
							{!! $errors->first('email','<div class="error">:message</div>') !!}
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
							<input type="text" class="dob" name="dob" value="{{ old('dob') }}">
							{!! $errors->first('dob','<div class="error">:message</div>') !!}
						</div>
						<div class="userDetailItem">
							<p>Phone</p>
							<input type="text" autocomplete="off"  name="phone" value="{{ old('phone') }}">
							{!! $errors->first('phone','<div class="error">:message</div>') !!}
						</div>
						<div class="userDetailItem regFormGender">
							<p>Gender</p>
							{!!Form::radio('gender', 'M') !!} Male 
							{!!Form::radio('gender', 'F') !!} Female
							{!!Form::radio('gender', 'O') !!} Others
							{!! $errors->first('gender','<div class="error">:message</div>') !!}
						</div>
						<br/>
						<span class="br_line"></span>
						@if(session('message'))
						{{session('message')}}
						@endif
						<div class="userDetailItem" style="margin:0 auto;">
                           		<a class="btn btn-primary login_loginbtn login_register_back" href="{{url('/auth/login')}}">Back</a> 
								<input class=" login_loginbtn"type="submit" value="Register">
						</div>
					</form>
			<form class="form-horizontal" id="orgForm" role="form" style="{{$display2}}" method="POST" action="{{ url('/admin/auth/register') }}">
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
						<br/>
						<span class="br_line"></span>
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
							<input type="text" class="dob" name="dob" value="{{ old('dob') }}">
							{!! $errors->first('dob','<div class="error">:message</div>') !!}
						</div>

						<div class="userDetailItem regFormGender">
							<p>Gender</p>
							{!!Form::radio('gender', 'M') !!} Male 
							{!!Form::radio('gender', 'F') !!} Female
							{!!Form::radio('gender', 'O') !!} Others
							{!! $errors->first('gender','<div class="error">:message</div>') !!}
						</div>
						<br/>
						<span class="br_line"></span>
						@if(session('message'))
						{{session('message')}}
						@endif
						<div class="userDetailItem" style="margin:0 auto;">
                           		<a class="btn btn-primary login_loginbtn login_register_back" href="{{url('/auth/login')}}">Back</a> 
								<input class=" login_loginbtn"type="submit" value="Register">
						</div>
					</form>
					</div>
					</div>
	<!-- </div> -->
</body>
</html>
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="{{ asset('/js/jquery.datetimepicker.full.js') }}"></script>
<script>
	$(document).ready(function($)
		{
			d= new Date();
			$('.dob').datetimepicker({
				format:'Y-m-d',
				timepicker:false,
				maxDate:'28.12.'+((d.getFullYear()-15).toString()),formatDate:'d.m.Y',
				startDate:'28.12.'+((d.getFullYear()-15).toString()),formatDate:'d.m.Y',
			});

			$('.regType').change(function(event) {
				if($(this).val() == 'S')
				{
					$('#singleForm').show();
					$('#orgForm').hide();
				}
				else
				{
					$('#orgForm').show();
					$('#singleForm').hide();
				}
 			});
    	});
</script>