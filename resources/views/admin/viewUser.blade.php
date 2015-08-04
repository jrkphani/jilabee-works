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
			<span>followups, minutes, tasker</span>
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
		<div class="meetingSettingITem">
			<p>Delivery performance review</p>
			<span>attendee</span>
			<div class="clearboth"></div>
		</div>
		<div class="meetingSettingITem">
			<p>Delivery performance review</p>
			<span>attendee</span>
			<div class="clearboth"></div>
		</div>
		<div class="meetingSettingITem">
			<p>Delivery performance review</p>
			<span>attendee</span>
			<div class="clearboth"></div>
		</div>
		<div class="meetingSettingITem">
			<p>Delivery performance review</p>
			<span>attendee</span>
			<div class="clearboth"></div>
		</div>
		<div class="meetingSettingITem">
			<p>Delivery performance review</p>
			<span>attendee</span>
			<div class="clearboth"></div>
		</div>
		<div class="adminUsersBtns">
			<div class="adminUsersBtnsLeft">
				<button>Edit Account</button>
			</div>
			
			<div class="adminUsersBtnsRight">
				<button>Reset password</button>
				<button>Change password</button>
			</div>
			<div class="clearboth"></div>
		</div>
	</div>
@endif