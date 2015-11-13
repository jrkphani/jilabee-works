@extends('admin')
@section('content')
		<div class="adminUsersLeft">
			<div class="inner1">
				{{-- <div class="filterSet1">
					<input type="text" placeholder="Search...">
					<select>
					  <option value="0">Sort by</option>
					  <option value="Option">Option 1</option>
					  <option value="Option">Option 2</option>
					  <option value="Option">Option 3</option>
					</select>
				</div> --}}
				<div class="adminUsersList">
					<ul>
						@foreach($users as $user)
						<li class="user" uid="{{$user->userId}}"><p>{{$user->name}}</p></li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
		<div id="adminUsersRight" class="adminUsersRight adminUsersEdit">
				Loading ....
		</div>
		<div class="clearboth"></div>
	<button class="addBtn" id="addUser"> </button>
	<div class="popupOverlay" id="popup" ></div>
@endsection
@section('javascript')
<script src="{{ asset('/js/jquery.datetimepicker.full.js') }}"></script>
<script src="{{ asset('/js/adminUser.js') }}"></script>
@endsection
@section('css')
<link href="{{ asset('/css/jquery.datetimepicker.css') }}" rel="stylesheet">
@endsection