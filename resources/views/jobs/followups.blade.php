<div class="row">
	<div class="col-md-6 col-md-offset-6">
		<div class="col-md-6">
			<button type="button" class="btn btn-primary" id="refresh">Refresh</button>
		</div>
		<div class="col-md-6">
			<button id="createTaskToggle" type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTaskModal">
		  Create Task
			</button>
		</div>
	</div>
	<div class="col-md-12">
		<div class="col-md-3">
			@if($jobtask->count())
			<ul>
				@foreach($jobtask as $job)
				<li myid="{{$job->id}}" class="followup">{{$job->title}}</li>
				@endforeach
			</ul>
			@endif
			@if($minutetask->count())
			<ul>
				@foreach($minutetask as $job)
				<li myid="{{$job->id}}" mid="{{$job->minuteId}}" class="followup">{{$job->title}}</li>
				@endforeach
			</ul>
			@endif
		</div>
		<div class="col-md-9" id="rightContent">
			right content
		</div>
	</div>

	<div id="createTaskModal" class="modal fade bs-example-modal-lg">
	  <div class="modal-dialog  modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Create Task</h4>
	      </div>
	      <div class="modal-body">
	        <div class="row">
		        {!! Form::open(array('id' => 'createTaskForm')) !!}
		        <div class="col-md-8 form-group">
		        	{!! Form::label('title', 'title',['class'=>'control-label']); !!}
		        	{!! Form::text('title', '',['class'=>'form-control'])!!}
		        	<div id="title_err" class="error"></div>
		        	
		        	{!! Form::label('description', 'description',['class'=>'control-label']); !!}
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
		        {!! Form::close() !!}
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button id="createTaskSubmit" type="button" class="btn btn-primary">Save</button>
	      </div>
	    </div>

	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>
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