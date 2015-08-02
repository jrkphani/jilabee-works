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
				@if($task->status == 'Sent')
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
							<button {{$mid}} tid="{{$task->id}}" id="accept" class="btn btn-primary">Accept</button>
							<button {{$mid}} tid="{{$task->id}}" id="reject" class="btn btn-primary">Reject</button>
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
							{!! Form::open(['id'=>$formId]) !!}
							{!! Form::textarea('reason', '',['cols'=>'35','rows'=>3]) !!}
							{!! Form::close() !!}
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
						<h4>Lorem ipsum doler sit amet</h4><br/>
						<p>TSJ52Q</p>
						<p>Created on: 25th jan 2015</p>
						<p>DUE: 20th March 2015</p>
					</div>
					<div class="popupContentSubTitle">
						<p> Assigned by: Mr. John K, updates: 3, revisions: nil</p>
					</div>
					<div class="popupContentText">
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam est orci, semper quis nulla in, finibus laoreet mi. Ut facilisis tortor eget semper tristique. Ut porta posuere risus, quis dapibus enim porttitor vitae. Cras vel mi ornare nunc tincidunt vulputate. Nullam vitae justo a arcu aliquet aliquam. Vivamus tristique non orci nec auctor. Suspendisse suscipit urna sed est porta imperdiet. Praesent eu vehicula mauris. Integer accumsan urna lorem, eu pretium sapien egestas.<br/><br/>
						Proin eu faucibus metus. Donec in purus lectus. Duis volutpat justo in varius venenatis. Aliquam pulvinar nec sem id hendrerit. Morbi facilisis convallis massa sed vestibulum. Curabitur ut nisi consequat, feugiat enim quis, blandit mauris. Etiam congue tellus ut odio ultricies, vel dictum odio consequat. 
						Vivamus tristique non orci nec auctor. Suspendisse suscipit urna sed est porta imperdiet. Praesent eu vehicula mauris. Integer accumsan urna lorem, eu pretium sapien egestas.<br/><br/>
						Proin eu faucibus metus. Donec in purus lectus. Duis volutpat justo in varius venenatis. Aliquam pulvinar nec sem id hendrerit. Morbi facilisis convallis massa sed vestibulum. Curabitur ut nisi consequat, feugiat enim quis, blandit mauris. Etiam congue tellus ut odio ultricies, vel dictum odio consequat.</p>
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
							<div class="chatRight">
								<span>Me</span>
								<div class="clearboth"></div>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.  </p>
							</div>
							<div class="chatRight">
								<span>Me</span>
								<div class="clearboth"></div>
								<p>OK done !  </p>
							</div>
							<div class="chatLeft">
								<span>Mr.John Smith</span>
								<p>Blah blah blah!</p>
							</div>
							<div class="chatRight">
								<span>Me</span>
								<div class="clearboth"></div>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.  </p>
							</div>
							<div class="chatRight">
								<span>Me</span>
								<div class="clearboth"></div>
								<p>OK done !  </p>
							</div>
							<div class="chatLeft">
								<span>Mr.John Smith</span>
								<p>Blah blah blah!</p>
							</div>
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
	</div>