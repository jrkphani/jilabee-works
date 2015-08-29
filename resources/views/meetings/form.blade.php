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
                    {!! Form::open() !!}
                    {{--meeting form --}}
                    <div class="userDetailItem">
                        <p>name of meeting </p>
                         <span> {!! Form::text('title','',['placeholder'=>'title'])!!}</span>
                        <div class="clearboth"></div>
                        <div id="title_err" class="error"></div>
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
                    {!!$errors->first('startDate','<div class="error">:message</div>')!!}
                    {!! Form::text('endDate','',['id'=>'endSateInput','placeholder'=>'end date']) !!}
                    {!!$errors->first('endDate','<div class="error">:message</div>')!!}
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
                                {{--<p>
                                    {!! Form::select('assigner[]',array(''=>'Assinger')+$attendees,'',array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
                                </p>--}}
                                <p>
                                    {!! Form::select('assignee[]',array(''=>'Assingee'),'',array('autocomplete'=>'off','class'=>'taskinput clearVal')) !!}
                                </p>
                                <p>{!! Form::text('dueDate[]','',array('class'=>"nextDateInput taskinput clearVal",'placeholder'=>'y-m-d','autocomplete'=>'off')) !!}</p>
                                <p>{!! Form::select('orginator[]',array(''=>'Orginator'),'',array('autocomplete'=>'off','class'=>'clearVal ideainput','style'=>'display:none;')) !!}</p>
                                <p>Draft</p>
                            </div>
                            <div class="clearboth"></div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                     <span id="add_more" type="submit" class="button">Add more</span>
                     <span id="add_more" type="submit" class="button">Save Draft</span>
                     <span id="add_more" type="submit" class="button">Send minute After Admin aprrove</span>
                </div>
            </div>
        </div>
        <div class="clearboth"></div>
    </div>
</div>
<script type="text/javascript">
nextDateInput();
</script>