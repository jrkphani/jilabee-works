<div class="row">
	<div class="col-md-6 col-md-offset-6">
		<div class="col-md-6">
			<button type="button" class="btn btn-primary" id="refresh">Refresh</button>
		</div>
	</div>
	<div class="col-md-12">
		<div class="col-md-3">
			@if($mytask->count())
			<ul>
				@foreach($mytask as $job)
				<li>{{$job->title}}</li>
				@endforeach
			</ul>
			@else
				No Tasks
			@endif
		</div>
		<div class="col-md-9">
			right content
		</div>
	</div>
</div>