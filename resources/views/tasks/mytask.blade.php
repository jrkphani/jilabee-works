
@extends('user')
@section('leftcontent')
@if($myTasks->first())
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

<div class="row scroll_horizontal">
	<div class="col-md-12">
		<div class="col-md-8">
      		<input type="text" class="form-control" placeholder="Search for...">
    	</div>
    	<div class="col-md-4">
	  		{!! Form::select('mytask_filter', ['duedate'=>'Timeline','meeting'=>'Meeting','assigner'=>'Assigner'], $input['sortby'], ['id'=>'mytask_filter','autocomplete'=>'off'])!!}
    	</div>
	   
	</div>
	@foreach($mytaskArr as $key=>$mytaskrow)
		<div class="col-md-12 border_bottom">
			<strong>{{$key}}</strong>
		</div>
		@foreach($mytaskrow as $mytaskcol)
			<div class="col-md-12 nopadding mytask" nid="{{$mytaskcol->id }}">
				<div class="col-md-9">
					{{$mytaskcol->title}}
				</div>
				<div class="col-md-3">
					{{ date("d M",strtotime($mytaskcol->due)) }}
				</div>
			</div>
		@endforeach
	@endforeach
</div>
<?php echo $myTasks->appends($input)->render(); ?>
@else
	No data to display!
@endif
@endsection
