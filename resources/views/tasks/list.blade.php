@if($minute)
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading" {{-- style="background-color:{{$minute->meeting->label}}" --}}>
				<div class="row">
						<div class="col-md-3">{{$minute->meeting->title}}</div>
						<div class="col-md-3">{{$minute->venue}}</div>
						<div class="col-md-3">{{$minute->created_at}}</div>
						<div class="col-md-3">
							<span class="glyphicon glyphicon-pencil"></span>
							<a href="{{url('profile/'.$minute->created_by)}}">
	  							{{$minute->createdby->name}}
	  							<span class="glyphicon glyphicon-user"></span>
	  						</a>
						</div>
						<?php
						$attendees = App\User::whereIn('id',explode(',', $minute->attendees))
							->lists('name','id');
						$absentes = App\User::whereIn('id',array_diff(explode(',', $minute->meeting->attendees),
							explode(',', $minute->attendees)))
							->lists('name','id');
						?>
					     @if($attendees)
						<div class="list-group alert alert-success col-md-12 margin_top_20">
					    	@foreach($attendees as $key=>$value)
	  							<a {{-- class="list-group-item" --}} href="{{url('profile/'.$key)}}">
	  								<span class="glyphicon glyphicon-user"></span>
	  								{{$value}}
	  							</a>
					    	@endforeach
					    </div>
					    @endif
					    @if($absentes)
						<div class="list-group alert alert-danger col-md-12 ">
					    	@foreach($absentes as $key=>$value)
	  							<a {{-- class="list-group-item" --}} href="">
	  								<span class="glyphicon glyphicon-user"></span>
	  								{{$value}}
	  							</a>
					    	@endforeach
					    </div>
					    @endif
					    @if($minute->meeting->isMinuter())
						<div class="col-md-12">
							<a href="{{url('meeting/'.$minute->meeting->id.'/nextminute')}}" class="pull-right btn btn-primary add_next_minute" style="padding:0px">Next Minute</a>
						</div>
						@endif
					</div>
			</div>
			<div class="panel-body">
				@if($minute->tasks()->first())
					@foreach($minute->tasks()->orderBy('updated_at', 'desc')->get() as $task)
					<div class="col-md-12">
						<div class="col-md-3">
							{{$task->title}}
						</div>
						<div class="col-md-7">
							{!! nl2br($task->description) !!}
						</div>
						<div class="col-md-2 nopadding">
							<div class="col-md-12">
								{{$task->status}}
							</div>
							<div class="col-md-12">
								<a href="{{ app_url('/profile/').$task->assignee}}">
									{{$task->getassignee->name}}
								</a>
							</div>
							<div class="col-md-12">
								{{date('Y-m-d',strtotime($task->due))}}
							</div>
							@if($minute->updated_by == Auth::id())
	        					<div class="col-md-12">
									<span tid="{{$task->id}}" class="btn glyphicon glyphicon-edit edit_task"></span>
								</div>
							@endif
						</div>
					</div>
					<div class="col-md-12 border_bottom"></div>
					@endforeach
				@else
					No date to display!
				@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endif