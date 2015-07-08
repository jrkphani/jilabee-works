<div class="row">
	<div class="col-md-6 col-md-offset-6">
		<div class="col-md-6">
			<button type="button" class="btn btn-primary" id="refresh">Refresh</button>
		</div>
	</div>
	<div class="col-md-12">
		<div class="col-md-3">
			<div class="col-md-12 form-group">
				<button id="newMeetingToggle" type="button" class="btn btn-primary" data-toggle="modal" data-target="#createMeetingModal">
					Request meeting creation
				</button>
			</div>
			@if($tempMeetings->count())
				Requested Meetings
				<ul>
				@foreach($tempMeetings as $meeting)
				<li class="tempMeeting" mid="{{$meeting->id}}" data-toggle="modal" data-target="#loadMeetingModal">
					<div class="col-md-10">{{$meeting->title}}</div>
					<div class="col-md-2">{{$meeting->status}}</div>
				</li>
				@endforeach
			</ul>
			@endif			
			@if($mymeetings->count())
			<div class="col-md-12">My Meetings</div>
			<div class="col-md-6">
				{!! Form::text('filter', '',['class'=>'form-control'])!!}
			</div>
			<div class="col-md-6">
				{!! Form::select('sortby', array('frequency' => 'Frequency', 'status' => 'Status'),'frequency',['class'=>'form-control']) !!}
			</div>
			<ul>
				@foreach($mymeetings as $meeting)
					@if($meeting->minutes()->count())
					<li class="meetings" mid="{{$meeting->id}}/{{$meeting->minutes()->first()->id}}">{{$meeting->title}}
					<ul>
						@foreach($meeting->minutes()->orderBy('updated_at','desc')->get() as $minute)
						<li class="minute" mid="{{$meeting->id}}/{{$minute->id}}">{{date('Y-m-d',strtotime($minute->minuteDate))}}
							@if($minute->lock_flag)
							<span class="pull-right">draft</span>
							@endif
						</li>
						@endforeach
					</ul>
					@else
					<li class="meetings" mid="{{$meeting->id}}">{{$meeting->title}}
					@endif
				</li>
				@endforeach
			</ul>
			@else
				No Minutes
			@endif
		</div>
		<div id="rightContent" class="col-md-9">
			{{-- right content --}}
		</div>
	</div>

	<div id="createMeetingModal" class="modal fade">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">New Meeings Request</h4>
	      </div>
	      <div class="modal-body">
	        <div class="row">
		        {!! Form::open(array('id' => 'createMeetingForm')) !!}
		        <div class="col-md-12 form-group">
		        	{!! Form::label('title', 'Meeting title',['class'=>'control-label']); !!}
		        	{!! Form::text('title', '',['class'=>'form-control'])!!}
		        	<div id="title_err" class="error"></div>
		        	
		        	{!! Form::label('description', 'Meeting description',['class'=>'control-label']); !!}
		        	{!! Form::textarea('description', '',['class'=>'form-control'])!!}
		        	<div id="description_err" class="error"></div>

		        	{!! Form::label('selectMinuters', 'Expected Minuters',['class'=>'control-label']); !!}
		        	<div id="selected_minuters"></div>
		        	
		        	{!! Form::text('selectMinuters', '',['class'=>'form-control'])!!}
		        	<div id="minuters_err" class="error"></div>

		        	{!! Form::label('selectAttendees', 'Expected Attendees',['class'=>'control-label']); !!}
		        	<div id="selected_attendees" class="form-group"></div>
		        	
		        	{!! Form::text('selectAttendees', '',['class'=>'form-control'])!!}
		        	<div id="attendees_err" class="error"></div>

		        	{!! Form::label('venue', 'Venue',['class'=>'control-label']); !!}
		        	{!! Form::text('venue', '',['class'=>'form-control'])!!}
		        	<div id="venue_err" class="error"></div>
		        </div>
		        {!! Form::close() !!}
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button id="createMeetingSubmit" type="button" class="btn btn-primary">Save</button>
	      </div>
	    </div>

	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	{{-- alax pop up --}}
	<div id="loadMeetingModal" class="modal fade">
	</div><!-- /.modal -->

</div>
<script type="text/javascript">
 $( "#selectMinuters" ).autocomplete({
            source: "/user/search",
            minLength: 2,
            select: function( event, ui ) {
                if($("#user" + ui.item.userId).length != 0)
                {
                  alert('User already exist!');
                  return false;
                }
                else
                {
                    insert = '<div class="col-md-6 attendees" id="user'+ui.item.userId+'"><input type="hidden" name="minuters[]" value="'+ui.item.userId+'">'+ui.item.value+'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
                    $('#selected_minuters').append(insert);
                    $(this).val("");
                    return false;
                }
                
            }
            });
 $( "#selectAttendees" ).autocomplete({
            source: "/user/search",
            minLength: 2,
            select: function( event, ui ) {
                if($("#user" + ui.item.userId).length != 0)
                {
                  alert('User already exist!');
                  return false;
                }
                else
                {
                    insert = '<div class="col-md-6 attendees" id="user'+ui.item.userId+'"><input type="hidden" name="attendees[]" value="'+ui.item.userId+'">'+ui.item.value+'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
                    $('#selected_attendees').append(insert);
                    $(this).val("");
                    return false;
                }
                
            }
            });
</script>