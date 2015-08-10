

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
		<h2><a href="">Followups</a> / <a href="">Tasks</a></h2>
		@if($task->status == 'Completed')
		<div class="col-md-12">
			<button class="btn btn-primary " id="acceptCompletion" {{$mid}} tid="{{$task->id}}">accept completion</button>
			<button class="btn btn-primary " id="rejectCompletion" {{$mid}} tid="{{$task->id}}">reject completion</button>
		</div>
		@endif
		<button onclick="$('#popup').hide();" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>	
	<div class="popupContent">
		<!--======================== Popup content starts here ===============================-->
		<div class="popupContentLeft">
			<!-- =================== Job details ====================  -->
			<div class="popupContentTitle">
				<h4>{{$task->title}}</h4><br/>
				<p>T{{$task->id}}</p>
				<p>Created on: 25th jan 2015</p>
				<p>DUE: {{$task->dueDate}}</p>
			</div>
			<div class="popupContentSubTitle">
				<p> Assigned by: {{$task->assignerDetail->name}}, updates: 3, revisions: nil</p>
			</div>
			<div class="popupContentText">
				{!!$task->description!!}
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
			<div class="popupButtons">
				<button id="editTask" {{$mid}} tid="{{$task->id}}">Edit Task</button>
				<button {{$mid}} tid="{{$task->id}}">Cancel Task</button>
				<button {{$mid}} tid="{{$task->id}}">Remove Task</button>
			</div>
		</div>
		<!-- =================== Popup right ====================  -->
		<div class="popupContentRight">
			<div class="popupSearchSection">
				
			</div>
			<!-- ================= Comment/chat section ====================  -->
			<div class="chatSection">
				<div class="chatContent">
					@if($task->comments()->first())
							@foreach($task->comments()->get() as $comment)
								@if($comment->created_by == Auth::id())
									<div class="chatRight">
										<span>Me - {{$comment->updated_at}}</span>
								@else
									<div class="chatLeft">
										<span>{{$comment->createdby->name}} - {{$comment->updated_at}}</span>
								@endif
										<div class="clearboth"></div>
										<p>{!! $comment->description !!}</p>
									</div>
							@endforeach
					@endif
					<div class="clearboth"></div>
				</div>
				<!-- ================= Chat input area fixed to bottom  ====================  -->
				<div class="chatInput">
					{!! Form::open(['id'=>"CommentForm".$task->id]) !!}
					{!! Form::textarea('description', '',['rows'=>3,'placeholder'=>'Type comment here']) !!}
					{!! $errors->first('description','<div class="error">:message</div>') !!}
					{!! Form::close() !!}
					<button {{$mid}} tid="{{$task->id}}" id="followupComment" class="btn btn-primary ">Post</button>
				</div>
			</div>
		</div>
		<div class="clearboth"></div>
	</div>
</div>
@endif