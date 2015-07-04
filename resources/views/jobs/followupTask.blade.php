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
	@if($task->status != 'closed')
		@if(($task->status == 'rejected' || $task->status == 'waiting') && !($task->minuteId))
			{!! Form::open()!!}
			{!! Form::text('title',$task->title) !!}
			{!! Form::textarea('description',$task->description) !!}
			<p>Due Date: {{$task->dueDate}}<p>
			<p>Status: {{$task->status}}<p>
				@if($task->status == 'rejected')
				<p>Reason: {{$task->reason}}</p>
				@endif
			{!! Form::close()!!}
			<button {{$parentAttr}}="{{$task->id}}" id="updateTaskBtn" class="btn btn-primary ">Update</button>
		@else
			<p>Status: {{$task->status}}<p>
			<p>Due Date: {{$task->dueDate}}<p>
			<p><strong>{{$task->title}}</strong></p>
			<p>{{$task->description}}<p>
			@if($task->comments()->first())
			<strong>Comments</strong>
				@foreach($task->comments()->get() as $comment)
				<p>
					{!! $comment->description !!}
					- By {!! $comment->createdby->name !!}
				</p>
				<div class="col-md-12"><hr></div>
				@endforeach
			@endif
			{!! Form::open(['id'=>"CommentForm".$task->id]) !!}
			{!! Form::textarea('description', old('description'),'') !!}
			{!! $errors->first('description','<div class="error">:message</div>') !!}
			{!! Form::close() !!}
			<button {{$parentAttr}}="{{$task->id}}" id="postComment" class="btn btn-primary ">Post</button>
		@endif
	@endif
</div>
@endif