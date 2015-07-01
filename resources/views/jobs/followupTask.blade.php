@if($task)
<div class="row">
	<strong>{{$task->title}}</strong>
	{{$task->description}}
</div>
@endif