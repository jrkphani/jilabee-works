@extends('master')
@section('content')
<?php 
	if($task->minuteId)
	{
		$formId = "Formm$task->id";
		$mid = "mid=".$task->minuteId;
		$action = url('/followups/'.$task->minuteId.'/comment/'.$task->id);
	}
	else
	{
		$formId = "Formt$task->id";
		$mid=NULL;
		$action = url('/followups/comment/'.$task->id);
	}
?>
	<div class="inner-container">
        <div id="horz-scroll">
	        <div class="inner-page">
	            <h2 class="sub-title">JOB ID #T{{$task->id}} <a href="{{url('jobs/now')}}" class="btn-close">X</a> </h2>
	            <div class="follow-header jobs">
	                <div class="user-img"><img src="img/profile/img-photo.jpg"></div>
	                <div class="title-col1">
	                    <p>Assigned to</p>
	                    {{$task->assigneeDetail->name}}
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
	                	@if($task->status != 'Completed' && $task->status != 'Complete' && $task->status != 'Sent' && $task->status != 'Closed'  && $task->status != 'Cancelled')
							@if($mid)
									@if($task->minute->filed == '1')
									<a href="{{url('/minute/'.$task->minuteId.'/markComplete/'.$task->id)}}" class="btn-normal complete" id="markComplete" tid="{{$task->id}}" {{$mid}}>
										<input type="checkbox" id="complete" />
										<label for="complete">Complete</label>
									</a>
									@endif
							@elseif($task->status != 'Rejected')
								<a href="{{url('/jobs/markComplete/'.$task->id)}}" class="btn-normal complete" id="markComplete" tid="{{$task->id}}" {{$mid}}>
										<input type="checkbox" id="complete" />
										<label for="complete">Complete</label>
									</a>
							@endif
						@endif
						@if($mid)
	                    	<a href="#" class="btn-normal minute">Minute</a>
	                    @endif
	                    @if(!$mid)
							@if($task->status != 'Closed' && $task->status != 'Cancelled')
								<button id="editTask" tid="{{$task->id}}" class="btn-normal">Edit Task</button>
								<button {{$mid}} tid="{{$task->id}}" id="cancelTask" class="btn-normal">Cancel Task</button>
								<button {{$mid}} tid="{{$task->id}}" id="deleteTask" class="btn-normal">Delete Task</button>
							@endif
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
							<div class="task-comment-right talkbubble">
								{!! Form::open(['id'=>"CommentForm".$task->id,'method'=>'POST','url'=>$action]) !!}
								{!! Form::textarea('description', Request::input('description',''),['rows'=>3,'placeholder'=>'Comment description','id'=>'taskCommentText','autocomplete'=>'off']) !!}
								{!! $errors->first('description','<div class="error">:message</div>') !!}
								{!! Form::close() !!}
							</div>
	                    <div class="comment-bottom">
								<span {{$mid}} tid="{{$task->id}}" id="taskComment" class="btn-inter btn-small">Update</span>
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
	                </div>

	                <div class="btn-view-all-box follow">
	                    <a href="#" class="btn-view-all">View All</a>
	                </div>

	            </div>

	        </div>

	        <div class="bottom-button">
	        <a href="{{url('jobs/now')}}" class="btn-inter btn-bottom">&lt; Back to jobs</a>
	        </div>
    	</div>
	</div>
@endsection
@section('javascript')
    <script type="text/javascript" src="{{ asset('/js/test.js') }}"></script>
@endsection