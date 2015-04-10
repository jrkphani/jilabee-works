@if($task)
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong>{{$task->title}}</strong>
				<p>Meeting : {{$task->minute->meeting->title}}</p>
				{{$task->minute->created_at}} | 
				{{$task->minute->venue}} |
				@if($task->assigner)
				{{$task->getassigner->name}}
				@endif
				<span class="pull-right">
					Status: <span class="{{$task->status}}">{{$task->status}}</span>
				</span>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<div class="col-md-12 alert alert-info" role="alert">
						{!! nl2br($task->description) !!}
					</div>
					@foreach($task->comments()->get() as $comment)
						<div class="col-md-12">
							<h6 class="col-md-12">
								On {{date('d M Y',strtotime($comment->updated_at))}} <a href="{{ app_url('/profile/'.$comment->created_by) }}" >{!! nl2br($comment->createdby->name) !!}</a> said:
							</h6>
							<div class="col-md-12">
								{!! nl2br($comment->description) !!}
							</div>
						</div>
					@endforeach
				</div>
					<div class="col-md-12">
						<textarea id="description" class="form-group col-md-12" rows='3' placeholder="description"></textarea>
						{!!$errors->first('description','<div class="alert alert-danger">:message</div>')!!}
						@if($task->status == 'waiting' && $task->assignee == Auth::id())
							<div class="col-md-12">
								<div class="col-md-2">
									<button id="accept_task" tid="{{ $task->id }}" class="pull-right btn btn-success">Accept</button>
								</div>
								<div class="col-md-10">
									<button id="reject_task" tid="{{ $task->id }}" class="pull-right btn btn-danger">Reject</button>
								</div>
							</div>
						@else
							<div class="col-md-12">
								<div class="col-md-12">
									<button id="add_comment" tid="{{ $task->id }}" class="pull-right btn btn-primary">Add Comment</button>
								</div>
							</div>
						@endif
					</div>
				
			</div>
		</div>
	</div>
</div>
@endif
