<div class="row">
	<div class="col-md-12">Due Date: {{$task->dueDate}}</div>
	<div class="col-md-12">Status: {{$task->status}}</div>
	{{-- <div class="col-md-12">Assignee: {{$task->assigneeDetail->name}}</div> --}}
	<div class="col-md-12">Assigner: {{$task->assigner}}</div>
	<div class="col-md-12">
		<strong>{{$task->title}}</strong>
	</div>
	<div class="col-md-12">
		{!!$task->description!!}
	</div>
	@if($task->comments()->first())
		<div class="col-md-12">
			<strong>Comments</strong>
			@foreach($task->comments()->get() as $comment)
			<p>
				{!! $comment->description !!}
				<p>{{$comment->createdby->name}}- {{$comment->updated_at}}</p>
			</p>
			<div class="col-md-12"><hr></div>
			@endforeach
		</div>
	@endif	
</div>