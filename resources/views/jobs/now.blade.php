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
        <input class="sb_reset" type="submit" id="showNowDiv" value="Reset">
    </div>
</div>
    <div class="jobs-scroll">
        <div class="scroll-pane horizontal-only jspScrollable"> 	
			@if($nowtasks)
				<div class="jobs-container">
					@foreach($nowtasks as $title=>$tasks)
						 <?php
					    	if($title == 'Past deadline')
					        {
					        	$tileclass="job-col pend";
					        }
					        else
					        {
					        	$tileclass="job-col";
					        }
					    ?>
					    <div class="{{$tileclass}}">
					        <h2><span>{{count($tasks['tasks'])}}</span>{{$title}}<strong class="badge this-week">+</strong></h2>
					       <div id="thisweek">
				                <div class="week">01<span>Feb</span></div>
				                <div class="week">02<span>Feb</span></div>
				                <div class="week">03<span>Feb</span></div>
				                <div class="week inactive">04<span>Feb</span></div>
				            </div>
					        <div class="jobs-list">
					        	@foreach($tasks['tasks'] as $task)
									<?php if($task->type == 'minute')
									{
										$mid = "mid=$task->minuteId";
										$formId = "Form$task->minuteId$task->id";
										$url=url('minute/'.$task->minuteId.'/task/'.$task->id);
									}
									else
									{
										$mid='';
										$formId = "Form$task->id";
										$url=url('jobs/task/'.$task->id);
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
						                    <div class="job-card-right">
						                        <a href="#" class="btn-notify-small"><strong>{{App\Model\Notifications::where('userId','=',Auth::id())->where('objectId','=',$task->id)->where('isRead','=','0')->count()}}</strong></a>
						                    </div>
						                    @if($task->status == 'Sent')
							                    <div class="job-status">
							                        <div class="job-status-desc">
							                            <div class="job-desc-copy textarea-example">
							                                <div class="textarea-wrapper">
								                                {!! Form::open(['id'=>$formId]) !!}
																{!! Form::textarea('reason', '',['cols'=>'25','rows'=>3,'autocomplete'=>'off']) !!}
																<div class="error" id="err_{{$task->id}}"></div>
																{!! Form::close() !!}
								                                <div class="textarea-clone"></div>
							                                </div>
							                            </div>
							                        </div>
							                        <button {{$mid}} tid="{{$task->id}}" class="reject btn-job-reject reject">Reject</button>
							                        <button {{$mid}} tid="{{$task->id}}" class="accept btn-job-accept accept">Accept</button>
							                    </div>
						                    @endif
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
       <a href="{{url('/jobs/history')}}" class="btn-history">History</a>
    <a href="{{url('/jobs/now')}}" class="btn-now inactive">Now</a>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript" src="{{ asset('/js/jobs.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/search.js') }}"></script>
@endsection