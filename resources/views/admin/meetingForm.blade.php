{!! Form::open(array('id' => 'createMeetingForm')) !!}
@if($meeting)
{!! Form::hidden('id',$meeting->id)!!}
<div class="inner2">
	<h4>
		{!! Form::text('title',$meeting->title,['placeholder'=>'title'])!!}
		<div id="title_err" class="error"></div>
	</h4>
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
		<p class="userDetailDesc">
            {!! Form::textarea('description',str_ireplace(["<br />","<br>","<br/>"], "\r\n", $meeting->description),['placeholder'=>'description'])!!}
		</p>
		<div id="description_err" class="error"></div>
	<br/><br/>
	<div class="meetingSettingsTitle">
		<h5>Participants</h5>
		<h5>Role in Meeting</h5>
		<div class="clearboth"></div>	
	</div>
	<div id="selectedParticipant">
	<?php
		if($meeting->minuters)
		{
			$participantsUser = App\Model\Profile::select('users.userId','profiles.name','profiles.roles')
                        ->join('users','profiles.userId','=','users.id')
                        ->whereIn('users.id',explode(',',$meeting->minuters))->get();
			foreach ($participantsUser as $user)
			{
			?>
				<div class="meetingSettingITem participant" roles="{{$user->roles}}" uid="{{$user->userId}}">
					<button class="removeMoreBtn removeParent"></button>
					<input type="hidden" name="participants[]" value="{{$user->userId}}">
					<p>{{$user->name}}</p>
					{!! Form::select('roles[]', $roles,'2',["class"=>"roles"])!!}
					<div class="clearboth"></div>
				</div>
		<?php
			}	
		}
		$participants = explode(',',$meeting->attendees);
		//print_r($participants); die;
		$participantsEmail = array();
		foreach($participants as $key => $value)
		{
			if(isEmail($value))
			{
				$participantsEmail[]= $value;
				unset($participants[$key]);
			}
		}
	if($participants)
	{
		$participantsUser = App\Model\Profile::select('users.userId','profiles.name','profiles.roles')
                        ->join('users','profiles.userId','=','users.id')
                        ->whereIn('users.id',$participants)->get();
		foreach ($participantsUser as $user)
		{
		?>
			<div class="meetingSettingITem participant" roles="{{$user->roles}}" uid="{{$user->userId}}">
				<button class="removeMoreBtn removeParent"></button>
				<input type="hidden" name="participants[]" value="{{$user->userId}}">
				<p>{{$user->name}}</p>
				{!! Form::select('roles[]', $roles,'1',["class"=>"roles"])!!}
				<div class="clearboth"></div>
			</div>
		<?php
		}
	}
	if(count($participantsEmail))
	{
		foreach ($participantsEmail as $email)
		{
		?>
			<div class="meetingSettingITem">
				<input type="hidden" name="participants[]" value="{{$email}}">
				<p>{{$email}}</p>
				{!! Form::select('roles[]', $roles,'1')!!}
				<div class="clearboth"></div>
			</div>
	<?php
		}
	}
	?>
	</div>
	<div class="meetingSettingITem">
		<p><input type="text" name="selectParticipant" id="selectParticipant" placeholder='search user'></p>
		{!! Form::select('', $roles,'1',['disabled'])!!} 
		<div id="participants_err" class="error"></div>
		<div class="clearboth"></div>
	</div>
	
	<div class="adminUsersBtns">
		<div class="adminUsersBtnsLeft">
			<button id="createMeetingSubmit">Update Meeting</button>
		</div>
		
		<div class="adminUsersBtnsRight">
			<button>Close Meeting</button>
			<button>End Meeting</button>
		</div>
		<div class="clearboth"></div>
	</div>
</div>
@else
<div class="inner2">
	<h4>
		{!! Form::text('title','',['placeholder'=>'title'])!!}
		<div id="title_err" class="error"></div>
	</h4>
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
		<p class="userDetailDesc">
            {!! Form::textarea('description','',['placeholder'=>'description'])!!}
		</p>
		<div id="description_err" class="error"></div>
	<br/><br/>
	<div class="meetingSettingsTitle">
		<h5>Participants</h5>
		<h5>Role in Meeting</h5>
		<div class="clearboth"></div>	
	</div>
	<div id="selectedParticipant">
	</div>
	<div class="meetingSettingITem">
		<p><input type="text" name="selectParticipant" id="selectParticipant" placeholder='search user'></p>
		{!! Form::select('', $roles,'1',['disabled'])!!} 
		<div id="participants_err" class="error"></div>
		<div class="clearboth"></div>
	</div>
	
	<div class="adminUsersBtns">
		<div class="adminUsersBtnsLeft">
			<button id="createMeetingSubmit">Update Meeting</button>
		</div>
		
		<div class="adminUsersBtnsRight">
			<button>Close Meeting</button>
			<button>End Meeting</button>
		</div>
		<div class="clearboth"></div>
	</div>
</div>
@endif
<script type="text/javascript">
$('#selectParticipant').autocomplete({
            source: "/user/search",
            minLength: 2,
            select: function( event, ui ) {
            	var selectedParticipant =$('#selectedParticipant');
                if(selectedParticipant.find( "[uid="+ui.item.userId+"]").html())
                {
                    alert('User already exist!');
                    return false;
                }
                else
                {
                	insert= '<div uid="'+ui.item.userId+'" roles="'+ui.item.roles+'" class="meetingSettingITem participant"><button class="removeMoreBtn removeParent"></button> <input type="hidden" value="'+ui.item.userId+'" name="participants[]"><p>'+ui.item.value+'</p>{!! Form::select("roles[]", $roles,"1",["class"=>"roles"])!!} <div class="clearboth"></div></div>';
                    selectedParticipant.append(insert);
                }
                $('#selectParticipant').val('');
                return false;
            }
            });
</script>