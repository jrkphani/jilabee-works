<div class="popupContentTitle">
@if($minute)
{{-- Previous Minutes Will Be Here --}}
	@if($previousMinute = App\Model\Minutes::where('meetingId','=',$meeting->id)->where('field','=','1')->orderBy('startDate', 'DESC')->limit(1)->first())
		@if($previousMinute)
		<br/><br/>
			<div class ="row">
				<p><strong>Previous Minutes</strong></p>
				@include('meetings.previousMinute',['minute'=>$previousMinute])
			</div>
		@endif
	@endif
@endif
<p><strong>Current Minutes</strong></p>
{!! Form::open(['id'=>'MinuteForm']) !!}
@if($minute)

	<h4>{{$meeting->title}}</h4>
<div class="col-md-12">ID: M{{$minute->id}}</div>
{!! Form::hidden('minuteId', $minute->id)!!}
<p>
{!! Form::text('venue',$minute->venue,['placeholder'=>'venue']) !!}
{{$errors->first('venue','<div class="error">:message</div>')}}
{!! Form::text('startDate',$minute->startDate,['class'=>'dateInput','placeholder'=>'date']) !!}
{!!$errors->first('startDate','<div class="error">:message</div>')!!}
{!! Form::text('endDate',$minute->endDate,['class'=>'dateInput','placeholder'=>'date']) !!}
{!!$errors->first('endDate','<div class="error">:message</div>')!!}
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
					<span class="markabsent"> Remove</span>
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
<button id="updateMinute">Update</button>
@else

	<h4>{{$meeting->title}}</h4>
	<p>
		{!! Form::text('venue',$meeting->venue,['placeholder'=>'venue']) !!}
		{!!$errors->first('venue','<div class="error">:message</div>')!!}
		{!! Form::text('startDate',date('Y-m-d'),['class'=>'dateInput','placeholder'=>'date']) !!}
		{!!$errors->first('startDate','<div class="error">:message</div>')!!}
		{!! Form::text('endDate',date('Y-m-d'),['class'=>'dateInput','placeholder'=>'date']) !!}
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
		</div>
		{!!$errors->first('attendees','<div class="error">:message</div>')!!}
	</p>
	<p>
		<div class="col-md-12"><strong>Absentees</strong></div>
		<div class="col-md-12" id="absentees"></div>
	</p>

<button id="updateMinute">Proceed</button>
@endif
{!! Form::close() !!}
</div>
@if($minute)
@include('meetings.createTask')
@endif

<script type="text/javascript">
//dateInput();
$('.dateInput').datepicker({dateFormat: "yy-mm-dd",maxDate: "today",changeMonth: true,changeYear: true});
function dateInputNext()
{
	$('.dateInputNext').datepicker({dateFormat: "yy-mm-dd",minDate: "today",changeMonth: true,changeYear: true});
}
dateInputNext();
</script>