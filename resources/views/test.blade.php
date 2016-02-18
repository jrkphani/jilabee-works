@extends('master')
@section('content')
<?php 
	if($task->minuteId)
	{
		$formId = "Formm$task->id";
		$mid = "mid=".$task->minuteId;
		$action = url('/minute/'.$task->minuteId.'/comment/'.$task->id);
	}
	else
	{
		$formId = "Formt$task->id";
		$mid=NULL;
		$action = url('/jobs/comment/'.$task->id);
	}
?>
	<div class="inner-container">
        <div id="horz-scroll">
	        <div class="inner-page">
	            <h2 class="sub-title">JOB ID #T{{$task->id}} <a href="{{url('jobs/now')}}" class="btn-close">X</a> </h2>
	            <div class="follow-header jobs">
	                <div class="user-img"><img src="img/profile/img-photo.jpg"></div>
	                <div class="title-col1">
	                    <p>Assigned by</p>
	                    {{$task->assignerDetail->name}}
	                </div>
	                <div class="title-col2 date">
	                    <p>Due Date</p>
	                    {{date("d, M Y",strtotime($task->dueDate))}}
	                </div>
	                <div class="title-col3 time">
	                    <p>Due Time</p>
	                    {{date('h:i A', strtotime($task->dueDate))}}
	                </div>
	                <div class="title-col4">
	                    <p>Status</p>
	                    {{$task->status}}
	                </div>
	                <div class="task-button">
	                	@if($task->status != 'Completed' && $task->status != 'Sent' && $task->status != 'Closed'  && $task->status != 'Cancelled')
							@if($mid)
									@if($task->minute->filed == '1')
									<a class="btn-normal complete" id="markComplete" tid="{{$task->id}}" {{$mid}}>
										<input type="checkbox" id="complete" />
										<label for="complete">Complete</label>
									</a>
									@endif
							@elseif($task->status != 'Rejected')
								<a class="btn-normal complete" id="markComplete" tid="{{$task->id}}" {{$mid}}>
										<input type="checkbox" id="complete" />
										<label for="complete">Complete</label>
									</a>
							@endif
						@endif
						@if($mid)
	                    	<a href="#" class="btn-normal minute">Minute</a>
	                    @endif
	                </div>
	            </div>

	            <div class="follow-content">
	                <div class="follow-row">
	                    <div class="follow-note">
	                        <label>Creation Date :</label>{{date("d, M Y-h:i A",strtotime($task->created_at))}}
	                    </div>
	                    <div class="follow-note">
	                        <label>Recently Updated :</label>{{date("d, M Y-h:i A",strtotime($task->updated_at))}}
	                    </div>
	                </div>
	                <div class="follow-row">
	                    <div class="task-title jobs">
	                        {{$task->title}}
	                    </div>
	                </div>
	                <div class="follow-row">
	                    <div class="task-copy">
	                    	{!!$task->description!!}
	                    </div>
	                </div>
	            </div>

	            <div class="follow-bottom">
	                <div class="task-comment">
	                    <div class="task-comment-left"><img src="img/profile/img-photo.jpg"></div>
	                    @if($task->status == 'Sent')
							{!! Form::open(['id'=>$formId]) !!}
							<div class="task-comment-right talkbubble">
								{!! Form::textarea('reason', '',['cols'=>'25','rows'=>3,'autocomplete'=>'off','placeholder'=>'Reason for reject']) !!}
								<div class="error" id="err_{{$task->id}}"></div>
							</div>
							{!! Form::close() !!}
						@else
							<div class="task-comment-right talkbubble">
								{!! Form::open(['id'=>"CommentForm".$task->id,'method'=>'POST','url'=>$action]) !!}
								{!! Form::textarea('description', Request::input('description',''),['rows'=>3,'placeholder'=>'Comment description','id'=>'taskCommentText','autocomplete'=>'off']) !!}
								{!! $errors->first('description','<div class="error">:message</div>') !!}
								{!! Form::close() !!}
							</div>
						@endif
	                    <div class="comment-bottom">
		                    @if($task->status == 'Sent')
		                        <div {{$mid}} tid="{{$task->id}}" class="btn-inter btn-small accept">Accept</div>
		                        <div  {{$mid}} tid="{{$task->id}}"class="btn-inter btn-small reject">Reject</div>
		                    @else
								<span {{$mid}} tid="{{$task->id}}" id="taskComment" class="btn-inter btn-small">Update</span>
		                    	
		                   	@endif
	                    </div>
	                </div>

	                <div class="task-history">
	                    <div class="task-history-title jobs">
	                        Activity <span>/ 15 Updates</span>
	                        <select>
	                            <option>All Updates</option>
	                        </select>
	                    </div>

	                    @if($task->comments()->count())
							@foreach($task->comments()->orderBy('updated_at','DESC')->get() as $comment)
								<div class="history-box">
			                        <div class="history-box-row1">
			                            <div class="col1 unread"></div>
			                            <div class="col2   note-icon info"></div>
			                            <div class="col3">Comment</div>
			                        </div>
			                        <div class="history-box-row2">
			                            <div class="follow-row copy">
			                                {{-- <div class="btn-edit"></div>
			                                <div class="btn-remove"></div> --}}
			                                <p>{!! $comment->description !!}</p>
			                            </div>
			                            <div class="follow-row">
			                                <div class="comment-user"><img src="img/profile/img-photo.jpg"/> </div>
			                                <div class="comment-name jobs">
			                                	@if($comment->created_by == Auth::id())
			                                		Me
			                                	@else
			                                		{{$comment->createdby->name}}
			                                	@endif
			                                </div>
			                                <div class="comment-time jobs">{{date('d,M Y H:i A',strtotime($comment->updated_at))}}</div>
			                            </div>
			                        </div>
			                    </div>
							@endforeach
						@endif
	                    <div class="history-box">
	                        <div class="history-box-row1">
	                            <div class="col1 unread"></div>
	                            <div class="col2   note-icon reject"></div>
	                            <div class="col3">Task Description Changed</div>
	                        </div>
	                        <div class="history-box-row2">
	                            <div class="follow-row copy">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam sollicitudin orci a rutrum dapibus. In at quam ante. Fusce sit amet lacus ullamcorper, ullamcorper enim vitae, tempor eros.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam sollicitudin orci a rutrum dapibus. In at quam ante. Fusce sit amet lacus ullamcorper, ullamcorper enim vitae, tempor eros.</div>
	                            <div class="follow-row">
	                                <div class="comment-user"><img src="img/profile/img-photo.jpg"/> </div>
	                                <div class="comment-name jobs">Optimus Prime</div>
	                                <div class="comment-time jobs">10.10 am</div>
	                            </div>
	                        </div>
	                    </div>


	                    <div class="history-box">
	                        <div class="history-box-row1">
	                            <div class="col1 unread"></div>
	                            <div class="col2  note-icon comment"></div>
	                            <div class="col3"></div>
	                        </div>
	                        <div class="history-box-row2">
	                            <div class="follow-row copy">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam sollicitudin orci a rutrum dapibus. In at quam ante. Fusce sit amet lacus ullamcorper, ullamcorper enim vitae, tempor eros.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam sollicitudin orci a rutrum dapibus. In at quam ante. Fusce sit amet lacus ullamcorper, ullamcorper enim vitae, tempor eros.</div>
	                            <div class="follow-row">
	                                <div class="comment-user"><img src="img/profile/img-photo.jpg"/> </div>
	                                <div class="comment-name jobs">Optimus Prime</div>
	                                <div class="comment-time jobs">10.10 am</div>
	                            </div>
	                        </div>
	                    </div>


	                    <div class="history-box">
	                        <div class="history-box-row1">
	                            <div class="col1 unread"></div>
	                            <div class="col2  note-icon accept"></div>
	                            <div class="col3">Title</div>
	                        </div>
	                        <div class="history-box-row2">
	                            <div class="follow-row copy">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam sollicitudin orci a rutrum dapibus. In at quam ante. Fusce sit amet lacus ullamcorper, ullamcorper enim vitae, tempor eros.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam sollicitudin orci a rutrum dapibus. In at quam ante. Fusce sit amet lacus ullamcorper, ullamcorper enim vitae, tempor eros.</div>
	                            <div class="follow-row">
	                                <div class="comment-user"><img src="img/profile/img-photo.jpg"/> </div>
	                                <div class="comment-name jobs">Optimus Prime</div>
	                                <div class="comment-time jobs">10.10 am</div>
	                            </div>
	                        </div>
	                    </div>

	                </div>

	                <div class="btn-view-all-box follow">
	                    <a href="#" class="btn-view-all">View All</a>
	                </div>

	            </div>

	        </div>

	        <div class="bottom-button">
	        <a href="#" class="btn-inter btn-bottom">&lt; Back to jobs</a>
	        </div>
    	</div>
	</div>
@endsection
@section('javascript')
    <script type="text/javascript" src="{{ asset('/js/test.js') }}"></script>
@endsection