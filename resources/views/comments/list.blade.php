@if($task)
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong>{{$task->title}}</strong>
				<p>Meeting : {{$task->minute->meeting->title}}</p>
				{{$task->minute->dt}} | 
				{{$task->minute->venue}} |
				@if($task->assignee)
				Assignee :
					@if($task->assignee == Auth::id())
						Me
					@else
						{{$task->getassignee->name}}
					@endif
					|
				@endif

				@if($task->assigner)
				Assigner :
					@if($task->assigner == Auth::id())
						Me
					@else
						{{$task->getassigner->name}}
					@endif
				@else
				Assigner : Team
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
						<textarea id="description" name="description" class="form-group col-md-12" rows='3' placeholder="description">{{old('description')}}</textarea>
						{!!$errors->first('description','<div class="alert alert-danger">:message</div>')!!}
					</div>
					@if($task->status == 'waiting' && $task->assignee == Auth::id())
						<div class="btn btn-success col-md-12" id="accept_task" tid="{{ $task->id }}">Accept</div>
						<div class="btn btn-danger col-md-12 margin_top_10" id="reject_task" tid="{{ $task->id }}" >Reject</div>
					@else

							<div class="col-md-12">
								<button id="add_comment" tid="{{ $task->id }}" class="pull-right btn btn-primary">Add Comment</button>
							</div>
					@endif
				
			</div>
		</div>
	</div>
</div>
@endif
@if(Session::has('message'))
<script type="text/javascript">
$.notify("{{Session::get('message')}}",
                {
                   className:'success',
                   globalPosition:'top center'
                });
</script>
@endif