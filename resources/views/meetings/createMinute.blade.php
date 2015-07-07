<div class="col-md-12"><strong>Today Minutes</strong></div>
{!! Form::open(array('id' => 'createMinuteForm')) !!}
{!! Form::text('venue',$meeting->venue) !!}
{!! Form::text('minuteDate',date('Y-m-d')) !!}
<div class"col-md-12">Attendees</div>
<div class="col-md-12" Id="attendees">
	<?php
	foreach ($users as $key=>$value)
	{
		echo '<div class="col-md-2 attendees" uid="u'.$key.'"><input type="hidden" name="attendees[]" value="'.$key.'">'.$value.'<span class="removeAttendees btn glyphicon glyphicon-trash"></span></div>';
	}
		?>
</div>
<div class="col-md-12"><strong>Absentees</strong></div>
<div class="col-md-12" Id="absentees"></div>
{!! Form::close() !!}
<button id="createMinute" mid="{{$meeting->id}}" type="button" class="btn btn-primary">
	Proceed
</button>
<div class="col-md-12" id="createMinuteError">
</div>