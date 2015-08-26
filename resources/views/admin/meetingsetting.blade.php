<div id="meetingList">
	@if($user)
	@foreach($attendees as $key=>$value)
		<div class="meetingSettingITem meetingParent">
			<span class="removeMeeting removeMoreBtn" mid="{{$key}}"></span>
			<p>{{$meetings[$key]}}</p>
			{{$roles[1]}}
			<div class="clearboth"></div>
		</div>
	@endforeach
	@foreach($minuters as $key=>$value)
		<div class="meetingSettingITem meetingParent">
			<span class="removeMeeting removeMoreBtn" mid="{{$key}}"></span>
			<p>{{$meetings[$key]}}</p>
			{{$roles[2]}}
			<div class="clearboth"></div>
		</div>
	@endforeach
	@endif
	<div class="meetingSettingITem meetingItem">
		<span class="removeMoreBtn removeMeeting"></span>
		<p>{!! Form::select('meetings[]', [''=>'Select Meeting']+$meetings)!!}</p>
		{!! Form::select('roles[]', [''=>'Select Role']+roles())!!}
		<div class="clearboth"></div>
	</div>
</div>
<div class="meetingSettingITem">
<button id="addmeeting" class="addMoreBtn"></button>
<div class="clearboth"></div>
</div>