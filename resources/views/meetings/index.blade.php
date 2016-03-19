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
    @foreach($meetings as $meeting)
    <a href="{{url('meetings/view/'.$meeting->id)}}" >{{$meeting->title}}</a>
    @endforeach
    <div class="meeting-landing" style="height: 500px"></div>
</div>
@endsection
@section('javascript')
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="{{ asset('/js/jquery.datetimepicker.full.js') }}"></script>
@endsection