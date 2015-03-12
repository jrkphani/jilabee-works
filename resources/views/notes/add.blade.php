@extends('master')
@section('css')
	<link href="{{ asset('/css/colorpicker.css') }}" rel="stylesheet">
@end
@section('usercontent')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-6">{{$minuteshistory->minute->title}}</div>
						<div class="col-md-4 col-md-offset-2">{{Auth::user()->name}}</div>
					</div>
				</div>
				<div class="panel-body">
						{!! Form::open(array('class'=>'form-horizontal','id'=>'notes_form', 'method'=>'POST','role'=>'form')) !!}
							<input type="hidden" id="minuteshistory_id" value="{{$minuteshistory->id}}">
							@if(($notesdraft) && ($notesdraft->first()))
								@foreach($notesdraft as $notes)
									<div class="row notes_form">
										<div class="col-md-3">
											<div class="form-group">
												<div class="col-md-12">
													{!! Form::text('title[]',$notes->title,array('class'=>"form-control",'placeholder'=>'Title','autocomplete'=>'off')) !!}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<div class="col-md-12">
													{!! Form::textarea('description[]',$notes->description,array('class'=>"form-control",'placeholder'=>'Description','autocomplete'=>'off','rows'=>1)) !!}
													
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="row">
												<div class="col-md-8">
													<div class="form-group">
														<div class="col-md-12">
															{!! Form::select('assignee[]',array('Assingee')+$users, $notes->assignee,array('class'=>"form-control",'autocomplete'=>'off')) !!}
															{{-- <select class="form-control" name="assignee[]" autocomplete="off">
																<option value="">Assingee</option>
																@foreach($users as $user)
																	<option value="{{$user->id}}">{{$user->name}}</option>
																@endforeach
															</select> --}}
														</div>
													</div>

												</div>
												<div class="col-md-4 btn remove_notes_form"><span class="glyphicon glyphicon-trash"></span></div>
											</div>
										</div>
									</div>
								@endforeach
							@endif
					<div class="row notes_form">
						<div class="col-md-3">
							<div class="form-group">
								<div class="col-md-12">
									{!! Form::text('title[]',old('title'),array('class'=>"form-control",'placeholder'=>'Title','autocomplete'=>'off')) !!}
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-md-12">
									<textarea type="text" autocomplete="off" class="form-control" rows="1" name="description[]" placeholder="Description">{{old('description')}}</textarea>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<div class="col-md-12">
											{!! Form::select('assignee[]',array('Assingee')+$users, '',array('class'=>"form-control",'autocomplete'=>'off')) !!}
											{{-- <select class="form-control" name="assignee[]" autocomplete="off">
												<option value="">Assingee</option>
												@foreach($users as $user)
													<option value="{{$user->id}}">{{$user->name}}</option>
												@endforeach
											</select> --}}
										</div>
									</div>

								</div>
								<div class="col-md-4 btn remove_notes_form"><span class="glyphicon glyphicon-trash"></span></div>
							</div>
						</div>
					</div>
					{!! Form::close() !!}
					<div class="row">
						<div class="col-md-8 col-md-offset-4">
							<div class="col-md-3">
								<button id="add_more" type="submit" class="btn btn-primary">Add more</button>
							</div>
							<div class="col-md-3">
								<button type="submit" class="btn btn-primary">Cancle</button>
							</div>
							<div class="col-md-3">
								<button id="save_changes" type="submit" class="btn btn-primary">Save Changes</button>
							</div>
							<div class="col-md-3">
								<button id="send_minute" type="submit" class="btn btn-primary">Send minutes</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="hidden" id="add_more_div">
	<div class="row notes_form">
		<div class="col-md-3">
			<div class="form-group">
				<div class="col-md-12">
					<input type="text" autocomplete="off" class="form-control" name="title[]" placeholder="Title" value="{{ old('title') }}">
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<div class="col-md-12">
					<textarea type="text" autocomplete="off" class="form-control" rows="1" name="description[]" placeholder="Description">{{old('description')}}</textarea>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="row">
				<div class="col-md-8">
					<div class="form-group">
						<div class="col-md-12">
							{!! Form::select('assignee[]',array('Assingee')+$users, '',array('class'=>"form-control",'autocomplete'=>'off')) !!}
						</div>
					</div>
				</div>
				<div class="col-md-4 btn remove_notes_form"><span class="glyphicon glyphicon-trash"></span></div>
			</div>
		</div>
	</div>
</div>

@endsection
@section('javascript')
     <script src="{{ asset('/js/add_notes.js') }}"></script>
@stop