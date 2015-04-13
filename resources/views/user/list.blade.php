@extends('admin')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="col-md-12 border_bottom">
					<div class="col-md-2">
					</div>
					<div class="col-md-2">
						<strong>Name</strong>
					</div>
					<div class="col-md-3">
						<strong>Email</strong>
					</div>
					<div class="col-md-2">
						<strong>DOB</strong>
					</div>
					<div class="col-md-2">
						<strong>Phone</strong>
					</div>
					<div class="col-md-1">
						<strong>Action</strong>
					</div>
				</div>
			@foreach($users as $user)
				<div class="col-md-12 border_bottom users">
					<div class="col-md-2">
						<img width="50" height="50" alt="Cinque Terre" class="img-circle" src="{{url('img/jotter.jpg')}}">
					</div>
					<div class="col-md-2">
						{{$user->name}}
					</div>
					<div class="col-md-3">
						{{$user->email}}
					</div>
					<div class="col-md-2">
						{{$user->profile->dob}}
					</div>
					<div class="col-md-2">
						{{$user->profile->phone}}
					</div>
					<div class="col-md-1">
						<a href="{{url('user/'.$user->id.'/edit')}}" ><span class=" glyphicon glyphicon-edit"></span> </a>
					</div>
					
				</div>
			@endforeach
			<div class="col-md-12">{!! $users->render() !!}</div>
		</div>
	</div>
</div>
@endsection