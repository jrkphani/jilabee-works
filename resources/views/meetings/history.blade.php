<div class="row">
	<div class="col-md-6 col-md-offset-6">
		<div class="col-md-6">
			<button type="button" class="btn btn-primary" id="refresh">Refresh</button>
		</div>
	</div>
	<div class="col-md-12">
		@if($meetings->count())
		<ul>
			@foreach($meetings as $meeting)
				<div class="col-md-4">
					<div class="jumbotron">{{$meeting->title}}
					<ul>
						@foreach($meeting->minutes()->orderBy('updated_at','desc')->get() as $minute)
						<li class="minutePopup" mid="{{$minute->id}}">{{date('Y-m-d',strtotime($minute->minuteDate))}}</li>
						@endforeach
					</ul>
				</div>
			</div>
			@endforeach
		</ul>
		@else
			No Meetings
		@endif
	</div>
	{{-- alax pop up --}}
	<div id="loadTaskModal" class="col-md-6 col-md-offset-3 modal fade">
	</div>
</div>