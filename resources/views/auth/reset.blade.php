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
			@if (session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
					@endif
					<form role="form" method="POST" action="{{ url('/password/reset') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="token" value="{{ $token }}">
						<center><label for="">Reset Password</label></center>
						<input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}">
						<input type="password" name="password" placeholder="Password">
						<input type="password" name="password_confirmation" placeholder="Confirm Password">
						@if (count($errors) > 0)	
							@foreach ($errors->all() as $error)
								<div class="error">{{ $error }}</div>
							@endforeach
						@endif
						<button type="submit">Reset Password</button>
					</form>
		</div>
	</div>
</body>
</html>