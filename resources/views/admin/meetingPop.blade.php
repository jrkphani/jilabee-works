<div class="popupWindow">
	<div class="popupHeader">
		<h2><a href="">Meeting Pending</a></h2>
		<button onclick="$('#popup').hide();" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>	
	<div class="popupContent">
		<div class="inner2">
			<h4>{{$meeting->title}}</h4>
			<div class="userDetailItem">
				<p>creation date</p>
				<span>{{$meeting->created_at}}</span>
				<div class="clearboth"></div>
			</div>
			<div class="userDetailItem">
				<p>creation request by</p>
				<span>{{$meeting->requestedby->name}}</span>
				<div class="clearboth"></div>
			</div>
			
			<div class="userDetailItem">
				<p>type of meeting </p>
				<span>{{$meeting->type}}</span>
				<div class="clearboth"></div>
			</div>

			<div class="userDetailItem">
				<p>purpose of meeting  </p>
				<span>{{$meeting->purpose}}</span>
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
								<span>'.$roles[2].'</span>
								<div class="clearboth"></div>
							</div>';
					}
				?>
			@endif
			@if($meeting->attendees)
				<?php
				$attendeesEmail = $attendees = array();
					foreach (explode(',',$meeting->attendees) as $key => $value)
					{
						if(isEmail($value))
						{
							$attendeesEmail[] = $value;
						}
						else
						{
							$attendees[] = $value;
						}
					}
					if(count($attendees))
					{
						$attendeesList = App\Model\Profile::select('userId','name')->whereIn('userId',$attendees)->get();
						foreach ($attendeesList as $attendee)
						{
							echo '<div class="meetingSettingITem">
									<p>'.$attendee->name.'</p>
									<span>'.$roles[1].'</span>
									<div class="clearboth"></div>
								</div>';
						}
					}
					if(count($attendeesEmail))
					{
						foreach ($attendeesEmail as $attendee)
						{
							echo '<div class="meetingSettingITem">
									<p>'.$attendee.'</p>
									<span>'.$roles[1].'</span>
									<div class="clearboth"></div>
								</div>';
						}
					}
				?>
			@endif
			<div class="adminUsersBtns">
				<div class="adminUsersBtnsLeft">
					<button class="" id="approveMeeting" mid="{{$meeting->id}}">Approve</button>
				</div>
				
				<div class="adminUsersBtnsRight">
					
					{!! Form::textarea('reason','',['id'=>'reason'])!!}
					<div id="reason_err" class="error"></div>
					<button class="" id="disapproveMeeting" mid="{{$meeting->id}}">Disapprove</button>
				</div>
				<div class="clearboth"></div>
			</div>
		</div>		
	</div>
</div>