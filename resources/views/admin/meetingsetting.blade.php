<h4>Meeting Settings</h4>
<div id="meetingList">
	@if($user)
	@foreach($attendees as $key=>$value)
		<div class="meetingParent">
			{{$value}} Attendee
			<div class="clearboth"></div>
			<span class="removeMeeting" mid="{{$key}}"> Remove</span>
		</div>
		@endforeach
		@foreach($minuters as $key=>$value)
			<div class="meetingParent">
			{{$value}} Minuter
			<div class="clearboth"></div>
			<span class="removeMeeting" mid="{{$key}}"> Remove</span>
		</div>
		@endforeach
	<div class="meetingItem">
		{!! Form::select('meetings[]', [''=>'Select Meeting']+$meetings)!!}
		{!! Form::select('roles[]', [''=>'Select Role']+roles())!!}
		<div class="clearboth"></div>
	</div>
	@else
	<div class="meetingItem">
		{!! Form::select('meetings[]', [''=>'Select Meeting']+$meetings)!!}
		{!! Form::select('roles[]', [''=>'Select Role']+roles())!!}
		<div class="clearboth"></div>
	</div>
	@endif
</div>
<button id="addmeeting">Add</button>