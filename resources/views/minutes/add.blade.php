@extends('master')
@section('usercontent')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Add Minute</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<div class="row">
									{!! Form::open(array('id'=>'minuteform','class'=>'form-horizontal','role'=>'form')) !!}
								<div class="col-md-6">
									<div class="col-md-12">
										<div class="form-group">
											<div class="col-md-12">
												{!! Form::text('title',old('title'),array('class'=>"form-control",'placeholder'=>'Title','autocomplete'=>'off')) !!}
												{!! $errors->first('title', '<span class="error">:message</span>') !!}
											</div>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<div class="col-md-12">
												{!! Form::text('venue',old('venue'),array('class'=>"form-control",'placeholder'=>'Venue','autocomplete'=>'off')) !!}
												{!! $errors->first('venue', '<span class="error">:message</span>') !!}
											</div>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label class="col-md-3 control-label">Label</label>
											<div class="col-md-6">
												<div id="colorpicker" data-color-format="rgb" data-color="rgb(255, 146, 180)">
													{!! Form::text('label',old('label'),array('class'=>"form-control",'id'=>'label','autocomplete'=>'off','readonly'=>'readonly','onclick'=>"getcolor()")) !!}
												</div>
												{!! $errors->first('label', '<span class="error">:message</span>') !!}
											</div>
											<div class="col-md-2 btn">
												<span id="resetColor" class="glyphicon glyphicon-refresh"></span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6" >
									
									{!! Form::text('','',array('class'=>"form-control",'placeholder'=>'Select Minuter','autocomplete'=>'off','id'=>'searchMinuter')) !!}
									{!! $errors->first('minuters', '<span class="error">:message</span>') !!}
									<div id="selected_minuter" class="col-md-12" >
										<h5>Minuters</h5>
										@if(Session::has('minuters'))
											@foreach(Session::get('minuters') as $key => $value)
												<div class="col-md-6 attendees" id="u{{$value}}">
													<input type="hidden" name="minuters[]" value="{{$value}}">{{App\User::find($value)->name}}<span class="removeParent btn glyphicon glyphicon-trash"></span>
												</div>
											@endforeach
										@endif
									</div>

									
									{!! Form::text('','',array('class'=>"form-control",'placeholder'=>'Select Attendees','autocomplete'=>'off','id'=>'searchUser')) !!}
									{!! $errors->first('attendees', '<span class="error">:message</span>') !!}
									<div id="selected_attendees" class="col-md-12" >
										<h5>Attendees</h5>
										@if(Session::has('attendees'))
											@foreach(Session::get('attendees') as $key => $value)
												<div class="col-md-6 attendees" id="u{{$value}}">
													<input type="hidden" name="attendees[]" value="{{$value}}">{{App\User::find($value)->name}}<span class="removeParent btn glyphicon glyphicon-trash"></span>
												</div>
											@endforeach
										@endif
									</div>
								</div>
								
								<div class="col-md-6 col-md-offset-6">
									<button id ="saveminute" type="submit" class="btn btn-primary">Save</button>
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
   	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
   	<script src="{{ asset('/js/add_minute.js') }}"></script>
@stop
@section('css')		
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
@stop