<div class="popupWindow">
	<div class="popupHeader">
		<h2><a href="">Jobs</a> / <a href="">New Tasks</a></h2>
		<button  onclick="$('#popup').hide();" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>	
	<div class="popupContent">
		<!--======================== Popup content starts here ===============================-->
		{!! Form::open(array('id' => 'createTaskForm')) !!}
				@if($task)

			<!-- =================== Job details ====================  -->
			
				<div class="popupContentTitle pcl_jobs">
					<label  class="pcl_jobs_label">Task Title</label>
					{!! Form::hidden('id', $task->id) !!}
					{!! Form::text('title', $task->title,['placeholder'=>'Task title']) !!}
					<div class="error" id="title_err"></div>
					<label  class="pcl_jobs_label">Assign to </label>
					<div class="pcl_jobs_assignee" id="selected_Assignee">
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
					<div class="clearboth"></div>
					<div class="error" id="assignee_err"></div>
					<label class="pcl_jobs_label">Client Email </label>
					{!! Form::text('clientEmail',$task->clientEmail,['id'=>'clientEmail','placeholder'=>'email']) !!}
					<div class="clearboth"></div>
					<label  class="pcl_jobs_label">Choose deadline</label> {!! Form::text('dueDate',$task->dueDate,['class'=>'nextDateInput']) !!}
					<div class="error" id="dueDate_err"></div>
			</div>
			<div class="popupContentLeft">
				<div class="popupContentText">
					{!! Form::textarea('description', $task->description,['rows'=>'6','cols'=>'30','placeholder'=>'description'])!!}
					<div class="error" id="description_err"></div>
				</div>
				<div class="popupContentText">
					{!! Form::textarea('notes', $task->notes,['rows'=>'6','cols'=>'30','placeholder'=>'notes'])!!}
					<div class="error" id="notes_err"></div>
				</div>
				
			</div>
				<div class="popupButtons popupButtonsFix">
					<button id="createTaskSave" >Save Draft</button>
					<button id="createTaskSubmit" >Send</button>
					<button id="deleteDraft" tid="{{$task->id}}" >Discard Draft</button>
				</div>
			@else
				<div class="popupContentTitle">
					<label class="pcl_jobs_label">Task Title </label>
					{!! Form::text('title', '',['placeholder'=>'Task title']) !!}
					<div class="error" id="title_err"></div>

					<label class="pcl_jobs_label">Assign to </label>
					<div class="pcl_jobs_assignee" id="selected_Assignee">
					</div>
					
					{!! Form::text('assignee','',['id'=>'selectAssignee','placeholder'=>'email']) !!}
					<div class="error" id="assignee_err"></div>
					<div class="clearboth"></div>
					<label class="pcl_jobs_label">Client Email </label>
					{!! Form::text('clientEmail','',['id'=>'clientEmail','placeholder'=>'email']) !!}
					<div class="clearboth"></div>
					<label class="pcl_jobs_label">Choose deadline</label> {!! Form::text('dueDate','',['class'=>'nextDateInput']) !!}
					<div class="error" id="dueDate_err"></div>
			</div>
			<div class="popupContentLeft">
				<div class="popupContentText">
					{!! Form::textarea('description', '',['rows'=>'6','cols'=>'30','placeholder'=>'description'])!!}
					<div class="error" id="description_err"></div>
				</div>
				<div class="popupContentText">
					{!! Form::textarea('notes', '',['rows'=>'6','cols'=>'30','placeholder'=>'notes'])!!}
					<div class="error" id="notes_err"></div>
				</div>
				
			</div>
				<div class="popupButtons popupButtonsFix">
					<button id="createTaskSave" >Save Draft</button>
					<button id="createTaskSubmit" >Send</button>
				</div>
		@endif
			{!! Form::close() !!}
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
				
			</div>
			<div class="chatInput chatInput_1row">
					<textarea name="" id=""  rows="2" disabled="disabled" placeholder="Type comment here"></textarea>
					<!-- <input type="button" value="Submit"> -->
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
nextDateInput();
</script>