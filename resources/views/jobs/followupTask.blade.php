@if($task)
<?php 
	if($task->minuteId)
	{
		$mid = "mid=".$task->minuteId;
	}
	else
	{
		$mid='';
	}
?>
<div class="row">
	<p><strong>{{$task->title}}</strong></p>
	<p>{!!$task->description!!}</p>
	@if($task->notes)
		<p><strong>Notes:</strong></p>
		<p>{!! $task->notes !!}</p>
	@endif
	<div class="col-md-12">Due Date: {{$task->dueDate}}</div>
	<div class="col-md-12">Assignee: 
		@if(isEmail($task->assignee))
			{{$task->assignee}}
		@else
			{{$task->assigneeDetail->name}}
		@endif
	</div>
	{{-- <div class="col-md-12">Assigner: @if($task->assigner){{$task->assignerDetail->name}} @endif</div> --}}
	<div class="col-md-12">
		Status: {{$task->status}}
	</div>
	@if($task->status == 'Rejected')
		<div class="col-md-12">Reason: {{$task->reason}}</div>
		{{-- <button class="btn btn-primary " id="rejectCompletion" {{$parentAttr}}="{{$task->id}}">Re-open</button> --}}
	@elseif($task->status == 'Completed')
		<div class="col-md-12">
			<button class="btn btn-primary " id="acceptCompletion" {{$mid}} tid="{{$task->id}}">accept completion</button>
			<button class="btn btn-primary " id="rejectCompletion" {{$mid}} tid="{{$task->id}}">reject completion</button>
		</div>
	@endif
	@if($task->comments()->first())
	<strong>Comments</strong>
		@foreach($task->comments()->get() as $comment)
		<p>
			{!! $comment->description !!}
			<p>{{$comment->createdby->name}} - {{$comment->updated_at}}</p>
		</p>
		<div class="col-md-12"><hr></div>
		@endforeach
	@endif
	@if($task->status != 'Closed')
		{{-- post new comment --}}
		{!! Form::open(['id'=>"CommentForm".$task->id]) !!}
		{!! Form::textarea('description', old('description'),'') !!}
		{!! $errors->first('description','<div class="error">:message</div>') !!}
		{!! Form::close() !!}
		<button {{$mid}} tid="{{$task->id}}" id="followupComment" class="btn btn-primary ">Post</button>
	@endif
</div>
@endif