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
			<button class="" id="approveMeeting" mid="{{$meeting->id}}">Approve</button>
			{!! Form::textarea('reason','',['id'=>'reason'])!!}
			<button class="" id="disapproveMeeting" mid="{{$meeting->id}}">Disapprove</button>
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
					<?php
					$details = unserialize($meeting->details);
					$attendees = array();
					for($i=0;$i<=count($details['title'])-1;$i++)
					{
						if($details['type'][$i] == 'task')
						{
							$attendees[] = $details['assignee'][$i];
						}
						else
						{
							$attendees[] = $details['orginator'][$i];
						}
					}
					$attendees = array_unique(array_filter($attendees));
					foreach($attendees as $user)
					{
						if(isEmail($user))
						{
							echo '<div class="meetingSettingITem">
										<p>'.$user.'Attendee</p>
										<div class="clearboth"></div>
									</div>';
						}
						else
						{
							if($attuser = getUSer(['userId'=>$user])->profile->name)
							{
								echo '<div class="meetingSettingITem">
										<p>'.$attuser.'-Attendee</p>
										<div class="clearboth"></div>
									</div>';
							}
						}
					}
					
					?>
			</div>
		<div class="clearboth"></div>
	</div>
</div>
</div>