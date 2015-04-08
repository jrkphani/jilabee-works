@extends('user')
@section('leftcontent')
@if($notes->first())
@foreach($notes as $note)
	<?php
	echo $note->minute_history->minute->name;
		$noteArr[$note->minute_history->minute->title][] = $note;
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