<div class="row">
	<div class="col-md-12 form-group">
		<button id="nextMinute" mid="{{$meeting->id}}" type="button" class="btn btn-primary pull-right">
			Proceed next minute of meeting
		</button>
		<div class="row">
			<div class="col-md-12" id="minuteBlock">
				<?php
					$participants = array();
	        		if($meeting->minuters)
	        		{
	        			$participants = explode(',',$meeting->minuters);
	        		}
	        		if($meeting->attendees)
	        		{
	        			foreach(explode(',',$meeting->attendees) as $attendees)
	        			{
	        				array_push($participants, $attendees);
	        			}
	        		}
	        		$users = App\Model\Profile::whereIn('userId',$participants)->lists('name','userId');
				?>
				@if($meeting->minutes()->first()->lock_flag)
					@include('meetings.createTask',['minute'=>$meeting->minutes()->first(),'usersList'=>$users])
				@else
				{!! Form::open(array('id' => 'createMinuteForm')) !!}
				{!! Form::text('venue',$meeting->venue) !!}
				{!! Form::text('minuteDate',date('Y-m-d')) !!}
				<div class="col-md-12" Id="attendees">
	        		<?php
    				foreach ($users as $key=>$value)
    				{
    					echo '<div class="col-md-2 attendees" uid="u'.$key.'"><input type="hidden" name="attendees[]" value="'.$key.'">'.$value.'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
    				}
	        			?>
	        	</div>
				{!! Form::close() !!}
				<button id="createMinute" mid="{{$meeting->id}}" type="button" class="btn btn-primary">
					Proceed
				</button>
				<div class="col-md-12" id="createMinuteError">
				</div>
				@endif
			</div>
		</div>
	</div>
</div>