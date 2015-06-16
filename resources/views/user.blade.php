@extends('master')
@section('css')
	<link href="{{ asset('/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/bootstrap-dialog.min.css') }}" rel="stylesheet">
@stop
@section('usercontent')
	<div class="container">
		<div class="row" id="stickynotes_open">
			<div class="col-md-1 btn pull-left">
				<span class="glyphicon glyphicon-chevron-left"></span>
			</div>
		</div>
		<div class="row" id="stickynotes">
			<div class="col-md-1 btn pull-left" id="stickynotes_close">
				<span class="glyphicon glyphicon-chevron-right"></span>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-tabs">
				    <li class="user_left_menu" id="menuMytask"><a href="#mytask">My Task {{-- <span class="badge">2</span> --}}</a>
				    </li>
				    <li class="user_left_menu" id="menuFolloup"><a href="#followup">Follow Up {{-- <span class="badge">4</span> --}}</a>
				    </li>
				    <li class="user_left_menu" id="menuMeetings"><a href="#meetings">Meetings {{-- <span class="badge">1</span> --}}</a>
				   </li>
				</ul>
			</div>
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-12  margin_top_10" id="user_left_menu_cont">
						<!-- Ajax content -->
						@yield('leftcontent')
					</div>
				</div>
			</div>
			<div class="col-md-8" id="content_right">
				<!-- Ajax content -->
				@yield('rightcontent')
			</div>
		</div>
	</div>
	@stop
	@section('javascript')
		<script src="{{ asset('/js/bootstrap-datepicker.min.js') }}"></script>
		<script src="{{ asset('/js/bootstrap-dialog.min.js') }}"></script>
	    <script src="{{ asset('/js/user.js') }}"></script>
	    <script src="{{ asset('/js/tasks.js') }}"></script>
	    <script src="{{ asset('/js/comments.js') }}"></script>
	@stop
