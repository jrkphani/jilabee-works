@extends('admin')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Register</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/auth/register') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">Organization Name</label>
							<div class="col-md-6">
								<input type="text" autocomplete="off" class="form-control" name="name" value="{{ old('name') }}">
								{!! $errors->first('name','<div class="error">:message</div>') !!}
							</div>
						</div>
						{{-- <div class="form-group">
							<label class="col-md-4 control-label">Tag</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="tagLine" value="{{ old('tagLine') }}">
								{!! $errors->first('name','<div class="error">:message</div>') !!}
							</div>
						</div> --}}
						<div class="form-group">
							<label class="col-md-4 control-label">Domain</label>
							<div class="col-md-6">
								<input type="text" autocomplete="off" class="form-control" name="domain" value="{{ old('domain') }}">
								{!! $errors->first('domain','<div class="error">:message</div>') !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-6">
								<input type="email" autocomplete="off" class="form-control" name="email" value="{{ old('email') }}">
								{!! $errors->first('email','<div class="error">:message</div>') !!}
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Secondary E-Mail</label>
							<div class="col-md-6">
								<input type="email" autocomplete="off" class="form-control" name="secondEmail" value="{{ old('secondEmail') }}">
								{!! $errors->first('secondEmail','<div class="error">:message</div>') !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Password</label>
							<div class="col-md-6">
								<input type="password" autocomplete="off" class="form-control" name="password">
								{!! $errors->first('password','<div class="error">:message</div>') !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Confirm Password</label>
							<div class="col-md-6">
								<input type="password" autocomplete="off" class="form-control" name="password_confirmation">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Phone</label>
							<div class="col-md-6">
								<input type="text" autocomplete="off" class="form-control" name="phone" value="{{ old('phone') }}">
								{!! $errors->first('phone','<div class="error">:message</div>') !!}
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Phone1</label>
							<div class="col-md-6">
								<input type="text" autocomplete="off" class="form-control" name="phone1" value="{{ old('phone1') }}">
								{!! $errors->first('phone1','<div class="error">:message</div>') !!}
							</div>
						</div>
						{{-- <div class="form-group">
							<label class="col-md-4 control-label">Require Licenses</label>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-2 control-label">Jobs</label>
									<div class="col-md-2">
										<input type="text" class="form-control dateInput" name="jobs" value="{{ old('jobs') }}">
									</div>
									<label class="col-md-2 control-label">Meetings</label>
									<div class="col-md-2">
										<input type="text" class="form-control dateInput" name="meetings" value="{{ old('meetings') }}">
									</div>
								</div>
								{!! $errors->first('dob','<div class="error">:message</div>') !!}
							</div>
						</div> --}}
						<div class="form-group">
							<div class="col-md-3 col-md-offset-3">
                           		<a class="btn btn-primary" href="{{ URL::previous() }}">Back</a> 
                        	</div>
							<div class="col-md-3 col-md-offset-3">
								<button type="submit" class="btn btn-primary">
									Register
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection