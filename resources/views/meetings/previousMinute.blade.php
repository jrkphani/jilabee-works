<?php
$attendess = App\Model\Profile::select('name')->whereIn('userId',explode(',',$minute->attendess))->get();
if($minute->absentees)
{
	$absentees = App\Model\Profile::select('name')->whereIn('userId',explode(',',$minute->absentees))->get();
}
else
{
	$absentees = NULL;
}
?>
<div ><strong>Minutes of the Meeting on: {{$minute->startDate}} - {{$minute->endDate}}</strong></div>
<div >ID: {{$minute->meetingId}}M{{$minute->id}}</div>
<div >	
	@if($minute->venue)
		Venue : {{$minute->venue}}
	@endif
</div>
<div >	
	{{-- not show closed/canceled task in last meeting --}}
	{{-- @foreach($minute->file()->where('status','!=','Canceled')->where('status','!=','Closed')->get() as $task) --}}
	@foreach($minute->file()->get() as $task)
		<div class="previousTaskBlock taskDiv">
			{!! Form::hidden('tid[]', $task->id)!!}
			{!! Form::hidden('type[]', 'task')!!}
			<div>
				<span class="removeTaskFrom removeMoreBtn"></span>
				{!! Form::select('',['task'=>'Task','idea'=>'Idea'],'',array('class'=>'type','autocomplete'=>'off','disabled')) !!}
				<div class="clearboth"></div>
			</div>
			<div class="minuteItemNumber">
				<p>1</p>
			</div>
			<div class="minuteItemLeft">
				<h5>{!! Form::text('title[]',$task->title,array('placeholder'=>'Title','autocomplete'=>'off','class'=>'clearVal')) !!}</h5>
				<p>{!! Form::textarea('description[]',$task->description,array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5,'class'=>'clearVal')) !!}</p>
			</div>
			<div class="minuteItemRight">
				<p>
					{!! Form::select('assigner[]',array(''=>'Assinger')+$attendees,$task->assigner,array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
				</p>
				<p>
					{!! Form::select('assignee[]',array(''=>'Assingee')+$attendees,$task->assignee,array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
				</p>
				<p>{!! Form::text('dueDate[]',$task->dueDate,array('class'=>"dateInputNext taskinput dateInput clearVal",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}</p>
				<p>{!! Form::select('orginator[]',array(''=>'Orginator')+$attendees,'',array('autocomplete'=>'off','class'=>'clearVal ideainput','style'=>'display:none;')) !!}</p>
			</div>
			<div class="clearboth"></div>
		</div>
	@endforeach
	@foreach($minute->ideas()->get() as $idea)
		<div>ID: {{$minute->meetingId}}M{{$minute->id}}D{{$idea->id}}</div>
		<div>Orginator: @if($idea->orginator){{$idea->orginatorDetail->name}} @endif</div>
		<div>{{$idea->title}}</div>
		<div>{!!$idea->description!!}</div>
	@endforeach
</div>