{!! Form::open(array('id' => 'createTaskForm')) !!}
@if($task)
{!! Form::hidden('id', $task->id) !!}
<div class="col-md-8 form-group">
	{!! Form::label('title', 'title',['class'=>'control-label']); !!}
	{!! Form::text('title', $task->title,['class'=>'form-control'])!!}
	<div id="title_err" class="error"></div>

	{!! Form::label('description', 'description',['class'=>'control-label']); !!}
	{!! Form::textarea('description', $task->description,['class'=>'form-control'])!!}
	<div id="description_err" class="error"></div>
	{!! Form::label('notes', 'notes',['class'=>'control-label']); !!}
	{!! Form::textarea('notes', $task->notes,['class'=>'form-control'])!!}
	<div id="notes_err" class="error"></div>
	</div>
	<div class="col-md-4">
	{!! Form::label('selectAssignee', 'select Assignee',['class'=>'control-label']) !!}
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
					<span class="removeParent btn glyphicon glyphicon-trash"></span>
				</div>
			@endif	
	@else
		<?php $display=''; ?>
	@endif
	</div>
	{!! Form::text('assignee',$assignee,['class'=>'form-control','id'=>'selectAssignee','placeholder'=>'search user','style'=>$display]) !!}
	<div id="assignee_err" class="error"></div>
	{!! Form::label('dueDate', 'deadline',['class'=>'control-label']) !!}
	{!! Form::text('dueDate',$task->dueDate,['class'=>'form-control dateInput']) !!}
	<div id="dueDate_err" class="error"></div>
</div>
@else
<div class="col-md-8 form-group">
	{!! Form::label('title', 'title',['class'=>'control-label']); !!}
	{!! Form::text('title', '',['class'=>'form-control'])!!}
	<div id="title_err" class="error"></div>

	{!! Form::label('description', 'description',['class'=>'control-label']) !!}
	{!! Form::textarea('description', '',['class'=>'form-control'])!!}
	<div id="description_err" class="error"></div>
	{!! Form::label('notes', 'notes',['class'=>'control-label']); !!}
	{!! Form::textarea('notes', '',['class'=>'form-control'])!!}
	<div id="notes_err" class="error"></div>
</div>
<div class="col-md-4">
	{!! Form::label('selectAssignee', 'select Assignee',['class'=>'control-label']) !!}
	<div id="selected_Assignee"></div>
	{!! Form::text('assignee','',['class'=>'form-control','id'=>'selectAssignee','placeholder'=>'search user']) !!}
	<div id="assignee_err" class="error"></div>
	{!! Form::label('dueDate', 'deadline',['class'=>'control-label']) !!}
	{!! Form::text('dueDate','',['class'=>'form-control dateInput']) !!}
	<div id="dueDate_err" class="error"></div>
</div>
@endif
{!! Form::close() !!}
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<button id="createTaskSave" type="button" class="btn btn-primary">Save Draft</button>
<button id="createTaskSubmit" type="button" class="btn btn-primary">Send</button>
<script type="text/javascript">
 $( "#selectAssignee" ).autocomplete({
            source: "/user/search",
            minLength: 2,
            select: function( event, ui ) {
	            insert = '<div class="assignee">'+ui.item.value+'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
	            $('#selected_Assignee').append(insert);
	            $(this).val(ui.item.userId);
	            $('#selectAssignee').hide();
	            return false;
            }
            });
dateInput();
</script>