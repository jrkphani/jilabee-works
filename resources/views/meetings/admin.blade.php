@extends('admin')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<a class="btn btn-primary pull-right" href="{{url('admin/meeting/add')}}">Add</a>
		</div>
		<div class="col-md-12">
				<div class="col-md-3">
					<strong>Title</strong>
				</div>
				<div class="col-md-4">
					<strong>Miunters</strong>
				</div>
				<div class="col-md-4">
					<strong>Attendees</strong>
				</div>
				<div class="col-md-1">
				</div>				
			</div>
		@if($meetings->first())
			@foreach($meetings as $meeting)
			<div class="col-md-12 border_top meetings">
				<div class="col-md-3">
					{{$meeting->title}}
				</div>
				<div class="col-md-4">
					@foreach(App\User::whereIn('id',explode(',', $meeting->minuters))->get() as $minuter)
					<div class="col-md-12"><a href="{{url('profile/'.$minuter->id)}}"> {{$minuter->name}}</a></div>
					@endforeach
				</div>
				<div class="col-md-4">
					@foreach(App\User::whereIn('id',explode(',', $meeting->attendees))->get() as $attendees)
					<div class="col-md-12"><a href="{{url('profile/'.$attendees->id)}}"> {{$attendees->name}}</a></div>
					@endforeach
				</div>
				<div class="col-md-1">
					<a href="{{url('admin/meeting/'.$meeting->id.'/edit')}}"><span class="glyphicon glyphicon-edit"></span></a>
				</div>				
			</div>
			@endforeach
		@else
		No data to display!
		@endif
	</div>
</div>
@endsection