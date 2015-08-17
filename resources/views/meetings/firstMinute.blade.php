<div class="popupWindow">
	<div class="popupHeader">
		<h2><a href="">Meetings</a> / <a href="">Minutes</a></h2>
		<button  onclick="$('#popup').hide();" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>	
	<div class="popupContent popUpBg1">
		<div class="popupDateList">
			@if($meeting->isMinuter())
			<button id="nextMinute" mid="{{$meeting->id}}" class="proceedBtn">Start meeting</button>
			@endif
		</div>
		
		<div class="popupMinutes">
			
		
		<!--======================== Popup content starts here ===============================-->
		<div id="minuteDiv">
			
		</div>
		</div>
		<div class="clearboth"></div>
	</div>
</div>