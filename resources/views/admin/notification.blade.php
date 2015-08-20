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
	<div class="boxList">
		<div class="boxTitle">
			<span class="boxTitleNumber boxNumberRed">5</span>
			<p>Meetings Pending</p>
			<div class="clearboth"></div>
		</div>
		@foreach($meetings as $meeting)
			<div class="box">
				<span class="boxNumber boxNumberRed">3</span>
				<div class="boxInner">
					<h4>{{$meeting->title}}</h4>
					<h6>Requested by: {{$meeting->requestedby->name}}</h6>
				</div>	
				<div class="boxRight meeting" mid="{{$meeting->id}}"></div>	
			</div>
		@endforeach
	</div>
	<!--=================================== List 2 ================================-->
	<div class="boxList">
		<div class="boxTitle">
			<span class="boxTitleNumber boxNumberGreen">2</span>
			<p>Users</p>
		</div>
		<div class="box">
			<span class="boxNumber boxNumberGreen">1</span>
			<div class="boxInner">
				<h4>Prepare project report</h4>
				<h6>6/7/2015 - 4.30pm</h6>
			</div>
			<div class="boxRight">
				
			</div>
		</div>
		<div class="box">
			<span class="boxNumber boxNumberGreen">2</span>
			<div class="boxInner">
				<h4>Prepare project report</h4>
				<h6>6/7/2015 - 4.30pm</h6>
			</div>
			<div class="boxRight">
				
			</div>
		</div>
	</div>
	<div class="clearboth"></div>
</div>
<div class="popupOverlay" id="popup" >
	</div>
@endsection
@section('javascript')
<script src="{{ asset('/js/adminNotification.js') }}"></script>
@endsection