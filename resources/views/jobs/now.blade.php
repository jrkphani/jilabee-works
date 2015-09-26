@if(count($nowtasks))
	<div class="mainListFilter">
		<input type="text" placeholder="Search..." id="nowSearch"><span class="commonbutton" id="showNowDiv">Reset</span>
		{!! Form::select('nowsortby',['timeline'=>'Time Line','meeting'=>'Group','assigner'=>'People'],$sortby,['id'=>'nowsortby','autocomplete'=>'off']) !!}
	</div>
	<div id="nowDiv" class="mainList">
	<!--=================================== List 1 ================================-->
		@foreach($nowtasks as $title=>$tasks)
			<div class="boxList">
				<div class="boxTitle">
					<span class="boxTitleNumber {{$tasks['colorClass']}}">{{count($tasks['tasks'])}}</span>
					<p>{{$title}}</p>
					<div class="clearboth"></div>
				</div>
				<?php $count =1; ?>
				@foreach($tasks['tasks'] as $task)
					<?php if($task->type == 'minute')
					{
						$mid = "mid=$task->minuteId";
						$formId = "Form$task->minuteId$task->id";
					}
					else
					{
						$mid='';
						$formId = "Form$task->id";
					}
					?>
						<div class="box">
							<span class="boxNumber {{$tasks['colorClass']}}">{{$count++}}</span>
							<div class="boxInner">
								<h4 class="searchTxt">{{$task->title}}</h4>
								<p class="searchTxt">{!!$task->description!!}</p>
								@if($task->status == 'Sent')
									{!! Form::open(['id'=>$formId]) !!}
									{!! Form::textarea('reason', '',['cols'=>'25','rows'=>3]) !!}
									<div class="error" id="err_{{$task->id}}"></div>
									{!! Form::close() !!}
									<button {{$mid}} tid="{{$task->id}}" id="accept">Accept</button>
									<button {{$mid}} tid="{{$task->id}}" id="reject">Reject</button>
								@endif
							</div>
							<div class="boxRight task" {{$mid}} tid="{{$task->id}}">
							@if($title == 'New')
								<p class="boxRightText">draft</p>
								@endif
							</div>
						</div>
				@endforeach
			</div>
		@endforeach
		<div class="clearboth"></div>
	</div>
@else
No Tasks
@endif
	<!--================ Buttons for now sections ======================-->
<div class="arrowBtn">
	<span id="moveleft"><img src="{{asset('images/arrow_left.png')}}"> </span>
	<p>History</p>
</div>