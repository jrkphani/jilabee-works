<div class="row">
	<div class="col-md-6 col-md-offset-6">
		<div class="col-md-6">
			<button type="button" class="btn btn-primary" id="refresh">Refresh</button>
		</div>
		<div class="col-md-6">
			<button id="createTaskToggle" type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTaskModal">
		  Create Task
			</button>
		</div>
	</div>
	<div class="col-md-12">
		<div class="col-md-3">
			@if($drafts->count())
			<ul>
				@foreach($drafts as $draft)
					<li tid="{{$draft->id}}" class="followupDraft">{{$draft->title}}<span class="pull-right">draft</span></li>
				@endforeach
			</ul>
			@endif
			@if($tasks->count())
			<ul>
				@foreach($tasks as $task)
					@if($task->type == 'minute')
						<li myid="{{$task->id}}" mid="{{$task->minuteId}}" class="followup">{{$task->title}}</li>
					@else
						<li myid="{{$task->id}}" class="followup">{{$task->title}}</li>	
					@endif				
				@endforeach
			</ul>
			@else
			No Tasks to follow
			@endif
		</div>
		<div class="col-md-9" id="rightContent">
			right content
		</div>
	</div>

	<div id="createTaskModal" class="modal fade bs-example-modal-lg">
	  <div class="modal-dialog  modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Create Task</h4>
	      </div>
	      <div class="modal-body">
	        <div class="row">
		        {!! Form::open(array('id' => 'createTaskForm')) !!}
		        {{-- ajax form load dynamic --}}
		        loading...
		        {!! Form::close() !!}
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button id="createTaskSave" type="button" class="btn btn-primary">Save Draft</button>
	        <button id="createTaskSubmit" type="button" class="btn btn-primary">Save</button>
	      </div>
	    </div>

	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>