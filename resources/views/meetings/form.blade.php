<div class="popupWindow">
    <div class="popupHeader">
        <h2>Create Meeting</a></h2>
        <button  onclick="$('#popup').hide();" class="popupClose"></button>
        <div class="clearboth"></div>
    </div>  
    <div class="popupContent createMeeting">
        <div class="popupMinutes">
            <div class="paper">
                <div class="paperBorder nextMinutePaper">
                    {!! Form::open(['id'=>'createMeetingForm']) !!}
                    @if($meeting)
                    {{--meeting form --}}
                    {!! Form::hidden('id',$meeting->id) !!}
                    <div class="userDetailItem">
                        <p>name of meeting </p>
                         <span> {!! Form::text('meetingTitle',$meeting->title,['placeholder'=>'title'])!!}</span>
                        <div class="clearboth"></div>
                        <div id="meetingTitle_err" class="error"></div>
                    </div>
                    <h4>
                       
                    </h4>
                    <div class="userDetailItem">
                        <p>type of meeting </p>
                         <span>{!! Form::text('meetingType',$meeting->type,['placeholder'=>'type'])!!}</span>
                        <div class="clearboth"></div>
                        <div id="meetingType_err" class="error"></div>
                    </div>

                    <div class="userDetailItem">
                        <p>purpose of meeting  </p>
                        <span>{!! Form::textarea('purpose',$meeting->purpose,['placeholder'=>'purpose'])!!}</span>
                        <div class="clearboth"></div>
                         <div id="purpose_err" class="error"></div>
                    </div>
                    <div class="userDetailItem">
                        <p>Short Description </p>
                        <span>{!! Form::textarea('meetingDescription',$meeting->description,['placeholder'=>'description'])!!}</span>
                        <div class="clearboth"></div>
                         <div id="meetingDescription_err" class="error"></div>
                    </div>                        

                    {!! Form::text('startDate',$meeting->startDate,['id'=>'startDateInput','placeholder'=>'start date']) !!}
                    <div id="startDate_err" class="error"></div>
                    {!! Form::text('endDate',$meeting->endDate,['id'=>'endSateInput','placeholder'=>'end date']) !!}
                    <div id="endDate_err" class="error"></div>
                    {{-- task forms --}}
                    <?php $details=unserialize($meeting->details);  ?>
                    <div id="taskAddBlock">
                    @for($i=0;$i<=count($details['title'])-1;$i++)
                        <div class="taskBlock taskDiv">
                            <div>
                                <span class="removeTaskFrom removeMoreBtn"></span>
                                {!! Form::select('type[]',['task'=>'Task','idea'=>'Idea'],$details['type'][$i],array('class'=>'type','autocomplete'=>'off','disabled')) !!}
                                <div class="clearboth"></div>
                            </div>
                            <div class="minuteItemNumber">
                                
                            </div>
                            <div class="minuteItemLeft">
                                <h5>{!! Form::text('title[]',$details['title'][$i],array('placeholder'=>'Title','autocomplete'=>'off','class'=>'clearVal')) !!}</h5>
                                <p>{!! Form::textarea('description[]',$details['description'][$i],array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5,'class'=>'clearVal')) !!}</p>
                            </div>
                            <div class="minuteItemRight">
                                <p>
                                   <?php
                                   $display='';

                                   if($details['type'][$i] == 'idea')
                                   {
                                    $display = "display:none;";
                                   }
                                   // if($details['assignee'][$i])
                                   // {
                                   //   if(isEmail($details['assignee'][$i]))
                                   //   {
                                   //    $display='';
                                   //   }
                                   //   else
                                   //   {
                                   //     $display='display:none;';
                                   //     $name = getUser(['userId'=>$details['assignee'][$i]])->profile->name;
                                   //    echo '<div class="assignee">yet to finish<span class="removeParent">remove</span></div>';
                                   //   }
                                   // }
                                   //  else
                                   //  {
                                   //      $display='';
                                   //  }
                                    ?>
                                {!! Form::text('assignee[]',$details['assignee'][$i],array('autocomplete'=>'off','class'=>'selectAssignee taskinput clearVal','placeholder'=>'Assigner','style'=>$display)) !!}
                                </p>
                                <p>{!! Form::text('dueDate[]',$details['dueDate'][$i],array('class'=>"nextDateInput taskinput clearVal",'placeholder'=>'y-m-d','autocomplete'=>'off','style'=>$display)) !!}</p>
                                <p>
                                    <?php
                                        $display='';
                                        if($details['type'][$i] == 'task')
                                       {
                                        $display = "display:none;";
                                       }
                                       ?>
                                   {!! Form::text('orginator[]',$details['orginator'][$i],array('autocomplete'=>'off','class'=>'clearVal ideainput selectOrginator','style'=>$display)) !!}
                               </p>
                                <p>Draft</p>
                            </div>
                            <div class="clearboth"></div>
                        </div>
                    @endfor
                    </div>
                    @else
                    <div class="userDetailItem">
                        <p>name of meeting </p>
                         <span> {!! Form::text('meetingTitle','',['placeholder'=>'title'])!!}</span>
                        <div class="clearboth"></div>
                        <div id="meetingTitle_err" class="error"></div>
                    </div>
                    <h4>
                       
                    </h4>
                    <div class="userDetailItem">
                        <p>type of meeting </p>
                         <span>{!! Form::text('meetingType','',['placeholder'=>'type'])!!}</span>
                        <div class="clearboth"></div>
                        <div id="meetingType_err" class="error"></div>
                    </div>

                    <div class="userDetailItem">
                        <p>purpose of meeting  </p>
                        <span>{!! Form::textarea('purpose','',['placeholder'=>'purpose'])!!}</span>
                        <div class="clearboth"></div>
                         <div id="purpose_err" class="error"></div>
                    </div>
                    <div class="userDetailItem">
                        <p>Short Description </p>
                        <span>{!! Form::textarea('meetingDescription','',['placeholder'=>'description'])!!}</span>
                        <div class="clearboth"></div>
                         <div id="meetingDescription_err" class="error"></div>
                    </div>                        

                    {!! Form::text('startDate','',['id'=>'startDateInput','placeholder'=>'start date']) !!}
                    <div id="startDate_err" class="error"></div>
                    {!! Form::text('endDate','',['id'=>'endSateInput','placeholder'=>'end date']) !!}
                    <div id="endDate_err" class="error"></div>
                    {{-- task forms --}}
                    <div id="taskAddBlock">
                        <div class="taskBlock taskDiv">
                            <div>
                                <span class="removeTaskFrom removeMoreBtn"></span>
                                {!! Form::select('type[]',['task'=>'Task','idea'=>'Idea'],'',array('class'=>'type','autocomplete'=>'off')) !!}
                                <div class="clearboth"></div>
                            </div>
                            <div class="minuteItemNumber">
                                
                            </div>
                            <div class="minuteItemLeft">
                                <h5>{!! Form::text('title[]','',array('placeholder'=>'Title','autocomplete'=>'off','class'=>'clearVal')) !!}</h5>
                                <p>{!! Form::textarea('description[]','',array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5,'class'=>'clearVal')) !!}</p>
                            </div>
                            <div class="minuteItemRight">
                                <p>
                                    {!! Form::text('assignee[]','',array('autocomplete'=>'off','class'=>'selectAssignee taskinput clearVal','placeholder'=>'Assigner')) !!}
                                </p>
                                <p>{!! Form::text('dueDate[]','',array('class'=>"nextDateInput taskinput clearVal",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}</p>
                                <p>{!! Form::text('orginator[]','',array('autocomplete'=>'off','class'=>'clearVal ideainput selectOrginator','style'=>'display:none;')) !!}</p>
                                <p>Draft</p>
                            </div>
                            <div class="clearboth"></div>
                        </div>
                    </div>
                    @endif
                    {!! Form::close() !!}
                     <span id="add_more" type="submit" class="button">Add more</span>
                     <span id="add_more" type="submit" class="button">Save Draft</span>
                     <span id="createMeetingSubmit" type="submit" class="button">Send minute After Admin aprrove</span>
                </div>
            </div>
        </div>
        <div class="clearboth"></div>
    </div>
</div>
<script type="text/javascript">
$('#startDateInput').appendDtpicker(
    {
    "maxDate": new Date(),
    "autodateOnStart": false,
    "closeOnSelected": true
    });
$('#endSateInput').appendDtpicker(
    {
    "maxDate": new Date(),
    "autodateOnStart": false,
    "closeOnSelected": true
    });
function selectAssignee()
{
$( ".selectAssignee" ).autocomplete({
            source: "/user/search",
            minLength: 2,
            select: function( event, ui ) {
                insert = '<div class="assignee">'+ui.item.value+'<span class="removeParent">remove</span></div>';
                $(this).parent('p').append(insert);
                $(this).val(ui.item.userId);
                $(this).hide();
                return false;
            }
            });
$( ".selectOrginator" ).autocomplete({
            source: "/user/search",
            minLength: 2,
            select: function( event, ui ) {
                insert = '<div class="assignee">'+ui.item.value+'<span class="removeParent">remove</span></div>';
                $(this).parent('p').append(insert);
                $(this).val(ui.item.userId);
                $(this).hide();
                return false;
            }
            });
}
selectAssignee();
nextDateInput();
</script>