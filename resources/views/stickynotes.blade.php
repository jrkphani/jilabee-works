<div class="row">
	<div class="col-md-12">
		{!! Form::open(['id'=>'sticknotes_form']) !!}
		{!! Form::textarea('description', '', ['id'=>'stick_text','cols'=>'60','rows'=>'3','autocomplete'=>'off']) !!}
		{!! Form::close() !!}
	</div>
	<div class="col-md-12">
		<span class="btn btn-primary pull-right" id="add_stick_notes">Add</span>
	</div>
	@foreach($stickynotes as $row)
		<div class="col-md-12 alert alert-info">
			<div class="col-md-12">
				<div class="col-md-10">
					<span class="date">{!! $row->updated_at !!}</span>
				</div>
				<div class="col-md-2">
					<span class="removeSticky btn glyphicon glyphicon-trash" sid="{{$row->id}}"></span>
				</div>
			</div>
			<div class="col-md-12">{!! $row->description !!}</div>
			<div class="col-md-10">{!! $row->description !!}</div>
			<div class="col-md-2"></div>
		</div>
	@endforeach
	
</div>