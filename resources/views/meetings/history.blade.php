@if(count($meetings))
	<div class="boxList">
	<div class="boxTitle">
		<span class="boxTitleNumber boxNumberBlue">{{count($meetings)}}</span>
		<p>Closed Meetings</p>
		<div class="clearboth"></div>
	</div>
	<?php $count = 1; ?>
	@foreach($meetings as $meeting)
	<?php $mid = null; $minute = $meeting->minutes()->first(); if($minute) {$mid = $minute->id; } ?>
		<div class="box">
			<span class="boxNumber boxNumberBlue">{{$count++}}</span>
			@if($mid)
			<div class="boxInner minute_history" mid="{{$mid}}">
				<h4>{{$meeting->title}}</h4>
				<p>{{$minute->updated_at}}
			</div>
			<div class="boxRight closed_minute" mid="{{$mid}}"></div>
			@else
			<div class="boxInner">
				<h4>{{$meeting->title}}</h4>
			</div>
			<div class="boxRight"></div>
			@endif
		</div>
	@endforeach
</div>
@endif