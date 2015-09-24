@extends('admin')
@section('content')
<div class="mainListFilter">
	<input type="text" placeholder="Search...">
	<select>
	  <option value="0">Sort by</option>
	  <option value="Option">Option 1</option>
	  <option value="Option">Option 2</option>
	  <option value="Option">Option 3</option>
	</select>
</div>
<div class="mainList">
	<!--=================================== List 1 ================================-->
	@if(count($meetings))
	<div class="boxList">
		<div class="boxTitle">
			<span class="boxTitleNumber boxNumberRed">{{count($meetings)}}</span>
			<p>Meetings Pending</p>
			<div class="clearboth"></div>
		</div>
		<?php $count=1; ?>
		@foreach($meetings as $meeting)
			<div class="box meeting" mid="{{$meeting->id}}">
				<span class="boxNumber boxNumberRed">{{$count++}}</span>
				<div class="boxInner">
					<h4>{{$meeting->title}}</h4>
					<h6>Requested by: {{$meeting->requestedby->name}}</h6>
				</div>	
				<div class="boxRight"></div>	
			</div>
		@endforeach
	</div>
	@endif
	<!--=================================== List 2 ================================-->
	@if(count($notifications))
	<div class="boxList">
		<div class="boxTitle">
			<span class="boxTitleNumber boxNumberGreen">{{count($notifications)}}</span>
			<p>Users</p>
		</div>
		<?php $count=1; ?>
		@foreach($notifications as $notification)
		<div class="box newusers"  mid="{{$notification->objectId}}">
			<span class="boxNumber boxNumberGreen">{{$count++}}</span>
			<div class="boxInner">
				<h4>{{$notification->meeting->title}}</h4>
				@foreach(unserialize($notification->body) as $key=>$value)
					{{$value}}<br>
				@endforeach
				<h6>6/7/2015 - 4.30pm</h6>
			</div>
			<div class="boxRight">
				
			</div>
		</div>
		@endforeach
	</div>
	@endif
	<div class="clearboth"></div>
</div>
<div class="popupOverlay" id="popup" >
	</div>
@endsection
@section('javascript')
<script src="{{ asset('/js/adminNotification.js') }}"></script>
@endsection