@if($tasks)
<div class="row">
	@foreach($tasks as $task)
		<div class="col-md-12">Due Date: {{$task->dueDate}}</div>
		<div class="col-md-12">Status: {{$task->status}}</div>
		<div class="col-md-12">Assignee: {{$task->assigneeDetail->name}}</div>
		<div class="col-md-12">Assigner: @if($task->assigner){{$task->assignerDetail->name}} @endif</div>
		<div class="col-md-12">
			<strong>{{$task->title}}</strong>
		</div>
		<div class="col-md-12">
			{{$task->description}}
		</div>
	@endforeach
</div>
@endif