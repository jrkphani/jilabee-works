<h4>Meeting Settings</h4>
<div id="meetingList">
	<div class="meetingSettingITem">
		<select name="meetings[]">
		@foreach($meetings as $meeting)
			<option value="{{$meeting->id}}">{{$meeting->title}}</option>
		@endforeach
		</select>
		<select name="roles[]">
			@foreach(roles() as $role)
				<option value="{{$role['id']}}">{{$role['name']}}</option>
			@endforeach
		</select>
		<div class="clearboth"></div>
	</div>
</div>
<button id="addmeeting">Add</button>