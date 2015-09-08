@if($task)
<?php 
	if($task->minuteId)
	{
		$mid = "mid=".$task->minuteId;
	}
	else
	{
		$mid=NULL;
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
			<p>
				Assigned by: {{$task->assignerDetail->name}}
				@if(count($task->file))
					@if($mid)
						,revisions:
					@else
						,updates:
					@endif
					{{count($task->file)}}
				@endif
			</p>
			Status: {{$task->status}}
			@if($task->status != 'Completed' && $task->status != 'Sent' && $task->status != 'Closed')
				@if($mid)
						@if($task->minute->filed == '1'))
						<button class="completeBtn" id="markComplete" tid="{{$task->id}}" {{$mid}}>Mark as Complete</button>
						@endif
				@else
					<button class="completeBtn" id="markComplete" tid="{{$task->id}}" {{$mid}}>Mark as Complete</button>
				@endif
			@endif
		</div>
		<div class="popupContentLeft">
			<div class="popupContentText">
				{!!$task->description!!}
			</div>
			
			@foreach($task->file as $file)
				<div class="updateItem">
					<h6> update: {{$file->created_at}}</h6>
					<p>{!!$file->description!!}</p>
				</div>
			@endforeach
			
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
