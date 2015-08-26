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
		<h2><a href="">Jobs</a> / <a href="">Edit Tasks</a></h2>
		<button  onclick="$('#popup').hide();" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>	
	<div class="popupContent">
		<!--======================== Popup content starts here ===============================-->
		{!! Form::open(array('id' => 'updateTaskForm')) !!}
				@if($task)
			<!-- =================== Job details ====================  -->
				<div class="popupContentTitle">
					{!! Form::hidden('id', $task->id) !!}
					{!! Form::text('title', $task->title,['placeholder'=>'Task title']) !!}
					<p class="taskNo">T{{$task->id}}</p>
					<div class="error" id="title_err"></div>
					<div class="clearboth"></div>
					<label>Assign to </label>
					<div id="selected_Assignee">
						<?php $assignee = $task->assignee; ?>
						@if($task->assignee)
								@if(isEmail($task->assignee))
									<?php $display=''; ?>
								@else
									<?php 
										$display='display:none;';
										$assignee = $task->assigneeDetail()->first()->user->userId;;
									?>
									<div class="assignee">
											{{$task->assigneeDetail->name}}
										<span class="removeParent">remove</span>
									</div>
								@endif	
						@else
							<?php $display=''; ?>
						@endif
					</div>
					{!! Form::text('assignee',$assignee,['id'=>'selectAssignee','placeholder'=>'search user','style'=>$display]) !!}
					<div class="error" id="assignee_err"></div>
					<label>Choose deadline</label> {!! Form::text('dueDate',$task->dueDate,['class'=>'dateInput']) !!}
					<div class="error" id="dueDate_err"></div>
			</div>
		<div class="popupContentLeft">
			<div class="popupContentText">
				{!! Form::textarea('description',str_ireplace(["<br />","<br>","<br/>"], "\r\n", $task->description),['rows'=>'10','cols'=>'30'])!!}
				<div class="error" id="description_err"></div>
			</div>
			<div class="popupContentText">
				{!! Form::textarea('notes', str_ireplace(["<br />","<br>","<br/>"], "\r\n", $task->notes),['rows'=>'10','cols'=>'30'])!!}
				<div class="error" id="notes_err"></div>
			</div>
			<div class="popupButtons popupButtonsFix">
				<button id="updateTaskSubmit" tid="{{$task->id}}">Update</button>
			</div>
		</div>
		@endif
			{!! Form::close() !!}
		<!-- =================== Popup right ====================  -->
		<div class="popupContentRight">
			<div class="popupSearchSection">
				
			</div>
			<!-- ================= Comment/chat section ====================  -->
			<div class="chatSection">
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
			</div>
		</div>
		<div class="clearboth"></div>
	</div>
</div>
<script type="text/javascript">
 $( "#selectAssignee" ).autocomplete({
            source: "/user/search",
            minLength: 2,
            select: function( event, ui ) {
	            insert = '<div class="assignee">'+ui.item.value+'<span class="removeParent">remove</span></div>';
	            $('#selected_Assignee').append(insert);
	            $(this).val(ui.item.userId);
	            $('#selectAssignee').hide();
	            return false;
            }
            });
dateInput();
</script>