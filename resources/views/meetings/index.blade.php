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
		<div class="contentMeetingsRight">
			<div class="paper">
				<div class="paperBorder">
					<div class="paperTitleLeft">
						<h3>Delivery performance monthly review </h3>
						<p> MT23SH meeting venue: Meeting hall, Banglore office</p>
					</div>
					<div class="paperTitleRight">
						<h3>9th March, 2015</h3>
						<p>3:15pm</p>
					</div>
					<div class="clearboth"></div>
					<div class="paperSubTitle">
						<p><span>Participants:</span> Mr.Smith, Ms. Marry, Mr. John, MS. Betty</p>
						<p><span>Absentees:</span> Mr. Kane, Ms.Jenny</p>
					</div>
					<h4>Previous Minutes</h4>
					<div class="minuteItem">
						<div class="minuteItemNumber">
							<p>1</p>
						</div>
						<div class="minuteItemLeft">
							<h5>Lorem ipsum dolor sit amet, consectetur adipiscing elit. </h5>
							<p>Praesent consequat auctor risus, et volutpat orci condimentum vel. Suspendisse ullamcorper ultricies lorem dapibus eleifend. Phasellus sed neque eu enim mattis condimentum eu in est. Praesent varius tincidunt risus, eu eleifend nisl. Fusce consequat nisl nec eleifend ultrices.</p>
						</div>
						<div class="minuteItemRight">
							<h6>TSK040</h6>
							<p>Mr.Smith</p>
							<p>14 March	</p>
						</div>
						<div class="clearboth"></div>
					</div>
					<div class="minuteItem">
						<div class="minuteItemNumber">
							<p>2</p>
						</div>
						<div class="minuteItemLeft">
							<h5>Lorem ipsum dolor sit amet, consectetur adipiscing elit. </h5>
							<p>Praesent consequat auctor risus, et volutpat orci condimentum vel. Suspendisse ullamcorper ultricies lorem dapibus eleifend. Phasellus sed neque eu enim mattis condimentum eu in est. Praesent varius tincidunt risus, eu eleifend nisl. Fusce consequat nisl nec eleifend ultrices.</p>
						</div>
						<div class="minuteItemRight">
							<h6>TSK040</h6>
							<p>Mr.Smith</p>
							<p>14 March	</p>
						</div>
						<div class="clearboth"></div>
					</div>
					<div class="minuteItem">
						<div class="minuteItemNumber">
							<p>3</p>
						</div>
						<div class="minuteItemLeft">
							<h5>Lorem ipsum dolor sit amet, consectetur adipiscing elit. </h5>
							<p>Praesent consequat auctor risus, et volutpat orci condimentum vel. Suspendisse ullamcorper ultricies lorem dapibus eleifend. Phasellus sed neque eu enim mattis condimentum eu in est. Praesent varius tincidunt risus, eu eleifend nisl. Fusce consequat nisl nec eleifend ultrices.</p>
						</div>
						<div class="minuteItemRight">
							<h6>TSK040</h6>
							<p>Mr.Smith</p>
							<p>14 March	</p>
						</div>
						<div class="clearboth"></div>
					</div>
					<div class="minuteItem">
						<div class="minuteItemNumber">
							<p>4</p>
						</div>
						<div class="minuteItemLeft">
							<h5>Lorem ipsum dolor sit amet, consectetur adipiscing elit. </h5>
							<p>Praesent consequat auctor risus, et volutpat orci condimentum vel. Suspendisse ullamcorper ultricies lorem dapibus eleifend. Phasellus sed neque eu enim mattis condimentum eu in est. Praesent varius tincidunt risus, eu eleifend nisl. Fusce consequat nisl nec eleifend ultrices.</p>
						</div>
						<div class="minuteItemRight">
							<h6>TSK040</h6>
							<p>Mr.Smith</p>
							<p>14 March	</p>
						</div>
						<div class="clearboth"></div>
					</div>
					<div class="minuteItem">
						<div class="minuteItemNumber">
							<p>5</p>
						</div>
						<div class="minuteItemLeft">
							<h5>Lorem ipsum dolor sit amet, consectetur adipiscing elit. </h5>
							<p>Praesent consequat auctor risus, et volutpat orci condimentum vel. Suspendisse ullamcorper ultricies lorem dapibus eleifend. Phasellus sed neque eu enim mattis condimentum eu in est. Praesent varius tincidunt risus, eu eleifend nisl. Fusce consequat nisl nec eleifend ultrices.</p>
						</div>
						<div class="minuteItemRight">
							<h6>TSK040</h6>
							<p>Mr.Smith</p>
							<p>14 March	</p>
						</div>
						<div class="clearboth"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="clearboth"></div>
			<div class="arrowBtn arrowBtnRight">
				<span id="moveright"><img src="images/arrow_right.png"> </span>
				<p>Now</p>
			</div>
			
	</div>

<!-- Now section -->
<div id="contentRight" class="contentRight">
		<div class="mainListFilter">
			<input type="text" placeholder="Search...">
			<select>
			  <option value="0">Sort by</option>
			  <option value="Option">Option 1</option>
			  <option value="Option">Option 2</option>
			  <option value="Option">Option 3</option>
			</select>
		</div>
	<div class="contentMeetingsLeft">
		
		<div class="boxList">
			<div class="boxTitle">
				<span class="boxTitleNumber boxNumberRed">{{count($pendingminutes)}}</span>
				<p>Pending</p>
				<div class="clearboth"></div>
			</div>
			@foreach($pendingminutes as $minute)
				<div class="box">
					<span class="boxNumber boxNumberRed">1</span>
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
				<span class="boxTitleNumber boxNumberBlue">{{count($recentMinutes)}}</span>
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
				<span class="boxTitleNumber boxNumberRed">{{count($notfiled)}}</span>
				<p>Not Filed</p>
				<div class="clearboth"></div>
			</div>
			@foreach($notfiled as $minute)
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
				<span class="boxTitleNumber boxNumberBlue">{{count($newmeetings)}}</span>
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
		{{-- right side content --}}
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