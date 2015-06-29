{!! Form::open(array('id' => 'createMinuteForm')) !!}
{!! Form::text('venue',$meeting->venue) !!}
{!! Form::text('minuteDate',date('Y-m-d')) !!}
<div class="col-md-12" Id="attendees">
	<?php
	foreach ($users as $key=>$value)
	{
		echo '<div class="col-md-2 attendees" uid="u'.$key.'"><input type="hidden" name="attendees[]" value="'.$key.'">'.$value.'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
	}
		?>
</div>
{!! Form::close() !!}
<button id="createMinute" mid="{{$meeting->id}}" type="button" class="btn btn-primary">
	Proceed
</button>
<div class="col-md-12" id="createMinuteError">
</div>