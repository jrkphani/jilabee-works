<div class="inner2">
    {!! Form::open(array('id' => 'createMeetingForm')) !!}
    <h4>Create Meeting</h4>
    @if($meeting)
        {!! Form::hidden('id',$meeting->id)!!}
        <div class="userDetailItem">
            <p>Meeting title</p>
            <span>{!! Form::text('title',$meeting->title)!!}</span>
            <div id="title_err" class="error"></div>
            <div class="clearboth"></div>
        </div>
        <div class="userDetailItem">
            <p>Meeting description</p>
            <?php
                $breaks = array("<br />","<br>","<br/>");  
                $description = str_ireplace($breaks, "\r\n", $meeting->description); 
            ?>
            <span>{!! Form::textarea('description',$description)!!}</span>
            <div id="description_err" class="error"></div>
            <div class="clearboth"></div>
        </div>

        <div class="userDetailItem">
            <p>Expected Minuters</p>
            <span>
                <div id="selected_minuters">
                    @if($meeting->minuters)
                    <?php
                        $minuters = App\Model\Profile::select('users.userId','profiles.name')
                        ->join('users','profiles.userId','=','users.id')
                        ->whereIn('users.id',explode(',',$meeting->minuters))->get();
                    ?>
                        @foreach($minuters as $minuter)
                          <div id="{{$minuter->userId}}" class="attendees"><input type="hidden" value="{{$minuter->userId}}" name="minuters[]">{{$minuter->name}}
                                <span class="removeParent"> remove</span>
                            </div>
                        @endforeach
                    @endif
                    {!! Form::text('selectMinuters', '',['id'=>'selectMinuters'])!!}
                </div>
            </span>
            <div id="minuters_err" class="error"></div>
            <div class="clearboth"></div>
        </div>
        
        <div class="userDetailItem">
            <p>Expected Attendees</p>
            <span>
                 <div id="selected_attendees">
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
                    {!! Form::text('selectAttendees', '',['id'=>'selectAttendees'])!!}
                </div>
            </span>
            <div id="attendees_err" class="error"></div>
            <div class="clearboth"></div>
        </div>

        <div class="userDetailItem">
            <p>Venue</p>
            <span>{!! Form::text('venue',$meeting->venue)!!}</span>
            <div id="venue_err" class="error"></div>
            <div class="clearboth"></div>
        </div>
    @else
        <div class="userDetailItem">
            <p>Meeting title</p>
            <span>{!! Form::text('title')!!}</span>
            <div id="title_err" class="error"></div>
            <div class="clearboth"></div>
        </div>
        <div class="userDetailItem">
            <p>Meeting description</p>
            <span>{!! Form::textarea('description')!!}</span>
            <div id="description_err" class="error"></div>
            <div class="clearboth"></div>
        </div>

        <div class="userDetailItem">
            <p>Expected Minuters</p>
            <span>
                <div id="selected_minuters">
                    {!! Form::text('selectMinuters', '',['id'=>'selectMinuters'])!!}
                </div>
            </span>
            <div id="minuters_err" class="error"></div>
            <div class="clearboth"></div>
        </div>
        
        <div class="userDetailItem">
            <p>Expected Attendees</p>
            <span>
                 <div id="selected_attendees">
                    {!! Form::text('selectAttendees', '',['id'=>'selectAttendees'])!!}
                </div>
            </span>
            <div id="attendees_err" class="error"></div>
            <div class="clearboth"></div>
        </div>

        <div class="userDetailItem">
            <p>Venue</p>
            <span>{!! Form::text('venue')!!}</span>
            <div id="venue_err" class="error"></div>
            <div class="clearboth"></div>
        </div>
    @endif
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