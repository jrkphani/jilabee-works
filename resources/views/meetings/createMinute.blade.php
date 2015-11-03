<div class="paper">
	<div class="paperBorder nextMinutePaper">
	{!! Form::open(['id'=>'MinuteForm']) !!}
	@if($minute)
		<h3>{{$meeting->title}}</h3>
	<div>ID: M{{$minute->id}}</div>
	{!! Form::hidden('minuteId', $minute->id)!!}
	<p>
	<br/>
	{!! Form::text('venue',$minute->venue,['placeholder'=>'venue']) !!}
	{{$errors->first('venue','<span class="error">:message</span>')}}
	{!! Form::text('startDate',$minute->startDate,['id'=>'startDateInput','placeholder'=>'start date']) !!}
	{!! Form::text('endDate',$minute->endDate,['id'=>'endSateInput','placeholder'=>'end date']) !!}
	@if($errors->has('startDate'))
		{!!$errors->first('startDate','<br><span class="error">:message</span>')!!}
	@elseif($errors->has('endDate'))
		{!!$errors->first('endDate','<br><span class="error">:message</span>')!!}
	@endif
	</p>
	<div class="attendeesLable">
			<h5>Attendees: </h5>
	</div>
			<div id="attendees" class="attendee_box">
				@foreach ($attendees as $key=>$value)
					<div class="attendees" uid="{{$key}}">
						{!! Form::hidden('attendees[]',$key) !!}
						{{$value}}
						<div class="markabsent"></div>
					</div>
				@endforeach
				@foreach ($attendeesEmail as $key=>$value)
					<div class="attendees" uid="{{$value}}">
						{!! Form::hidden('attendees[]',$value) !!}
						{{$value}}
						<div class="markabsent"></div>
					</div>
				@endforeach
			</div>
			{!!$errors->first('attendees','<div class="error">:message</div>')!!}
			<div class="clearboth"></div>
			<div class="absenteesLable">
			<h5>Absentees:</h5>
			</div>
			<div id="absentees" class="absentee_box">
				<?php
				 $emails = $absentees = array();
				 if($minute->absentees)
				 {
				 	foreach (explode(',',$minute->absentees) as $value)
				 	{
				 		if(isEmail($value))
				 		{
				 			$emails[$value]=$value;
				 		}
				 		else
				 		{
				 			$absentees[]=$value;
				 		}
				 	}
				 }
				 $absentees = App\Model\Profile::select('profiles.name','users.userId')
				 			->whereIn('profiles.userId',$absentees)
							->join('users','profiles.userId','=','users.id')
							->lists('profiles.name','users.userId');
				 ?>
				@foreach ($absentees as $key=>$value)
					<div class="absentees" uid="{{$key}}">
						{!! Form::hidden('absentees[]',$key) !!}
						{{$value}}
						<div class="removeabsent"></div>
					</div>
				@endforeach
				@foreach ($emails as $key=>$value)
					<div class="absentees" uid="{{$value}}">
						{!! Form::hidden('absentees[]',$value) !!}
						{{$value}}
						<div class="removeabsent"></div>
					</div>
				@endforeach
			</div>
			<div class="clearboth"></div>
			<input type="text" id="addParticipant" placeholder='add user'>
	<div id="updateMinute" class="button">Update</div>
	<br/>
	@else

		<h3>{{$meeting->title}}</h3>
		<p>
			{!! Form::text('venue',$meeting->venue,['placeholder'=>'venue']) !!}
			{!!$errors->first('venue','<span class="error">:message</span>')!!}
			{!! Form::text('startDate','',['id'=>'startDateInput','placeholder'=>'start date']) !!}
			{!! Form::text('endDate','',['id'=>'endSateInput','placeholder'=>'end date']) !!}
			@if($errors->has('startDate'))
				{!!$errors->first('startDate','<br><span class="error">:message</span>')!!}
			@elseif($errors->has('endDate'))
				{!!$errors->first('endDate','<br><span class="error">:message</span>')!!}
			@endif
		</p>
			<div class="attendeesLable">
				<h5>Attendees: </h5>
			</div>
			<div id="attendees"  class="attendee_box">
				@foreach ($attendees as $key=>$value)
					<div class="attendees" uid="{{$key}}">
						{!! Form::hidden('attendees[]',$key) !!}
						{{$value}}
						<span class="markabsent"></span>
					</div>
				@endforeach
				@foreach ($attendeesEmail as $key=>$value)
					<div class="attendees" uid="{{$value}}">
						{!! Form::hidden('attendees[]',$value) !!}
						{{$value}}
						<div class="markabsent"></div>
					</div>
				@endforeach
			</div>
			{!!$errors->first('attendees','<div class="error">:message</div>')!!}
			<div class="clearboth"></div>
			<div class="absenteesLable">
				<h5>Absentees: </h5>
			</div>
			<div id="absentees" class="absentee_box"></div>
			<div class="clearboth"></div>
			<input type="text" id="addParticipant" placeholder='add user'>
	<span class="button" id="updateMinute">Proceed</span>

	@endif
	{!! Form::close() !!}
	@if($minute)
		@include('meetings.createTask')
	@endif
	</div>

</div>

<script type="text/javascript">

$('#startDateInput').datetimepicker({
	format:'Y-m-d H:i',
	maxDate:0,
});
$('#endSateInput').datetimepicker({
	format:'Y-m-d H:i',
	maxDate:0,
});
$('#addParticipant').autocomplete({
            source: "/user/search",
            minLength: 2,
            select: function( event, ui ) {
                if($('#attendees, #absentees').find( "[uid="+ui.item.userId+"]").html())
                {
                	$('#addParticipant').val('');
                    alert('User already exist!');
                    return false;
                }
                else
                {
                	$('#attendees').append('<div uid="'+ui.item.userId+'" class="attendees"><input type="hidden" value="'+ui.item.userId+'" name="attendees[]">'+ui.item.value+'<div class="markabsent"></div></div>');
                }
                $('#addParticipant').val('');
                return false;
            }
            });
nextDateInput();
</script>