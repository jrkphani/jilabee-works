Review of old Minutes
<?php
	$minutes = App\Model\MinuteTasks::select('minuteTasks.*')->WhereIn('minuteTasks.id',function($query) use ($lastFiledMinute){
								$query->select('taskId')
		                    		->from('filedMinutes')
		                       		->where('filedMinutes.status','!=','Closed')
		                       		->where('filedMinutes.status','!=','Cancelled')
		                       		->where('filedMinutes.minuteId','=',$lastFiledMinute->id);
							})->get();
?>
<div >	
	{{-- not show closed/canceled task in last meeting --}}
	{{-- @foreach($minute->file()->where('status','!=','Canceled')->where('status','!=','Closed')->get() as $task) --}}
	@foreach($minutes as $task)
		<div class="previousTaskBlock taskDiv">
			{!! Form::hidden('tid[]', $task->id)!!}
			{!! Form::hidden('type[]', 'task')!!}
			<div>
				{!! Form::select('',['task'=>'Task','idea'=>'Idea'],'',array('class'=>'type','autocomplete'=>'off','disabled')) !!}
				<div class="clearboth"></div>
			</div>
			<div class="minuteItemNumber">
		
			</div>
			<div class="minuteItemLeft">
				<h5>{!! Form::text('title[]',$task->title,array('placeholder'=>'Title','autocomplete'=>'off','class'=>'clearVal')) !!}</h5>
				<p>{!! Form::textarea('description[]',$task->description,array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5,'class'=>'clearVal')) !!}</p>
			</div>
			<div class="minuteItemRight">
			{{-- 	<p>
					{!! Form::select('assigner[]',array(''=>'Assinger')+$attendees,$task->assigner,array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
				</p> --}}
				<p>
					{!! Form::select('assignee[]',array(''=>'Assingee')+$attendees+$attendeesEmail,$task->assignee,array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
				</p>
				<p>{!! Form::text('dueDate[]',$task->dueDate,array('class'=>"nextDateInput taskinput dateInput clearVal",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}</p>
				<p>{!! Form::select('orginator[]',array(''=>'Orginator'),'',array('style'=>'display:none;')) !!}</p>
			</div>
			<div class="clearboth"></div>
		</div>
	@endforeach
</div>