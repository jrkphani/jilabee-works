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
@include('notes.list',['noteArr'=>$noteArr,'sortby'=>$input['sortby']])
@else
	No data to display!
@endif
@endsection

@section('javascript')
@parent
@if(Request::segment(1) == 'followup')
    <script>
	$(document).ready(function($)
		{
			$('#menuFolloup').addClass('active');
			$(".note:first").click();
    	});
	</script>
@else
<script>
	$(document).ready(function($)
		{
			$('#menuMytask').addClass('active');
			$(".note:first").click();
    	});
	</script>
@endif

@stop