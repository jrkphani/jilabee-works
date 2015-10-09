@if($historytasks)
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
@else
	No tasks
@endif