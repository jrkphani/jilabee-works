@extends('admin')
@section('content')

admin dashboard
<ul>
	<li><a href="{{url('/admin/user/add')}}">Add User</a></li>
	<li><a href="{{url('/admin/meetings')}}">Meetings</a></li>
</ul>
@endsection