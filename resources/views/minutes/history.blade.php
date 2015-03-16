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
				    <table class="table">
				        <tbody>
				        	@if($minutes->minute_history()->first())
				        		@foreach($minutes->minute_history()->get() as $history)
				        			<tr class="bg-info">
				        				<td>
				        					<?php $attendees = explode(',',$history->attendees); ?>
				        					<ul class="list-group">
				        					@foreach($attendees as $userID)
  												<li class="list-group-item">
  													<a href=""><span class="glyphicon glyphicon-user"></span>
  													{{App\User::find($userID)->name}}</a>
  												</li>
				        					@endforeach
				        					</ul>
				        				</td>
				        				<td>{{$history->venue}}</td>
				        				<td>
				        					<span class="glyphicon glyphicon-pencil"></span>
				        					<a href="">{{$history->updatedby->name}}
				        					<span class="glyphicon glyphicon-user"></span></a>
				        				</td>
				        			</tr>
				        			<tr>
				        				<td>notes here  </td>
				        			</tr>
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