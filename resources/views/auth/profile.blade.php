@extends('master')
@section('content')
	<div >
		<div class="profile_div">
			<div class="profile_pic">
				<img width="100" height="100" alt="Cinque Terre" class="img-circle" src="http://app.localjotter.com/img/jotter.jpg">
			</div>
			<div class="profile_row">
				<div>Name</div>
				<div>{{$user->name}}</div>
				<div class="clearboth"></div>
			</div>
			<div class="profile_row">
				<div>Email</div>
				<div>{{$user->email}}</div>
				<div class="clearboth"></div>
			</div>
			<div class="profile_row">
				<div >Phone</div>
				<div >{{$user->profile->phone}}</div>
				<div class="clearboth"></div>
			</div>
			<div  class="profile_row">
				<div >DOB</div>
				<div >{{$user->profile->dob}}</div>
				<div class="clearboth"></div>
			</div>
			<a href="{{url('profile/edit')}}" class="login_loginbtn login_register_back" >Edit</a>
			<br/>
			<br/>
		</div>
	</div>
@endsection