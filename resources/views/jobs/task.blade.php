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
	<div class="col-md-12">
		<strong>{{$task->title}}</strong>
	</div>
	<div class="col-md-12">
		{{$task->description}}
	</div>
	@if($task->status == 'waiting')
		{!! Form::open(['id'=>$parentAttr."Form".$task->id]) !!}
		{!! Form::textarea('reason', '','') !!}
		@if(isset($reason_err))
			<div class="error">{{$reason_err}}</div>
		@endif
		{!! Form::close() !!}
		<button {{$parentAttr}}="{{$task->id}}" id="accept" class="btn btn-primary">Accept</button>
		<button {{$parentAttr}}="{{$task->id}}" id="reject" class="btn btn-primary">Reject</button>
	@elseif($task->status == 'rejected')
		Refused Reason : {{$task->reason}}
	@else
		<button type="submit" {{$parentAttr}}="{{$task->id}}" class="btn btn-primary pull-right">Mark as Completed</button>
		{!! Form::open(['id'=>$parentAttr."Form".$task->id]) !!}
		{!! Form::textarea('comment', '','') !!}
		{!! Form::close() !!}
		<button {{$parentAttr}}="{{$task->id}}" id="postComment" class="btn btn-primary ">Post</button>
	@endif
	{{-- <div class="col-md-12">
		{!! Form::select('status', array('rejected' => 'reject', 'finished' => 'finished')) !!}
	</div> --}}
</div>
@endif