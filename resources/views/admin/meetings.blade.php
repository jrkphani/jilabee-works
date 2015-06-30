@extends('admin')
@section('css')		
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
@stop
@section('content')
<div class="row">
	<div class="col-md-12 form-group">
		<button id="newMeetingToggle" type="button" class="btn btn-primary" data-toggle="modal" data-target="#createMeetingModal">
			New meeting
		</button>
	</div>
		@if($meetings->count())
			@foreach($meetings as $meeting)
			<div class="col-md-12" id="meetingBlock{{$meeting->id}}">
			{!! Form::open(array('id' => 'm'.$meeting->id)) !!}
			<div class="col-md-12 form-group">
				{!! Form::hidden('mid', $meeting->id) !!}
	        	{!! Form::label('title', 'Meeting title',['class'=>'control-label']); !!}
	        	{!! Form::text('title', $meeting->title,['class'=>'form-control title','id'=>''])!!}
	        	<div class="title_err error"></div>
	        	
	        	{!! Form::label('description', 'Meeting description',['class'=>'control-label']); !!}
	        	{!! Form::textarea('description', $meeting->description,['class'=>'form-control','id'=>''])!!}
	        	<div class="description_err error"></div>

	        	{!! Form::label('selectMinuters', 'Expected Minuters',['class'=>'control-label']); !!}
	        	<div class="selected_minuters">
	        		@if($meeting->minuters)
	        			<?php
	        				$minuters = App\Model\Profile::select('userId','name')->whereIn('userId',explode(',', $meeting->minuters))->get();
	        				foreach ($minuters as $minuter)
	        				{
	        					//echo $minuter->value;
	        					echo '<div class="col-md-2 attendees" uid="u'.$minuter->userId.'"><input type="hidden" name="minuters[]" value="'.$minuter->userId.'">'.$minuter->name.'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
	        				}
	        			?>
	        		@endif
	        	</div>
	        	
	        	{!! Form::text('selectMinuters', '',['class'=>'form-control selectMinuters','id'=>''])!!}
	        	<div class="minuters_err error"></div>

	        	{!! Form::label('selectAttendees', 'Expected Attendees',['class'=>'control-label']); !!}
	        	<div class="selected_attendees">
	        			@if($meeting->attendees)
	        			<?php
	        				$attendees = App\Model\Profile::select('userId','name')->whereIn('userId',explode(',', $meeting->attendees))->get();
	        				foreach ($attendees as $attendee)
	        				{
	        					//echo $minuter->value;
	        					echo '<div class="col-md-2 attendees" uid="u'.$attendee->userId.'"><input type="hidden" name="minuters[]" value="'.$attendee->userId.'">'.$attendee->name.'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
	        				}
	        			?>
	        			@endif
	        	</div>
	        	
	        	{!! Form::text('selectAttendees', '',['class'=>'form-control selectAttendees','id'=>''])!!}
	        	<div class="attendees_err error"></div>

	        	{!! Form::label('venue', 'Venue',['class'=>'control-label']); !!}
	        	{!! Form::text('venue', $meeting->venue,['class'=>'form-control','id'=>''])!!}
	        	<div class="venue_err error"></div>

	        	{!! Form::label('reason', 'Reason for disapprove',['class'=>'control-label']); !!}
	        	{!! Form::text('reason', $meeting->reason,['class'=>'form-control','placeholder'=>'Reason for disapprove','id'=>''])!!}
	        	<div class="reason_err error"></div>
		    </div>
			{!! Form::close() !!}
			<div class="row">
				<div class="col-md-12">
					Requested by : {{$meeting->requested_by}}
				</div>
				<div class="col-md-3 col-md-offset-3">
					<button type="button" class="btn btn-primary approve" id="{{$meeting->id}}">Approve</button>
					@if($meeting->status != 'rejected')
					<button type="button" class="btn btn-primary disapprove" id="{{$meeting->id}}">Disapprove</button>
					@endif
				</div>
	    	</div>
	    	</div>
			@endforeach
		@endif
	<div id="createMeetingModal" class="modal fade">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">New Meeings</h4>
	      </div>
	      <div class="modal-body">
	        <div class="row">
		        {!! Form::open(array('id' => 'createMeetingForm')) !!}
		        <div class="col-md-12 form-group">
		        	{!! Form::label('title', 'Meeting title',['class'=>'control-label']); !!}
		        	{!! Form::text('title', '',['class'=>'form-control'])!!}
		        	<div id="title_err" class="error"></div>
		        	
		        	{!! Form::label('description', 'Meeting description',['class'=>'control-label']); !!}
		        	{!! Form::textarea('description', '',['class'=>'form-control'])!!}
		        	<div id="description_err" class="error"></div>

		        	{!! Form::label('selectMinuters', 'Expected Minuters',['class'=>'control-label']); !!}
		        	<div id="selected_minuters"></div>
		        	
		        	{!! Form::text('selectMinuters', '',['class'=>'form-control'])!!}
		        	<div id="minuters_err" class="error"></div>

		        	{!! Form::label('selectAttendees', 'Expected Attendees',['class'=>'control-label']); !!}
		        	<div id="selected_attendees" class="form-group"></div>
		        	
		        	{!! Form::text('selectAttendees', '',['class'=>'form-control'])!!}
		        	<div id="attendees_err" class="error"></div>

		        	{!! Form::label('venue', 'Venue',['class'=>'control-label']); !!}
		        	{!! Form::text('venue', '',['class'=>'form-control'])!!}
		        	<div id="venue_err" class="error"></div>
		        </div>
		        {!! Form::close() !!}
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button id="createMeetingSubmit" type="button" class="btn btn-primary">Save</button>
	      </div>
	    </div>

	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>
@endsection
@section('javascript')
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="{{ asset('/js/adminMeetings.js') }}"></script>
@endsection