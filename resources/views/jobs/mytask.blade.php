<div class="row">
	<div class="col-md-6 col-md-offset-6">
		<div class="col-md-6">
			<button type="button" class="btn btn-primary" id="refresh">Refresh</button>
		</div>
	</div>
	<div class="col-md-12">
		<div class="col-md-3">
			@if($jobtask->count())
			<ul>
				@foreach($jobtask as $job)
				<li myid="{{$job->id}}" class="task">{{$job->title}}</li>
				@endforeach
			</ul>
			@endif
			@if($minutetask->count())
			<ul>
				@foreach($minutetask as $job)
				<li myid="{{$job->id}}" mid="{{$job->minuteId}}" class="task">{{$job->title}}</li>
				@endforeach
			</ul>
			@endif
		</div>
		<div class="col-md-9" id="rightContent">
			right content
		</div>
	</div>
</div>