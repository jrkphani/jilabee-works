@extends('user')
@section('leftcontent')
@if($notes->first())
@foreach($notes as $note)
	<?php
        if(date('Y-m-d',strtotime($note->due)) == date('Y-m-d'))
        {
        	$noteArr["Today"][] = $note;
        }
        else if(date('Y-m-d',strtotime($note->due)) < date('Y-m-d'))
        {
        	if(date('W',strtotime($note->due)) == date('W'))
	        {
	        	$noteArr["This week"][] = $note;
	        }
	        else
	        {
	        	if(date('W',strtotime($note->due)) == date('W')-1)
	        	{
	        		$noteArr["Last week"][] = $note;	
	        	}
	        	else
	        	{
	        		$noteArr["Previous"][] = $note;	
	        	}
	        }

        }
        else if(date('Y-m-d',strtotime($note->due)) > date('Y-m-d'))
        {
        	if(date('W',strtotime($note->due)) == date('W'))
	        {
	        	$noteArr["This week"][] = $note;
	        }
	        else
	        {
	        	if(date('W',strtotime($note->due)) == date('W')+1)
	        	{
	        		$noteArr["Next week"][] = $note;
	        	}
	        	else
	        	{
	        		$noteArr["Upcoming"][] = $note;
	        	}
	        }
        }
	?>
@endforeach
<div class="table-responsive">          
    <table class="table">
        <tbody>
        	@foreach($noteArr as $key=>$noterow)
        		<tr>
		        	<td class="rotate_90_left border_left border_top">{{$key}}</td>
		        	<td>
		        		<table class="table border_left">
		        			@foreach($noterow as $notecol)
		        			<tr class="border_top">
		        				<td>@if($notecol->due){{ date("d M Y",strtotime($notecol->due)) }} @endif</td>
					            <td class="note" nid="{{$notecol->id }}">{{$notecol->title}} </td>
		        			    <td>
		        			    	<span class="glyphicon glyphicon-tag pull-right" aria-hidden="true" style="color:{{ $notecol->minute_history->minute->label}}"></span>
		        			    </td>
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
			$('#menuMytask').addClass('active');
    	});
	</script>
@stop