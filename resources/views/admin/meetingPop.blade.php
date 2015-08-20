<div class="popupWindow">
	<div class="popupHeader">
		<h2><a href=""><a href="">Meeting Pending</a></h2>
		<button onclick="$('#popup').hide();" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>	
	<div class="popupContent">
		<div class="popupContentTitle">
			<h4>{{$meeting->title}}</h4>
			<p>Created on: {{$meeting->created_at}}</p>
			<p> Creation request by: {{$meeting->requestedby->name}}</p>
		</div>
		<div class="popupContentLeft">
			<div class="popupContentText">
				{!!$meeting->description!!}
			</div>
		</div>
		<!-- =================== Popup right ====================  -->
		<div class="popupContentRight">
			<div class="popupSearchSection">
				
			</div>
			<!-- ================= Comment/chat section ====================  -->
			<div class="chatSection">
				@if($meeting->minuters)
					<?php
						$minuters = App\Model\Profile::select('userId','name')->whereIn('userId',explode(',', $meeting->minuters))->get();
						foreach ($minuters as $minuter)
						{
							//echo $minuter->value;
							echo '<div class="meetingSettingITem">
									<p>'.$minuter->name.' - Minuter</p>
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
										<p>'.$attendee->name.'-Attendee</p>
										<div class="clearboth"></div>
									</div>';
							}
						}
						if(count($attendeesEmail))
						{
							foreach ($attendeesEmail as $attendee)
							{
								echo '<div class="meetingSettingITem">
										<p>'.$attendee.'Attendee</p>
										<div class="clearboth"></div>
									</div>';
							}
						}
					?>
				@endif
			</div>
		<div class="clearboth"></div>
	</div>
</div>
</div>