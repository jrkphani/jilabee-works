@extends('user')
@section('leftcontent')
@include('minutes.filter')
	@if($minutes->first())
	<div class="table-responsive scroll_horizontal">          
	    <table class="table table-bordered">
	        <tbody>
	        	@foreach($minutes as $minute)
	        		<tr>
			        	<td style="background-color:{{$minute->label}}">{{$minute->title}}
			        		<span mid="{{$minute->id}}" class="add_first_minute pull-right btn btn-primary glyphicon glyphicon-forward"></span>
			        	</td>
			        </tr>
			        <tr>
			        	<td>
			        		<table class="table">
			        			@foreach($minute->minute_history()->orderBy('updated_at', 'desc')->get() as $minute_history)
			        			<tr class="minutehistory" mhid="{{$minute_history->id}}">
			        				<td class="date">{{ date("d M",strtotime($minute_history->updated_at)) }}</td>
			        				<td>
			        					{{ $minute_history->venue }}
			        					@if($minute_history->lock_flag != 0)
			        					<span class="glyphicon glyphicon-pencil glyphicon-pencil-animate pull-right"></span>
			        				@endif
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
			$('#menuMinutes').addClass('active');
			$(".minutehistory:first").click();
    	});
	</script>
@stop
