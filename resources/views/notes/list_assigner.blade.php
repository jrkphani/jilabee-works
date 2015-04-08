@extends('user')
@section('leftcontent')
@if($notes->first())
@foreach($notes as $note)
	<?php
		if($note->assigner)
		{
			$noteArr[$note->getassigner->name][] = $note;
		}
		else
		{
			$noteArr['Team'][] = $note;
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
    <script>
	$(document).ready(function($)
		{
			$('#menuMytask').addClass('active');
			$(".note:first").click();
    	});
	</script>
@stop