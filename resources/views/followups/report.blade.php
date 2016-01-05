@extends('master')
@section('css')		
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link href="{{ asset('/css/jquery.datetimepicker.css') }}" rel="stylesheet">
<style type="text/css" media="screen">
	#centralContainer {width:100% !important}
</style>
@stop
@section('content')
<div class="indexLogin">
		<h1></h1>
		<p>
			<center>Followup Report</center></p>
		<div class="indexLoginForm">
			<form class="form-horizontal" role="form" method="POST" action="{{ url('/report/') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				
				<input type="text" name="startdate" class="date" placeholder="start date" value="{{ old('startdate') }}" autocomplete="off">
				{!! $errors->first('startdate','<div class="error">:message</div><br/>') !!}
				
				<input type="text" name="enddate" class="date" autocomplete="off" placeholder="end date" value="{{ old('enddate') }}"><br/>
				{!! $errors->first('enddate','<div class="error">:message</div><br/>') !!}
				<center><input class="login_loginbtn" type="submit" value="Submit"></center>
				<div class="clearboth"></div>
			</form>    	 
		</div>
		<br/>
			<div class="clearboth"></div>
	</div>
@endsection
@section('javascript')
<script src="{{ asset('/js/jquery.datetimepicker.full.js') }}"></script>
<script>
$(document).ready(function($)
	{
		$('.date').datetimepicker({
				format:'Y-m-d',
				timepicker:false,
				maxDate:'28.12',formatDate:'d.m.Y',
			});
	});
</script>
@endsection