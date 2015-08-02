@if($task)
<?php 
	if($task->minuteId)
	{
		$mid = "mid=".$task->minuteId;
	}
	else
	{
		$mid='';
	}
?>
<div class="popupWindow">
	<div class="popupHeader">
		<h2><a href="">Jobs</a> / <a href="">Pending Tasks</a></h2>
		<button onclick="toggle_visibility('popup1');" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>	
	<div class="popupContent">
		<!--======================== Popup content starts here ===============================-->
		<div class="popupContentLeft">
			<!-- =================== Job details ====================  -->
			<div class="popupContentTitle">
				<h4>{{$task->title}}</h4><br/>
				<p>{{$task->id}}</p>
				<p>Created on: 25th jan 2015</p>
				<p>DUE: {{$task->dueDate}}</p>
			</div>
			<div class="popupContentSubTitle">
				<p> Assigned by: {{$task->assignerDetail->name}}, updates: 3, revisions: nil</p>
			</div>
			<div class="popupContentText">
				{{$task->description}}
			</div>
			
			
			<!-- ================= Updates ====================  -->
			<!-- ================= Update item each ====================  -->
			<div class="updateItem">
				<h6> update: 16/08/2015</h6>
				<p>Vivamus tristique non orci nec auctor. Suspendisse suscipit urna sed est porta imperdiet. Praesent eu vehicula mauris. Integer accumsan urna lorem, eu pretium sapien egestas.</p>
			</div>
			<!-- ================= Update item each ====================  -->
			<div class="updateItem">
				<h6> update: 16/08/2015</h6>
				<p>Vivamus tristique non orci nec auctor. Suspendisse suscipit urna sed est porta imperdiet. Praesent eu vehicula mauris. Integer accumsan urna lorem, eu pretium sapien egestas.</p>
			</div>
		</div>
		<!-- =================== Popup right ====================  -->
		<div class="popupContentRight">
			<div class="popupSearchSection">
				
			</div>
			<!-- ================= Comment/chat section ====================  -->
			<div class="chatSection">
				<div class="chatContent">
					<div class="chatLeft">
						<span>Mr.John Smith</span>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam est orci, semper quis nulla in, finibus laoreet mi. Ut facilisis tortor eget semper tristique. </p>
					</div>
					<div class="chatLeft">
						<span>Mr.John Smith</span>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam est orci, semper quis nulla in, finibus laoreet mi. Ut facilisis tortor eget semper tristique. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam est orci, semper quis nulla in, finibus laoreet mi. Ut facilisis tortor eget semper tristique.</p>
					</div>
					@if($task->comments()->first())
							@foreach($task->comments()->get() as $comment)
							<div class="chatRight">
								<span>{{$comment->createdby->name}} - {{$comment->updated_at}}</span>
								<div class="clearboth"></div>
								<p>{!! $comment->description !!}</p>
							</div>
							@endforeach
					@endif
					<div class="clearboth"></div>
				</div>
				<!-- ================= Chat input area fixed to bottom  ====================  -->
				<div class="chatInput">
					<textarea name="" id=""  rows="3" placeholder="Type comment here"></textarea>
					<input type="button" value="Submit">
				</div>
			</div>
		</div>
		<div class="clearboth"></div>
	</div>
</div>












======
{{-- <div class="row">
	<div class="col-md-12">Due Date: {{$task->dueDate}}</div>
	<div class="col-md-12">Status: {{$task->status}}</div>
	<div class="col-md-12">Assignee: {{$task->assigneeDetail->name}}</div>
	<div class="col-md-12">Assigner: @if($task->assigner){{$task->assignerDetail->name}} @endif</div>
	<div class="col-md-12">
		<strong>{{$task->title}}</strong>
	</div>
	<div class="col-md-12">
		{{$task->description}}
	</div>
	@if($task->comments()->first())
		<div class="col-md-12">
			<strong>Comments</strong>
			@foreach($task->comments()->get() as $comment)
			<p>
				{!! $comment->description !!}
				<p>{{$comment->createdby->name}} - {{$comment->updated_at}}</p>
			</p>
			<div class="col-md-12"><hr></div>
			@endforeach
		</div>
	@endif
	@if($task->status == 'Sent')
		{!! Form::open(['id'=>"Form".$task->id]) !!}
		{!! Form::textarea('reason', '','') !!}
		@if(isset($reason_err))
			<div class="error">{{$reason_err}}</div>
		@endif
		{!! Form::close() !!}
		<button {{$mid}} tid="{{$task->id}}" id="accept" class="btn btn-primary">Accept</button>
		<button {{$mid}} tid="{{$task->id}}" id="reject" class="btn btn-primary">Reject</button>
	@elseif($task->status == 'Rejected')
		Refused Reason : {!! $task->reason !!}
	@else
		@if($task->status != 'Completed')
		<button type="submit" id="markComplete" {{$mid}} tid="{{$task->id}}" class="btn btn-primary pull-right">Mark as Completed</button>
		@endif
		{!! Form::open(['id'=>"CommentForm".$task->id]) !!}
		{!! Form::textarea('description', '','') !!}
		{!! $errors->first('description','<div class="error">:message</div>') !!}
		{!! Form::close() !!}
		<button {{$mid}} tid="{{$task->id}}" id="taskComment" class="btn btn-primary ">Post</button>
	@endif
</div> --}}
@endif