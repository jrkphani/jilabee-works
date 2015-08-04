@extends('admin')
@section('content')
	<div class="adminContent">
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
						@foreach($users as $user)
						<li class="listHighlight1 user" uid="{{$user->userId}}"><p>{{$user->name}}</p></li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
		<div id="adminUsersRight" class="adminUsersRight">
		</div>
		<div class="clearboth"></div>
	</div>
	<button class="addBtn"> </button>
@endsection
@section('javascript')
<script src="{{ asset('/js/adminUser.js') }}"></script>
@stop