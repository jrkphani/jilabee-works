@extends('master')
@section('css')
	<link href="{{ asset('/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
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
			<div id="stickynotes_content" class="col-md-12">
				@include('notes.stickynotes',['stickynotes'=>App\Model\Stickynotes::where('created_by',Auth::id())->orderBy('updated_at','DESC')->get()])
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-tabs nav-justified">
				    <li class="{{ Request::is( 'admin/userlist') ? 'active' : '' }}">
				    	<a href="{{url('admin/userlist')}}" >Manage Users</a>
				    </li>
				    <li class="{{ Request::is( 'admin/meetings') ? 'active' : '' }}">
				    	<a href="{{url('admin/meetings')}}"> Manage Meetings</a>
				    </li>
				</ul>
			</div>
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12  margin_top_10" id="user_left_menu_cont">
						@yield('content')
					</div>
				</div>
			</div>
		</div>
	</div>
	@stop
	@section('javascript')
		<script src="{{ asset('/js/bootstrap-datepicker.min.js') }}"></script>
	@stop
