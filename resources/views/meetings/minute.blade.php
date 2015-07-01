<div class="row">
	<div class="col-md-12">
		<strong>{{$meeting->title}}</strong>
	</div>
	<div class="col-md-12 form-group">
		<?php $createMinute = 0; ?>
		@if(App\Model\Minutes::isMinuter($meeting->id))
			<?php $createMinute = 1; ?>
			@if($meeting->minutes()->count())
				@if($meeting->minutes()->where('lock_flag','!=','NULL')->count())
					<?php $createMinute = 2; ?>
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
			<div class="col-md-12" id="previousMinutes">
				@if($minute)
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
					<div class="col-md-12">	
						@if($minute->venue)
							Venue : {{$minute->venue}}
						@endif
					</div>
					<div class="col-md-12">	
						@foreach($minute->tasks()->get() as $task)
							<div class="col-md-12">{{$task->title}}</div>
						@endforeach
					</div>
				@else
				No minutes yet
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
					@else
						<div class="col-md-12" id="minuteBlock">
							@include('meetings.createTask',['minute'=>$meeting->minutes()->where('lock_flag','!=','NULL')->first(),'usersList'=>$users])
						</div>
					@endif
			@endif
		</div>
	</div>
</div>