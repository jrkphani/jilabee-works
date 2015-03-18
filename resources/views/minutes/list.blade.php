@extends('user')
@section('leftcontent')
	@if($minutes->first())
	@foreach($minutes as $minute)
		<?php

	        if(date('Y-m-d',strtotime($minute->updated_at)) == date('Y-m-d'))
	        {
	        	$minuteArr["Today"][] = $minute;
	        }
	        else if(date('Y-m-d',strtotime($minute->updated_at)) < date('Y-m-d'))
	        {
	        	if(date('W',strtotime($minute->updated_at)) == date('W'))
		        {
		        	$minuteArr["This week"][] = $minute;
		        }
		        else
		        {
		        	if(date('W',strtotime($minute->updated_at)) == date('W')-1)
		        	{
		        		$minuteArr["Last week"][] = $minute;	
		        	}
		        	else
		        	{
		        		$minuteArr["Previous"][] = $minute;	
		        	}
		        }

	        }
	        /*else if(date('Y-m-d',strtotime($minute->updated_at)) > date('Y-m-d'))
	        {
	        	if(date('W',strtotime($minute->updated_at)) == date('W'))
		        {
		        	$minuteArr["This week"][] = $minute;
		        }
		        else
		        {
		        	if(date('W',strtotime($minute->updated_at)) == date('W')+1)
		        	{
		        		$minuteArr["Next week"][] = $minute;
		        	}
		        	else
		        	{
		        		$minuteArr["Upcoming"][] = $minute;
		        	}
		        }
	        }*/
		?>
	@endforeach
	@include('minutes.filter')
	<div class="table-responsive scroll_horizontal">          
	    <table class="table">
	        <tbody>
	        	@foreach($minuteArr as $key=>$minuterow)
	        		<tr>
			        	<td class="rotate_90_left border_left border_top">{{$key}}</td>
			        	<td>
			        		<table class="table border_left">
			        			@foreach($minuterow as $minutecol)
			        			<tr class="border_top">
			        				<td class="date">{{ date("d M",strtotime($minutecol->updated_at)) }}</td>
						            <td class="minute" id="m{{$minutecol->id }}">{{$minutecol->title}} </td>
			        			    <td><span class="glyphicon glyphicon-tag" style="color:{{$minutecol->label}};" aria-hidden="true"></span></td>
			        			</tr>
			        			@endforeach
			        		</table>
			        	</td>
			        </tr>
	        	@endforeach	
		    </tbody>
		</table>
	</div>
	@else
		No data to display!
	@endif
@endsection


@section('javascript')
@parent
    <script>
	$(document).ready(function($)
		{
			$('#menuMinutes').addClass('active');
    	});
	</script>
@stop
