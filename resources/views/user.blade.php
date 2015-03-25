@if(Request::ajax())
	@yield('leftcontent')
	@yield('rightcontent')
	{{die()}}
@else
	@extends('master')
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
			<div id="stickynotes_content" class="col-md-12">
				@include('stickynotes')
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-12">
						<ul class="nav nav-tabs">
						    <li class="user_left_menu" id="menuMytask"><a href="#">My Task <span class="badge">2</span></a>
						    </li>
						    <li class="user_left_menu" id="menuFolloup" url="{{ app_url('/') }}">
						    	<a href="#">Follow Ups <span class="badge">4</span></a>

						    </li>
						    <li class="user_left_menu" id="menuMinutes">
						    	<a href="#">Minutes <span class="badge">1</span></a>
						    </li>
					  	</ul>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12  margin_top_20 border_right" id="user_left_menu_cont">
						<!-- Ajax content -->
						@yield('leftcontent')
					</div>
				</div>
			</div>
			<div class="col-md-8 border_left" id="content_right">
				<!-- Ajax content -->
				@yield('rightcontent')
			</div>
		</div>
	</div>
	@stop
	@section('javascript')		
	    <script src="{{ asset('/js/user.js') }}"></script>
	    <script src="{{ asset('/js/add_comment.js') }}"></script>
	    <script src="{{ asset('/js/add_notes.js') }}"></script>
	@stop
@endif