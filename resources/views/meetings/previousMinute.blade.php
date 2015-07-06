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
<strong>Minutes of the Meeting on: {{$minute->minuteDate}}</strong>
<div class="col-md-12">ID: M{{$minute->meetingId}}S{{$minute->id}}</div>
<div class="col-md-12">	
	@if($minute->venue)
		Venue : {{$minute->venue}}
	@endif
</div>
<div class="col-md-12">	
	@foreach($minute->tasks()->get() as $task)
		<div class="col-md-12">Due Date: {{$task->dueDate}}</div>
		<div class="col-md-12">Status: {{$task->status}}</div>
		<div class="col-md-12">Assignee: {{$task->assigneeDetail->name}}</div>
		<div class="col-md-12">Assigner: @if($task->assigner){{$task->assignerDetail->name}} @endif</div>
		<div class="col-md-12">{{$task->title}}</div>
		<div class="col-md-12">{!!$task->description!!}</div>
		<div class="col-md-12"><hr></div>
	@endforeach
</div>