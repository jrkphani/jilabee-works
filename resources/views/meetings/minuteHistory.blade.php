<div class="popupWindow">
	<div class="popupHeader">
		<h2><a href="">Meetings</a> / <a href="">Minutes</a></h2>
		<button  onclick="$('#popup').hide();" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>	
	<div class="popupContent popUpBg1">
		<div class="popupDateList">
			@if($minute->meeting->isMinuter())
			<button id="nextMinute" mid="{{$minute->meetingId}}" class="proceedBtn">Proceed to next meeting</button>
			@endif
			<h4>Previous Meetings</h4>
			@foreach($minutes as $row)
				@if($row->id == $minute->id)
					<button class="popupDateBtn minuteDiv active" mid="{{$row->id}}">{{$row->minuteDate}}</button>
				@else
					<button class="popupDateBtn minuteDiv" mid="{{$row->id}}">{{$row->minuteDate}}</button>
				@endif
			@endforeach
		</div>
		
		<div class="popupMinutes">
			
		
		<!--======================== Popup content starts here ===============================-->
		<div id="minuteDiv">
			@include('meetings.minute',['minute'=>$minute])	
		</div>
		</div>
		<div class="clearboth"></div>
	</div>
</div>