
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{env('APP_NAME')}}</title>
	<link href="{{ asset('/css/jotter.css') }}" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/base.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('css/sss.css') }}" />
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="login_body login_body_admin">
	<header>
	<!-- 	<h1>Jotter</h1> -->
	</header>
<div class="indexLogin">

  	<h1>{{env('APP_NAME')}}</h1>
  	<div class="indexLoginForm">		
  			
					<form  role="form" method="POST" action="{{ url('/admin/auth/login') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div>
							<label >E-Mail Address</label>
							<div >
								<input type="email" class="form-control" name="email" placeholder="email@domain.com" value="{{ old('email') }}">
								{!! $errors->first('email','<div class="error">:message</div>') !!}
							</div>
						</div>

						<div >
							<label >Password</label>
							<div >
								<input type="password" class="form-control login_admin_pwd" name="password">
								{!! $errors->first('password','<div class="error">:message</div>') !!}
							</div>
						</div>
						<div >
							<label class="login_admin_checkbox">
								<input type="checkbox" name="remember"> Remember Me
							</label>
						</div>
							
						<div  class="login_admin_loginbtn">
							<button type="submit" class="login_loginbtn">Login</button><br/>
							<a  href="{{ url('/password/email') }}">Forgot Your Password?</a>
						</div>
						
					</form>	
				</div>

    {{-- <div class="admin_extra">
    	 <h4>Don't have a login?</h4>
    	 <a href="{{ url('/admin/auth/register') }}">Register</a>
    	 <h4>Want to learn more?</h4>
    	 <a>See how Jotter can help you and your organisatoin!</a>
    </div> --}}
  </div>

	<footer class="footer">
      <div class="container">
      	<div class="row">
      		<div class="col-md-6">
		        <p class="text-muted">
		        	<span class="glyphicon glyphicon-copyright-mark"></span>{{date('Y')}}
		        	<span class="border_left">Anabond</span>
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