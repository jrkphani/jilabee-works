@if(Request::segment(1) == 'notes')
@include('task_filter',['sortby'=>$sortby])
 <table class="table table-bordered">
 	<tr>
 		<td>Due Date</td>
 		<td>Title</td>
 		<td>Assigner</td>
 		<td>Meeting</td>
 	</tr>
 </table>
@else
@include('followup_filter',['sortby'=>$sortby])
 <table class="table table-bordered">
 	<tr>
 		<td>Due Date</td>
 		<td>Title</td>
 		<td>Assignee</td>
 		<td>Meeting</td>
 	</tr>
 </table>
@endif

<div class="table-responsive scroll_horizontal">          
    <table class="table table-bordered">
        <tbody>
        	@foreach($noteArr as $key=>$noterow)
        		<tr>
		        	<td><strong>{{$key}}</strong></td>
		        	<td><tr>
		        		<table class="table">
		        			@foreach($noterow as $notecol)
		        			<tr>
		        				<td>@if($notecol->due){{ date("d M Y",strtotime($notecol->due)) }} @endif</td>
					            <td class="note btn btn-link" nid="{{$notecol->id }}">{{$notecol->title}} </td>
					            <td>
					            	@if(Request::segment(1) == 'notes')
						            	@if($notecol->assigner)
						            	{{$notecol->getassigner->name}}
						            	@else
						            	Team
						            	@endif
						            @else
						            @if($notecol->assignee)
						            	{{$notecol->getassignee->name}}
						            	@endif
					            	@endif
					            </td>
					            <td>{{$notecol->minute_history->minute->title }} </td>
		        			    {{-- <td>
		        			    	<span class="glyphicon glyphicon-tag pull-right" aria-hidden="true" style="color:{{ $notecol->minute_history->minute->label}}"></span>
		        			    </td> --}}
		        			</tr>
		        			@endforeach
		        		</table>
		        	</tr>
		        	</td>
		        </tr>
        	@endforeach	
	    </tbody>
	</table>
</div>
<?php echo $notes->appends($input)->render(); ?>