{!! Form::open(['id'=>'taskEditForm'])!!}
<div class="row">
	<div class="col-md-12">
		<div class="col-md-3">
				{!! Form::text('title',$task->title,array('class'=>"form-control",'placeholder'=>'Title','autocomplete'=>'off')) !!}
				{!! $errors->first('title','<div class="alert alert-info">:message</div>')!!}
		</div>
		<div class="col-md-6">
					{!! Form::textarea('description',$task->description,array('class'=>"form-control",'placeholder'=>'Description','autocomplete'=>'off','rows'=>1)) !!}
					{!! $errors->first('description','<div class="alert alert-info">:message</div>')!!}
		</div>
		<div class="col-md-3">
					{!! Form::text('due',date('Y-m-d',strtotime($task->due)),array('class'=>"form-control dateInput",'placeholder'=>'Due Date','autocomplete'=>'off')) !!}
					{!! $errors->first('due','<div class="alert alert-info">:message</div>')!!}
		</div>
	</div>
	
	<div class="col-md-12 margin_top_20">
			<div class="col-md-6">
				{!! Form::select('assigner',array('Assinger')+$users, explode(',',$task->assigner),array('class'=>"form-control",'autocomplete'=>'off')) !!}
				{!! $errors->first('assigner','<div class="alert alert-info">:message</div>')!!}
			</div>
			<div class="col-md-6">
				{!! Form::select('assignee',array('Assingee')+$users, explode(',',$task->assignee),array('class'=>"form-control",'autocomplete'=>'off')) !!}
				{!! $errors->first('assignee','<div class="alert alert-info">:message</div>')!!}
			</div>
	</div>
	<div class="col-md-12">
		@if($task->comments()->count())
			@foreach($task->comments()->get() as $comment)
				<div class="col-md-8 border_top">
					{!! nl2br($comment->description) !!}
				</div>
				<div class="col-md-4 border_top">
					<a href="{{ app_url('/profile/'.$comment->created_by) }}" >{!! nl2br($comment->createdby->name) !!}
					</a>
				</div>
			@endforeach
		@endif
	</div>
</div>
{!! Form::close()!!}
<script type="text/javascript">
$('.dateInput').datepicker({format: "yyyy-mm-dd",startDate: "today"});
</script>