<div class="row">
	<div class="col-md-6 col-md-offset-6">
		<div class="col-md-6">
			<button type="button" class="btn btn-primary" id="refresh">Refresh</button>
		</div>
	</div>
	<div class="col-md-12">
		<div class="col-md-3">
			@if($tasks->count())
			<ul>
				@foreach($tasks as $task)
					@if($task->type == 'minute')
						<li myid="{{$task->id}}" mid="{{$task->minuteId}}" class="task">{{$task->title}}</li>
					@else
						<li myid="{{$task->id}}" class="task">{{$task->title}}</li>	
					@endif				
				@endforeach
			</ul>
			@else
			No Tasks Yet
			@endif
		</div>
		<div class="col-md-9" id="rightContent">
			right content
		</div>
	</div>
</div>