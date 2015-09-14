@extends('master')
@section('css')		
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link href="{{ asset('/css/jquery.simple-dtpicker.css') }}" rel="stylesheet">
@stop
@section('content')
<div id="contentLeft" class="contentLeft">
	<div class="mainListFilter">
				<input type="text" placeholder="Search...">
				<select>
				  <option value="0">Any meeting</option>
				  <option value="Option">Option 1</option>
				  <option value="Option">Option 2</option>
				  <option value="Option">Option 3</option>
				</select>
				<select>
				  <option value="0">Any time</option>
				  <option value="Option">Option 1</option>
				  <option value="Option">Option 2</option>
				  <option value="Option">Option 3</option>
				</select>
				<select>
			  <option value="0">Location</option>
			  <option value="Option">Option 1</option>
			  <option value="Option">Option 2</option>
			  <option value="Option">Option 3</option>
			</select>
			</div>
		<div class="contentMeetingsLeft">
			<div class="boxList">
			<div class="boxTitle">
				<span class="boxTitleNumber boxNumberBlue">{{count($closedMeetings)}}</span>
				<p>Closed Meetings</p>
				<div class="clearboth"></div>
			</div>
			@foreach($closedMeetings as $minute)
				<div class="box">
					<span class="boxNumber boxNumberBlue">1</span>
					<div class="boxInner minute_history" mid="{{$minute->id}}">
						<h4>{{$minute->meeting()->withTrashed()->first()->title}}</h4>
						<p>{{$minute->startDate}}</p>
					</div>
					<div class="boxRight closed_minute" mid="{{$minute->id}}"></div>
				</div>
			@endforeach
			</div>
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
			<input type="text" placeholder="Search...">
			<select>
			  <option value="0">Sort by</option>
			  <option value="Option">Option 1</option>
			  <option value="Option">Option 2</option>
			  <option value="Option">Option 3</option>
			</select>
		</div>
	<div class="contentMeetingsLeft">
		
		<div class="boxList">
			<div class="boxTitle">
				<span class="boxTitleNumber boxNumberRed">{{count($pendingmeetings)}}</span>
				<p>Pending</p>
				<div class="clearboth"></div>
			</div>
			@foreach($pendingmeetings as $meeting)
			<?php $details = unserialize($meeting->details); ?>
				<div class="box">
					<span class="boxNumber boxNumberRed">1</span>
					<div class="boxInner pendingmeetings" mid="{{$meeting->id}}">
						<h4>{{$meeting->title}}</h4>
						<p>{{$meeting->created_at}}</p>
					</div>
					<div class="boxRight" mid="{{$meeting->id}}">
						@if($meeting->draft == '1')
						<p class="boxRightText">draft</p>
						@endif
					</div>
				</div>
			@endforeach
		</div>
		<div class="boxList">
			<div class="boxTitle">
				<span class="boxTitleNumber boxNumberBlue">{{count($recentMinutes)}}</span>
				<p>Recent Minutes</p>
				<div class="clearboth"></div>
			</div>
			@foreach($recentMinutes as $minute)
				<div class="box">
					<span class="boxNumber boxNumberBlue">1</span>
					<div class="boxInner minute_history" mid="{{$minute->id}}">
						<h4>{{$minute->meeting->title}}</h4>
						<p>{{$minute->startDate}}</p>
					</div>
					<div class="boxRight minute" mid="{{$minute->id}}"></div>
				</div>
			@endforeach
		</div>
		<div class="boxList">
			<div class="boxTitle">
				<span class="boxTitleNumber boxNumberRed">{{count($notfiled)}}</span>
				<p>Not Filed</p>
				<div class="clearboth"></div>
			</div>
			@foreach($notfiled as $minute)
				<div class="box">
					<span class="boxNumber boxNumberRed">1</span>
					<div class="boxInner minute_history" mid="{{$minute->id}}">
						<h4>{{$minute->meeting->title}}</h4>
						<p>{{$minute->startDate}}</p>
					</div>
					<div class="boxRight minute" mid="{{$minute->id}}"></div>
				</div>
			@endforeach
		</div>
		<div class="boxList">
			<div class="boxTitle">
				<span class="boxTitleNumber boxNumberBlue">{{count($newmeetings)}}</span>
				<p>New Meetings</p>
				<div class="clearboth"></div>
			</div>
			@foreach($newmeetings as $meeting)
				<div class="box">
					<span class="boxNumber boxNumberBlue">1</span>
					<div class="boxInner" mid="{{$meeting->id}}">
						<h4>{{$meeting->title}}</h4>
					</div>
					<div class="boxRight firstMinute" mid="{{$meeting->id}}"></div>
				</div>
			@endforeach
		</div>

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