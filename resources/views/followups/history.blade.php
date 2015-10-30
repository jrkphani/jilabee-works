@if($historytasks)
	@foreach($historytasks as $title=>$tasks)
		<div class="boxList">
			<div class="boxTitle">
				<span class="boxTitleNumber boxNumberBlue">{{count($tasks['tasks'])}}</span>
				<p>{{$title}}</p>
				<div class="clearboth"></div>
			</div>
			<?php $count=1; ?>
			@foreach($tasks['tasks'] as $task)
				<?php if($task->type == 'minute')
				{
					$mid = "mid=$task->minuteId";
				}
				else
				{
					$mid='';
				}
				?>
					<div class="box followup" {{$mid}} tid="{{$task->id}}">
						<span class="boxNumber boxNumberBlue">{{$count++}}</span>
						<div class="boxInner">
							<h4 class="searchTxt">{{$task->title}}</h4>
							<p class="searchTxt">{!!$task->description!!}</p>
							<p class="searchTxt">Last update: {{$task->dueDate}}</p>
						</div>
						<div class="boxRight">
						</div>
					</div>
			@endforeach
		</div>
	@endforeach
@else
	No jobs
@endif