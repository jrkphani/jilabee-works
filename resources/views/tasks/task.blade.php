<div class="col-md-12 border_bottom" id="task{{$task->id}}">
	<div class="col-md-3">
		{{$task->title}}
	</div>
	<div class="col-md-7">
		{!! nl2br($task->description) !!}
	</div>
	<div class="col-md-2 nopadding">
		<div class="col-md-12 nopadding">
			@if(isset($task->status))
			<?php $statusArr = ['close'=>'close'];
				$statusArr[$task->status]=$task->status
			?>
				
				<div class="col-md-10 nopadding">
					{!!Form::select('status',$statusArr,$task->status,['autocomplete'=>'off','class'=>'changeStatus form-control','tid'=>$task->id]) !!}
				</div>
				@if($task->status == "rejected")
				<div class="col-md-2 nopadding">
					<span class="glyphicon glyphicon-question-sign btn btn-link" data-toggle="tooltip" data-placement="bottom" title="{{$task->comments()->first()->description}}"></span>
				</div>
				@endif
			@endif
		</div>
		<div class="col-md-12">
			<a href="{{ app_url('/profile/').$task->assignee}}">
				{{(isset($task->getassignee->name)?$task->getassignee->name:'')}}
			</a>
		</div>
		<div class="col-md-12">
			{{(isset($task->due)?date('Y-m-d',strtotime($task->due)):'')}}
		</div>
		@if($task->status == "close")
			<div class="col-md-12">
				{{date('Y-m-d',strtotime($task->updated_at))}}
			</div>
		@endif
		@if($task->minute->updated_by == Auth::id() && ($task->status == 'waiting' || $task->status == 'rejected'))
			        					<div class="col-md-12">
				<span tid="{{$task->id}}" class="btn glyphicon glyphicon-edit edit_task"></span>
			</div>
		@endif
	</div>
</div>