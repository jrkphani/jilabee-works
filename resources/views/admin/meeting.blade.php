@if($meeting)
<div class="inner2">
	<h4>{{$meeting->title}}</h4>
	<div class="userDetailItem">
		<p>creation date</p>
		<span>{{$meeting->created_at}}</span>
		<div class="clearboth"></div>
	</div>
	<div class="userDetailItem">
		<p>creation request by</p>
		<span>Aravind Kumar</span>
		<div class="clearboth"></div>
	</div>
	
	<div class="userDetailItem">
		<p>type of meeting </p>
		<span>Project</span>
		<div class="clearboth"></div>
	</div>

	<div class="userDetailItem">
		<p>purpose of meeting  </p>
		<span>To track the project process</span>
		<div class="clearboth"></div>
	</div>
		<br/><br/>
		<h4>Short Description</h4>
		<p class="userDetailDesc">{!! $meeting->description!!}</p>

	<br/><br/>
	
	<div class="meetingSettingsTitle">
		<h5>Participants</h5>
		<h5>Role in Meeting</h5>
		<div class="clearboth"></div>	
	</div>
	@if($meeting->minuters)
		<?php
			$minuters = App\Model\Profile::select('userId','name')->whereIn('userId',explode(',', $meeting->minuters))->get();
			foreach ($minuters as $minuter)
			{
				//echo $minuter->value;
				echo '<div class="meetingSettingITem">
						<p>'.$minuter->name.'</p>
						<span>minutes taker</span>
						<div class="clearboth"></div>
					</div>';
			}
		?>
	@endif
	@if($meeting->attendees)
		<?php
			$attendees = App\Model\Profile::select('userId','name')->whereIn('userId',explode(',', $meeting->attendees))->get();
			foreach ($attendees as $attendee)
			{
				//echo $minuter->value;
				echo '<div class="meetingSettingITem">
						<p>'.$attendee->name.'</p>
						<span>attendee</span>
						<div class="clearboth"></div>
					</div>';
			}
		?>
	@endif
	<div class="adminUsersBtns">
		<div class="adminUsersBtnsLeft">
			<button>Edit Meeting</button>
		</div>
		
		<div class="adminUsersBtnsRight">
			<button>Close Meeting</button>
			<button>End Meeting</button>
		</div>
		<div class="clearboth"></div>
	</div>
</div>
@endif