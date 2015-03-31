<div class="row">
	@foreach($stickynotes as $row)
		<div class="col-md-12 alert alert-info">
			<div class="col-md-10">{{$row->description}}</div>
			<div class="col-md-2"><span class="removeSticky btn glyphicon glyphicon-trash"></span></div>
		</div>
	@endforeach
	<div class="col-md-12">
		{!! Form::open(['id'=>'sticknotes_form']) !!}
		{!! Form::textarea('stick_text', '', ['id'=>'stick_text','cols'=>'60','rows'=>'3']) !!}
		{!! Form::close() !!}
	</div>
	<div class="col-md-12">
		<span class="btn btn-primary pull-right" id="add_stick_notes">Add</span>
	</div>
</div>