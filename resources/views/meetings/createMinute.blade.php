<div class="popupContentTitle">
<p><strong>Current Minutes</strong></p>
{!! Form::open(['id'=>'MinuteForm']) !!}
@if($minute)

	<h4>{{$meeting->title}}</h4>
<div class="col-md-12">ID: M{{$minute->id}}</div>
{!! Form::hidden('minuteId', $minute->id)!!}
<p>
{!! Form::text('venue',$minute->venue,['placeholder'=>'venue']) !!}
{{$errors->first('venue','<div class="error">:message</div>')}}
{!! Form::text('minuteDate',$minute->minuteDate,['class'=>'dateInput','placeholder'=>'date']) !!}
{!!$errors->first('minuteDate','<div class="error">:message</div>')!!}
</p>
<p>
		<strong>Attendees</strong>
		<div id="attendees">
			 <?php
			 $emails = $attendees = array();
			 if($minute->attendees)
			 {
			 	foreach (explode(',',$minute->attendees) as $value)
			 	{
			 		if(isEmail($value))
			 		{
			 			$emails[]=$value;
			 		}
			 		else
			 		{
			 			$attendees[]=$value;
			 		}
			 	}
			 }
			 $attendees = App\Model\Profile::whereIn('userId',$attendees)->lists('name','userId');
			 ?>
			@foreach ($attendees as $key=>$value)
				<div class="attendees" uid="u{{$key}}">
					{!! Form::hidden('attendees[]',$key) !!}
					{{$value}}
				</div>
			@endforeach
			@foreach ($emails as $key=>$value)
				<div class="attendees" uid="u'.$value.'">
					{!! Form::hidden('attendees[]',$value) !!}
					{{$value}}
				</div>
			@endforeach
		</div>
		{!!$errors->first('attendees','<div class="error">:message</div>')!!}
	</p>
	<p>
		<strong>Absentees</strong>
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
				</div>
			@endforeach
			@foreach ($emails as $key=>$value)
				<div class="absentees" uid="u'.$value.'">
					{!! Form::hidden('absentees[]',$value) !!}
					{{$value}}
				</div>
			@endforeach
		</div>
	</p>

@else

	<h4>{{$meeting->title}}</h4>
	<p>
		{!! Form::text('venue','',['placeholder'=>'venue']) !!}
		{!!$errors->first('venue','<div class="error">:message</div>')!!}
		{!! Form::text('minuteDate','',['class'=>'dateInput','placeholder'=>'date']) !!}
		{!!$errors->first('minuteDate','<div class="error">:message</div>')!!}
	</p>
	<p>
		<strong>Attendees</strong>
		<div id="attendees">
			@foreach ($attendees as $key=>$value)
				<div class="attendees" uid="u'.$user->userId.'">
					{!! Form::hidden('attendees[]',$key) !!}
					{{$value}}
				</div>
			@endforeach
		</div>
		{!!$errors->first('attendees','<div class="error">:message</div>')!!}
	</p>
	<p>
		<div class="col-md-12"><strong>Absentees</strong></div>
		<div class="col-md-12" id="absentees"></div>
	</p>


@endif
{!! Form::close() !!}
<button id="updateMinute">Update</button>
</div>
@if($minute)
@include('meetings.createTask')
@endif

<script type="text/javascript">
dateInput();
</script>