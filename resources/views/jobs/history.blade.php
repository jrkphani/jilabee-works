@extends('master')
@section('content')
<div class="header-row2">
    <h2>Jobs</h2>
    <div class="header-search">
    	<input type="text" class="sb_input" placeholder="Search..." id="nowSearch" autocomplete="off" value="{{$nowsearchtxt}}">
    	<input class="sb_search" type="submit" value="">
    </div>
    <div class="header-sort">
    	{!! Form::select('nowsortby',['timeline'=>'Time Line','meeting'=>'Group','assigner'=>'People'],$nowsortby,['id'=>'nowsortby','autocomplete'=>'off']) !!}
    </div>
    <div class="header-reset">
    <a href="{{url('jobs/now')}}" class="sb_reset">Reset</a>
    </div>
</div>
    <div class="jobs-scroll">
        <div class="scroll-pane horizontal-only jspScrollable"> 	
			@if($nowtasks)
				<div class="jobs-container">
					@foreach($nowtasks as $title=>$tasks)
					    <div class="job-col">
					        <h2><span>{{count($tasks['tasks'])}}</span>{{$title}}{{-- <strong class="badge this-week">+</strong>--}}</h2>
					       {{--<div id="thisweek">
				                <div class="week">01<span>Feb</span></div>
				                <div class="week">02<span>Feb</span></div>
				                <div class="week">03<span>Feb</span></div>
				                <div class="week inactive">04<span>Feb</span></div>
				            </div> --}}
					        <div class="jobs-list">
					        	@foreach($tasks['tasks'] as $task)
									<?php if($task->type == 'minute')
									{
										$mid = "mid=$task->minuteId";
										$formId = "Formm$task->id";
										$url=url('minute/'.$task->minuteId.'/task/'.$task->id);
										$accept = url('/minute/'.$task->minuteId.'/acceptTask/'.$task->id);
									}
									else
									{
										$mid='';
										$formId = "Formt$task->id";
										$url=url('jobs/task/'.$task->id);
										$accept = url('/jobs/acceptTask/'.$task->id);
									}
									?>
									<div class="job-card">
						                <p><a href="{{$url}}">{{$task->title}}<img src="{{asset('/img/ico-hover.png')}}" /> </a></p>
						                <div class="job-card-bot">
						                    <div class="job-card-left">
						                        <div class="job-assign-pic"><img src="{{asset('/img/profile/img-user.jpg')}}"/></div>
						                        <div class="job-card-detail">
						                           <h4>{{$task->assignerDetail->name}}</h4>
						                            <div class="job-date">
						                            	{{date("d, M 'y",strtotime($task->dueDate))}}
						                            </div>
						                            <div class="job-time">{{date("H:i A",strtotime($task->dueDate))}}</div>
						                        </div>
						                    </div>
						                    <!-- <div class="job-card-right">
						                        <a href="#" class="btn-notify-small"><strong>{{App\Model\Notifications::where('userId','=',Auth::id())->where('objectId','=',$task->id)->where('isRead','=','0')->count()}}</strong></a>
						                    </div> -->
						                </div>
						            </div>
								@endforeach
					        </div>
					    </div>
					@endforeach
				</div>
			@else
				No jobs
			@endif
       	</div>
    </div>
    <div class="jobs-footer">
       <a href="#" class="btn-history inactive">History</a>
    	<a href="{{url('/jobs/now')}}" class="btn-now">Now</a>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
	$('#nowsortby').change(function(event) {
		reloadfilter();
	});
	$('#nowSearch').keypress(function(event) {
		if(event.which == 13)
	    {
	    	reloadfilter();	        
	    }
	});
	function reloadfilter()
	{
		params = '&sortby='+$('#nowsortby').val();
	    if($('#nowSearch').val().trim().length > 0)
	    {
	        params = params +'&searchtxt='+$('#nowSearch').val();
	    }
	    window.location.href = "/jobs/history?"+params;
	}
    </script>
@endsection