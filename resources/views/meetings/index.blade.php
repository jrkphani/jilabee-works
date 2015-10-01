@extends('master')
@section('css')		
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link href="{{ asset('/css/jquery.simple-dtpicker.css') }}" rel="stylesheet">
@stop
@section('content')
<div id="contentLeft" class="contentLeft">
	<div class="mainListFilter">
				<input type="text" placeholder="Search..." id="historySearch" autocomplete="off" value="{{$historysearchtxt}}">
				<span id="showHistroyDiv" class="button">Reset</span>
			</div>
		<div class="contentMeetingsLeft">
			@include('meetings.history')
		</div>
		<div class="contentMeetingsRight" id="historyMeetingsRight">
		</div>
		<div class="clearboth"></div>
			<div class="arrowBtn arrowBtnRight">
				<span id="moveright"><img src="images/arrow_right.png"> </span>
				<p>Now</p>
			</div>
			
	</div>

<!-- Now section -->
<div id="contentRight" class="contentRight">
		<div class="mainListFilter">
			<input type="text" placeholder="Search..." id="nowSearch" autocomplete="off" value="{{$nowsearchtxt}}">
			<span class="button" id="showNowDiv">Reset</span>
		</div>
	<div class="contentMeetingsLeft">
		@include('meetings.now')
	</div>
	<div class="contentMeetingsRight" id="nowMeetingsRight">
		{{-- right side content --}}
	</div>
	<div class="clearboth"></div>
	<div class="arrowBtn">		
		<span id="moveleft"><img src="images/arrow_left.png"> </span>
		<p>History</p>
	</div>
	@if((Auth::user()->isAdmin !=1) && (Auth::user()->profile->role == 2))
	<button id="addMeeting" class="addBtn meetingsAddBtn"> </button>
	@endif
	<div class="popupOverlay" id="popup" ></div>
</div>

@endsection
@section('javascript')
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="{{ asset('/js/jquery.simple-dtpicker.js') }}"></script>
<script src="{{ asset('/js/meetings.js') }}"></script>
@endsection