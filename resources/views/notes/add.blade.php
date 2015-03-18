@extends('user')
@section('rightcontent')
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-3">{{$minuteshistory->minute->title}}</div>
						<div class="col-md-3">{{$minuteshistory->minute->venue}}</div>
						<div class="col-md-3">{{$minuteshistory->minute->created_at}}</div>
						<div class="col-md-3">
							<span class="glyphicon glyphicon-pencil"></span>
							<a href="">
	  							{{Auth::user()->name}}
	  							<span class="glyphicon glyphicon-user"></span>
	  						</a>
						</div>
						<?php $attendees = array_filter(explode(',',$minuteshistory->attendees)); ?>
					     @if($attendees)
						<div class="list-group col-md-12 margin_top_20">
					    	@foreach($attendees as $userID)
	  							<a {{-- class="list-group-item" --}} href="">
	  								<span class="glyphicon glyphicon-user"></span>
	  								{{App\User::find($userID)->name}}
	  							</a>
					    	@endforeach
					    </div>
					    @endif
					</div>
				</div>
				<div class="panel-body">
						{!! Form::open(array('class'=>'form-horizontal','id'=>'notes_form', 'method'=>'POST','role'=>'form')) !!}
							@if($minuteshistory->notes_draft()->first())
								@foreach($minuteshistory->notes_draft()->get() as $notes)
									<div class="row notes_form">
										<div class="col-md-3">
											<div class="form-group">
												<div class="col-md-12">
													{!! Form::text('title[]',$notes->title,array('class'=>"form-control",'placeholder'=>'Title','autocomplete'=>'off')) !!}
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<div class="col-md-12">
													{!! Form::textarea('description[]',$notes->description,array('class'=>"form-control",'placeholder'=>'Description','autocomplete'=>'off','rows'=>1)) !!}
													
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<div class="col-md-12">
													{!! Form::text('due[]',$notes->due,array('class'=>"form-control",'placeholder'=>'Due Date','autocomplete'=>'off')) !!}
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
						<div class="col-md-4">
							<div class="form-group">
								<div class="col-md-12">
									<textarea type="text" autocomplete="off" class="form-control" rows="1" name="description[]" placeholder="Description">{{old('description')}}</textarea>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class="col-md-12">
									{!! Form::text('due[]','',array('class'=>"form-control",'placeholder'=>'Due Date','autocomplete'=>'off')) !!}
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
								<button id="save_changes" mhid={{$minuteshistory->id}} type="submit" class="btn btn-primary">Save Changes</button>
							</div>
							<div class="col-md-3">
								<button id="send_minute" mhid={{$minuteshistory->id}} type="submit" class="btn btn-primary">Send minutes</button>
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
									{!! Form::text('title[]',old('title'),array('class'=>"form-control",'placeholder'=>'Title','autocomplete'=>'off')) !!}
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<div class="col-md-12">
									<textarea type="text" autocomplete="off" class="form-control" rows="1" name="description[]" placeholder="Description">{{old('description')}}</textarea>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class="col-md-12">
									{!! Form::text('due[]','',array('class'=>"form-control",'placeholder'=>'Due Date','autocomplete'=>'off')) !!}
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