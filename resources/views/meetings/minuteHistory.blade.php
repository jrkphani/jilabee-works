<div class="popupWindow">
	<div class="popupHeader">
		<h2><a href="">Meetings</a> / <a href="">Minutes</a></h2>
		<button  onclick="$('#popup').hide();" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>	
	<div class="popupContent popUpBg1">
		<div class="popupDateList">
			@if($minute->meeting->isMinuter())
				@if($minute->field == '1')
					<button id="nextMinute" mid="{{$minute->meetingId}}" class="proceedBtn">Proceed to next meeting</button>
				@elseif($minute->field == '0' && $minute->created_by == Auth::id())
					<button id="nextMinute" mid="{{$minute->meetingId}}" class="proceedBtn">Proceed to non field meeting</button>
					@endif
			@endif
			<h4>Previous Meetings</h4>
			@foreach($minutes as $row)
				<?php $isfield=""; if($row->field == '0') { $isfield=" - Draft"; }?>
				@if($row->id == $minute->id)
					<button class="popupDateBtn minuteDiv active" mid="{{$row->id}}">{{$row->startDate}}{{$isfield}}</button>
				@else
					<button class="popupDateBtn minuteDiv" mid="{{$row->id}}">{{$row->startDate}}{{$isfield}}</button>
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