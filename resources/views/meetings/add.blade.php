@extends('master')
@section('usercontent')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Add Meeting</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<div class="row">
									{!! Form::open(array('id'=>'meetingAddForm','class'=>'form-horizontal','role'=>'form')) !!}
									<div class="col-md-12">
										<div class="col-md-6">
											<div class="form-group">
												<div class="col-md-12">
													{!! Form::text('title',old('title'),array('class'=>"form-control",'placeholder'=>'Title','autocomplete'=>'off')) !!}
													{!! $errors->first('title', '<span class="error">:message</span>') !!}
												</div>
											</div>
										</div>
									
										<div class="col-md-6">
											<div class="form-group">
												<div class="col-md-12">
													{!! Form::text('venue',old('venue'),array('class'=>"form-control",'placeholder'=>'Venue','autocomplete'=>'off')) !!}
													{!! $errors->first('venue', '<span class="error">:message</span>') !!}
												</div>
											</div>
										</div>
									</div>

								<div class="col-md-12" >
									<div class="col-md-6">
										{!! Form::text('','',array('class'=>"form-control",'placeholder'=>'Select Minuter','autocomplete'=>'off','id'=>'searchMinuter')) !!}
										{!! $errors->first('minuters', '<span class="error">:message</span>') !!}
										<div id="selected_minuters" class="col-md-12" >
											<h5>Minuters</h5>
											@if(Session::has('minuters'))
												@foreach(Session::get('minuters') as $key => $value)
													<div class="col-md-6 attendees" id="u{{$value}}">
														<input type="hidden" name="minuters[]" value="{{$value}}">{{App\User::find($value)->name}}<span class="removeParent btn glyphicon glyphicon-trash"></span>
													</div>
												@endforeach
											@endif
										</div>
									</div>

									<div class="col-md-6">
										{!! Form::text('','',array('class'=>"form-control",'placeholder'=>'Select Attendees','autocomplete'=>'off','id'=>'searchAttendess')) !!}
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
								</div>
								
								<div class="col-md-12">
									<a href="{{ URL::previous() }}" class="btn btn-primary">Cancel</a>
									<button id ="savemeeting" type="submit" class="btn btn-primary pull-right">Save</button>
								</div>
								{!! Form::close() !!}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('javascript')		
   	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
   	<script src="{{ asset('/js/add_meeting.js') }}"></script>
@stop
@section('css')		
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
@stop