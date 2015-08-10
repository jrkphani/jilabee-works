<div class="popupWindow">
	<div class="popupHeader">
		<h2><a href="">Jobs</a> / <a href="">New Tasks</a></h2>
		<button  onclick="$('#popup').hide();" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>	
	<div class="popupContent">
		<!--======================== Popup content starts here ===============================-->
		<div class="popupContentLeft">
			<!-- =================== Job details ====================  -->
			{!! Form::open(array('id' => 'createTaskForm')) !!}
				@if($task)
				<div class="popupContentTitle">
					{!! Form::hidden('id', $task->id) !!}
					{!! Form::text('title', $task->title,['placeholder'=>'Task title']) !!}
					<div class="error" id="title_err"></div>
					<p>Assign to </p>
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
					<p>Choose deadline</p> {!! Form::text('dueDate',$task->dueDate,['class'=>'dateInput']) !!}
					<div class="error" id="dueDate_err"></div>
			</div>
			<div class="popupContentText">
				{!! Form::textarea('description', $task->description,['rows'=>'10','cols'=>'30'])!!}
				<div class="error" id="description_err"></div>
			</div>
			<div class="popupContentText">
				{!! Form::textarea('notes', $task->notes,['rows'=>'10','cols'=>'30'])!!}
				<div class="error" id="notes_err"></div>
			</div>
			@else
				<div class="popupContentTitle">
					{!! Form::text('title', '',['placeholder'=>'Task title']) !!}
					<div class="error" id="title_err"></div>
					<p>Assign to </p>
					<div id="selected_Assignee">
					</div>
					{!! Form::text('assignee','',['id'=>'selectAssignee','placeholder'=>'search user']) !!}
					<div class="error" id="assignee_err"></div>
					<p>Choose deadline</p> {!! Form::text('dueDate','',['class'=>'dateInput']) !!}
					<div class="error" id="dueDate_err"></div>
			</div>
			<div class="popupContentText">
				{!! Form::textarea('description', '',['rows'=>'10','cols'=>'30'])!!}
				<div class="error" id="description_err"></div>
			</div>
			<div class="popupContentText">
				{!! Form::textarea('notes', '',['rows'=>'10','cols'=>'30'])!!}
				<div class="error" id="notes_err"></div>
			</div>
			@endif
			{!! Form::close() !!}
			<div class="popupButtons">
				<button id="createTaskSave" type="button" class="btn btn-primary">Save Draft</button>
				<button id="createTaskSubmit" type="button" class="btn btn-primary">Send</button>
			</div>
		</div>
		<!-- =================== Popup right ====================  -->
		<div class="popupContentRight">
			<div class="popupSearchSection">
				
			</div>
			<!-- ================= Comment/chat section ====================  -->
			<div class="chatSection">
				<div class="chatContent">
					
					<div class="clearboth"></div>
				</div>
				<!-- ================= Chat input area fixed to bottom  ====================  -->
				<div class="chatInput">
					<textarea name="" id=""  rows="3" placeholder="Type comment here"></textarea>
					<input type="button" value="Submit">
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