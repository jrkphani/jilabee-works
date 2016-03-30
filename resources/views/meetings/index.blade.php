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
        <select name="meeting_id" id="meeting_id" tabindex="1" autocomplete="off">
            <option>SELECT MEETING</option>
            @foreach($meetings as $meeting)
            <option href="{{url('meetings/view/'.$meeting->id)}}" >{{$meeting->title}}</option>
            @endforeach
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
</div>
@endsection
@section('javascript')
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="{{ asset('/js/jquery.datetimepicker.full.js') }}"></script>
<script type="text/javascript" charset="utf-8">
    $('#meeting_id').change(function(event) {
        window.location = $('option:selected', this).attr('href');
    });
</script>
@endsection