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
	@if($task->status == 'rejected' || $task->status == 'waiting')
		{!! Form::open()!!}
		{!! Form::text('title',$task->title) !!}
		{!! Form::textarea('description',$task->title) !!}
		<p>Status: {{$task->status}}<p>
			@if($task->status == 'rejected')
			<p>Reason: {{$task->reason}}</p>
			@endif
		{!! Form::close()!!}
		<button {{$parentAttr}}="{{$task->id}}" id="updateTaskBtn" class="btn btn-primary ">Update</button>
	@else
		<p><strong>{{$task->title}}</strong></p>
		<p>{{$task->description}}<p>
		{!! Form::open(['id'=>"CommentForm".$task->id]) !!}
		{!! Form::textarea('comment', '','') !!}
		{!! Form::close() !!}
		<button {{$parentAttr}}="{{$task->id}}" id="postComment" class="btn btn-primary ">Post</button>
	@endif
</div>
@endif