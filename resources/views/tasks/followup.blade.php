<div class="row">
<div class="col-md-12">
		
    	<div class="col-md-4 nopadding">
	  		{!! Form::select('followup_filter', ['duedate'=>'Timeline','meeting'=>'Meeting','assignee'=>'Assignee'], $input['sortby'], ['id'=>'followup_filter','autocomplete'=>'off','class'=>'form-control'])!!}
    	</div>
    	<div class="col-md-8 ">
    		<div class="col-md-10 nopadding">
    			<input type="text" class="form-control" value="{{$input['search']}}"id="folloupSearchInput" placeholder="Search ">
    		</div>
    		<div class="col-md-2 nopadding">
    			<span class="glyphicon glyphicon-search btn btn-primary" id="folloupSearch"></span>
    		</div>
    	</div>
</div>
</div>
@if($followup->first())
@if($input['sortby'] == 'duedate' || !($input['sortby']))
	@foreach($followup as $task)
		<?php
	        if(date('Y-m-d',strtotime($task->due)) == date('Y-m-d'))
	        {
	        	$taskArr["Today"][] = $task;
	        }
	        else if(date('Y-m-d',strtotime($task->due)) < date('Y-m-d'))
	        {
	        	if(date('W',strtotime($task->due)) == date('W'))
		        {
		        	$taskArr["This week"][] = $task;
		        }
		        else
		        {
		        	if(date('W',strtotime($task->due)) == date('W')-1)
		        	{
		        		$taskArr["Last week"][] = $task;	
		        	}
		        	else
		        	{
		        		$taskArr["Previous"][] = $task;	
		        	}
		        }

	        }
	        else if(date('Y-m-d',strtotime($task->due)) > date('Y-m-d'))
	        {
	        	if(date('W',strtotime($task->due)) == date('W'))
		        {
		        	$taskArr["This week"][] = $task;
		        }
		        else
		        {
		        	if(date('W',strtotime($task->due)) == date('W')+1)
		        	{
		        		$taskArr["Next week"][] = $task;
		        	}
		        	else
		        	{
		        		$taskArr["Upcoming"][] = $task;
		        	}
		        }
	        }
		?>
	@endforeach
@elseif($input['sortby'] == 'assignee')
	@foreach($followup as $task)
		<?php
			$taskArr[$task->getassignee->name][] = $task;
		?>
	@endforeach
@elseif($input['sortby'] == 'meeting')
	@foreach($followup as $task)
		<?php
	        $taskArr[$task->minute->meeting->title][] = $task;
		?>
	@endforeach
@endif

<div class="row scroll_horizontal">
	@foreach($taskArr as $key=>$taskrow)
		<div class="col-md-12 border_bottom margin_top_10">
			<strong>{{$key}}</strong>
		</div>
		@foreach($taskrow as $taskcol)
			<a class="col-md-12 nopadding followup" id="task{{$taskcol->id }}" href="#followup#task{{$taskcol->id}}">
				<div class="col-md-9">
					{{$taskcol->title}}
				</div>
				<div class="col-md-3">
					{{ date("d M",strtotime($taskcol->due)) }}
				</div>
			</a>
		@endforeach
	@endforeach
</div>
<?php echo $followup->appends($input)->render(); ?>
@else
	No data to display!
@endif
