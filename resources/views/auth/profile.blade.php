@extends('master')
@section('usercontent')
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="col-md-12">
				<img width="100" height="100" alt="Cinque Terre" class="img-circle" src="http://app.localjotter.com/img/jotter.jpg">
			</div>
			<div class="col-md-12">
				<div class="col-md-6">Name</div>
				<div class="col-md-6">{{$user->name}}</div>
			</div>
			<div class="col-md-12">
				<div class="col-md-6">Email</div>
				<div class="col-md-6">{{$user->email}}</div>
			</div>
			<div class="col-md-12">
				<div class="col-md-6">Phone</div>
				<div class="col-md-6">{{$user->profile->phone}}</div>
			</div>
			<div class="col-md-12">
				<div class="col-md-6">DOB</div>
				<div class="col-md-6">{{$user->profile->dob}}</div>
			</div>
			<a href="{{url('profile/edit')}}" class="btn btn-primary col-md-12 margin_top_10" >Edit</a>
		</div>
	</div>
</div>
@endsection