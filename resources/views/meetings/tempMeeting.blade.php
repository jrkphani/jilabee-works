<div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">New Meeings Request</h4>
	      </div>
	      <div class="modal-body">
	      		<div class="row">
		        {!! Form::open(array('id' => 'loadMeetingForm')) !!}
				<div class="col-md-12 form-group">
					{!! Form::hidden('mid', $tempMeetings->id) !!}
					{!! Form::label('title', 'Meeting title',['class'=>'control-label']); !!}
					{!! Form::text('title', $tempMeetings->title,['class'=>'form-control title','id'=>''])!!}
					{!! $errors->first('title','<div class="error">:message</div>') !!}
					
					{!! Form::label('description', 'Meeting description',['class'=>'control-label']); !!}
					{!! Form::textarea('description', $tempMeetings->description,['class'=>'form-control','id'=>''])!!}
					{!! $errors->first('description','<div class="error">:message</div>') !!}

					{!! Form::label('selectMinuters', 'Expected Minuters',['class'=>'control-label']); !!}
					<div id="tempselected_minuters">
						@if($tempMeetings->minuters)
							<?php
								$minuters = App\Model\Profile::select('userId','name')->whereIn('userId',explode(',', $tempMeetings->minuters))->get();
								foreach ($minuters as $minuter)
								{
									echo '<div class="col-md-2 attendees" id="u'.$minuter->userId.'"><input type="hidden" name="minuters[]" value="'.$minuter->userId.'">'.$minuter->name.'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
								}
							?>
						@endif
					</div>
					
					{!! Form::text('selectMinuters', '',['class'=>'form-control selectMinuters','id'=>'tempselectMinuters'])!!}
					{!! $errors->first('minuters','<div class="error">:message</div>') !!}

					{!! Form::label('selectAttendees', 'Expected Attendees',['class'=>'control-label']); !!}
					<div id="tempselected_attendees">
							@if($tempMeetings->attendees)
							<?php
								$attendees = App\Model\Profile::select('userId','name')->whereIn('userId',explode(',', $tempMeetings->attendees))->get();
								foreach ($attendees as $attendee)
								{
									echo '<div class="col-md-2 attendees" id="u'.$attendee->userId.'"><input type="hidden" name="attendees[]" value="'.$attendee->userId.'">'.$attendee->name.'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
								}
							?>
							@endif
					</div>
					
					{!! Form::text('selectAttendees', '',['class'=>'form-control selectAttendees','id'=>'tempselectAttendees'])!!}
					{!! $errors->first('attendees','<div class="error">:message</div>') !!}

					{!! Form::label('venue', 'Venue',['class'=>'control-label']); !!}
					{!! Form::text('venue', $tempMeetings->venue,['class'=>'form-control','id'=>''])!!}
					{!! $errors->first('venue','<div class="error">:message</div>') !!}

					{!! Form::label('reason', 'Reason for disapprove : ',['class'=>'control-label']); !!}
					{{$tempMeetings->reason}}
					{!! Form::close() !!}
				</div>
				<div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        	<button mid="{{$tempMeetings->id}}" id="loadMeetingSubmit" type="button" class="btn btn-primary">Save</button>
		      </div>
	  		</div>
	    </div>
	  </div>
	</div>
	<script type="text/javascript">
 $( "#tempselectMinuters" ).autocomplete({
            source: "/user/search",
            minLength: 2,
            select: function( event, ui ) {
                if($("#u" + ui.item.userId).length != 0)
                {
                  alert('User already exist!');
                  return false;
                }
                else
                {
                    insert = '<div class="col-md-6 attendees" id="u'+ui.item.userId+'"><input type="hidden" name="minuters[]" value="'+ui.item.userId+'">'+ui.item.value+'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
                    $('#tempselected_minuters').append(insert);
                    $(this).val("");
                    return false;
                }
                
            }
            });
 $( "#tempselectAttendees" ).autocomplete({
            source: "/user/search",
            minLength: 2,
            select: function( event, ui ) {
                if($("#u" + ui.item.userId).length != 0)
                {
                  alert('User already exist!');
                  return false;
                }
                else
                {
                    insert = '<div class="col-md-6 attendees" id="u'+ui.item.userId+'"><input type="hidden" name="attendees[]" value="'+ui.item.userId+'">'+ui.item.value+'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
                    $('#tempselected_attendees').append(insert);
                    $(this).val("");
                    return false;
                }
                
            }
            });
</script>