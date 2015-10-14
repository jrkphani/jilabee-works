{!! Form::open(array('class'=>'form-horizontal','id'=>'tasksAddForm', 'method'=>'POST','role'=>'form')) !!}
<div id="taskAddBlock" class="minuteItem">
	@if($minute)

{{-- Previous Minutes Will Be Here --}}
	@if($lastFiledMinute = App\Model\Minutes::where('meetingId','=',$meeting->id)->where('filed','=','1')->orderBy('startDate', 'DESC')->limit(1)->first())
		@if($lastFiledMinute)	
				@include('meetings.previousMinute',['lastFiledMinute'=>$lastFiledMinute])
		@endif
	@endif
	<h5>New Minutes</h5>
		<?php
		if($minute->tasks->count())
		{
			// unfiled minutes
			foreach($minute->tasks as $task)
			{ ?>
			<div class="notfiledTaskBlock taskDiv" tid="{{$task->id}}">
				{!! Form::hidden('tid[]', $task->id)!!}
				{!! Form::hidden('type[]', 'task')!!}
				<div>
					<span class="removeTask removeMoreBtn"></span>
					{!! Form::select('',['task'=>'Task'],'',array('class'=>'type','autocomplete'=>'off','disabled')) !!}
					<div class="clearboth"></div>
				</div>
				<div class="minuteItemNumber">
					
				</div>
				<div class="minuteItemLeft">
					<h5>{!! Form::text('title[]',$task->title,array('placeholder'=>'Title','autocomplete'=>'off','class'=>'clearVal')) !!}</h5>
					<p>{!! Form::textarea('description[]',str_ireplace(["<br />","<br>","<br/>"], "\r\n", $task->description),array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5,'class'=>'clearVal')) !!}</p>
				</div>
				<div class="minuteItemRight">
					{{--<p>
						{!! Form::select('assigner[]',array(''=>'Assinger')+$attendees,$task->assigner,array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
					</p>--}}
					<p>
					<?php if(isEmail($task->assignee))
					{
						$taskassignee = $task->assignee;
					}
					else
					{
						$taskassignee = getUser(['id'=>$task->assignee])->userId;
					}
					?>
						{!! Form::select('assignee[]',array(''=>'Assingee')+$attendees+$attendeesEmail+$absentees+$emails,$taskassignee,array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
					</p>
					<p>{!! Form::text('dueDate[]',$task->dueDate,array('class'=>"nextDateInput taskinput clearVal",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}</p>
					<p>{!! Form::select('orginator[]',array(''=>'Orginator'),'',array('style'=>'display:none;')) !!}</p>
				</div>
				<div class="clearboth"></div>
			</div>

		<?php }

		// unfiled ideas
			foreach($minute->ideas as $idea)
			{ ?>
			<div class="notfiledTaskBlock taskDiv" tid="{{$idea->id}}">
				{!! Form::hidden('tid[]', $idea->id)!!}
				{!! Form::hidden('type[]', 'idea')!!}
				<div>
					<span class="removeIdea removeMoreBtn"></span>
					{!! Form::select('',['idea'=>'Idea'],'',array('class'=>'type','autocomplete'=>'off','disabled')) !!}
					<div class="clearboth"></div>
				</div>
				<div class="minuteItemNumber">
					
				</div>
				<div class="minuteItemLeft">
					<h5>{!! Form::text('title[]',$idea->title,array('placeholder'=>'Title','autocomplete'=>'off','class'=>'clearVal')) !!}</h5>
					<p>{!! Form::textarea('description[]',str_ireplace(["<br />","<br>","<br/>"], "\r\n", $idea->description),array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5,'class'=>'clearVal')) !!}</p>
				</div>
				<div class="minuteItemRight">
					<p>
						{!! Form::select('assignee[]',array(''=>'Assinger'),'',array('style'=>'display:none;')) !!}
					</p>
					<p>{!! Form::text('dueDate[]','',array('style'=>'display:none;')) !!}</p>
					<p>{!! Form::select('orginator[]',array(''=>'Orginator')+$attendees+$attendeesEmail+$absentees+$emails,$idea->orginator,array('autocomplete'=>'off','class'=>'clearVal ideainput')) !!}</p>
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
			<div class="taskBlock taskDiv">
				{!! Form::hidden('tid[]', NULL)!!}
				<div>
					<span class="removeTaskFrom removeMoreBtn"></span>
					{!! Form::select('type[]',['task'=>'Task','idea'=>'Idea'],'',array('class'=>'type','autocomplete'=>'off')) !!}
					<div class="clearboth"></div>
				</div>
				<div class="minuteItemNumber">
					
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
					{{--<p>
						{!! Form::select('assigner[]',array(''=>'Assinger')+$attendees,$draft->assigner,array('autocomplete'=>'off','class'=>'taskinput clearVal','style'=>$taskdisplay)) !!}
					</p>--}}
					<p>
						{!! Form::select('assignee[]',array(''=>'Assingee')+$attendees+$attendeesEmail+$absentees+$emails,$draft->assignee,array('autocomplete'=>'off','class'=>'taskinput clearVal','style'=>$taskdisplay)) !!}
					</p>
					<p>{!! Form::text('dueDate[]',$draft->dueDate,array('class'=>"nextDateInput taskinput clearVal",'placeholder'=>'y-m-d','autocomplete'=>'off','style'=>$taskdisplay)) !!}</p>
					<p>{!! Form::select('orginator[]',array(''=>'Orginator')+$attendees+$attendeesEmail+$absentees+$emails,$draft->orginator,array('autocomplete'=>'off','class'=>'clearVal ideainput','style'=>$display)) !!}</p>
					<p>Draft</p>
				</div>
				<div class="clearboth"></div>
			</div>
		<?php
			}
		}
		?>	
	@endif
	<div class="taskBlock taskDiv">
		{!! Form::hidden('tid[]', NULL)!!}
		<div>
			<span class="removeTaskFrom removeMoreBtn"></span>
			{!! Form::select('type[]',['task'=>'Task','idea'=>'Idea'],'',array('class'=>'type','autocomplete'=>'off')) !!}
			<div class="clearboth"></div>
		</div>
		<div class="minuteItemNumber">
			
		</div>
		<div class="minuteItemLeft">
			<h5>{!! Form::text('title[]','',array('placeholder'=>'Title','autocomplete'=>'off','class'=>'clearVal')) !!}</h5>
			<p>{!! Form::textarea('description[]','',array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5,'class'=>'clearVal')) !!}</p>
		</div>
		<div class="minuteItemRight">
			{{--<p>
				{!! Form::select('assigner[]',array(''=>'Assinger')+$attendees,'',array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
			</p>--}}
			<p>
				{!! Form::select('assignee[]',array(''=>'Assingee')+$attendees+$attendeesEmail+$absentees+$emails,'',array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
			</p>
			<p>{!! Form::text('dueDate[]','',array('class'=>"nextDateInput taskinput clearVal",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}</p>
			<p>{!! Form::select('orginator[]',array(''=>'Orginator')+$attendees+$attendeesEmail+$absentees+$emails,'',array('autocomplete'=>'off','class'=>'clearVal ideainput','style'=>'display:none;')) !!}</p>
			<p>Draft</p>
		</div>
		<div class="clearboth"></div>
	</div>
</div>
{!! Form::close() !!}
<br/>
<p>
	<div id="createTaskError">	</div>
	<button id="send_minute" mid="{{$minute->id}}" type="submit" class="">
		@if($minute->tasks->count())
			Update minutes
		@else
			Send minutes
		@endif
	</button>
	{{--@if(!$minute->tasks->count()) --}}
	<button id="save_changes"  mid="{{$minute->id}}" type="submit">Save Draft</button>
	{{--@endif --}}
	<button id="add_more" mid="{{$minute->id}}" type="submit">Add more</button>
</p>