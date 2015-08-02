<!--=================================== contentLeft - History section ================================-->
{{-- <div class="contentLeft">
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
				<span class="boxTitleNumber boxNumberGreen">2</span>
				<p>Completed today</p>
			</div>
			<div class="box">
				<span class="boxNumber boxNumberGreen">1</span>
				<div class="boxInner">
					<h4>Prepare project report</h4>
				</div>
				<div class="boxRight">
					
				</div>
			</div>
			<div class="box">
				<span class="boxNumber boxNumberGreen">2</span>
				<div class="boxInner">
					<h4>Prepare project report</h4>
				</div>
				<div class="boxRight">
					
				</div>
			</div>
		</div>
	</div>
		<!--================ Buttons for now sections ======================-->
<div class="arrowBtn arrowBtnRight">
	<p>Now</p>
	<span><img src="images/arrow_right.png"> </span>
</div>
</div> --}}
<!--=================================== contentRight - Main/default section ================================-->
<div class="contentRight">
	@if($tasks->count())
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
							<h4 tid="{{$task->id}}" class="task">{{$task->title}}</h4>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed rhoncus metus ut nisi convallis aliquam.</p>
							{!! Form::open(['id'=>$formId]) !!}
							{!! Form::textarea('reason', '',['cols'=>'35','rows'=>3]) !!}
							@if(isset($reason_err))
								<div class="error">{{$reason_err}}</div>
							@endif
							{!! Form::close() !!}
							<button {{$mid}} tid="{{$task->id}}" id="accept">Accept</button>
							@if($task->status != 'Rejected')
							<button {{$mid}} tid="{{$task->id}}" id="reject">Reject</button>
							@endif
						</div>
						<div class="boxRight task" {{$mid}} tid="{{$task->id}}"></div>
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
							<h4 tid="{{$task->id}}" class="task">{{$task->title}}</h4>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed rhoncus metus ut nisi convallis aliquam.</p>
							{{-- {!! Form::open(['id'=>$formId]) !!}
							{!! Form::textarea('update', '',['cols'=>'35','rows'=>3]) !!}
							{!! Form::close() !!}
							<button {{$mid}} tid="{{$task->id}}" class="btn btn-primary">Update</button> --}}
						</div>
						<div class="boxRight task" {{$mid}} tid="{{$task->id}}"></div>
					</div>
				@endif
			@endforeach
		</div>
			<!--=================================== List 3 ================================-->
		<div class="boxList">
			<div class="boxTitle">
				<span class="boxTitleNumber boxNumberGrey">5</span>
				<p>Today</p>
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
				@if($task->status == 'Closed')
					<div class="box">
						<span class="boxNumber boxNumberBlue">1</span>
						<div class="boxInner">
							<h4 tid="{{$task->id}}" class="task">{{$task->title}}</h4>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed rhoncus metus ut nisi convallis aliquam.</p>
							{!! Form::open(['id'=>$formId]) !!}
							{!! Form::textarea('reason', '',['cols'=>'35','rows'=>3]) !!}
							{!! Form::close() !!}
						</div>
						<div class="boxRight task" {{$mid}} tid="{{$task->id}}"></div>
					</div>
				@endif
			@endforeach
		</div>
		<div class="clearboth"></div>
	</div>
		<!--================ Buttons for now sections ======================-->
	<div class="arrowBtn">
		<p>History</p>
		<span><img src="images/arrow_left.png"> </span>
	</div>
	<button class="addBtn"> </button>
	@endif
</div>
<div class="clearboth"></div>
<!--========================================= POP UP 1 ===================================================-->
	<div class="popupOverlay" id="popup1" >
	</div>