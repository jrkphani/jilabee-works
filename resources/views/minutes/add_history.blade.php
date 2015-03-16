<div class="row">
		{!! Form::open(array('class'=>'form-horizontal','method'=>'POST','role'=>'form', 'id'=>'minute_history_form')) !!}
	<div class="col-md-4">
		<div class="form-group">
			<div class="col-md-12">
				{!! Form::text('venue',$minutes->venue,array('class'=>"form-control",'placeholder'=>'Venue','autocomplete'=>'off')) !!}
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="form-group">
			<label class="col-md-4 control-label">Attendees</label>
			<div class="col-md-8">
				{!! Form::select('attendees[]',$users,'',array('class'=>"form-control",'autocomplete'=>'off','multiple'=>'multiple')) !!}
			</div>
		</div>
	</div>
	{!! Form::close() !!}
</div>