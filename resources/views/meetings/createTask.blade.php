NEW MINUTES
{!! Form::open(array('class'=>'form-horizontal','id'=>'tasksAddForm', 'method'=>'POST','role'=>'form')) !!}
<div id="taskAddBlock">
	@if($minute)
		<?php $drafts = $minute->draft(); ?>
		@if($drafts->count())
			@foreach($drafts->get() as $draft)
			<div class="taskBlock">
				<div class="pull-right"><span class="removeTaskFrom">Remove</span></div>
				<p>{!! Form::select('type[]',['task'=>'Task','idea'=>'Idea'],$draft->type,array('class'=>'type','autocomplete'=>'off')) !!}
				Status
				{!! Form::select('',[''=>'Draft'],'',array('disabled'=>'disabled')) !!}</p>

				<p>{!! Form::text('title[]',$draft->title,array('placeholder'=>'Title','autocomplete'=>'off')) !!}

				{!! Form::textarea('description[]',$draft->description,array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5)) !!}</p>

				<p>{{$draft->assinger}} {!! Form::select('assigner[]',array(''=>'Assinger')+$attendees,$draft->assinger,array('autocomplete'=>'off')) !!}
			
				{!! Form::select('assignee[]',array(''=>'Assingee')+$attendees,$draft->assignee,array('autocomplete'=>'off')) !!}

				{!! Form::text('dueDate[]',$draft->dueDate,array('class'=>"dateInput",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}

				{!! Form::select('orginator[]',array(''=>'Orginator')+$attendees,$draft->orginator,array('autocomplete'=>'off')) !!}</p>
			</div>
			@endforeach
		@endif
	@endif
	<div class="taskBlock">
		<div class="pull-right"><span class="removeTaskFrom">Remove</span></div>
		<p>{!! Form::select('type[]',['task'=>'Task','idea'=>'Idea'],'',array('class'=>'type','autocomplete'=>'off')) !!}
		Status
		{!! Form::select('',[''=>'Draft'],'',array('disabled'=>'disabled')) !!}</p>

		<p>{!! Form::text('title[]','',array('placeholder'=>'Title','autocomplete'=>'off')) !!}

		{!! Form::textarea('description[]','',array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5)) !!}</p>

		<p>{!! Form::select('assigner[]',array(''=>'Assinger')+$attendees,'',array('autocomplete'=>'off')) !!}
	
		{!! Form::select('assignee[]',array(''=>'Assingee')+$attendees,'',array('autocomplete'=>'off')) !!}

		{!! Form::text('dueDate[]','',array('class'=>"dateInput",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}

		{!! Form::select('orginator[]',array(''=>'Orginator')+$attendees,'',array('autocomplete'=>'off')) !!}</p>
	</div>
</div>
{!! Form::close() !!}
<p>
	<div id="createTaskError">	</div>
	<button id="send_minute" mid="{{$minute->id}}" type="submit" class="btn btn-primary">Send minutes</button>
	<button id="save_changes"  mid="{{$minute->id}}" type="submit" class="btn btn-primary">Save Draft</button>
	<button id="add_more" mid="{{$minute->id}}" type="submit" class="btn btn-primary pull-right">Add more</button>
</p>
