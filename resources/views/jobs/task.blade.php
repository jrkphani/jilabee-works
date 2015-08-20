@if($task)
<?php 
	if($task->minuteId)
	{
		$mid = "mid=".$task->minuteId;
	}
	else
	{
		$mid='';
	}
?>
<div class="popupWindow">
	<div class="popupHeader">
		<h2><a href="">Jobs</a> / <a href="">Pending Tasks</a></h2>
		<button onclick="$('#popup').hide();" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>	
	<div class="popupContent">
		<div class="popupContentTitle">
			<h4>{{$task->title}}</h4>
			<p>T{{$task->id}} / Created on: 25th jan 2015 / DUE: {{$task->dueDate}}</p>
			<p> Assigned by: {{$task->assignerDetail->name}}, updates: 3, revisions: nil</p>
			{{$task->status}}
			@if($task->status != 'Completed' && $task->status != 'Sent')
			<button class="completeBtn" id="markComplete" tid="{{$task->id}}" {{$mid}}>Mark as Complete</button>
			@endif
		</div>
		<div class="popupContentLeft">
			<div class="popupContentText">
				{{$task->description}}
			</div>
			
			
			<!-- ================= Updates ====================  -->
			<!-- ================= Update item each ====================  -->
			<div class="updateItem">
				<h6> update: 16/08/2015</h6>
				<p>Vivamus tristique non orci nec auctor. Suspendisse suscipit urna sed est porta imperdiet. Praesent eu vehicula mauris. Integer accumsan urna lorem, eu pretium sapien egestas.</p>
			</div>
			<!-- ================= Update item each ====================  -->
			<div class="updateItem">
				<h6> update: 16/08/2015</h6>
				<p>Vivamus tristique non orci nec auctor. Suspendisse suscipit urna sed est porta imperdiet. Praesent eu vehicula mauris. Integer accumsan urna lorem, eu pretium sapien egestas.</p>
			</div>
		</div>
		<!-- =================== Popup right ====================  -->
		<div class="popupContentRight">
			<div class="popupSearchSection">
				
			</div>
			<!-- ================= Comment/chat section ====================  -->
			<div class="chatSection">
				<div class="chatContent">
					@if($task->comments()->first())
							@foreach($task->comments()->get() as $comment)
								@if($comment->created_by == Auth::id())
									<div class="chatRight">
										<span>Me - {{$comment->updated_at}}</span>
								@else
									<div class="chatLeft">
										<span>{{$comment->createdby->name}} - {{$comment->updated_at}}</span>
								@endif
										<div class="clearboth"></div>
										<p>{!! $comment->description !!}</p>
									</div>
							@endforeach
					@endif
					<div class="clearboth"></div>
				</div>
				<!-- ================= Chat input area fixed to bottom  ====================  -->
				<div class="chatInput">
					{!! Form::open(['id'=>"CommentForm".$task->id]) !!}
					{!! Form::textarea('description', '',['rows'=>3,'placeholder'=>'Type comment here']) !!}
					{!! $errors->first('description','<div class="error">:message</div>') !!}
					{!! Form::close() !!}
					<button {{$mid}} tid="{{$task->id}}" id="taskComment" class="btn btn-primary ">Post</button>
				</div>
			</div>
		</div>
		<div class="clearboth"></div>
	</div>
</div>
</div>
@endif
