<div class="paper">
	<div class="paperBorder nextMinutePaper">
	{!! Form::open(['id'=>'MinuteForm']) !!}
	@if($minute)
		<h3>{{$meeting->title}}</h3>
	<div>ID: M{{$minute->id}}</div>
	{!! Form::hidden('minuteId', $minute->id)!!}
	<p>
	{!! Form::text('venue',$minute->venue,['placeholder'=>'venue']) !!}
	{{$errors->first('venue','<div class="error">:message</div>')}}
	{!! Form::text('startDate',$minute->startDate,['id'=>'startDateInput','placeholder'=>'date']) !!}
	{!!$errors->first('startDate','<div class="error">:message</div>')!!}
	{!! Form::text('endDate',$minute->endDate,['id'=>'endSateInput','placeholder'=>'date']) !!}
	{!!$errors->first('endDate','<div class="error">:message</div>')!!}
	</p>
	<div class="attendeesLable">
			<h5>Attendees</h5>
	</div>
			<div id="attendees">
				@foreach ($attendees as $key=>$value)
					<div class="attendees" uid="u{{$key}}">
						{!! Form::hidden('attendees[]',$key) !!}
						{{$value}}
						<div class="markabsent"></div>
					</div>
				@endforeach
				@foreach ($attendeesEmail as $key=>$value)
					<div class="attendees" uid="u'.$value.'">
						{!! Form::hidden('attendees[]',$value) !!}
						{{$value}}
						<div class="markabsent"></div>
					</div>
				@endforeach
			</div>
			{!!$errors->first('attendees','<div class="error">:message</div>')!!}
			<div class="clearboth"></div>
			<div class="absenteesLable">
			<h5>Absentees</h5>
			</div>
			<div id="absentees">
				<?php
				 $emails = $absentees = array();
				 if($minute->absentees)
				 {
				 	foreach (explode(',',$minute->absentees) as $value)
				 	{
				 		if(isEmail($value))
				 		{
				 			$emails[]=$value;
				 		}
				 		else
				 		{
				 			$absentees[]=$value;
				 		}
				 	}
				 }
				 $absentees = App\Model\Profile::whereIn('userId',$absentees)->lists('name','userId');
				 ?>
				@foreach ($absentees as $key=>$value)
					<div class="absentees" uid="u{{$key}}">
						{!! Form::hidden('absentees[]',$key) !!}
						{{$value}}
						<div class="removeabsent"></div>
					</div>
				@endforeach
				@foreach ($emails as $key=>$value)
					<div class="absentees" uid="u'.$value.'">
						{!! Form::hidden('absentees[]',$value) !!}
						{{$value}}
						<div class="removeabsent"></div>
					</div>
				@endforeach
			</div>
			<div class="clearboth"></div>
	<button id="updateMinute">Update</button>
	@else

		<h3>{{$meeting->title}}</h3>
		<p>
			{!! Form::text('venue',$meeting->venue,['placeholder'=>'venue']) !!}
			{!!$errors->first('venue','<div class="error">:message</div>')!!}
			{!! Form::text('startDate','',['id'=>'startDateInput','placeholder'=>'date']) !!}
			{!!$errors->first('startDate','<div class="error">:message</div>')!!}
			{!! Form::text('endDate','',['id'=>'endSateInput','placeholder'=>'date']) !!}
			{!!$errors->first('endDate','<div class="error">:message</div>')!!}
		</p>
		<p>
			<strong>Attendees</strong>
			<div id="attendees">
				@foreach ($attendees as $key=>$value)
					<div class="attendees" uid="u{{$key}}">
						{!! Form::hidden('attendees[]',$key) !!}
						{{$value}}
						<span class="markabsent"> Remove</span>
					</div>
				@endforeach
				@foreach ($attendeesEmail as $key=>$value)
					<div class="attendees" uid="u'.$value.'">
						{!! Form::hidden('attendees[]',$value) !!}
						{{$value}}
						<div class="markabsent"></div>
					</div>
				@endforeach
			</div>
			{!!$errors->first('attendees','<div class="error">:message</div>')!!}
		</p>
		<p>
			<div><strong>Absentees</strong></div>
			<div id="absentees"></div>
		</p>

	<button id="updateMinute">Proceed</button>
	@endif
	{!! Form::close() !!}
	@if($minute)
		@include('meetings.createTask')
	@endif
	</div>
</div>

<script type="text/javascript">

$('#startDateInput').appendDtpicker(
	{
	"minDate": new Date(),
	"autodateOnStart": false,
    "closeOnSelected": true
    });
$('#endSateInput').appendDtpicker(
	{
    "minDate": new Date(),
    "autodateOnStart": false,
	"closeOnSelected": true
    });
//  $('#endSateInput').change(function()
//  {
//  	$('#startDateInput').handleDtpicker('destroy');
//     $('#startDateInput').appendDtpicker(
//     {
// 	    "maxDate": new Date($('#endSateInput').val()),
// 	    "autodateOnStart": false,
//     	"closeOnSelected": true
// 	});
// });

// $('#startDateInput').change(function()
// {
// 	t=Date.parseDate($('#startDateInput').val(), "Y-m-dTg:i a");
// 	alert(t);
// 	$('#endSateInput').handleDtpicker('destroy');
//     $('#endSateInput').appendDtpicker(
//     {
// 	    "minDate": new Date($('#startDateInput').val()),
// 	    "autodateOnStart": false,
//     	"closeOnSelected": true
//     });
// });




nextDateInput();
</script>