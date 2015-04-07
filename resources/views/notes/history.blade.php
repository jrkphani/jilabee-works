@if($notes)
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<p>{{$notes->title}}</p>
				{{$notes->minute_history->minute->title}} | 
				{{$notes->minute_history->created_at}} | 
				{{$notes->minute_history->venue}} |
				@if($notes->assigner)
				{{$notes->getassigner->name}}
				@endif
				<span class="pull-right">
					Status: <span class="{{$notes->status}}">{{$notes->status}}</span>
				</span>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<div class="col-md-12 alert alert-info" role="alert">
						{!! nl2br($notes->description) !!}
					</div>
					@foreach($notes->notes_history()->get() as $history)
						<div class="col-md-8 border_top">
							{!! nl2br($history->description) !!}
						</div>
						<div class="col-md-4 border_top">
							<a href="{{ app_url('/profile/') }}" >{!! nl2br($history->createdby->name) !!}
							<span class="glyphicon glyphicon-user"></span></a>
						</div>
					@endforeach
				</div>
					<div class="col-md-12">
						<textarea id="description" class="form-group col-md-12" rows='3' placeholder="description"></textarea>
						{!!$errors->first('description','<div class="alert alert-danger">:message</div>')!!}
						@if($notes->status == 'waiting' && $notes->where('id','=',$notes->id)->whereRaw('FIND_IN_SET('.Auth::id().',assignee)')->count())
							<div class="col-md-12">
								<div class="col-md-2">
									<button id="accept_task" nid="{{ $notes->id }}" class="pull-right btn btn-success">Accept</button>
								</div>
								<div class="col-md-10">
									<button id="reject_task" nid="{{ $notes->id }}" class="pull-right btn btn-danger">Reject</button>
								</div>
							</div>
						@else
							<div class="col-md-12">
								<div class="col-md-12">
									<button id="add_comment" nid="{{ $notes->id }}" class="pull-right btn btn-primary">Add Comment</button>
								</div>
							</div>
						@endif
					</div>
				
			</div>
		</div>
	</div>
</div>
@endif
