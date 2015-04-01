@extends('master')
@section('usercontent')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			@foreach($users as $user)
				<div class="col-md-12 border_bottom">
					<div class="col-md-2">
						<img width="50" height="50" alt="Cinque Terre" class="img-circle" src="{{url('img/jotter.jpg')}}">
					</div>
					<div class="col-md-3">
						{{$user->name}}
					</div>
					<div class="col-md-3">
						{{$user->email}}
					</div>
					<div class="col-md-3">
						{{$user->profile->dob}}
					</div>
					<div class="col-md-1">
						<a href="{{url('edit/user/'.$user->id)}}" ><span class=" glyphicon glyphicon-edit"></span> </a>
					</div>
					
				</div>
			@endforeach
			<div class="col-md-12">{!! $users->render() !!}</div>
		</div>
	</div>
</div>
@endsection