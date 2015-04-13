<div class="row">
<div class="col-md-12">
    	<div class="col-md-4 nopadding">
	  		{!! Form::select('mytask_filter', ['duedate'=>'Timeline','meeting'=>'Meeting','assigner'=>'Assigner'], $input['sortby'], ['id'=>'mytask_filter','autocomplete'=>'off','class'=>'form-control'])!!}
    	</div>
    	<div class="col-md-8 ">
    		<div class="col-md-10 nopadding">
    			<input type="text" value="{{$input['search']}}" class="form-control" id="myTaskSearchInput" placeholder="Search ">
    		</div>
    		<div class="col-md-2 nopadding">
    			<span class="glyphicon glyphicon-search btn btn-primary" id="myTaskSearch"></span>
    		</div>
    	</div>
</div>
</div>
@if($myTasks->first())
@if($input['sortby'] == 'duedate' || !($input['sortby']))
	@foreach($myTasks as $mytask)
		<?php
	        if(date('Y-m-d',strtotime($mytask->due)) == date('Y-m-d'))
	        {
	        	$mytaskArr["Today"][] = $mytask;
	        }
	        else if(date('Y-m-d',strtotime($mytask->due)) < date('Y-m-d'))
	        {
	        	if(date('W',strtotime($mytask->due)) == date('W'))
		        {
		        	$mytaskArr["This week"][] = $mytask;
		        }
		        else
		        {
		        	if(date('W',strtotime($mytask->due)) == date('W')-1)
		        	{
		        		$mytaskArr["Last week"][] = $mytask;	
		        	}
		        	else
		        	{
		        		$mytaskArr["Previous"][] = $mytask;	
		        	}
		        }

	        }
	        else if(date('Y-m-d',strtotime($mytask->due)) > date('Y-m-d'))
	        {
	        	if(date('W',strtotime($mytask->due)) == date('W'))
		        {
		        	$mytaskArr["This week"][] = $mytask;
		        }
		        else
		        {
		        	if(date('W',strtotime($mytask->due)) == date('W')+1)
		        	{
		        		$mytaskArr["Next week"][] = $mytask;
		        	}
		        	else
		        	{
		        		$mytaskArr["Upcoming"][] = $mytask;
		        	}
		        }
	        }
		?>
	@endforeach
@elseif($input['sortby'] == 'assigner')
	@foreach($myTasks as $mytask)
		<?php
		if($mytask->assigner)
		{
			$mytaskArr[$mytask->getassigner->name][] = $mytask;
		}
		else
		{
			$mytaskArr['Team'][] = $mytask;
		}
		?>
	@endforeach
@elseif($input['sortby'] == 'meeting')
	@foreach($myTasks as $mytask)
		<?php
	        $mytaskArr[$mytask->minute->meeting->title][] = $mytask;
		?>
	@endforeach
@endif

<div class="row scroll_horizontal">
	
	@foreach($mytaskArr as $key=>$mytaskrow)
		<div class="col-md-12 border_bottom margin_top_10">
			<strong>{{$key}}</strong>
		</div>
		@foreach($mytaskrow as $mytaskcol)
			<a class="col-md-12 nopadding mytask" id="task{{$mytaskcol->id }}" href="#mytask#task{{$mytaskcol->id}}">
				<div class="col-md-9">
					{{$mytaskcol->title}}
				</div>
				<div class="col-md-3">
					{{ date("d M",strtotime($mytaskcol->due)) }}
				</div>
			</a>
		@endforeach
	@endforeach
</div>
<?php echo $myTasks->appends($input)->render(); ?>
@else
	No data to display!
@endif
