{!! Form::open(array('class'=>'form-horizontal','id'=>'tasksAddForm', 'method'=>'POST','role'=>'form')) !!}
<div id="taskAddBlock" class="minuteItem">
	@if($minute)

{{-- Previous Minutes Will Be Here --}}
	@if($previousMinute = App\Model\Minutes::where('meetingId','=',$meeting->id)->where('filed','=','1')->orderBy('startDate', 'DESC')->limit(1)->first())
		@if($previousMinute)	
				@include('meetings.previousMinute',['minute'=>$previousMinute])
		@endif
	@endif

		<?php
		if($minute->tasks->count())
		{
			//echo "yet to finsh edit minute task"; die;
			foreach($minute->tasks()->get() as $task)
			{ ?>
			Unfiled Minutes
			<div class="notfiledTaskBlock">
				{!! Form::hidden('tid[]', $task->id)!!}
				{!! Form::hidden('type[]', 'task')!!}
				<div>
					<span class="removeTaskFrom removeMoreBtn"></span>
					{!! Form::select('',['task'=>'Task','idea'=>'Idea'],'',array('class'=>'type','autocomplete'=>'off','disabled')) !!}
					<div class="clearboth"></div>
				</div>
				<div class="minuteItemNumber">
					<p>1</p>
				</div>
				<div class="minuteItemLeft">
					<h5>{!! Form::text('title[]',$task->title,array('placeholder'=>'Title','autocomplete'=>'off','class'=>'clearVal')) !!}</h5>
					<p>{!! Form::textarea('description[]',$task->description,array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5,'class'=>'clearVal')) !!}</p>
				</div>
				<div class="minuteItemRight">
					<p>
						{!! Form::select('assigner[]',array(''=>'Assinger')+$attendees,$task->assigner,array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
					</p>
					<p>
						{!! Form::select('assignee[]',array(''=>'Assingee')+$attendees,$task->assignee,array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
					</p>
					<p>{!! Form::text('dueDate[]',$task->dueDate,array('class'=>"dateInputNext taskinput dateInput clearVal",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}</p>
					<p>{!! Form::select('orginator[]',array(''=>'Orginator')+$attendees,'',array('autocomplete'=>'off','class'=>'clearVal ideainput','style'=>'display:none;')) !!}</p>
				</div>
				<div class="clearboth"></div>
			</div>
		<?php }
		}
		else
		{
			//load task from draft if any
			foreach($minute->draft()->get() as $draft)
			{
		?>
			Draft Minutes
			<div class="taskBlock">
				{!! Form::hidden('tid[]', NULL)!!}
				<div>
					<span class="removeTaskFrom removeMoreBtn"></span>
					{!! Form::select('type[]',['task'=>'Task','idea'=>'Idea'],'',array('class'=>'type','autocomplete'=>'off')) !!}
					<div class="clearboth"></div>
				</div>
				<div class="minuteItemNumber">
					<p>1</p>
				</div>
				<div class="minuteItemLeft">
					<h5>{!! Form::text('title[]',$draft->title,array('placeholder'=>'Title','autocomplete'=>'off','class'=>'clearVal')) !!}</h5>
					<p>{!! Form::textarea('description[]',$draft->description,array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5,'class'=>'clearVal')) !!}</p>
				</div>
				<div class="minuteItemRight">
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
					<p>
						{!! Form::select('assigner[]',array(''=>'Assinger')+$attendees,$draft->assigner,array('autocomplete'=>'off','class'=>'taskinput clearVal','style'=>$taskdisplay)) !!}
					</p>
					<p>
						{!! Form::select('assignee[]',array(''=>'Assingee')+$attendees,$draft->assignee,array('autocomplete'=>'off','class'=>'taskinput clearVal','style'=>$taskdisplay)) !!}
					</p>
					<p>{!! Form::text('dueDate[]',$draft->dueDate,array('class'=>"dateInputNext dateInput taskinput clearVal",'placeholder'=>'y-m-d','autocomplete'=>'off','style'=>$taskdisplay)) !!}</p>
					<p>{!! Form::select('orginator[]',array(''=>'Orginator')+$attendees,$draft->orginator,array('autocomplete'=>'off','class'=>'clearVal ideainput','style'=>$display)) !!}</p>
					<p>Draft</p>
				</div>
				<div class="clearboth"></div>
			</div>
		<?php
			}
		}
		?>	
	@endif
	<div class="taskBlock">
		{!! Form::hidden('tid[]', NULL)!!}
		<div>
			<span class="removeTaskFrom removeMoreBtn"></span>
			{!! Form::select('type[]',['task'=>'Task','idea'=>'Idea'],'',array('class'=>'type','autocomplete'=>'off')) !!}
			<div class="clearboth"></div>
		</div>
		<div class="minuteItemNumber">
			<p>1</p>
		</div>
		<div class="minuteItemLeft">
			<h5>{!! Form::text('title[]','',array('placeholder'=>'Title','autocomplete'=>'off','class'=>'clearVal')) !!}</h5>
			<p>{!! Form::textarea('description[]','',array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5,'class'=>'clearVal')) !!}</p>
		</div>
		<div class="minuteItemRight">
			<p>
				{!! Form::select('assigner[]',array(''=>'Assinger')+$attendees,'',array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
			</p>
			<p>
				{!! Form::select('assignee[]',array(''=>'Assingee')+$attendees,'',array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
			</p>
			<p>{!! Form::text('dueDate[]','',array('class'=>"dateInputNext taskinput clearVal dateInput",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}</p>
			<p>{!! Form::select('orginator[]',array(''=>'Orginator')+$attendees,'',array('autocomplete'=>'off','class'=>'clearVal ideainput','style'=>'display:none;')) !!}</p>
			<p>Draft</p>
		</div>
		<div class="clearboth"></div>
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
