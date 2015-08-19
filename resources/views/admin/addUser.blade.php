<div class="inner2">
	<h4>Add User</h4>
	{!!Form::open(['id'=>'addUserForm'])!!}
	@if($user)
	<input type="hidden" name="uid" value="{{$user->id}}"/>
	<div class="userDetailItem">
		<p>Name </p>
		<span><input type="text" name="name" value="{{$user->name}}"></span>
		<div class="error" id="name_err"></div>
		<div class="clearboth"></div>
	</div>
	<div class="userDetailItem">
		<p>Email</p>
		<span><input type="email" name="email" value="{{$user->email}}"></span>
		<div class="error" id="email_err"></div>
		<div class="clearboth"></div>
	</div>
	<div class="userDetailItem">
		<p>Password</p>
		<span><input type="password" name="password" autocomplete="off" value=""></span>
		<div class="error" id="password_err"></div>
		<div class="clearboth"></div>
	</div>
	<div class="userDetailItem">
		<p>Confirm Password</p>
		<span><input type="password" name="password_confirmation"></span>
		<div class="clearboth"></div>
	</div>
	<div class="userDetailItem">
		<p>Phone</p>
		<span><input type="text" name="phone" value="{{$user->phone}}"></span>
		<div class="error" id="phone_err"></div>
		<div class="clearboth"></div>
	</div>
	<div class="userDetailItem">
		<p>DOB</p>
		<span><input type="text" class="dateInput" name="dob" value="{{$user->dob}}"></span>
		<div class="error" id="dateInput_err"></div>
		<div class="clearboth"></div>
	</div>
	<div class="userDetailItem">
		<p>Gender</p>
		<span>
			{!! Form::select('gender', ['M'=>'Male','F'=>'Female','O'=>'Others'],$user->gender)!!}
		</span>
		<div class="error" id="gender_err"></div>
		<div class="clearboth"></div>
	</div>
	<div class="userDetailItem">
		<p>Roles</p>
		<span> 
			<?php $roles = explode(',',$user->roles); ?>
			@foreach(roles() as $key=>$value)
			@if(in_array($key, $roles))
				<?php $flag='true'; ?>
				@else
				<?php $flag=''; ?>
			@endif
			{!! Form::checkbox('roles[]',$key,$flag)!!} {{$value}}
			@endforeach
		</span>
		<div class="error" id="gender_err"></div>
		<div class="clearboth"></div>
	</div>
	@else
	<div class="userDetailItem">
		<p>Name </p>
		<span><input type="text" name="name"></span>
		<div class="error" id="name_err"></div>
		<div class="clearboth"></div>
	</div>
	<div class="userDetailItem">
		<p>Email</p>
		<span><input type="email" name="email"></span>
		<div class="error" id="email_err"></div>
		<div class="clearboth"></div>
	</div>
	<div class="userDetailItem">
		<p>Password</p>
		<span><input type="password" name="password"></span>
		<div class="error" id="password_err"></div>
		<div class="clearboth"></div>
	</div>
	<div class="userDetailItem">
		<p>Confirm Password</p>
		<span><input type="password" name="password_confirmation"></span>
		<div class="clearboth"></div>
	</div>
	<div class="userDetailItem">
		<p>Phone</p>
		<span><input type="text" name="phone"></span>
		<div class="error" id="phone_err"></div>
		<div class="clearboth"></div>
	</div>
	<div class="userDetailItem">
		<p>DOB</p>
		<span><input type="text" class="dateInput" name="dob" ></span>
		<div class="error" id="dateInput_err"></div>
		<div class="clearboth"></div>
	</div>
	<div class="userDetailItem">
		<p>Gender</p>
		<span> {!! Form::select('gender', ['M'=>'Male','F'=>'Female','O'=>'Others'])!!}
		</span>
		<div class="error" id="gender_err"></div>
		<div class="clearboth"></div>
	</div>
	<div class="userDetailItem">
		<p>Roles</p>
		<span> 
			@foreach(roles() as $key=>$value)
			{!! Form::checkbox('roles[]',$key)!!} {{$value}}
			@endforeach
		</span>
		<div class="error" id="gender_err"></div>
		<div class="clearboth"></div>
	</div>
	@endif
		@include('admin.meetingsetting')
	{!!Form::close()!!}
	@if($user)
		<button id="editUserSubmit" type="submit" uid="{{$user->userId}}">Update</button>
	@else
		<button id="addUserSubmit" type="submit">Register</editton>
	@endif
</div>
<script>
$(document).ready(function($)
	{
		 $('.dateInput').datepicker({dateFormat: "yy-mm-dd",maxDate: "-15y",changeMonth: true,changeYear: true});
	});
</script>