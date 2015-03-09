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
						<div class="col-md-2"></div>
						<div class="col-md-8">
							<div class="row">
								<form class="form-horizontal" role="form" method="POST" action="#">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-4 control-label">Title</label>
										<div class="col-md-8">
											<input type="text" class="form-control" name="title" placeholder="Title" value="{{ old('title') }}">
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-4 control-label">Label</label>
										<div class="col-md-6">
											{{-- <input type="text" class="form-control" id="flag" name="flag" placeholder="Title" value="{{ old('title') }}"> --}}
											{{-- <span class="colorpicker"></span> --}}
											<div id="colorpicker" data-color-format="rgb" data-color="rgb(255, 146, 180)">
												<input type="text" autocomplete="off" readonly="readonly" value="" class="form-control" id="label" name="label">
											</div>
										</div>
										<div class="col-md-2 btn">
											<span id="resetColor" class="glyphicon glyphicon-remove"></span>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-md-offset-4">
									<button type="submit" class="btn btn-primary">Add</button>
								</div>
								</form>
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