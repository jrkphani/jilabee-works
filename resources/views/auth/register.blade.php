@extends('master')

@section('guestcontent')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Register</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="{{ old('name') }}">
								{!! $errors->first('name','<div class="error">:message</div>') !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
								{!! $errors->first('email','<div class="error">:message</div>') !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
								{!! $errors->first('password','<div class="error">:message</div>') !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Confirm Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Phone</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
								{!! $errors->first('phone','<div class="error">:message</div>') !!}
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
								{!! $errors->first('gender','<div class="error">:message</div>') !!}
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Role</label>
							<div class="col-md-6">
								{!!Form::select('role',array('1'=>'user','999'=>'admin')) !!}
								{!! $errors->first('role','<div class="error">:message</div>') !!}
							</div>
						</div>
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