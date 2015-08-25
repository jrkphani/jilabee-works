{!! Form::open(array('class'=>'form-horizontal','id'=>'tasksAddForm', 'method'=>'POST','role'=>'form')) !!}
<div id="taskAddBlock">
	@if($minute)

{{-- Previous Minutes Will Be Here --}}
	@if($previousMinute = App\Model\Minutes::where('meetingId','=',$meeting->id)->where('filed','=','1')->orderBy('startDate', 'DESC')->limit(1)->first())
		@if($previousMinute)
			<div class ="row">
				<p><strong>Previous Minutes</strong></p>
				@include('meetings.previousMinute',['minute'=>$previousMinute])
			</div>
		@endif
	@endif


New Minutes
		<?php
		if($minute->tasks->count())
		{
			//echo "yet to finsh edit minute task"; die;
			foreach($minute->tasks()->get() as $task)
			{ ?>
			<div class="taskBlock">
				{!! Form::hidden('tid[]', $task->id)!!}
				{!! Form::hidden('type[]', 'task')!!}
				<p>{!! Form::text('title[]',$task->title,array('placeholder'=>'Title','autocomplete'=>'off','class'=>'clearVal')) !!}

				{!! Form::textarea('description[]',$task->description,array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5,'class'=>'clearVal')) !!}</p>
				
				<p>{!! Form::select('assigner[]',array(''=>'Assinger')+$attendees,$task->assigner,array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
			
				{!! Form::select('assignee[]',array(''=>'Assingee')+$attendees,$task->assignee,array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}

				{!! Form::text('dueDate[]',$task->dueDate,array('class'=>"dateInputNext taskinput clearVal",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}</p>
			</div>
		<?php }
		}
		else
		{
			//load task from draft if any
			foreach($minute->draft()->get() as $draft)
			{
		?>
			<div class="taskBlock">
				{!! Form::hidden('tid[]', NULL)!!}
				<div class="pull-right"><span class="removeTaskFrom">Remove</span></div>
				<p>{!! Form::select('type[]',['task'=>'Task','idea'=>'Idea'],$draft->type,array('class'=>'type','autocomplete'=>'off')) !!}
				Status
				{!! Form::select('',[''=>'Draft'],'',array('disabled'=>'disabled')) !!}</p>

				<p>{!! Form::text('title[]',$draft->title,array('placeholder'=>'Title','autocomplete'=>'off','class'=>'clearVal')) !!}

				{!! Form::textarea('description[]',$draft->description,array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5,'class'=>'clearVal')) !!}</p>
				<?php if($draft->type == 'task')
						{
							$display = 'display:none;';
							$taskdisplay = '';
						}
						else
						{
							$display = '';
							$taskdisplay = 'display:none;';
						}
				?>
				<p>{!! Form::select('assigner[]',array(''=>'Assinger')+$attendees,$draft->assigner,array('autocomplete'=>'off','class'=>'taskinput clearVal','style'=>$taskdisplay)) !!}
			
				{!! Form::select('assignee[]',array(''=>'Assingee')+$attendees,$draft->assignee,array('autocomplete'=>'off','class'=>'taskinput clearVal','style'=>$taskdisplay)) !!}

				{!! Form::text('dueDate[]',$draft->dueDate,array('class'=>"dateInputNext taskinput clearVal",'placeholder'=>'y-m-d','autocomplete'=>'off','style'=>$taskdisplay)) !!}
				{!! Form::select('orginator[]',array(''=>'Orginator')+$attendees,$draft->orginator,array('autocomplete'=>'off','class'=>'clearVal ideainput','style'=>$display)) !!}</p>
			</div>
		<?php
			}
		}
		?>	
	@endif
	<div class="taskBlock">
		{!! Form::hidden('tid[]', NULL)!!}
		<div class="pull-right"><span class="removeTaskFrom">Remove</span></div>
		<p>{!! Form::select('type[]',['task'=>'Task','idea'=>'Idea'],'',array('class'=>'type','autocomplete'=>'off')) !!}
		Status
		{!! Form::select('',[''=>'Draft'],'',array('disabled'=>'disabled')) !!}</p>

		<p>{!! Form::text('title[]','',array('placeholder'=>'Title','autocomplete'=>'off','class'=>'clearVal')) !!}

		{!! Form::textarea('description[]','',array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5,'class'=>'clearVal')) !!}</p>

		<p>{!! Form::select('assigner[]',array(''=>'Assinger')+$attendees,'',array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
	
		{!! Form::select('assignee[]',array(''=>'Assingee')+$attendees,'',array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}

		{!! Form::text('dueDate[]','',array('class'=>"dateInputNext taskinput clearVal",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}

		{!! Form::select('orginator[]',array(''=>'Orginator')+$attendees,'',array('autocomplete'=>'off','class'=>'clearVal ideainput','style'=>'display:none;')) !!}</p>
	</div>
</div>
{!! Form::close() !!}
<p>
	<div id="createTaskError">	</div>
	<button id="send_minute" mid="{{$minute->id}}" type="submit" class="btn btn-primary">
		@if($minute->tasks->count())
			Update minutes
		@else
			Send minutes
		@endif
	</button>
	{{--@if(!$minute->tasks->count()) --}}
	<button id="save_changes"  mid="{{$minute->id}}" type="submit" class="btn btn-primary">Save Draft</button>
	{{--@endif --}}
	<button id="add_more" mid="{{$minute->id}}" type="submit" class="btn btn-primary pull-right">Add more</button>
</p>
