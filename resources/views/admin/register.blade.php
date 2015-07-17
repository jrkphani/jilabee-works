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
						<div class="form-group">
							<label class="col-md-4 control-label">Domain</label>
							<div class="col-md-6">
								<input type="text" autocomplete="off" class="form-control" name="domain" value="{{ old('domain') }}">
								{!! $errors->first('domain','<div class="error">:message</div>') !!}
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
						<div class="col-md-12">Admin User Details</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Admin Name</label>
							<div class="col-md-6">
								<input type="text" autocomplete="off" class="form-control" name="adminname" value="{{ old('name') }}">
								{!! $errors->first('adminname','<div class="error">:message</div>') !!}
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
							<label class="col-md-4 control-label">DOB</label>
							<div class="col-md-6">
								<input type="text" class="form-control dateInput" name="dob" value="{{ old('dob') }}">
								{!! $errors->first('dob','<div class="error">:message</div>') !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Gender</label>
							<div class="col-md-6">
								{!!Form::radio('gender', 'M') !!} Male 
								{!!Form::radio('gender', 'F') !!} Female
								{!!Form::radio('gender', 'O') !!} Others
								{!! $errors->first('gender','<div class="error">:message</div>') !!}
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-3 col-md-offset-3">
                           		<a class="btn btn-primary" href="{{url('/admin')}}">Back</a> 
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
@section('javascript')
<script src="{{ asset('/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
	$(document).ready(function($)
		{
			 $('.dateInput').datepicker({format: "yyyy-mm-dd",endDate: "-15y",startView: 2,autoclose: true});
    	});
	</script>
@stop
@section('css')
	<link href="{{ asset('/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@stop