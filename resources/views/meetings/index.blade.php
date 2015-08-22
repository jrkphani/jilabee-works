@extends('master')
@section('css')		
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
@stop
@section('content')
<div id="contentLeft" class="contentLeft">
			<div class="mainListFilter">
						<input type="text" placeholder="Search...">
						<select>
						  <option value="0">Any meeting</option>
						  <option value="Option">Option 1</option>
						  <option value="Option">Option 2</option>
						  <option value="Option">Option 3</option>
						</select>
						<select>
						  <option value="0">Any time</option>
						  <option value="Option">Option 1</option>
						  <option value="Option">Option 2</option>
						  <option value="Option">Option 3</option>
						</select>
						<select>
					  <option value="0">Location</option>
					  <option value="Option">Option 1</option>
					  <option value="Option">Option 2</option>
					  <option value="Option">Option 3</option>
					</select>
					</div>
				<div class="contentMeetingsLeft">
					
					<div class="boxList">
						<div class="boxTitle">
							<span class="boxTitleNumber boxNumberRed">2</span>
							<p>Draft</p>
							<div class="clearboth"></div>
						</div>
						<div class="box">
							<span class="boxNumber boxNumberRed">1</span>
							<div class="boxInner">
								<h4>Project review</h4>
								<p>27/3/2015 4.30pm</p>
							</div>
							<div class="boxRight">
								
							</div>
						</div>
						<div class="box">
							<span class="boxNumber boxNumberRed">2</span>
							<div class="boxInner">
								<h4>Marketing Planning</h4>
								<p>27/3/2015 4.30pm</p>
							</div>
							<div class="boxRight">
								
							</div>
						</div>
					</div>
					<div class="boxList">
						<div class="boxTitle">
							<span class="boxTitleNumber boxNumberBlue">2</span>
							<p>Recent Minutes</p>
							<div class="clearboth"></div>
						</div>
						<div class="box">
							<span class="boxNumber boxNumberBlue">1</span>
							<div class="boxInner">
								<h4>Project review</h4>
								<p>27/3/2015 4.30pm</p>
							</div>
							<div class="boxRight">
								
							</div>
						</div>
						<div class="box">
							<span class="boxNumber boxNumberBlue">2</span>
							<div class="boxInner">
								<h4>Marketing Planning</h4>
								<p>27/3/2015 4.30pm</p>
							</div>
							<div class="boxRight">
								
							</div>
						</div>
					</div>
				</div>
				<div class="contentMeetingsRight" id="historyMeetingsRight"></div>
				<div class="clearboth"></div>
					<div class="arrowBtn arrowBtnRight">
						<span id="moveright"><img src="images/arrow_right.png"> </span>
						<p>Now</p>
					</div>
					
			</div>
<!-- Now section -->
<div id="contentRight" class="contentRight">
	<div class="contentMeetingsLeft">
		<div class="mainListFilter">
			<input type="text" placeholder="Search...">
			<select>
			  <option value="0">Sort by</option>
			  <option value="Option">Option 1</option>
			  <option value="Option">Option 2</option>
			  <option value="Option">Option 3</option>
			</select>
		</div>

		<div class="boxList">
			<div class="boxTitle">
				<span class="boxTitleNumber boxNumberBlue">2</span>
				<p>Pending Meetings</p>
				<div class="clearboth"></div>
			</div>
			@foreach($pendingminutes as $minute)
				<div class="box">
					<span class="boxNumber boxNumberBlue">1</span>
					<div class="boxInner  minute_history" mid="{{$minute->id}}">
						<h4>{{$minute->meeting->title}}</h4>
						<p>{{$minute->startDate}}</p>
					</div>
					<div class="boxRight minute" mid="{{$minute->id}}"></div>
				</div>
			@endforeach
		</div>

		<div class="boxList">
			<div class="boxTitle">
				<span class="boxTitleNumber boxNumberRed">2</span>
				<p>Not Field</p>
				<div class="clearboth"></div>
			</div>
			@foreach($notfield as $minute)
				<div class="box">
					<span class="boxNumber boxNumberRed">1</span>
					<div class="boxInner minute_history" mid="{{$minute->id}}">
						<h4>{{$minute->meeting->title}}</h4>
						<p>{{$minute->startDate}}</p>
					</div>
					<div class="boxRight minute" mid="{{$minute->id}}"></div>
				</div>
			@endforeach
		</div>
		<div class="boxList">
			<div class="boxTitle">
				<span class="boxTitleNumber boxNumberBlue">2</span>
				<p>Recent Minutes</p>
				<div class="clearboth"></div>
			</div>
			@foreach($recentMinutes as $minute)
				<div class="box">
					<span class="boxNumber boxNumberBlue">1</span>
					<div class="boxInner minute_history" mid="{{$minute->id}}">
						<h4>{{$minute->meeting->title}}</h4>
						<p>{{$minute->startDate}}</p>
					</div>
					<div class="boxRight minute" mid="{{$minute->id}}"></div>
				</div>
			@endforeach
		</div>
		<div class="boxList">
			<div class="boxTitle">
				<span class="boxTitleNumber boxNumberBlue">2</span>
				<p>New Meetings</p>
				<div class="clearboth"></div>
			</div>
			@foreach($newmeetings as $meeting)
				<div class="box">
					<span class="boxNumber boxNumberBlue">1</span>
					<div class="boxInner" mid="{{$meeting->id}}">
						<h4>{{$meeting->title}}</h4>
					</div>
					<div class="boxRight firstMinute" mid="{{$meeting->id}}"></div>
				</div>
			@endforeach
		</div>
	</div>
	<div class="contentMeetingsRight" id="nowMeetingsRight">
	</div>
	<div class="clearboth"></div>
	<div class="arrowBtn">
		<span id="moveleft"><img src="images/arrow_left.png"> </span>
		<p>History</p>
	</div>
	<button id="addMeeting" class="addBtn meetingsAddBtn"> </button>
	<div class="popupOverlay" id="popup" ></div>

</div>
@endsection
@section('javascript')
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="{{ asset('/js/meetings.js') }}"></script>
@endsection