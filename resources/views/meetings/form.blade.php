<div class="popupWindow">
    <div class="popupHeader">
        <h2>Create Meeting</h2>
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
                    @if($meeting->approved == '-1')
                        <div class="userDetailItem">
                            <p>Rejected Reason: </p>
                             <span>{{$meeting->reason}}</span>
                            <div class="clearboth"></div>
                            <div id="meetingType_err" class="error"></div>
                        </div>
                    @endif
                    <div class="userDetailItem">
                        <p>Meeting Title </p>
                         <span> {!! Form::text('meetingTitle',$meeting->title,['placeholder'=>'title'])!!}</span>
                        <div class="clearboth"></div>
                        <div id="meetingTitle_err" class="error"></div>
                    </div>
                    <div class="userDetailItem">
                        <p>Type of meeting </p>
                         <span>{!! Form::text('meetingType',$meeting->type,['placeholder'=>'type'])!!}</span>
                        <div class="clearboth"></div>
                        <div id="meetingType_err" class="error"></div>
                    </div>

                    <div class="userDetailItem">
                        <p>Purpose of meeting  </p>
                        <span>{!! Form::text('purpose',$meeting->purpose,['placeholder'=>'purpose'])!!}</span>
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
                                {!! Form::select('type[]',['task'=>'Task','idea'=>'Idea'],$details['type'][$i],array('class'=>'type','autocomplete'=>'off')) !!}
                                <div class="clearboth"></div>
                            </div>
                            <div class="minuteItemNumber">
                                
                            </div>
                            <div class="minuteItemLeft">
                                <h5>{!! Form::text('title[]',$details['title'][$i],array('placeholder'=>'Title','autocomplete'=>'off','class'=>'clearVal')) !!}</h5>
                                <p>{!! Form::textarea('description[]',$details['description'][$i],array('placeholder'=>'Description','autocomplete'=>'off','rows'=>5,'class'=>'clearVal')) !!}</p>
                            </div>
                            <div class="minuteItemRight">
                                <div class="parentDiv">
                                   <?php
                                   $display='';

                                   if($details['type'][$i] == 'idea')
                                   {
                                    $display = "display:none;";
                                   }
                                   else
                                   {
                                    if($details['assignee'][$i])
                                       {
                                         if(isEmail($details['assignee'][$i]))
                                         {
                                          $display='';
                                         }
                                         else
                                         {
                                           $display='display:none;';
                                           $name = getUser(['userId'=>$details['assignee'][$i]])->profile->name;
                                          echo '<div class="assignee">'.$name.'<span class="removeParent"></span></div>';
                                         }
                                       }
                                        else
                                        {
                                            $display='';
                                        }
                                   }
                                    ?>
                                {!! Form::text('assignee[]',$details['assignee'][$i],array('autocomplete'=>'off','class'=>'selectAssignee taskinput clearVal','placeholder'=>'Assigner','style'=>$display)) !!}
                                </div>
                                <?php
                                $display = "";
                                if($details['type'][$i] == 'idea')
                                   {
                                    $display = "display:none;";
                                   }
                                   ?>
                                <p>{!! Form::text('dueDate[]',$details['dueDate'][$i],array('class'=>"nextDateInput taskinput clearVal",'placeholder'=>'y-m-d','autocomplete'=>'off','style'=>$display)) !!}</p>
                                 <div class="parentDiv">
                                    <?php
                                        $display='';
                                        if($details['type'][$i] == 'task')
                                       {
                                        $display = "display:none;";
                                       }
                                       else
                                       {
                                        if($details['orginator'][$i])
                                           {
                                             if(isEmail($details['orginator'][$i]))
                                             {
                                              $display='';
                                             }
                                             else
                                             {
                                               $display='display:none;';
                                               $name = getUser(['userId'=>$details['orginator'][$i]])->profile->name;
                                              echo '<div class="assignee">'.$name.'<span class="removeParent"></span></div>';
                                             }
                                           }
                                            else
                                            {
                                                $display='';
                                            }
                                       }
                                       ?>
                                   {!! Form::text('orginator[]',$details['orginator'][$i],array('autocomplete'=>'off','class'=>'clearVal ideainput selectAssignee','style'=>$display)) !!}
                                </div>
                                <p>Draft</p>
                            </div>
                            <div class="clearboth"></div>
                        </div>
                    @endfor
                    </div>
                     <div id="title_err" class="error"></div>
                        <div id="description_err" class="error"></div>
                        <div id="assignee_err" class="error"></div>
                        <div id="dueDate_err" class="error"></div>
                    @else
                    <div class="userDetailItem">
                        <p>Name of meeting </p>
                         <span> {!! Form::text('meetingTitle','',['placeholder'=>'title'])!!}</span>
                        <div class="clearboth"></div>
                        <div id="meetingTitle_err" class="error"></div>
                    </div>
                    <h4>
                       
                    </h4>
                    <div class="userDetailItem">
                        <p>Type of meeting </p>
                         <span>{!! Form::text('meetingType','',['placeholder'=>'type'])!!}</span>
                        <div class="clearboth"></div>
                        <div id="meetingType_err" class="error"></div>
                    </div>

                    <div class="userDetailItem">
                        <p>Purpose of meeting  </p>
                        <span>{!! Form::text('purpose','',['placeholder'=>'purpose'])!!}</span>
                        <div class="clearboth"></div>
                         <div id="purpose_err" class="error"></div>
                    </div>
                    <div class="userDetailItem">
                        <p>Short Description </p>
                        <span>{!! Form::textarea('meetingDescription','',['placeholder'=>'description'])!!}</span>
                        <div class="clearboth"></div>
                         <div id="meetingDescription_err" class="error"></div>
                    </div>                        
                    <div class="userDetailItem">
                        <p>Start Date </p>
                     	{!! Form::text('startDate','',['id'=>'startDateInput','placeholder'=>'start date']) !!}
                 	   	<div id="startDate_err" class="error"></div>
                 	</div> 
                 	<div class="userDetailItem">
                 		<p>End Date </p>
                  		{!! Form::text('endDate','',['id'=>'endSateInput','placeholder'=>'end date']) !!}
                 	    <div id="endDate_err" class="error"></div>
                  	</div> 
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
                                 <div class="parentDiv">
                                    {!! Form::text('assignee[]','',array('autocomplete'=>'off','class'=>'selectAssignee taskinput clearVal','placeholder'=>'Assigner')) !!}
                                </div>
                                <p>{!! Form::text('dueDate[]','',array('class'=>"nextDateInput taskinput clearVal",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}</p>
                                 <div class="parentDiv">{!! Form::text('orginator[]','',array('selectAssignee'=>'off','class'=>'clearVal ideainput selectOrginator','style'=>'display:none;')) !!}</div>
                                <p>Draft</p>
                            </div>
                            <div class="clearboth"></div>
                        </div>
                    </div>
                     <div id="title_err" class="error"></div>
                        <div id="description_err" class="error"></div>
                        <div id="assignee_err" class="error"></div>
                        <div id="dueDate_err" class="error"></div>
                    @endif
                    {!! Form::close() !!}
                    <br/>
                     <span id="add_more" type="submit" class="button">Add more</span>
                     <span id="draftMeetingSubmit" type="submit" class="button">Save Draft</span>
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
                insert = '<div class="assignee">'+ui.item.value+'<span class="removeParent"></span></div>';
                $(this).parent('.parentDiv').append(insert);
                $(this).val(ui.item.userId);
                $(this).hide();
                return false;
            }
            });
// $( ".selectOrginator" ).autocomplete({
//             source: "/user/search",
//             minLength: 2,
//             select: function( event, ui ) {
//                 insert = '<div class="assignee">'+ui.item.value+'<span class="removeParent">remove</span></div>';
//                 $(this).parent('.parentDiv').append(insert);
//                 $(this).val(ui.item.userId);
//                 $(this).hide();
//                 return false;
//             }
//             });
}
selectAssignee();
nextDateInput();
</script>