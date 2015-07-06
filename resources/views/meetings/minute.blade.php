<div class="row">
	<div class="col-md-12">
		<strong>{{$meeting->title}}</strong>
	</div>
	<div class="col-md-12 form-group">
		<?php $createMinute = 0; ?>
		@if($meeting->isMinuter())
			<?php $createMinute = 1; ?>
			@if($meeting->minutes()->count())
				@if($meeting->minutes()->where('lock_flag','!=','NULL')->count())
				<?php $createMinute = 2; ?>
					<!-- disable proceed buton -->
				@else
					<button id="nextMinute" mid="{{$meeting->id}}" type="button" class="btn btn-primary pull-right">
						Proceed next minute of meeting
					</button>
				@endif
			@else
				<button id="nextMinute" mid="{{$meeting->id}}" type="button" class="btn btn-primary pull-right">
						Proceed next minute of meeting
					</button>
			@endif
		@endif
		
		<div class="row">
			<div class="col-md-12">
				@if($minute && (!$minute->lock_flag))
					<?php
					$attendess = App\Model\Profile::select('name')->whereIn('userId',explode(',',$minute->attendess))->get();
					if($minute->absentees)
					{
						$absentees = App\Model\Profile::select('name')->whereIn('userId',explode(',',$minute->absentees))->get();
					}
					else
					{
						$absentees = NULL;
					}
					?>
					<strong>Minutes of the Meeting on: {{$minute->minuteDate}}</strong>
					<div class="col-md-12">ID: M{{$minute->meetingId}}S{{$minute->id}}</div>
					<div class="col-md-12">	
						@if($minute->venue)
							Venue : {{$minute->venue}}
						@endif
					</div>
					<div class="col-md-12">	
						@foreach($minute->tasks()->get() as $task)
							<div class="col-md-12">Due Date: {{$task->dueDate}}</div>
							<div class="col-md-12">Status: {{$task->status}}</div>
							<div class="col-md-12">Assignee: {{$task->assigneeDetail->name}}</div>
							<div class="col-md-12">Assigner: @if($task->assigner){{$task->assignerDetail->name}} @endif</div>
							<div class="col-md-12">{{$task->title}}</div>
							<div class="col-md-12">{!!$task->description!!}</div>
							<div class="col-md-12"><hr></div>
						@endforeach
					</div>
				@endif
			</div>
			@if($createMinute)
					<?php
						$participants = array();
		        		if($meeting->minuters)
		        		{
		        			$participants = explode(',',$meeting->minuters);
		        		}
		        		if($meeting->attendees)
		        		{
		        			foreach(explode(',',$meeting->attendees) as $attendees)
		        			{
		        				array_push($participants, $attendees);
		        			}
		        		}
		        		$users = App\Model\Profile::whereIn('userId',$participants)->lists('name','userId');
					?>
					@if($createMinute == 1)
						<div class="col-md-12" id="minuteBlock" style="display:none">
							@include('meetings.createMinute',['minute'=>$meeting])
						</div>
					@elseif($minute->lock_flag == Auth::id())
						<div class="col-md-12" id="minuteBlock">
							@include('meetings.createTask',['minute'=>$meeting->minutes()->where('lock_flag','!=','NULL')->first(),'usersList'=>$users])
						</div>
					@endif
			@endif
		</div>
	</div>
</div>