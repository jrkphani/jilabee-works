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
	<p><strong>{{$task->title}}</strong></p>
	<p>{{$task->description}}</p>
	<div class="col-md-12">Due Date: {{$task->dueDate}}</div>
	<div class="col-md-12">Assignee: {{$task->assigneeDetail->name}}</div>
	<div class="col-md-12">Assigner: @if($task->assigner){{$task->assignerDetail->name}} @endif</div>
	<div class="col-md-12">
		<?php 
			$status = array_unique([$task->status=>$task->status,'Open'=>'Open','Closed'=>'Close','Cancelled'=>'Cancel']);
		?>
		Status: {!! Form::select('statusChange', $status,$task->status,['id'=>'statusChange','mtask'=>$task->id]) !!}
	</div>
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
	{{-- post new comment --}}
	{!! Form::open(['id'=>"CommentForm".$task->id]) !!}
	{!! Form::textarea('description', old('description'),'') !!}
	{!! $errors->first('description','<div class="error">:message</div>') !!}
	{!! Form::close() !!}
	<button {{$parentAttr}}="{{$task->id}}" id="postComment" class="btn btn-primary ">Post</button>
</div>
@endif