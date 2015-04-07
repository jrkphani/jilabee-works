<link href="{{ asset('/css/bootstrap-dialog.min.css') }}" rel="stylesheet">
<?php $canedit = 0; ?>
@if($minuteshistory)
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading" {{-- style="background-color:{{$minuteshistory->minute->label}}" --}}>
				<div class="row">
						<div class="col-md-3">{{$minuteshistory->minute->title}}</div>
						<div class="col-md-3">{{$minuteshistory->venue}}</div>
						<div class="col-md-3">{{$minuteshistory->created_at}}</div>
						<div class="col-md-3">
							<span class="glyphicon glyphicon-pencil"></span>
							<a href="{{url('profile/'.$minuteshistory->created_by)}}">
	  							{{$minuteshistory->createdby->name}}
	  							<span class="glyphicon glyphicon-user"></span>
	  						</a>
						</div>
						<?php
						$attendees = App\User::whereIn('id',explode(',', $minuteshistory->attendees))
							->lists('name','id');
						$absentes = App\User::whereIn('id',array_diff(explode(',', $minuteshistory->minute->attendees),
							explode(',', $minuteshistory->attendees)))
							->lists('name','id');
						?>
					     @if($attendees)
						<div class="list-group alert alert-success col-md-12 margin_top_20">
					    	@foreach($attendees as $key=>$value)
	  							<a {{-- class="list-group-item" --}} href="{{url('profile/'.$key)}}">
	  								<span class="glyphicon glyphicon-user"></span>
	  								{{$value}}
	  							</a>
					    	@endforeach
					    </div>
					    @endif
					    @if($absentes)
						<div class="list-group alert alert-danger col-md-12 ">
					    	@foreach($absentes as $key=>$value)
	  							<a {{-- class="list-group-item" --}} href="">
	  								<span class="glyphicon glyphicon-user"></span>
	  								{{$value}}
	  							</a>
					    	@endforeach
					    </div>
					    @endif
					    @if($minuteshistory->minute->hasPermissoin())
						<?php $canedit = 1; ?>
						<div class="col-md-12">
							<span mid="{{$minuteshistory->minute->id}}" class="pull-right btn btn-primary add_next_minute" style="padding:0px">Continue Session</span>
						</div>
						@endif
					</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">       
				    <table class="table table-hover table-bordered">
				        <tbody>
				        	@if($minuteshistory->notes()->first())
				        			<tr>
				        				<td>
				        					<table class="table borderless">
				        						@foreach($minuteshistory->notes()->orderBy('updated_at', 'desc')->get() as $notes)
				        						<tr>
							        				<td>	
							        					{{$notes->title}}
							        				</td>
							        				<td>	
							        					{{$notes->description}}
							        				</td>
							        				<td>
							        					@if($notes->assigner)
							        					<a href="{{ app_url('/profile/').$notes->assignee}}">
															{{$notes->getassigner->name}}
															<span class="glyphicon glyphicon-user"></span>
															<span class="glyphicon glyphicon-hand-right"></span>
														</a>
							        					
							        					@endif
							        					@if($notes->assignee)
							        					<a href="{{ app_url('/profile/').$notes->assignee}}">
															{{$notes->getassignee->name}}
															<span class="glyphicon glyphicon-user"></span>
														</a>
							        					@endif
							        				</td>
							        				<td>	
							        					{{$notes->due}}
							        				</td>
							        				<td>
							        					<span class="{{$notes->status}}">{{$notes->status}}</span>
							        				</td>
							        				@if($canedit)
							        				<td>	
							        					<span nid="{{$notes->id}}" class="btn glyphicon glyphicon-edit edit_note"></span>
							        				</td>
							        				@endif
							        			</tr>
						        				@endforeach
						        			</table>
				        				</td>
				        			</tr>
				        	@else
				        	No date to display!
				        	@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endif