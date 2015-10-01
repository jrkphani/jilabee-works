@if(count($meetings))
	<div class="boxList">
	<div class="boxTitle">
		<span class="boxTitleNumber boxNumberBlue">{{count($meetings)}}</span>
		<p>Closed Meetings</p>
		<div class="clearboth"></div>
	</div>
	<?php $count = 1; ?>
	@foreach($meetings as $meeting)
	<?php $minute = $meeting->minutes()->first(); ?>
		<div class="box">
			<span class="boxNumber boxNumberBlue">{{$count++}}</span>
			<div class="boxInner minute_history" mid="{{$minute->id}}">
				<h4>{{$meeting->title}}</h4>
				<p>{{$minute->updated_at}}
			</div>
			<div class="boxRight closed_minute" mid="{{$minute->id}}"></div>
		</div>
	@endforeach
</div>
@endif