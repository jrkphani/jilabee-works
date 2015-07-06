@if($task)
<?php 
	if($task->minuteId)
	{
		$parentAttr = 'mtask';
	}
	else
	{
		$parentAttr = 'task';
	}
?>
<div class="row">
	<div class="col-md-12">Due Date: {{$task->dueDate}}</div>
	<div class="col-md-12">Status: {{$task->status}}</div>
	<div class="col-md-12">Assignee: {{$task->assigneeDetail->name}}</div>
	<div class="col-md-12">Assignee: {{$task->assignerDetail->name}}</div>
	<div class="col-md-12">
		<strong>{{$task->title}}</strong>
	</div>
	<div class="col-md-12">
		{{$task->description}}
	</div>
	@if($task->comments()->first())
		<div class="col-md-12">
			<strong>Comments</strong>
			@foreach($task->comments()->get() as $comment)
			<p>
				{!! $comment->description !!}
				<p>{{$comment->createdby->name}} - {{$comment->updated_at}}</p>
			</p>
			<div class="col-md-12"><hr></div>
			@endforeach
		</div>
	@endif
	@if($task->status == 'Sent')
		{!! Form::open(['id'=>$parentAttr."Form".$task->id]) !!}
		{!! Form::textarea('reason', '','') !!}
		@if(isset($reason_err))
			<div class="error">{{$reason_err}}</div>
		@endif
		{!! Form::close() !!}
		<button {{$parentAttr}}="{{$task->id}}" id="accept" class="btn btn-primary">Accept</button>
		<button {{$parentAttr}}="{{$task->id}}" id="reject" class="btn btn-primary">Reject</button>
	@elseif($task->status == 'Rejected')
		Refused Reason : {!! $task->reason !!}
	@else
		<button type="submit" {{$parentAttr}}="{{$task->id}}" class="btn btn-primary pull-right">Mark as Completed</button>
		{!! Form::open(['id'=>"CommentForm".$task->id]) !!}
		{!! Form::textarea('description', '','') !!}
		{!! $errors->first('description','<div class="error">:message</div>') !!}
		{!! Form::close() !!}
		<button {{$parentAttr}}="{{$task->id}}" id="postComment" class="btn btn-primary ">Post</button>
	@endif
	
</div>
@endif