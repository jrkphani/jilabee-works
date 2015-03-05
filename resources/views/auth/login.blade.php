@extends('master')

@section('guestcontent')

<div class="container">
  <div class="row">
  	<div class="col-sm-8 border_right">
  		<div class="col-sm-6 col-sm-offset-6"><h4>Login</h4></div>
  			<div class="col-sm-12">
    			@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-8">
								<input type="email" class="form-control" name="email" placeholder="email@domain.com" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Password</label>
							<div class="col-md-8">
								<input type="password" class="form-control" name="password">
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
    	 <h4>Don't have a login?</h4>
    	 <a>Sign Up!</a>
    	 <h4>Want to learn more?</h4>
    	 <a>See how Jotter can help you and your organisatoin!</a>
    </div>
  </div>
</div>

@endsection
