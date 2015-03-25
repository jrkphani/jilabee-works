<link href="{{ asset('/css/bootstrap-dialog.min.css') }}" rel="stylesheet">
<?php $checkMid = NULL; ?>
@if($minute_history)
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading" style="background-color:{{$minute_history->minute->label}}">
				{{$minute_history->minute->title}}
				@if($minute_history->lock_flag == 0)
					<span id="continue_minute" mid="{{$minute_history->minute->id}}" class="pull-right btn btn-primary" style="padding:0px">Continue Session</span>
				@endif
			</div>
			<div class="panel-body">
				<div class="table-responsive">       
				    <table class="table table-hover table-bordered">
				        <tbody>
				        	@if($minute_history->notes()->first())
				        			<tr>
				        				<td>
				        					<table class="table borderless">
				        						@foreach($minute_history->notes()->orderBy('updated_at', 'desc')->get() as $notes)
				        						<tr>
							        				<td>	
							        					{{$notes->title}}
							        				</td>
							        				<td>	
							        					{{$notes->description}}
							        				</td>
							        				<td>
							        					@if($notes->assignee)
							        					<a href="{{ app_url('/profile/').$notes->assignee}}">
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