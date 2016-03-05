@extends('master')
@section('css')		
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link href="{{ asset('/css/jquery.datetimepicker.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="header-row2">
    <h2>Meetings</h2>
    <div class="header-search">
        <label for="RadioGroup1_0"><input type="radio" name="RadioGroup1" value="radio" id="RadioGroup1_0" /> ongoing</label>
        <label for="RadioGroup1_1"><input type="radio" name="RadioGroup1" value="radio" id="RadioGroup1_1" /> Archived</label>
        <label for="RadioGroup1_2"><input type="radio" name="RadioGroup1" value="radio" id="RadioGroup1_2" /> All</label>
    </div>
    <div class="header-sort">
        <select name="meeting_id" id="meeting_id" tabindex="1">
            <option>SELECT MEETING</option>
            <option>Meeting name 01 </option>
            <option>Meeting name 02</option>
            <option>Meeting name 03</option>
        </select>
    </div>
    <div class="header-reset">

    </div>
	@if((Auth::user()->isAdmin !=1) && (Auth::user()->profile->role == 2))
    	<a href="create-followup.html" class="btn-inter btn-create-task">+ create  meeting</a>
    @endif
</div>
<div class="inner-container follow">
    <div id="horz-scroll" class="content jobs-scroll"></div>
    <div class="meeting-landing" style="height: 500px"></div>
    @if(count($minutes['notfiled']) || count($minutes['pendingmeetings']) || count($minutes['newmeetings']))
		<div class="">
			<div class="">
				<span class="">{{count($minutes['notfiled'])+count($minutes['pendingmeetings'])+count($minutes['newmeetings'])}}</span>
				<p>Draft</p>
				<div class="clearboth"></div>
			</div>
			<?php $count = 1; ?>
			@foreach($minutes['newmeetings'] as $meeting)
				<div class="">
					<span class="">{{$count++}}</span>
					<div class="Inner" mid="{{$meeting->id}}">
						<h4>{{$meeting->title}}</h4>
					</div>
					<div class=" firstMinute" mid="{{$meeting->id}}"></div>
				</div>
			@endforeach
			@foreach($minutes['pendingmeetings'] as $meeting)
			<?php $details = unserialize($meeting->details); ?>
				<div class=" pendingmeetings" mid="{{$meeting->id}}">
					<span class="">{{$count++}}</span>
					<div class="Inner">
						<h4>{{$meeting->title}}</h4>
						<p>{{$meeting->created_at}}</p>
					</div>
					<div class="" mid="{{$meeting->id}}">
						@if($meeting->draft == '1')
						<p class="Text">draft</p>
						@endif
					</div>
				</div>
			@endforeach
			@foreach($minutes['notfiled'] as $minute)
				<div class="">
					<span class="">{{$count++}}</span>
					<div class="Inner minute_history" mid="{{$minute->id}}">
						<h4>{{$minute->meeting->title}}</h4>
						<p>{{$minute->startDate}}</p>
					</div>
					<div class=" minute" mid="{{$minute->id}}"></div>
				</div>
			@endforeach	
		</div>
	@endif
	@if(count($minutes['recentMinutes']))
		<div class="">
			<div class="">
				<span class="">{{count($minutes['recentMinutes'])}}</span>
				<p>Recent Minutes</p>
				<div class="clearboth"></div>
			</div>
			<?php $count = 1; ?>
			@foreach($minutes['recentMinutes'] as $minute)
				<div class="">
					<span class="">{{$count++}}</span>
					<div class="Inner minute_history" mid="{{$minute->id}}">
						<h4>{{$minute->meeting->title}}</h4>
						<p>{{$minute->startDate}}</p>
					</div>
					<div class=" minute" mid="{{$minute->id}}"></div>
				</div>
			@endforeach
		</div>
	@endif
</div>
@endsection
@section('javascript')
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="{{ asset('/js/jquery.datetimepicker.full.js') }}"></script>
<script src="{{ asset('/js/meetings.js') }}"></script>
<script src="{{ asset('/js/search.js') }}"></script>
@endsection