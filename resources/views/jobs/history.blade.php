@if(count($historytasks))
	<div class="mainListFilter">
		<input type="text" placeholder="Search..." id="historySearch"> <span id="showHistroyDiv">Reset</span>
		{!! Form::select('days',['7'=>'Last 7 days','7'=>'Last 7 days','14'=>'Last 14 days','30'=>'Last 30 days','90'=>'Last 90 days'],$days,['id'=>'days','autocomplete'=>'off']) !!}
		<select  class="dropdown">
		  <option value="0">Any one</option>
		  <option value="Option">Option 1</option>
		  <option value="Option">Option 2</option>
		  <option value="Option">Option 3</option>
		</select>
		    <select name="speed" class="dropdown">
		      <option>Slower</option>
		      <option>Slow</option>
		      <option selected="selected">Medium</option>
		      <option>Fast</option>
		      <option>Faster</option>
		    </select>
		<button>Reset all</button>
	</div>
	<div id="historyDiv" class="mainList">
		@foreach($historytasks as $title=>$tasks)
			<div class="boxList">
				<div class="boxTitle">
					<span class="boxTitleNumber boxNumberBlue">{{count($tasks['tasks'])}}</span>
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
							<span class="boxNumber boxNumberBlue">{{$count++}}</span>
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
	</div>
@else
No Tasks
@endif
<!--================ Buttons for now sections ======================-->
<div class="arrowBtn arrowBtnRight">
	<span id="moveright"><img src="{{asset('images/arrow_right.png')}}"> </span>
	<p>Now</p>
</div>