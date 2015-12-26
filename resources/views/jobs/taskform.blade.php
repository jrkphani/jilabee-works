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
					<p class="taskNo pcl_jobs_label_tskno">T{{$task->id}}</p>
					<div class="error" id="title_err"></div>
					<div class="clearboth"></div>
					<label class="pcl_jobs_label">Assign to </label>
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
					{!! Form::text('assignee',$assignee,['id'=>'selectAssignee','placeholder'=>'email','style'=>$display]) !!}
					<div class="error" id="assignee_err">{{$errors->first('assignee')}}</div>
					<label class="pcl_jobs_label">Client Email </label>
					{!! Form::text('clientEmail',$task->clientEmail,['id'=>'clientEmail','placeholder'=>'email']) !!}
					<div class="clearboth"></div>
					<label class="pcl_jobs_label">Choose deadline</label> {!! Form::text('dueDate',$task->dueDate,['class'=>'dateInput']) !!}
					<div class="error" id="dueDate_err">{{$errors->first('dueDate')}}</div>
			</div>
		<div class="popupContentLeft">
			<div class="popupContentText">
				{!! Form::textarea('description',str_ireplace(["<br />","<br>","<br/>"], "\r\n", $task->description),['rows'=>'6','cols'=>'30'])!!}
				<div class="error" id="description_err">{{$errors->first('description')}}</div>
			</div>
			<div class="popupContentText">
				{!! Form::textarea('notes', str_ireplace(["<br />","<br>","<br/>"], "\r\n", $task->notes),['rows'=>'6','cols'=>'30'])!!}
				<div class="error" id="notes_err">{{$errors->first('notes')}}</div>
			</div>
			
		</div>
		<div class="popupButtons popupButtonsFix taskChatFix">
			<button id="updateTaskSubmit" tid="{{$task->id}}">Update</button>
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
				<div class="chatInput chatInput_1row taskChatFix">
					{!! Form::open(['id'=>"CommentForm".$task->id]) !!}
					{!! Form::textarea('description', '',['rows'=>3,'placeholder'=>'Type comment here','id'=>'taskCommentText']) !!}
					{!! $errors->first('description','<div class="error">:message</div>') !!}
					{!! Form::close() !!}
					<button {{$mid}} tid="{{$task->id}}" id="taskComment" style="display:none;">Post</button>
				</div>
			</div>
		</div>
			</div>
		</div>
		<div class="clearboth"></div>
	</div>
</div>
<script type="text/javascript">
<?php
if(getOrgId())
{
?>
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
<?php
}
?>
dateInput();
</script>