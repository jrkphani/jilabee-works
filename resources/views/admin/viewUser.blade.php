@if($user)
	<div class="inner2">
		<h4>{{$user->name}}</h4>
		<div class="userDetailItem">
			<p>Username</p>
			<span>{{$user->userId}}</span>
			<div class="clearboth"></div>
		</div>
		<div class="userDetailItem">
			<p>User rights</p>
			<span>
				<?php $roles = explode(',',$user->roles); ?>
				@foreach(roles() as $key=>$value)
				@if(in_array($key, $roles))
					{{$value}},
				@endif
				@endforeach
			</span>
			<div class="clearboth"></div>
		</div>
		<div>
			<div class="userDetailItemX">
				<div class="userDetailItem">
					<p>DOB</p>
					<span>{{$user->dob}}</span>
					<div class="clearboth"></div>
				</div>
				<div class="userDetailItem">
					<p>Email</p>
					<span>{{$user->email}}</span>
					<div class="clearboth"></div>
				</div>
			</div>
			<div class="userDetailItemX">
				<div class="userDetailItem">
					<p>Sex</p>
					<?php $gender = ['M'=>'Male','F'=>'Female','O'=>'Others']; ?>
					<span>{{$gender[$user->gender]}}</span>
					<div class="clearboth"></div>
				</div >
				<div class="userDetailItem">
					<p>Phone</p>
					<span>{{$user->phone}}</span>
					<div class="clearboth"></div>
				</div>
			</div>
			<div class="clearboth"></div>
		</div>
		
		<div class="userDetailItem">
			<p>Department</p>
			<span>Maintenance</span>
			<div class="clearboth"></div>
		</div>
		<br/><br/>
		<h4>Meeting Settings</h4>
		<div class="meetingSettingsTitle">
			<h5>Meeting</h5>
			<h5>Role in Meeting</h5>
			<div class="clearboth"></div>	
		</div>
		@foreach($attendees as $attendee)
			<div class="meetingSettingITem">
				<p>{{$attendee}}</p>
				<span>Attendee</span>
				<div class="clearboth"></div>
			</div>
		@endforeach
		@foreach($minuters as $minuter)
			<div class="meetingSettingITem">
				<p>{{$minuter}}</p>
				<span>Minuter</span>
				<div class="clearboth"></div>
			</div>
		@endforeach
		<div class="adminUsersBtns">
			<div class="adminUsersBtnsLeft">
				<button id="editUser" uid="{{$user->userId}}">Edit Account</button>
			</div>
			
			<div class="adminUsersBtnsRight">
				<button>Reset password</button>
				<button>Change password</button>
			</div>
			<div class="clearboth"></div>
		</div>
	</div>
@endif