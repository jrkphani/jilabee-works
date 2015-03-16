<link href="{{ asset('/css/bootstrap-dialog.min.css') }}" rel="stylesheet">
<?php $checkMid = NULL; ?>
@if($minutes)
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading" style="background-color:{{$minutes->label}}">
				{{$minutes->title}}
				@if(!$minutes->minute_history()->where('lock_flag','!=','0')->count())
					<span id="continue_minute" mid="{{$minutes->id}}" class="pull-right btn btn-primary" style="padding:0px">Continue Session</span>
				@endif
			</div>
			<div class="panel-body">
				<div class="table-responsive">       
				    <table class="table table-hover table-bordered">
				        <tbody>
				        	@if($minutes->minute_history()->first())
				        		@foreach($minutes->minute_history()->get() as $history)
				        			<tr>
				        				<td><table class="table borderless">
				        					<tr>
				        					<td>
					        					<?php $attendees = array_filter(explode(',',$history->attendees)); ?>
					        					@if($attendees)
					        					<ul class="list-group">
					        					@foreach($attendees as $userID)
	  												<li class="list-group-item">
	  													<a href=""><span class="glyphicon glyphicon-user"></span>
	  													{{App\User::find($userID)->name}}</a>
	  												</li>
					        					@endforeach
					        					</ul>
					        					@endif
					        				</td>
					        				<td>{{$history->venue}}</td>
					        				<td>
					        					<span class="glyphicon glyphicon-pencil"></span>
					        					<a href="">{{$history->updatedby->name}}
					        					<span class="glyphicon glyphicon-user"></span></a>
					        				</td>
					        			</tr>
				        				</table></td>
				        			</tr>
				        			
				        			@if($history->notes()->first())
				        			<tr>
				        				<td>
				        					<table class="table borderless">
				        						@foreach($history->notes()->get() as $notes)
				        						<tr>
							        				<td>	
							        					{{$notes->title}}
							        				</td>
							        				<td>	
							        					{{$notes->description}}
							        				</td>
							        				<td>
							        					@if($notes->assignee)
							        					<a href="">
															{{$notes->getassignee->name}}
															<span class="glyphicon glyphicon-user"></span>
														</a>
							        					
							        					@endif
							        				</td>
							        				<td>
							        					<span nid="{{$notes->id}}" class="glyphicon glyphicon-eye-open btn note"></span>
							        				</td>
							        			</tr>
						        				@endforeach
						        			</table>
				        				</td>
				        			</tr>
				        			@endif
				        				
				        		@endforeach
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
<script src="{{ asset('/js/bootstrap-dialog.min.js') }}"></script>
<script src="{{ asset('/js/add_minute_history.js') }}"></script>