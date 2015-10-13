<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<div id="toPrint" class="paper">
	<div  class="paperBorder">
		<div class="paperTitleLeft">
		<?php
		$meeting = $minute->meeting()->withTrashed()->first();
		?>
			<h3>Meeting #{{$meeting->id}}</h3>
			<h3>{{$meeting->title}}</h3>
			<p>meeting venue: {{$minute->venue}}</p>
		</div>
		<div class="paperTitleRight">
			<h3>{{date('d-M-Y',strtotime($minute->startDate))}}</h3>
			<p>{{date('H:i:s',strtotime($minute->endDate))}}</p>
		</div>
		<div class="clearboth"></div>
		<div class="paperSubTitle">
			<p>
				<span>Participants:</span>
				@foreach(explode(',',$minute->attendees) as $attendee)
					@if(isEmail($attendee))
						{{$attendee}} ,
					@else
					<?php $attendees[]=$attendee; ?>
					@endif
				@endforeach
				@if(isset($attendees))
					@foreach(App\Model\Profile::whereIn('userId',$attendees)->lists('name','userId') as $attendee)
					{{$attendee}} ,
					@endforeach
				@endif
			</p>
			<p>
				<span>Absentees:</span>
				@foreach(explode(',',$minute->absentees) as $absentee)
					@if(isEmail($absentee))
						{{$absentee}} ,
					@else
					<?php $absentees[]=$absentee; ?>
					@endif
				@endforeach
				@if(isset($absentees))
					@foreach(App\Model\Profile::whereIn('userId',$absentees)->lists('name','userId') as $absentees)
						{{$absentees}} ,
					@endforeach
				@endif
			</p>
		</div>
		<h4>Minutes</h4>
		@if($minute->filed == '0')
			<?php 
				$lastFiledMinute = App\Model\Minutes::where('filed','=','1')
								->where('meetingId',$meeting->id)
								->orderBy('startDate', 'DESC')->limit(1)->first();
								
				if($lastFiledMinute)
				{
					$tasks = App\Model\MinuteTasks::where('minuteId',$minute->id)
							->orWhereIn('id',function($query) use ($lastFiledMinute){
								$query->select('taskId')
		                    		->from('filedMinutes')
		                       		->where('filedMinutes.status','!=','Closed')
		                       		->where('filedMinutes.status','!=','Cancelled')
		                       		->where('filedMinutes.minuteId','=',$lastFiledMinute->id);
							})->get();
				}
				else
				{
					$tasks = $minute->tasks;
				}
			?>
		@else
			<?php $tasks = $minute->file; ?>
		@endif
		<?php $count = 1; ?>
		@foreach( $tasks as $task)
			<div class="minuteItem">
				<div class="minuteItemNumber">
					<p>{{$count++}}</p>
				</div>
				<div class="minuteItemLeft">
					<h5>{{$task->title}}</h5>
					<p>{!!$task->description!!}</p>
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
					<p>{{$task->dueDate}}</p>
					<p>{{$task->status}}</p>
				</div>
				<div class="clearboth"></div>
			</div>
		@endforeach

		@if($minute->ideas()->count())
			<h4>Ideas</h4>
			@foreach($minute->ideas()->get() as $idea)
				<div class="minuteItem">
					<div class="minuteItemNumber">
						<p>{{$count++}}</p>
					</div>
					<div class="minuteItemLeft">
						<h5>{{$idea->title}}</h5>
						<p>{!!$idea->description!!}</p>
					</div>
					<div class="minuteItemRight">
						<h6>ID{{$idea->id}}</h6>
						<p>
							@if($idea->orginator)
								@if(isEmail($idea->orginator))
									{{$idea->orginator}}
								@else
									{{$idea->orginatorDetail->name}}
								@endif
							@endif
						</p>
					</div>
					<div class="clearboth"></div>
				</div>
			@endforeach
		@endif
		@if($minute->filed == '0')
			<span class="draft_tag"></span>
		@endif
	</div>
</div>
</body>
	</html>