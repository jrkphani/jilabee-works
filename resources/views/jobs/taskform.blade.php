{!! Form::hidden('_token', csrf_token(),['id'=>'_token']) !!}
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
	@if($task->assignee)
		<?php $display='display:none;'; ?>
			@if(isEmail($task->assignee))
					<div class="assignee">
							{{$task->assignee}}
						<span class="removeParent btn glyphicon glyphicon-trash"></span>
					</div>
				@else
					<div id="u{{$task->assignee}}" class="assignee">
						<input type="hidden" value="{{$task->assignee}}" name="assignee">
							{{$task->assigneeDetail->name}}
						<span class="removeParent btn glyphicon glyphicon-trash"></span>
					</div>
				@endif
		
	@else
		<?php $display=''; ?>
	@endif
	</div>
	{!! Form::text('selectAssignee','',['class'=>'form-control','id'=>'selectAssignee','placeholder'=>'search user','style'=>$display]) !!}
	<span class="or" >OR</span>
	{!! Form::text('assigneeEmail',isEmail($task->assignee),['class'=>'form-control','id'=>'assigneeEmail','placeholder'=>'add email','style'=>$display])!!}
	<div id="assignee_err" class="error"></div>
	<div id="assigneeEmail_err" class="error"></div>
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
	{!! Form::text('selectAssignee','',['class'=>'form-control','id'=>'selectAssignee','placeholder'=>'search user']) !!}
	<span class="or" >OR</span>
	{!! Form::text('assigneeEmail', '',['class'=>'form-control','id'=>'assigneeEmail','placeholder'=>'add email'])!!}
	<div id="assignee_err" class="error"></div>
	<div id="assigneeEmail_err" class="error"></div>

	{!! Form::label('dueDate', 'deadline',['class'=>'control-label']) !!}
	{!! Form::text('dueDate','',['class'=>'form-control dateInput']) !!}
	<div id="dueDate_err" class="error"></div>
</div>
@endif
<script type="text/javascript">
 $( "#selectAssignee" ).autocomplete({
            source: "/user/search",
            minLength: 2,
            select: function( event, ui ) {
                if($("#u" + ui.item.userId).length != 0)
                {
                  alert('User already exist!');
                  return false;
                }
                else
                {
                    insert = '<div class="assignee" id="u'+ui.item.userId+'"><input type="hidden" name="assignee" value="'+ui.item.userId+'">'+ui.item.value+'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
                    $('#selected_Assignee').append(insert);
                    $(this).val("");
                    $('#selectAssignee , #assigneeEmail, .or').hide();
                    return false;
                }
                
            }
            });
dateInput();
</script>