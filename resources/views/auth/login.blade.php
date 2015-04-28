
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Jotter</title>
	
	<link href='http://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/sticky-footer-navbar.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/jotter.css') }}" rel="stylesheet">
	@yield('css')

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
<div class="container">
  <div class="row">
  	<div class="col-sm-8 border_right">
  		<div class="col-sm-8 col-sm-offset-5">
  			<div class="navbar-brand" id="logo" style="height:100px; font-size:100px"><strong>Jotter</strong></div>
  		</div>
  		<div class="col-sm-6 col-sm-offset-6"><h4>Login</h4></div>
  			<div class="col-sm-12">
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-8">
								<input type="email" class="form-control" name="email" placeholder="email@domain.com" value="{{ old('email') }}">
								{!! $errors->first('email','<div class="error">:message</div>') !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Password</label>
							<div class="col-md-8">
								<input type="password" class="form-control" name="password">
								{!! $errors->first('password','<div class="error">:message</div>') !!}
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember"> Remember Me
									</label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">Login</button>

								<a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a>
							</div>
						</div>
					</form>
				</div>
    </div>
    <div class="col-sm-4">
    	 {{-- <h4>Don't have a login?</h4>
    	 <a href="{{ app_url('/auth/register') }}">Register</a> --}}
    	 <h4>Want to learn more?</h4>
    	 <a>See how Jotter can help you and your organisatoin!</a>
    </div>
  </div>
</div>
	<footer class="footer">
      <div class="container">
      	<div class="row">
      		<div class="col-md-6">
		        <p class="text-muted">
		        	<span class="glyphicon glyphicon-copyright-mark"></span>{{date('Y')}}
		        	<span class="border_left">Jotter</span>
		        	<span class="border_left">All Rights Reserved</span>
		        </p>
		    </div>
		    <div class="col-md-6">
		        <p class="text-muted text-right">
		        	<span>About Us</span>
		        	<span class="border_left">Private Policy</span>
		        </p>
		    </div>
    	</div>
      </div>
	</footer>
</body>
</html>