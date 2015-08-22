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
<div class="col-md-12"><strong>Minutes of the Meeting on: {{$minute->startDate}} - {{$minute->endDate}}</strong></div>
<div class="col-md-12">ID: {{$minute->meetingId}}M{{$minute->id}}</div>
<div class="col-md-12">	
	@if($minute->venue)
		Venue : {{$minute->venue}}
	@endif
</div>
<div class="col-md-12">	
	{{-- not show closed/canceled task in last meeting --}}
	{{-- @foreach($minute->file()->where('status','!=','Canceled')->where('status','!=','Closed')->get() as $task) --}}
	@foreach($minute->file()->get() as $task)
		<div class="taskBlock">
				{!! Form::hidden('tid[]', $task->id)!!}
				{!! Form::hidden('type[]', 'task')!!}
				<p>{!! Form::text('title[]',$task->title,array('placeholder'=>'Title','autocomplete'=>'off','class'=>'clearVal')) !!}

				{!! Form::textarea('description[]',$task->description,array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5,'class'=>'clearVal')) !!}</p>
				
				<p>{!! Form::select('assigner[]',array(''=>'Assinger')+$attendees,$task->assigner,array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
			
				{!! Form::select('assignee[]',array(''=>'Assingee')+$attendees,$task->assignee,array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}

				{!! Form::text('dueDate[]',$task->dueDate,array('class'=>"dateInputNext taskinput clearVal",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}</p>
			</div>
		{{-- <div class="col-md-12">ID: {{$minute->meetingId}}M{{$minute->id}}T{{$task->id}}</div>
		<div class="col-md-12">Due Date: {{$task->dueDate}}</div>
		<div class="col-md-12">Status: {{$task->status}}</div>
		<div class="col-md-12">Assignee: {{$task->assigneeDetail->name}}</div>
		<div class="col-md-12">Assigner: @if($task->assigner){{$task->assignerDetail->name}} @endif</div>
		<div class="col-md-12">{{$task->title}}</div>
		<div class="col-md-12">{!!$task->description!!}</div>
		<div class="col-md-12"><hr></div> --}}
	@endforeach
	@foreach($minute->ideas()->get() as $idea)
		<div class="col-md-12">ID: {{$minute->meetingId}}M{{$minute->id}}D{{$idea->id}}</div>
		<div class="col-md-12">Orginator: @if($idea->orginator){{$idea->orginatorDetail->name}} @endif</div>
		<div class="col-md-12">{{$idea->title}}</div>
		<div class="col-md-12">{!!$idea->description!!}</div>
		<div class="col-md-12"><hr></div>
	@endforeach
</div>