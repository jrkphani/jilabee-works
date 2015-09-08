@extends('master')
@section('css')		
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link href="{{ asset('/css/jquery.simple-dtpicker.css') }}" rel="stylesheet">
@stop
@section('content')
{{-- content left--}}
	<div id="contentLeft" class="contentLeft">
				<div class="mainListFilter">
					<input type="text" placeholder="Search...">
					<select>
					  <option value="0">Any origin</option>
					  <option value="Option">Option 1</option>
					  <option value="Option">Option 2</option>
					  <option value="Option">Option 3</option>
					</select>
					<select>
					  <option value="0">Any one</option>
					  <option value="Option">Option 1</option>
					  <option value="Option">Option 2</option>
					  <option value="Option">Option 3</option>
					</select>
					<select>
					  <option value="0">Any time</option>
					  <option value="Option">Option 1</option>
					  <option value="Option">Option 2</option>
					  <option value="Option">Option 3</option>
					</select>
					<select>
					  <option value="0">Location</option>
					  <option value="Option">Option 1</option>
					  <option value="Option">Option 2</option>
					  <option value="Option">Option 3</option>
					</select>
					<button>Reset all</button>
				</div>
				<div class="mainList">
					<!--=================================== List 1 ================================-->
					<div class="boxList">
						<div class="boxTitle">
							<span class="boxTitleNumber boxNumberGrey">{{count($taskClosed['previous'])}}</span>
							<p>Closed Eariler</p>
						</div>
						<?php $count =1; ?>
						@foreach($taskClosed['previous'] as $task)
						<?php if($task->type == 'minute')
							{
								$mid = "mid=$task->minuteId";
							}
							else
							{
								$mid='';
							}
						?>

							<div class="box">
								<span class="boxNumber boxNumberGrey">{{$count++}}</span>
								<div class="boxInner">
									<h4>{{$task->title}}</h4>
								</div>
								<div class="boxRight task" {{$mid}} tid="{{$task->id}}"></div>
							</div>

						@endforeach
					</div>
					<!--=================================== List 2 ================================-->
					<div class="boxList">
						<div class="boxTitle">
							<span class="boxTitleNumber boxNumberRed">{{count($taskClosed['lastWeek'])}}</span>
							<p>Closed Recently</p>
						</div>
						<?php $count =1; ?>
						@foreach($taskClosed['lastWeek'] as $task)
						<?php if($task->type == 'minute')
							{
								$mid = "mid=$task->minuteId";
							}
							else
							{
								$mid='';
							}
						?>

							<div class="box">
								<span class="boxNumber boxNumberRed">{{$count++}}</span>
								<div class="boxInner">
									<h4>{{$task->title}}</h4>
								</div>
								<div class="boxRight task" {{$mid}} tid="{{$task->id}}"></div>
							</div>

						@endforeach
					</div>
					<!--=================================== List 3 ================================-->
					<div class="boxList">
						<div class="boxTitle">
							<span class="boxTitleNumber boxNumberGreen">{{count($taskClosed['recent'])}}</span>
							<p>Closed Today</p>
						</div>
						<?php $count =1; ?>
						@foreach($taskClosed['recent'] as $task)
						<?php if($task->type == 'minute')
							{
								$mid = "mid=$task->minuteId";
							}
							else
							{
								$mid='';
							}
						?>

							<div class="box">
								<span class="boxNumber boxNumberGreen">{{$count++}}</span>
								<div class="boxInner">
									<h4>{{$task->title}}</h4>
								</div>
								<div class="boxRight task" {{$mid}} tid="{{$task->id}}"></div>
							</div>

						@endforeach
					</div>
					<!--=================================== List 4 ================================-->
					<div class="boxList">
						<div class="boxTitle">
							<span class="boxTitleNumber boxNumberGrey">{{count($taskCancelled)}}</span>
							<p>Cancelled Tasks</p>
						</div>
						<?php $count =1; ?>
						@foreach($taskCancelled as $task)
						<?php if($task->type == 'minute')
							{
								$mid = "mid=$task->minuteId";
							}
							else
							{
								$mid='';
							}
						?>

							<div class="box">
								<span class="boxNumber boxNumberGrey">{{$count++}}</span>
								<div class="boxInner">
									<h4>{{$task->title}}</h4>
								</div>
								<div class="boxRight task" {{$mid}} tid="{{$task->id}}"></div>
							</div>

						@endforeach
					</div>
				</div>
					<!--================ Buttons for now sections ======================-->
				<div class="arrowBtn arrowBtnRight">
					
					<span id="moveright"><img src="images/arrow_right.png"> </span>
					<p>Now</p>
				</div>
			</div>
{{-- content right--}}
	<div id="contentRight" class="contentRight">
		<div class="mainListFilter">
			<input type="text" placeholder="Search...">
			<select>
			  <option value="0">Sort by</option>
			  <option value="Option">Option 1</option>
			  <option value="Option">Option 2</option>
			  <option value="Option">Option 3</option>
			</select>
		</div>
		<div class="mainList">
		<!--=================================== List 1 ================================-->
		<div class="boxList">
				<div class="boxTitle">
					<span class="boxTitleNumber boxNumberGrey">{{count($drafts)}}</span>
					<p>Draft</p>
				</div>
				<?php $count =1; ?>
				@foreach($drafts as $task)
					<div class="box">
						<span class="boxNumber boxNumberBlue">{{$count++}}</span>
						<div class="boxInner">
							<h4 >{{$task->title}}</h4>
							<p>{!!$task->description!!}</p>
						</div>
						<div class="boxRight followupDraft" tid="{{$task->id}}"></div>
					</div>
				@endforeach
			</div>
			<!--=================================== List 2 ================================-->
			<div class="boxList">
				<div class="boxTitle">
					<span class="boxTitleNumber boxNumberRed">{{count($taskNotFiled)}}</span>
					<p>Newly Added</p>
					<div class="clearboth"></div>
				</div>
				<?php $count =1; ?>
				@foreach($taskNotFiled as $task)
					<?php if($task->type == 'minute')
					{
						$mid = "mid=$task->minuteId";
					}
					else
					{
						$mid='';
					}
					?>
						<div class="box">
							<span class="boxNumber boxNumberRed">{{$count++}}</span>
							<div class="boxInner">
								<h4 >{{$task->title}}</h4>
								<p>{!!$task->description!!}</p>
							</div>
							<div class="boxRight followup" {{$mid}} tid="{{$task->id}}"></div>
						</div>
				@endforeach
			</div>
				<!--=================================== List 3 ================================-->
			<div class="boxList">
				<div class="boxTitle">
					<span class="boxTitleNumber boxNumberBlue">{{count($taskToFinsh)}}</span>
					<p>Pending</p>
				</div>
				<?php $count =1; ?>
				@foreach($taskToFinsh as $task)
					<?php if($task->type == 'minute')
					{
						$mid = "mid=$task->minuteId";
					}
					else
					{
						$mid='';
					}
					?>
						<div class="box">
							<span class="boxNumber boxNumberBlue">{{$count++}}</span>
							<div class="boxInner">
								<h4 >{{$task->title}}</h4>
								<p>{!!$task->description!!}</p>
							</div>
							<div class="boxRight followup" {{$mid}} tid="{{$task->id}}"></div>
						</div>
				@endforeach
			</div>
				<!--=================================== List 4 ================================-->
			<div class="boxList">
				<div class="boxTitle">
					<span class="boxTitleNumber boxNumberGreen">{{count($taskCompleted)}}</span>
					<p>Completed</p>
				</div>
				<?php $count =1; ?>
				@foreach($taskCompleted as $task)
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
							<span class="boxNumber boxNumberGreen">{{$count++}}</span>
							<div class="boxInner">
								<h4 >{{$task->title}}</h4>
								<p>{!!$task->description!!}</p>
							</div>
							<div class="boxRight task" {{$mid}} tid="{{$task->id}}"></div>
						</div>
				@endforeach
			</div>
			<div class="clearboth"></div>
		</div>
			<!--================ Buttons for now sections ======================-->
		<div class="arrowBtn">
			<span id="moveleft"><img src="images/arrow_left.png"> </span>
			<p>History</p>
		</div>
		<button id="createTask" class="addBtn"> </button>
	</div>
	<div class="clearboth"></div>
	<!--========================================= POP UP 1 ===================================================-->
	<div class="popupOverlay" id="popup" >
	</div>
@endsection
@section('javascript')
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="{{ asset('/js/jquery.simple-dtpicker.js') }}"></script>
<script src="{{ asset('/js/followups.js') }}"></script>
@endsection