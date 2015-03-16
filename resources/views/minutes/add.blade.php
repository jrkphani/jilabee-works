@extends('master')
@section('css')
	<link href="{{ asset('/css/colorpicker.css') }}" rel="stylesheet">
@end
@section('usercontent')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">Add Minute</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<div class="row">
									{!! Form::open(array('class'=>'form-horizontal','method'=>'POST','role'=>'form')) !!}
								<div class="col-md-4">
									<div class="form-group">
										<div class="col-md-12">
											{!! Form::text('title',old('title'),array('class'=>"form-control",'placeholder'=>'Title','autocomplete'=>'off')) !!}
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<div class="col-md-12">
											{!! Form::text('venue',old('venue'),array('class'=>"form-control",'placeholder'=>'Venue','autocomplete'=>'off')) !!}
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<div class="col-md-10">
											<div id="colorpicker" data-color-format="rgb" data-color="rgb(255, 146, 180)">
												{!! Form::text('label',old('label'),array('class'=>"form-control",'id'=>'label','autocomplete'=>'off','readonly'=>'readonly')) !!}
											</div>
										</div>
										<div class="col-md-2 btn">
											<span id="resetColor" class="glyphicon glyphicon-remove"></span>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-md-offset-6">
									<button type="submit" class="btn btn-primary">Add</button>
								</div>
								{!! Form::close() !!}
							</div>
						</div>
						<div class="col-md-2"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('javascript')
    <script src="{{ asset('/js/bootstrap-colorpicker.js') }}"></script>
     <script src="{{ asset('/js/add_minute.js') }}"></script>
@stop