@extends('admin')
@section('content')
	<div class="row">
		<div class="col-md-12">
			<a href="{{url('/admin/user/add')}}" class="pull-right">Add User</a>
		</div>
		<div class="col-md-12">
			<table class="table">
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Email</th>
				</tr>
				@foreach($users as $user)
					<tr>
						<td>{{$user->userId}}</td>
						<td>{{$user->name}}</td>
						<td>{{$user->user()->first()->email}}</td>
					</tr>
				@endforeach
			</table>
		</div>
		<div class="form-group">
			<div class="col-md-12">
           		<a class="btn btn-primary" href="{{url('admin')}}">Back</a> 
        	</div>
		</div>
	</div>
@endsection