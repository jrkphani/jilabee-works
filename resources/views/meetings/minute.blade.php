<div class="paper">
	<div class="paperBorder">
		<div class="paperTitleLeft">
			<h3>{{$minute->meeting->title}}</h3>
			<p> MT23SH meeting venue: {{$minute->venue}}</p>
		</div>
		<div class="paperTitleRight">
			<h3>{{date('d-M-Y',strtotime($minute->minuteDate))}}</h3>
			<p>{{date('H:s:i',strtotime($minute->minuteDate))}}</p>
		</div>
		<div class="clearboth"></div>
		<div class="paperSubTitle">
			<p>
				<span>Participants:</span>
				@foreach(App\Model\Profile::whereIn('userId',explode(',',$minute->attendees))->lists('name','userId') as $attendee)
				{{$attendee}} ,
				@endforeach
			</p>
			<p>
				<span>Absentees:</span>
				@foreach(App\Model\Profile::whereIn('userId',explode(',',$minute->absentees))->lists('name','userId') as $absentees)
					{{$absentees}} ,
				@endforeach
			</p>
		</div>
		<h4>Previous Minutes</h4>
		@foreach($minute->tasks as $task)
			<div class="minuteItem">
				<div class="minuteItemNumber">
					<p>1</p>
				</div>
				<div class="minuteItemLeft">
					<h5>{{$task->title}}</h5>
					<p>{{$task->description}}</p>
				</div>
				<div class="minuteItemRight">
					<h6>MT{{$task->id}}</h6>
					<p>
						@if(isEmail($task->assignee))
						{{$task->assignee}}
						@else
						{{$task->assigneeDetail->name}}
						@endif
					</p>
					<p>14 March	</p>
				</div>
				<div class="clearboth"></div>
			</div>
		@endforeach
	</div>
</div>