@extends('master')
@section('usercontent')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-3">{{$minute->meeting->title}}</div>
						<div class="col-md-3">{{$minute->venue}}</div>
						<div class="col-md-3">{{$minute->dt}}</div>
						<div class="col-md-3">
							<span class="glyphicon glyphicon-pencil"></span>
							<a href="">
	  							{{Auth::user()->name}}
	  							
	  						</a>
						</div>
						<?php
						$attendees = App\User::whereIn('id',explode(',', $minute->attendees))
							->lists('name','id');
						$absentees = App\User::whereIn('id',explode(',', $minute->absentees))
							->lists('name','id');
						?>
					     @if($attendees)
						<div class="list-group alert alert-success col-md-12 margin_top_20">
					    	@foreach($attendees as $key=>$value)
	  							<a href="{{url('profile/'.$key)}}">
	  								
	  								{{$value}}
	  							</a>
					    	@endforeach
					    </div>
					    @endif
					    @if($absentees)
						<div class="list-group alert alert-danger col-md-12 ">
					    	@foreach($absentees as $key=>$value)
	  							<a href="{{url('profile/'.$key)}}">
	  								
	  								{{$value}}
	  							</a>
					    	@endforeach
					    </div>
					    @endif
					</div>
				</div>
				<div class="panel-body">
						{!! Form::open(array('class'=>'form-horizontal','id'=>'tasksAddForm', 'method'=>'POST','role'=>'form')) !!}
							@if($minute->tasks_draft()->first())
								@foreach($minute->tasks_draft()->get() as $task)
								<?php $display=''; if($task->taskidea == 'idea') { $display='none';} ?>
								
									<div class="row task_form border_bottom padding_top_20">
										<div class="task_form_non_edit">
											<div class="col-md-12" style="min-height: 14px"><a href="" class="edit_icon  glyphicon glyphicon-edit pull-right" style="display:none;"></a></div> 
											<div class="col-md-8">
												<div class="col-md-12">{{$task->title}}</div>
												<div class="col-md-12 margin_top_20" style="max-height: 100px; min-height: 100px; overflow-y: scroll;">{!! nl2br($task->description) !!}</div>
											</div>
											<div class="col-md-4">
												<div class="col-md-12">
													<div class="col-md-6">{{$task->taskidea}}</div>
													<div class="col-md-6">
														@if($task->due && $task->taskidea == 'task')
															{{date('Y-m-d',strtotime($task->due))}}
														@endif
													</div>
												</div>
										
												<div class="col-md-12">
													<div class="col-md-6  margin_top_20">
														@if($task->assigner)
															{{$users[$task->assigner]}}
														@endif
													</div>
													<div class="col-md-6  margin_top_20">
														@if($task->assignee && $task->taskidea == 'task')
															{{$users[$task->assignee]}}
														@endif
													</div>
												</div>	
											</div>
										</div>
										<div class="col-md-12 task_form_edit" style="display:none;">
											<div class="col-md-8">
												<div class="form-group">
													<div class="col-md-12">
														{!! Form::text('title[]',$task->title,array('class'=>"form-control",'placeholder'=>'Title','autocomplete'=>'off')) !!}
													</div>
												</div>
												<div class="form-group">
													<div class="col-md-12">
														{!! Form::textarea('description[]',$task->description,array('class'=>"form-control",'placeholder'=>'Description','autocomplete'=>'off','rows'=>5)) !!}
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="col-md-12 form-group">
													<div class="col-md-6">
														{!! Form::select('taskidea[]',['task'=>'Task','idea'=>'Idea'],$task->taskidea,array('class'=>"form-control taskidea",'autocomplete'=>'off')) !!}
													</div>
													<div class="col-md-6 taskinput" style="display:{{$display}}">
														<?php
															$due = '';
															if($task->due)
															$due = date('Y-m-d',strtotime($task->due));
														?>
														{!! Form::text('due[]',$due,array('class'=>"form-control dateInput",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}
													</div>
												</div>
												
												<div class="col-md-12 form-group">
													<div class="col-md-6">
														{!! Form::select('assigner[]',array('Assinger')+$users, explode(',',$task->assigner),array('class'=>"form-control",'autocomplete'=>'off')) !!}
														
													</div>
													<div class="taskinput col-md-6" style="display:{{$display}}">
														{!! Form::select('assignee[]',array(''=>'Assingee')+$users, explode(',',$task->assignee),array('class'=>"form-control",'autocomplete'=>'off')) !!}
														
													</div>
												</div>
												<div class="col-md-12 form-group">
													<div class="col-md-4 col-md-offset-1 btn btn-default ok_task_form">Ok</div>
													<div class="col-md-4 col-md-offset-2 btn btn-warning remove_task_form">Discard</div>
												</div>
												
											</div>	
										</div>
										
									</div>
								@endforeach
							@else
							<div class="mask_layer"></div>
							<div class="row task_form border_bottom padding_top_20">
										<div class="task_form_non_edit">
										</div>
										<div class="col-md-12 task_form_edit">
											<div class="col-md-8">
												<div class="form-group">
													<div class="col-md-12">
														{!! Form::text('title[]','',array('class'=>"form-control",'placeholder'=>'Title','autocomplete'=>'off')) !!}
													</div>
												</div>
												<div class="form-group">
													<div class="col-md-12">
														{!! Form::textarea('description[]','',array('class'=>"form-control",'placeholder'=>'Description','autocomplete'=>'off','rows'=>5)) !!}
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="col-md-12 form-group">
													<div class="col-md-6">
														{!! Form::select('taskidea[]',['task'=>'Task','idea'=>'Idea'],'',array('class'=>"form-control taskidea",'autocomplete'=>'off')) !!}
													</div>
													<div class="col-md-6 taskinput" style="display:{{$display}}">
														<?php
															$due = '';
															if($task->due)
															$due = date('Y-m-d',strtotime($task->due));
														?>
														{!! Form::text('due[]','',array('class'=>"form-control dateInput",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}
													</div>
												</div>
												
												<div class="col-md-12 form-group">
													<div class="col-md-6">
														{!! Form::select('assigner[]',array('Assinger')+$users,'',array('class'=>"form-control",'autocomplete'=>'off')) !!}
														
													</div>
													<div class="taskinput col-md-6" style="display:{{$display}}">
														{!! Form::select('assignee[]',array(''=>'Assingee')+$users,'',array('class'=>"form-control",'autocomplete'=>'off')) !!}
														
													</div>
												</div>
												<div class="col-md-12 form-group">
													<div class="col-md-4 col-md-offset-1 btn btn-default ok_task_form">Ok</div>
													<div class="col-md-4 col-md-offset-2 btn btn-warning remove_task_form">Discard</div>
												</div>
											</div>
										</div>	
									</div>
						@endif
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					{!! Form::close() !!}
					<div class="row margin_top_10">
						<div class="col-md-6">
							<button id="send_minute" mhid={{$minute->id}} type="submit" class="btn btn-primary">Send minutes</button>
						</div>
						<div class="col-md-4">
							<button id="save_changes" mhid="{{$minute->id}}" type="submit" class="btn btn-primary">Save</button>
						</div>
						<div class="col-md-2">
							<span id="add_more" type="submit" class="btn btn-primary pull-right">Add more</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="hidden" id="add_more_div">
	<div class="mask_layer" style="display:none;"></div>
	<div class="row task_form border_bottom padding_top_20">
		<div class="task_form_non_edit"></div>
			<div class="col-md-12 task_form_edit">									
										<div class="col-md-8">
											<div class="form-group">
												<div class="col-md-12">
													{!! Form::text('title[]','',array('class'=>"form-control",'placeholder'=>'Title','autocomplete'=>'off')) !!}
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-12">
													{!! Form::textarea('description[]','',array('class'=>"form-control",'placeholder'=>'Description','autocomplete'=>'off','rows'=>5)) !!}
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="col-md-12 form-group">
												<div class="col-md-6">
													{!! Form::select('taskidea[]',['task'=>'Task','idea'=>'Idea'],'',array('class'=>"form-control taskidea",'autocomplete'=>'off')) !!}
												</div>
												<div class="col-md-6 taskinput" style="display:{{$display}}">
													<?php
														$due = '';
														if($task->due)
														$due = date('Y-m-d',strtotime($task->due));
													?>
													{!! Form::text('due[]','',array('class'=>"form-control dateInput",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}
												</div>
											</div>
											
											<div class="col-md-12 form-group">
												<div class="col-md-6">
													{!! Form::select('assigner[]',array('Assinger')+$users,'',array('class'=>"form-control",'autocomplete'=>'off')) !!}
													
												</div>
												<div class="taskinput col-md-6" style="display:{{$display}}">
													{!! Form::select('assignee[]',array(''=>'Assingee')+$users,'',array('class'=>"form-control",'autocomplete'=>'off')) !!}
													
												</div>
											</div>
											<div class="col-md-12 form-group">
												<div class="col-md-4 col-md-offset-1 btn btn-default ok_task_form">Ok</div>
												<div class="col-md-4 col-md-offset-2 btn btn-warning remove_task_form">Discard</div>
											</div>
											
										</div>	
									</div>
								</div>
</div>
</div>
@endsection
@section('javascript')		
   	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
   	<script src="{{ asset('/js/bootstrap-datepicker.min.js') }}"></script>
   	
   	<script language="JavaScript">
   	var needToConfirm = true;
	window.onbeforeunload = confirmExit;
	function confirmExit()
	{
		if (needToConfirm)
		{
			return 'You want to leave - data you have entered may not be saved.';	
		}
	}
	</script>
	<script src="{{ asset('/js/add_tasks.js') }}"></script>
@stop
@section('css')		
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link href="{{ asset('/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@stop