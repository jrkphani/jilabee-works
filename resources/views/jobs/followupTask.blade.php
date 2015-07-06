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
	@if($task->status != 'Closed')
		@if(($task->status == 'Rejected' || $task->status == 'Sent') && !($task->minuteId))
			{!! Form::open()!!}
			{!! Form::text('title',$task->title) !!}
			{!! Form::textarea('description',$task->description) !!}
			<p>Due Date: {{$task->dueDate}}<p>
			<p>Status: {{$task->status}}<p>
			{!! Form::close()!!}
			<button {{$parentAttr}}="{{$task->id}}" id="updateTaskBtn" class="btn btn-primary ">Update</button>
		@else
			<div class="col-md-12">Due Date: {{$task->dueDate}}</div>
			<div class="col-md-12">Status: {{$task->status}}</div>
			<div class="col-md-12">Assignee: {{$task->assigneeDetail->name}}</div>
			<div class="col-md-12">Assigner: @if($task->assigner){{$task->assignerDetail->name}} @endif</div>
			<p><strong>{{$task->title}}</strong></p>
			<p>{{$task->description}}</p>
			@if($task->status == 'Rejected')
				<p>Reason: {{$task->reason}}</p>
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
			{{-- if($task->status != 'Rejected') --}}
			@if($task->status == 'Open')
				{!! Form::open(['id'=>"CommentForm".$task->id]) !!}
				{!! Form::textarea('description', old('description'),'') !!}
				{!! $errors->first('description','<div class="error">:message</div>') !!}
				{!! Form::close() !!}
				<button {{$parentAttr}}="{{$task->id}}" id="postComment" class="btn btn-primary ">Post</button>
			@endif
		@endif
	@endif
</div>
@endif