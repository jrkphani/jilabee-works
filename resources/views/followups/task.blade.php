

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
		<h2><a href="">Followups</a> / <a href="">Tasks</a></h2>
		<button onclick="$('#popup').hide();" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>	
	<div class="popupContent">
		<div class="popupContentTitle">
				<h4>{{$task->title}}</h4>
				<p>T{{$task->id}} / Created on: 25th jan 2015  / DUE: {{$task->dueDate}}</p>
				<p> Assignee to: 
				@if(isEmail($task->assignee))
					{{$task->assignee}}
				@else
					{{$task->assigneeDetail->name}}
				@endif
				@if(count($task->file))
					@if($mid)
						,revisions:
					@else
						,updates:
					@endif
				{{count($task->file)}}
				@endif
				</p>
				<p>Status: {{$task->status}}</p>
				@if($task->reason)
				<p>Last Rejected Reason: {!! $task->reason!!}</p>
				@endif

				@if($task->status == 'Completed')
				<div class="accept_reject">
					{{-- 
					//has to complet in minutes only for the minute task as per phani instruct
					--}}
					@if(!$task->minuteId)
					<button class="btn btn-primary " id="acceptCompletion" {{$mid}} tid="{{$task->id}}">accept completion</button>
					@endif
					<button class="btn btn-primary " id="rejectCompletion" {{$mid}} tid="{{$task->id}}">reject completion</button>
				</div>
				@endif
			</div>
		<div class="popupContentLeft">
			<div class="popupContentText">
				{!!str_ireplace(["<br />","<br>","<br/>"], "\r\n", $task->description)!!}
			</div>
			@if(!$mid)
			<div class="popupContentText">
				{!!str_ireplace(["<br />","<br>","<br/>"], "\r\n", $task->notes)!!}
			</div>
			@endif
			@foreach($task->file as $file)
			<div class="updateItem">
				<h6> update: {{$file->created_at}}</h6>
				<p>{!!$file->description!!}</p>
			</div>
			@endforeach
		</div>
		
		<div class="popupButtons popupButtonsFix">
			@if(!$task->minuteId)
				<button id="editTask" tid="{{$task->id}}">Edit Task</button>
				<button {{$mid}} tid="{{$task->id}}" id="cancelTask">Cancel Task</button>
				<button {{$mid}} tid="{{$task->id}}" id="deleteTask">Delete Task</button>
			@endif
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
				<div class="chatInput chatInput_1row">
					{!! Form::open(['id'=>"CommentForm"]) !!}
					{!! Form::textarea('description',Request::input('description',''),['rows'=>3,'placeholder'=>'Type comment here','id'=>'followupCommentText']) !!}
					{!! $errors->first('description','<div class="error">:message</div>') !!}
					{!! Form::close() !!}
					<button {{$mid}} tid="{{$task->id}}" id="followupComment" style="display:none;">Post</button>
					
				</div>
			</div>
		</div>
		<div class="clearboth"></div>
	</div>
</div>
</div>
@endif