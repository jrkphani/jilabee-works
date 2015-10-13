<h5>Review of old Minutes</h5>
<?php
	$minutes = App\Model\MinuteTasks::select('minuteTasks.*')->WhereIn('minuteTasks.id',function($query) use ($lastFiledMinute){
								$query->select('taskId')
		                    		->from('filedMinutes')
		                       		->where('filedMinutes.status','!=','Closed')
		                       		->where('filedMinutes.status','!=','Cancelled')
		                       		->where('filedMinutes.minuteId','=',$lastFiledMinute->id);
							})->get();
	// $attendeesEmailArr=$attendeesArr=array();
	// if($lastFiledMinute->attendees)
	// {
	// 	foreach(explode(',',$lastFiledMinute->attendees) as $value)
	// 	{
	// 		if(isEmail($value))
	// 		{
	// 			$attendeesEmailArr[$value] = $value;
	// 		}
	// 		else
	// 		{
	// 			$participants[]=$value;
	// 		}
	// 	}
	// }
	// $attendeesArr =  App\Model\Profile::select('profiles.name','users.userId')->whereIn('profiles.userId',$participants)
	// 		->join('users','profiles.userId','=','users.id')
	// 		->lists('profiles.name','users.userId');
	// 		$emailsArr = $absenteesArr = array();
	// 			 if($lastFiledMinute->absentees)
	// 			 {
	// 			 	foreach (explode(',',$lastFiledMinute->absentees) as $value)
	// 			 	{
	// 			 		if(isEmail($value))
	// 			 		{
	// 			 			$emailsArr[$value]=$value;
	// 			 		}
	// 			 		else
	// 			 		{
	// 			 			$absenteesArr[]=$value;
	// 			 		}
	// 			 	}
	// 			 }
	// 			 $absenteesArr = App\Model\Profile::select('profiles.name','users.userId')
	// 			 			->whereIn('profiles.userId',$absenteesArr)
	// 						->join('users','profiles.userId','=','users.id')
	// 						->lists('profiles.name','users.userId');
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
				<h5>{!! Form::text('title[]',$task->title,array('placeholder'=>'Title','autocomplete'=>'off','class'=>'clearVal onchange')) !!}</h5>
				<p>{!! Form::textarea('description[]',$task->description,array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5,'class'=>'clearVal onchange')) !!}</p>
			</div>
			<div class="minuteItemRight">
			{{-- 	<p>
					{!! Form::select('assigner[]',array(''=>'Assinger')+$attendees,$task->assigner,array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
				</p> --}}
				<p>
				<?php
				if(isEmail($task->assignee))
				{
					$assignee = $task->assignee;
					$attendeesArr = [''=>'Assingee',$assignee=>$assignee]+$attendees+$attendeesEmail+$absentees+$emails;
				}
				else
				{
					$getuser = getUser(['id'=>$task->assignee]);
					$assignee=$getuser->userId;
					$attendeesArr = [''=>'Assingee',$assignee=>$getuser->profile->name]+$attendees+$attendeesEmail+$absentees+$emails;
				}
				?>
					{!! Form::select('assignee[]',$attendeesArr,$assignee,array('autocomplete'=>'off','class'=>'taskinput clearVal onchange')) !!}
				</p>
				<p>{!! Form::text('dueDate[]',$task->dueDate,array('class'=>"nextDateInput taskinput dateInput clearVal onchange",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}</p>
				<p>{!! Form::select('status'.$task->id,['Sent'=>'Reopen','Open'=>'Open','Closed'=>'Completed','Cancelled'=>'Cancel'],$task->status,['class'=>'status']) !!}</p>
				<p>{!! Form::select('orginator[]',array(''=>'Orginator'),'',array('style'=>'display:none;')) !!}</p>
			</div>
			<div class="clearboth"></div>
		</div>
	@endforeach
</div>