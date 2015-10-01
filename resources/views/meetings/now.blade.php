@if(count($minutes['notfiled']) || count($minutes['pendingmeetings']) || count($minutes['newmeetings']))
<div class="boxList">
	<div class="boxTitle">
		<span class="boxTitleNumber boxNumberRed">{{count($minutes['notfiled'])+count($minutes['pendingmeetings'])+count($minutes['newmeetings'])}}</span>
		<p>Draft</p>
		<div class="clearboth"></div>
	</div>
	<?php $count = 1; ?>
	@foreach($minutes['newmeetings'] as $meeting)
		<div class="box">
			<span class="boxNumber boxNumberRed">{{$count++}}</span>
			<div class="boxInner" mid="{{$meeting->id}}">
				<h4>{{$meeting->title}}</h4>
			</div>
			<div class="boxRight firstMinute" mid="{{$meeting->id}}"></div>
		</div>
	@endforeach
	@foreach($minutes['pendingmeetings'] as $meeting)
	<?php $details = unserialize($meeting->details); ?>
		<div class="box pendingmeetings" mid="{{$meeting->id}}">
			<span class="boxNumber boxNumberRed">{{$count++}}</span>
			<div class="boxInner">
				<h4>{{$meeting->title}}</h4>
				<p>{{$meeting->created_at}}</p>
			</div>
			<div class="boxRight" mid="{{$meeting->id}}">
				@if($meeting->draft == '1')
				<p class="boxRightText">draft</p>
				@endif
			</div>
		</div>
	@endforeach
	@foreach($minutes['notfiled'] as $minute)
		<div class="box">
			<span class="boxNumber boxNumberRed">{{$count++}}</span>
			<div class="boxInner minute_history" mid="{{$minute->id}}">
				<h4>{{$minute->meeting->title}}</h4>
				<p>{{$minute->startDate}}</p>
			</div>
			<div class="boxRight minute" mid="{{$minute->id}}"></div>
		</div>
	@endforeach	
</div>
@endif
@if(count($minutes['recentMinutes']))
<div class="boxList">
	<div class="boxTitle">
		<span class="boxTitleNumber boxNumberBlue">{{count($minutes['recentMinutes'])}}</span>
		<p>Recent Minutes</p>
		<div class="clearboth"></div>
	</div>
	<?php $count = 1; ?>
	@foreach($minutes['recentMinutes'] as $minute)
		<div class="box">
			<span class="boxNumber boxNumberBlue">{{$count++}}</span>
			<div class="boxInner minute_history" mid="{{$minute->id}}">
				<h4>{{$minute->meeting->title}}</h4>
				<p>{{$minute->startDate}}</p>
			</div>
			<div class="boxRight minute" mid="{{$minute->id}}"></div>
		</div>
	@endforeach
</div>
@endif