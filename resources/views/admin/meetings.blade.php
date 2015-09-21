@extends('admin')
@section('content')

		<div class="adminUsersLeft">
			<div class="inner1">
				<div class="filterSet1">
					<input type="text" placeholder="Search...">
					<select>
					  <option value="0">Sort by</option>
					  <option value="Option">Option 1</option>
					  <option value="Option">Option 2</option>
					  <option value="Option">Option 3</option>
					</select>
				</div>
				<div class="adminUsersList">
					<ul>
						@foreach($meetings as $meeting)
						@if($meeting->active == '0')
							<li class="meeting deactive_aul_item" mid="{{$meeting->id}}"><p>{{$meeting->title}}</p></li>
						@else
							<li class="meeting" mid="{{$meeting->id}}"><p>{{$meeting->title}}</p></li>
						@endif
						@endforeach
					</ul>
				</div>
			</div>
		</div>
		<div id="adminUsersRight" class="adminUsersRight">
		</div>
		<div class="clearboth"></div>
	
	<button class="addBtn" id="addMeeting"> </button>
	<div class="popupOverlay" id="popup" ></div>
@endsection
@section('javascript')
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="{{ asset('/js/adminMeetings.js') }}"></script>
@endsection
@section('css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
@endsection