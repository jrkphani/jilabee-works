<div class="inner2">
	{!!Form::open(['id'=>'addUserForm'])!!}
	@if($user)
	<div class="userDetailItem">
		<p>Name</p>
		<input type="text" name="name" value="{{$user->name}}">
		<div class="error" id="name_err"></div>
		<div class="clearboth"></div>
	</div>
	<div class="userDetailItem">
		<p>User Rights</p>
		<div class="userDetailItemY">
			{!! Form::select('role',$roles,$user->role)!!}
			{!! Form::hidden('prerole',$user->role,['id'=>'prerole'])!!}
		</div>
		<div class="error" id="role_err"></div>
		<div class="clearboth"></div>
	</div>
	
	<div>
		<div class="userDetailItemX">
			<div class="userDetailItem">
				<p>DOB</p>
				<input type="text" id="dob" name="dob" value="{{$user->dob}}">
				<div class="error" id="dob_err"></div>
				<div class="clearboth"></div>
			</div>
			<div class="userDetailItem">
				<p>Email</p>
				<input type="email" name="email" value="{{$user->email}}">
				<div class="error" id="email_err"></div>
				<div class="clearboth"></div>
			</div>
		</div>
		<div class="userDetailItemX">
			<div class="userDetailItem">
				<p>Sex</p>
				{!! Form::select('gender', ['M'=>'Male','F'=>'Female','O'=>'Others'],$user->gender)!!}
				<div class="error" id="gender_err"></div>
				<div class="clearboth"></div>
			</div >
			<div class="userDetailItem">
				<p>Phone</p>
				<input type="text" name="phone" value="{{$user->phone}}">
				<div class="error" id="phone_err"></div>
				<div class="clearboth"></div>
			</div>
		</div>
		<div class="clearboth"></div>
		<div class="userDetailItemX">
			<div class="userDetailItem">
				<p>Password</p>
				<input type="password" name="password">
				<div class="clearboth"></div>
			</div >
			<div class="userDetailItem">
				<p>Confirm Password</p>
				<input type="password" name="password_confirmation">
				<div class="clearboth"></div>
			</div>
			<div class="error" id="password"></div>
		</div>
		<div class="clearboth"></div>
	</div>
	
	<div class="userDetailItem">
		<p>Department</p>
		<input type="text" name="department" value="{{$user->department}}">
		<div class="error" id="department_err"></div>
		<div class="clearboth"></div>
	</div>
	<br/><br/>
	<h4>Meeting Settings</h4>
	<div class="meetingSettingsTitle">

		<h5>Meeting</h5>
		<h5>Role in Meeting</h5>
		<div class="clearboth"></div>	
	</div>
	@else
	<div class="userDetailItem">
		<p>Name</p>
		<input type="text" name="name">
	</div>
	<div class="error" id="name_err"></div>
	<div class="clearboth"></div>
	<div class="userDetailItem">
		<p>User Rights</p>
		<div class="userDetailItemY">
			{!! Form::select('role',$roles,'1',['id'=>'role'])!!}
		</div>
		<div class="error" id="role_err"></div>
		<div class="clearboth"></div>
	</div>
	
	<div>
		<div class="userDetailItemX">
			<div class="userDetailItem">
				<p>DOB</p>
				<input type="text" id="dob" name="dob" >
				<div class="error" id="dob_err"></div>
				<div class="clearboth"></div>
			</div>
			<div class="userDetailItem">
				<p>Email</p>
				<input type="email" name="email">
				<div class="error" id="email_err"></div>
				<div class="clearboth"></div>
			</div>
		</div>
		<div class="userDetailItemX">
			<div class="userDetailItem">
				<p>Sex</p>
				{!! Form::select('gender', ['M'=>'Male','F'=>'Female','O'=>'Others'])!!}
				<div class="error" id="gender_err"></div>
				<div class="clearboth"></div>
			</div >
			<div class="userDetailItem">
				<p>Phone</p>
				<input type="text" name="phone">
				<div class="error" id="phone_err"></div>
				<div class="clearboth"></div>
			</div>
		</div>
		<div class="clearboth"></div>
		<div class="userDetailItemX">
			<div class="userDetailItem">
				<p>Password</p>
				<input type="password" name="password">
				<div class="clearboth"></div>
			</div >
			<div class="userDetailItem">
				<p>Confirm Password</p>
				<input type="password" name="password_confirmation">
				<div class="clearboth"></div>
			</div>
			<div class="error" id="password_err"></div>
		</div>
		<div class="clearboth"></div>
	</div>
	
	<div class="userDetailItem">
		<p>Department</p>
		<input type="text" name="department" value="">
		<div class="error" id="department_err"></div>
		<div class="clearboth"></div>
	</div>
	<br/><br/>
	<h4>Meeting Settings</h4>
	<div class="meetingSettingsTitle">

		<h5>Meeting</h5>
		<h5>Role in Meeting</h5>
		<div class="clearboth"></div>	
	</div>
	@endif
	@include('admin.meetingsetting')
{!! Form::close()!!}
	<div class="adminUsersBtns">
		
			@if($user)
			<div class="adminUsersBtnsLeft">
				<button id="editUserSubmit" type="submit" uid="{{$user->userId}}">Update</button>
				<button url="{{url('admin/user/edit/'.$user->userId)}}" class="backBtn" divId="adminUsersRight">Revert changes</button>
				<button url="{{url('admin/user/view/'.$user->userId)}}" class="backBtn" divId="adminUsersRight">Cancel changes</button>
			</div>
			@else
			<div class="adminUsersBtnsLeft">
				<button id="addUserSubmit" type="submit">Save</button>
			</div>
			<div class="adminUsersBtnsRight">
				<span divid="adminUsersRight" class="button" onclick="$('.listHighlight1:first').click();">Back</span>
			</div>
			@endif
		<div class="clearboth"></div>
	</div>

</div>


<script>
$(document).ready(function($)
	{
		curentval = $('#dob').val();
		 d= new Date();
			d.setFullYear(d.getFullYear()-15);
			$('#dob').appendDtpicker(
			    {
			    "autodateOnStart": false,
			    "maxDate":d,
			    "closeOnSelected": true,
			    "dateOnly":true
			    });
			$('#dob').handleDtpicker('setDate',d);
			$('#dob').val(curentval);
	});
</script>