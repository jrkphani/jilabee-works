<div class="popupWindow">
	<div class="popupHeader">
		<h2><a href="">Meeting Pending</a></h2>
		<button onclick="$('#popup').hide();" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>	
	<div class="popupContent meetingPendingPopupbg"> 
	<div class="adminUsersRight">
		
	
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

			<br/>
			
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
			<?php
			if($notification->body)
			{
				$attendeesEmail = $attendees = array();
					foreach (unserialize($notification->body) as $key => $value)
					{
						if(isEmail($value))
						{
							$attendeesEmail[] = $value;
						}
						else
						{
							$attendees[] = $key;
						}
					}
					if(count($attendees))
					{
						$attendeesList = App\Model\Profile::select('userId','name','role')->whereIn('userId',$attendees)->get();
						foreach ($attendeesList as $attendee)
						{
						?>
						<div class="meetingSettingITem participant" roles="{{$attendee->role}}">
							<span class="removeMoreBtn removeParent removeParent2"></span>
							<input type="hidden" name="participants[]" value="{{$attendee->userId}}">
							<p>{{$attendee->name}}</p>
							<span>{!! Form::select('roles[]', $roles,'1',["class"=>"roles"])!!}</span>
							<div class="clearboth"></div>
						</div>
						<?php
						}
					}
					if(count($attendeesEmail))
					{
						foreach ($attendeesEmail as $attendee)
						{
						?>
						<div class="meetingSettingITem participant" roles="1">
							<span class="removeMoreBtn removeParent removeParent2"></span>
							<input type="hidden" name="participants[]" value="{{$attendee}}">
							<p>{{$attendee}}</p>
							<span>{!! Form::select('roles[]', $roles,'1',["class"=>"roles"])!!}</span>
							<div class="clearboth"></div>
						</div>
						<?php
						}
					}
				
			}
			?>
			<div class="adminUsersBtns">
				<div class="adminUsersBtnsLeft">
					<span id="createMeetingSubmit" class="button">Revert Changes</span>
				</div>
				<div class="adminUsersBtnsRight">
					<span id="createMeetingSubmit" class="button">Update Meeting</span>
				</div>
				<div class="clearboth"></div>
			</div>
		</div>	
		</div>	
		<div class="clearboth"></div>
	</div>
</div>