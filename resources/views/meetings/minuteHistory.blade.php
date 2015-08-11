<div class="popupWindow">
	<div class="popupHeader">
		<h2><a href="">Meetings</a> / <a href="">Minutes</a></h2>
		<button  onclick="$('#popup').hide();" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>	
	<div class="popupContent">
		<div class="popupDateList">
			<button class="proceedBtn">Proceed to next meeting</button>
			<h4>Previous Meetings</h4>
			<button class="popupDateBtn" mid="{{$minute->id}}">{{$minute->minuteDate}}</button>
			@foreach($minutes as $row)
			<button class="popupDateBtn minuteDiv" mid="{{$row->id}}">{{$row->minuteDate}}</button>
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