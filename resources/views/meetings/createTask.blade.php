<div class="pull-right" Id="editMinute">edit</div>
<div class="col-md-12">Today Minutes</div>
<div class="col-md-12">ID#{{$minute->meetingId}} </div>
{!! Form::open(array('id' => 'updateMinuteForm')) !!}
{!! Form::hidden('meetingId', $minute->meetingId,['id'=>'meetingId'])!!}
{!! Form::text('venue',$minute->venue,['class'=>'updateMinute','disabled'=>'disabled']) !!}
{!! Form::text('minuteDate',$minute->minuteDate,['class'=>'updateMinute','disabled'=>'disabled']) !!}
<div class="col-md-12"><strong>Attendees</strong></div>
<div class="col-md-12" Id="attendees">
	<?php 
	if($minute->attendees)
	{
		$attendees = explode(',',$minute->attendees);
		$users = App\Model\Profile::select('userId','name')->whereIn('userId',$attendees)->get();
		foreach ($users as $user)
		{
			echo '<div class="col-md-2 attendees" uid="u'.$user->userId.'"><input type="hidden" name="attendees[]" value="'.$user->userId.'">'.$user->name.'<span class="removeAttendees btn glyphicon glyphicon-trash" style="display:none"></span></div>';
		}
	}
	?>
</div>
<div class="col-md-12"><strong>Absentees</strong></div>
<div class="col-md-12" Id="absentees">
	<?php 
	if($minute->absentees)
	{
		$absentees = explode(',',$minute->absentees);
		$users = App\Model\Profile::select('userId','name')->whereIn('userId',$absentees)->get();
		foreach ($users as $user)
		{
			echo '<div class="col-md-2 absentees" uid="u'.$user->userId.'">'.$user->name.'<span class="removeAbsentees btn glyphicon glyphicon-trash" style="display:none"></span></div>';
		}
	}
	?>
</div>
{!! Form::close() !!}
<button id="updateMinute" mid="{{$minute->id}}" type="button" class="btn btn-primary" style="display:none">update</button>
<button id="canleMinute" type="button" class="btn btn-primary" style="display:none">cancel</button>
<div class="col-md-12" id="updateMinuteError"></div>
{!! Form::open(array('class'=>'form-horizontal','id'=>'tasksAddForm', 'method'=>'POST','role'=>'form')) !!}
<div class="col-md-12" id="taskAddBlock">
	{{-- include draft --}}
	@if($drafts = $minute->draft()->get())
		@foreach($drafts as $draft)
			<div class="row taskBlock">
				<div class="pull-right"><span class="removeTaskFrom btn glyphicon glyphicon-trash"></span></div>
				<div class="col-md-12">
					<div class="form-group">
						<div class="col-md-2">
							{!! Form::select('type[]',['task'=>'Task','idea'=>'Idea'],$draft->type,array('class'=>"form-control type",'autocomplete'=>'off')) !!}
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<div class="col-md-12">
							{!! Form::text('title[]',$draft->title,array('class'=>"form-control",'placeholder'=>'Title','autocomplete'=>'off')) !!}
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12">
							{!! Form::textarea('description[]',$draft->description,array('class'=>"form-control",'placeholder'=>'Description','autocomplete'=>'off','rows'=>5)) !!}
						</div>
					</div>
				</div>
				<div class="col-md-12">				
					<div class="col-md-12 form-group">
						<div class="col-md-4">
							{!! Form::select('assigner[]',array(''=>'Assinger')+$usersList,$draft->assigner,array('class'=>"form-control",'autocomplete'=>'off')) !!}
							
						</div>
						<div class="taskinput col-md-4">
							{!! Form::select('assignee[]',array(''=>'Assingee')+$usersList,$draft->assignee,array('class'=>"form-control",'autocomplete'=>'off')) !!}
							
						</div>
						<div class="col-md-4">
							{!! Form::text('dueDate[]',$draft->dueDate,array('class'=>"form-control dateInput",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}
						</div>
					</div>
				</div>
			</div>
		@endforeach
	@endif
	{{-- empty add task form --}}
	<div class="row taskBlock">
		<div class="pull-right"><span class="removeTaskFrom btn glyphicon glyphicon-trash"></span></div>
		<div class="col-md-12">
			<div class="form-group">
				<div class="col-md-2">
					{!! Form::select('type[]',['task'=>'Task','idea'=>'Idea'],'',array('class'=>"form-control type",'autocomplete'=>'off')) !!}
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group">
				<div class="col-md-12">
					{!! Form::text('title[]','',array('class'=>"form-control",'placeholder'=>'Title','autocomplete'=>'off')) !!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12">
					{!! Form::textarea('description[]','',array('class'=>"form-control",'placeholder'=>'Description','autocomplete'=>'off','rows'=>5)) !!}
				</div>
			</div>
		</div>
		<div class="col-md-12">				
			<div class="col-md-12 form-group">
				<div class="col-md-4">
					{!! Form::select('assigner[]',array(''=>'Assinger')+$usersList,'',array('class'=>"form-control",'autocomplete'=>'off')) !!}
					
				</div>
				<div class="taskinput col-md-4">
					{!! Form::select('assignee[]',array(''=>'Assingee')+$usersList,'',array('class'=>"form-control",'autocomplete'=>'off')) !!}
					
				</div>
				<div class="col-md-4">
					{!! Form::text('dueDate[]','',array('class'=>"form-control dateInput",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}
				</div>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
<div class="row">
	<div class="col-md-12" id="createTaskError">
	</div>
	<div class="col-md-6">
		<button id="send_minute" mid="{{$minute->id}}" type="submit" class="btn btn-primary">Send minutes</button>
	</div>
	<div class="col-md-4">
		<button id="save_changes" mid="{{$minute->id}}" type="submit" class="btn btn-primary">Save Draft</button>
	</div>
	<div class="col-md-2">
		<span id="add_more" type="submit" class="btn btn-primary pull-right">Add more</span>
	</div>
</div>
<script type="text/javascript">
dateInput();
</script>