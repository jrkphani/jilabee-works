@extends('master')
@section('css')		
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
@stop
@section('content')
	<div class="contentRight">
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
					<span class="boxTitleNumber boxNumberBlue">{{$tasks->count()}}</span>
					<p>Newly Added</p>
					<div class="clearboth"></div>
				</div>
				@foreach($tasks as $task)
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
					@if($task->status == 'Sent' || $task->status == 'Rejected')
						<div class="box">
							<span class="boxNumber boxNumberBlue">1</span>
							<div class="boxInner">
								<h4 tid="{{$task->id}}" >{{$task->title}}</h4>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed rhoncus metus ut nisi convallis aliquam.</p>
								{!! Form::open(['id'=>$formId]) !!}
								{!! Form::textarea('reason', '',['cols'=>'35','rows'=>3]) !!}
								@if(isset($reason_err))
									<div class="error">{{$reason_err}}</div>
								@endif
								{!! Form::close() !!}
								<button {{$mid}} tid="{{$task->id}}">update</button>
							</div>
							<div class="boxRight followup" {{$mid}} tid="{{$task->id}}"></div>
						</div>
					@endif
				@endforeach
			</div>
				<!--=================================== List 2 ================================-->
			<div class="boxList">
				<div class="boxTitle">
					<span class="boxTitleNumber boxNumberRed">2</span>
					<p>Pending</p>
				</div>
				@foreach($tasks as $task)
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
					@if($task->status == 'Open')
						<div class="box">
							<span class="boxNumber boxNumberBlue">1</span>
							<div class="boxInner">
								<h4 tid="{{$task->id}}" >{{$task->title}}</h4>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed rhoncus metus ut nisi convallis aliquam.</p>
								{{-- {!! Form::open(['id'=>$formId]) !!}
								{!! Form::textarea('update', '',['cols'=>'35','rows'=>3]) !!}
								{!! Form::close() !!}
								<button {{$mid}} tid="{{$task->id}}" class="btn btn-primary">Update</button> --}}
							</div>
							<div class="boxRight followup" {{$mid}} tid="{{$task->id}}"></div>
						</div>
					@endif
				@endforeach
			</div>
				<!--=================================== List 3 ================================-->
			<div class="boxList">
				<div class="boxTitle">
					<span class="boxTitleNumber boxNumberGrey">5</span>
					<p>Draft</p>
				</div>
				@foreach($drafts as $task)
					<div class="box">
						<span class="boxNumber boxNumberBlue">1</span>
						<div class="boxInner">
							<h4 tid="{{$task->id}}" >{{$task->title}}</h4>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed rhoncus metus ut nisi convallis aliquam.</p>
						</div>
						<div class="boxRight followupDraft" tid="{{$task->id}}"></div>
					</div>
				@endforeach
			</div>
			<div class="clearboth"></div>
		</div>
			<!--================ Buttons for now sections ======================-->
		<div class="arrowBtn">
			<p>History</p>
			<span id="moveleft"><img src="images/arrow_left.png"> </span>
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
<script src="{{ asset('/js/followups.js') }}"></script>
@endsection