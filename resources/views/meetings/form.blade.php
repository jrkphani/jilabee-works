<div class="popupWindow">
	<div class="popupHeader">
		<h2><a href="">Meeting</a> / <a href="">Create</a></h2>
		<button onclick="$('#popup').hide();" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>
	<div class="popupContent">
		{!! Form::open(array('id' => 'createMeetingForm')) !!}
		<div class="popupContentLeft">
            @if($meeting)
                {!! Form::hidden('id',$meeting->id)!!}
                {!! Form::label('title', 'Meeting title',['class'=>'control-label']); !!}
                {!! Form::text('title', $meeting->title,['class'=>'form-control'])!!}
                <div id="title_err" class="error"></div>
                
                {!! Form::label('description', 'Meeting description',['class'=>'control-label']); !!}
                <?php
                    $breaks = array("<br />","<br>","<br/>");  
                    $description = str_ireplace($breaks, "\r\n", $meeting->description); 
                ?>
                {!! Form::textarea('description', $description,['class'=>'form-control'])!!}
                <div id="description_err" class="error"></div>

                {!! Form::label('selectAttendees', 'Expected Attendees',['class'=>'control-label']); !!}
                <div id="selected_attendees" class="form-group">
                    @if($meeting->attendees)
                        <?php
                        $attendeesEmail = $attendees = array();
                            foreach (explode(',',$meeting->attendees) as $key => $value)
                            {
                                if(isEmail($value))
                                {
                                    $attendeesEmail[] = $value;
                                }
                                else
                                {
                                    $attendees[] = $value;
                                }
                            }
                            if(count($attendees))
                            {
                                $attendeesList = App\Model\Profile::select('users.userId','profiles.name')
                        ->join('users','profiles.userId','=','users.id')
                        ->whereIn('users.id',$attendees)->get();
                                foreach ($attendeesList as $attendee)
                                {
                                    echo '<div id="'.$attendee->userId.'" class="attendees"><input type="hidden" value="'.$attendee->userId.'" name="attendees[]">'.$attendee->name.'
                                        <span class="removeParent"> remove</span>
                                    </div>';
                                }
                            }
                            if(count($attendeesEmail))
                            {
                                foreach ($attendeesEmail as $attendee)
                                {
                                    echo '<div id="'.$attendee.'" class="attendees"><input type="hidden" value="'.$attendee.'" name="attendees[]">'.$attendee.'
                                        <span class="removeParent"> remove</span>
                                    </div>';
                                }
                            }
                        ?>
                    @endif
                    {!! Form::text('selectAttendees', '',['class'=>'form-control'])!!}
                </div>
                <div id="attendees_err" class="error"></div>

                {!! Form::label('venue', 'Venue',['class'=>'control-label']); !!}
                {!! Form::text('venue', '',['class'=>'form-control'])!!}
                <div id="venue_err" class="error"></div>
            @else
    			{!! Form::label('title', 'Meeting title',['class'=>'control-label']); !!}
            	{!! Form::text('title', '',['class'=>'form-control'])!!}
            	<div id="title_err" class="error"></div>
            	
            	{!! Form::label('description', 'Meeting description',['class'=>'control-label']); !!}
            	{!! Form::textarea('description', '',['class'=>'form-control'])!!}
            	<div id="description_err" class="error"></div>
                {{--
            	{!! Form::label('selectMinuters', 'Expected Minuters',['class'=>'control-label']); !!}
            	<div id="selected_minuters">
                    {!! Form::text('selectMinuters', '',['class'=>'form-control'])!!}
                </div>
            	<div id="minuters_err" class="error"></div>
                --}}
            	{!! Form::label('selectAttendees', 'Expected Attendees',['class'=>'control-label']); !!}
            	<div id="selected_attendees" class="form-group">
                    {!! Form::text('selectAttendees', '',['class'=>'form-control'])!!}
                </div>
            	<div id="attendees_err" class="error"></div>

            	{!! Form::label('venue', 'Venue',['class'=>'control-label']); !!}
            	{!! Form::text('venue', '',['class'=>'form-control'])!!}
            	<div id="venue_err" class="error"></div>
            @endif
		</div>
		<div class="popupContentRight">
		</div>
		{!!Form::close()!!}
	</div>
    @if($meeting)
    <button id="createMeetingSubmit">Update</button>
    @else
	<button id="createMeetingSubmit">Create</button>
    @endif
</div>
<script type="text/javascript">
$( "#selectMinuters" ).autocomplete({
            source: "/user/search",
            minLength: 2,
            select: function( event, ui ) {
                if($("#" + ui.item.userId).length != 0)
                {
                  alert('User already exist!');
                  return false;
                }
                else
                {
                    insert = '<div class="attendees" id="'+ui.item.userId+'"><input type="hidden" name="minuters[]" value="'+ui.item.userId+'">'+ui.item.value+'<span class="removeParent"> remove</span></div>';
                    $('#selected_minuters').prepend(insert);
                    $(this).val("");
                    return false;
                }
                
            }
            });
 $( "#selectAttendees" ).autocomplete({
            source: "/user/search",
            minLength: 2,
            select: function( event, ui ) {
                if($("#" + ui.item.userId).length != 0)
                {
                  alert('User already exist!');
                  return false;
                }
                else
                {
                    insert = '<div class="attendees" id="'+ui.item.userId+'"><input type="hidden" name="attendees[]" value="'+ui.item.userId+'">'+ui.item.value+'<span class="removeParent"> remove</span></div>';
                    $('#selected_attendees').prepend(insert);
                    $(this).val("");
                    return false;
                }
                
            }
            });
</script>