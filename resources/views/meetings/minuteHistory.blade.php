<div class="popupWindow">
	<div class="popupHeader">
		<h2><a href="">Meetings</a> / <a href="">Minutes</a></h2>
		<button  onclick="$('#popup').hide();" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>	
	<div class="popupContent popUpBg1">
		<div class="popupDateList">
			@if($minute->meeting->isMinuter())
				@if($minute->filed == '1')
					<button id="nextMinute" mid="{{$minute->meetingId}}" class="proceedBtn">Proceed to next meeting</button>
				@elseif($minute->filed == '0' && $minute->created_by == Auth::id())
					<button id="nextMinute" mid="{{$minute->meetingId}}" class="proceedBtn">Edit last minutes</button>
					@endif
			@endif
			@foreach($minutes as $row)
				<?php $isfiled=""; if($row->filed == '0') { $isfiled=" - Draft"; }?>
				@if($row->id == $minute->id)
					<button class="popupDateBtn minuteDiv active" mid="{{$row->id}}">{{$row->startDate}}{{$isfiled}}</button>
				@else
					<button class="popupDateBtn minuteDiv" mid="{{$row->id}}">{{$row->startDate}}{{$isfiled}}</button>
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