<div class="row">
	<div class="col-md-12">
		<strong>{{$meeting->title}}</strong>
	</div>
	<div class="col-md-12">
		{{$meeting->description}}
	</div>
	<div class="col-md-12 form-group">
		<?php
		$isMinuter = 0;
		$showMinutes = 0;
		if($meeting->isMinuter())
		{
			$isMinuter = 1;
		}
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
	@if($minute)
		@if($minute->lock_flag != NULL)
			<!-- disable proceed buton -->
				@if($minute->lock_flag == Auth::id())
					<div class="col-md-12" id="minuteBlock">
						@include('meetings.createTask',['minute'=>$meeting->minutes()->where('lock_flag','!=','NULL')->first(),'usersList'=>$users])
					</div>
				@else
					{{-- show nothing bcz the minute is in draft mode --}}
				@endif
		@elseif($meeting->minutes()->where('lock_flag','!=','NULL')->count())
			<!-- disable proceed button but show the minutes-->
			@include('meetings.previousMinute',['minute'=>$minute,'users'=>$users])
		@else
			@if($isMinuter)
				<button id="nextMinute" mid="{{$meeting->id}}" type="button" class="btn btn-primary pull-right">
					Proceed next minute of meeting
				</button>
				@include('meetings.previousMinute',['minute'=>$minute])
				<div class="col-md-12" id="minuteBlock" style="display:none">
					@include('meetings.createMinute',['minute'=>$meeting])
				</div>
			@endif
		@endif
	@else
	<div class="col-md-12"><br/><br/><br/><br/>No Minutes Yet - Create First Minute</div>
		@if($isMinuter)
				<button id="nextMinute" mid="{{$meeting->id}}" type="button" class="btn btn-primary pull-right">
					Start first minute of meeting
				</button>
				<div class="col-md-12" id="minuteBlock" style="display:none">
					@include('meetings.createMinute',['minute'=>$meeting])
				</div>
			@endif
	@endif
	</div>
</div>