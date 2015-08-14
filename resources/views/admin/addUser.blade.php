<div class="popupWindow">
	<div class="popupHeader">
		<h2><a href="">User</a> / <a href="">Add</a></h2>
		<button onclick="$('#popup').hide();" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>	
	<div class="popupContent">
		{!!Form::open(['id'=>'addUserForm'])!!}
		<div class="popupContentLeft">
			<p>Name <input type="text" name="name">
			<div class="error" id="name_err"></div></p>
			<p>Email <input type="email" name="email">
			<div class="error" id="email_err"></div></p>
			<p>Password <input type="password" name="password">
			<div class="error" id="password_err"></div></p>
			<p>Confirm Password<input type="password" name="password_confirmation"></p>
			<p>Phone<input type="text" name="phone">
			<div class="error" id="phone_err"></div></p>
			<p>DOB<input type="text" class="dateInput" name="dob" >
			<div class="error" id="dateInput_err"></div></p>
			<p>Gender {!!Form::radio('gender', 'M') !!} Male 
				{!!Form::radio('gender', 'F') !!} Female
				{!!Form::radio('others', 'O') !!} Others
				<div class="error" id="gender_err"></div></p>
		</div>
		<div class="popupContentRight">
			@include('admin.meetingsetting')
		</div>
		{!!Form::close()!!}
	</div>
	<button id="addUserSubmit" type="submit">Register</button>
</div>
    <script>
	$(document).ready(function($)
		{
			 $('.dateInput').datepicker({dateFormat: "yy-mm-dd",maxDate: "-15y",changeMonth: true,changeYear: true});
    	});
	</script>