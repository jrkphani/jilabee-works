@extends('master')
@section('css')		
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
@stop
@section('content')

	<!--=================================== contentLeft - History section ================================-->
	<div class="contentLeft" id="contentLeft">
		<div class="mainListFilter">
					<input type="text" placeholder="Search...">
					<select  class="dropdown">
					  <option value="0">Any origin</option>
					  <option value="Option">Option 1</option>
					  <option value="Option">Option 2</option>
					  <option value="Option">Option 3</option>
					</select>
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
		<div class="mainList">
			<!--=================================== List 1 ================================-->
			<div class="boxList">
				<div class="boxTitle">
					<span class="boxTitleNumber boxNumberRed">5</span>
					<p>Recent</p>
					<div class="clearboth"></div>
				</div>
				<div class="box">
					<span class="boxNumber boxNumberRed">1</span>
					<div class="boxInner">
						<h4>Deliver samples to Axson. </h4>
						<h6>Last Update - 6/7/2015</h6>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed rhoncus metus ut nisi convallis aliquam.</p>
					</div>
					<div class="boxRight">
						
					</div>
				</div>
				<div class="box">
					<span class="boxNumber boxNumberRed">2</span>
					<div class="boxInner">
						<h4>Visit Machinery Manufacture Lorem ipsum dolor sit amet.</h4>
						<h6>Last Update - 6/7/2015</h6>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed rhoncus metus ut nisi convallis aliquam.</p>
						
						
					</div>
					<div class="boxRight">
						
					</div>
				</div>
				<div class="box">
					<span class="boxNumber boxNumberRed">3</span>
					<div class="boxInner">
						<h4>Prepare project report</h4>
						<h6>Last Update - 6/7/2015</h6>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed rhoncus metus ut nisi convallis aliquam.</p>
					</div>	
					<div class="boxRight">
						
					</div>	
				</div>
				<div class="box">
					<span class="boxNumber boxNumberRed">4</span>
					<div class="boxInner">
						<h4>Prepare project report</h4>
					</div>	
					<div class="boxRight">
						
					</div>
				</div>
				<div class="box">
					<span class="boxNumber boxNumberRed">5</span>
					<div class="boxInner">
						<h4>Prepare project report</h4>
					</div>	
					<div class="boxRight">
						
					</div>
				</div>
			</div>
			<!--=================================== List 2 ================================-->
			<div class="boxList">
				<div class="boxTitle">
					<span class="boxTitleNumber boxNumberGreen">{{count($taskNotFiled)}}</span>
					<p>Closed</p>
				</div>
				<?php $count =1; ?>
				@foreach($taskClosed as $task)
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
		</div>
			<!--================ Buttons for now sections ======================-->
	<div class="arrowBtn arrowBtnRight">
				<span id="moveright"><img src="{{asset('images/arrow_right.png')}}"> </span>
				<p>Now</p>
			</div>
	</div>
	<!--=================================== contentRight - Main/default section ================================-->
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
					<span class="boxTitleNumber boxNumberBlue">{{count($taskNotFiled)}}</span>
					<p>Newly Added</p>
					<div class="clearboth"></div>
				</div>
				<?php $count =1; ?>
				@foreach($taskNotFiled as $task)
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
								<h4 tid="{{$task->id}}" class="task">{{$task->title}}</h4>
								<p>{{$task->description}}</p>
								@if($task->status == 'Sent')
									{!! Form::open(['id'=>$formId]) !!}
									{!! Form::textarea('reason', '',['cols'=>'35','rows'=>3]) !!}
									<div class="error" id="err_{{$task->id}}"></div>
									{!! Form::close() !!}
									<button {{$mid}} tid="{{$task->id}}" id="accept">Accept</button>
									<button {{$mid}} tid="{{$task->id}}" id="reject">Reject</button>
								@endif
							</div>
							<div class="boxRight task" {{$mid}} tid="{{$task->id}}"></div>
						</div>
				@endforeach
			</div>
				<!--=================================== List 2 ================================-->
			<div class="boxList">
				<div class="boxTitle">
					<span class="boxTitleNumber boxNumberRed">{{count($taskNotFiled)}}</span>
					<p>Pending</p>
				</div>
				<?php $count =1; ?>
				@foreach($taskToFinsh as $task)
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
								<h4 tid="{{$task->id}}" class="task">{{$task->title}}</h4>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed rhoncus metus ut nisi convallis aliquam.</p>
								{{-- {!! Form::open(['id'=>$formId]) !!}
								{!! Form::textarea('update', '',['cols'=>'35','rows'=>3]) !!}
								{!! Form::close() !!}
								<button {{$mid}} tid="{{$task->id}}" class="btn btn-primary">Update</button> --}}
							</div>
							<div class="boxRight task" {{$mid}} tid="{{$task->id}}"></div>
						</div>
				@endforeach
			</div>
				<!--=================================== List 3 ================================-->
			<div class="boxList">
				<div class="boxTitle">
					<span class="boxTitleNumber boxNumberGrey">{{count($taskNotFiled)}}</span>
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
							<span class="boxNumber boxNumberBlue">{{$count++}}</span>
							<div class="boxInner">
								<h4 tid="{{$task->id}}" class="task">{{$task->title}}</h4>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed rhoncus metus ut nisi convallis aliquam.</p>
								{!! Form::open(['id'=>$formId]) !!}
								{!! Form::textarea('reason', '',['cols'=>'35','rows'=>3]) !!}
								{!! Form::close() !!}
							</div>
							<div class="boxRight task" {{$mid}} tid="{{$task->id}}"></div>
						</div>
				@endforeach
			</div>
			<div class="clearboth"></div>
		</div>
			<!--================ Buttons for now sections ======================-->
		<div class="arrowBtn">
			<span id="moveleft"><img src="{{asset('images/arrow_left.png')}}"> </span>
			<p>History</p>
		</div>
		<button class="addBtn"> </button>
	</div>
	<div class="clearboth"></div>
	<!--========================================= POP UP 1 ===================================================-->
	<div class="popupOverlay" id="popup" >
	</div>
@endsection
@section('javascript')
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="{{ asset('/js/jobs.js') }}"></script>
<script type="text/javascript">
$( ".dropdown" ).selectmenu();
</script>
@endsection