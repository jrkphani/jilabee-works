$('#centralViewer').on('click', '.markabsent', function(event) {
            userName = $(this).parent(".attendees" ).text();
            userId = $(this).parent(".attendees" ).attr('uid').match(/\d+/);
            html = '<div uid="u'+userId+'" class="absentees"><input type="hidden" value="'+userId+'" name="absentees[]">'+userName+'<div class="removeabsent"></div></div>';
            $('#absentees').append(html);
            $(this).parent(".attendees" ).remove();
        });
function loadMinute(mid,divId)
{
    $.ajax({
        url: '/minute/'+mid,
        type: 'GET',
        dataType: 'html',
        //data: {param1: 'value1'},
    })
    .done(function(htmlData)
    {
        $('#'+divId).html(htmlData);
    })
    .fail(function(xhr) {
        checkStatus(xhr.status);
    })
    .always(function(xhr) {
        checkStatus(xhr.status);
    });
}
$('#centralContainer').on('click', '#createMeetingSubmit', function(event) {
        event.preventDefault();
        $.ajax({
            url: '/meetings/create',
            type: 'POST',
            dataType: 'json',
            data: $('#createMeetingForm').serialize(),
        })
        .done(function(jsonData) {

            if(jsonData.success == 'no')
            {
                if(jsonData.hasOwnProperty('validator'))
                {
                    $('.error').html('');
                    $.each(jsonData.validator, function(index, val) {
                         $('#'+index+'_err').html(val);
                    });
                }
            }
            else if(jsonData.success == 'yes')
            {
                $.notify('Meeting Created Successfully',
                {
                   className:'success',
                   globalPosition:'top center'
                });
                location.reload();
                //$('#popup').load('/minute/first/'+jsonData.meetingId);
            }
            //////console.log("success");
        })
        .fail(function() {
            ////console.log("error");
        })
        .always(function() {
            ////console.log("complete");
        });
        
    });
$('#centralContainer').on('click', '#addMeeting', function(event)
{
    event.preventDefault();
    popupContentAjaxGet('/meetings/create');
});
$('#centralContainer').on('click', '.pendingmeetings', function(event)
{
    event.preventDefault();
    popupContentAjaxGet('/meetings/load/'+$(this).attr('mid'));
});
/*$('#centralContainer').on('keyup', '#selectAttendees', function(event) {
    event.preventDefault();
    if($(this).val().length)
    {
        if((event.which == 188) || (event.which == 13))
        {
            emailArr = $(this).val().split(",");
            if(emailArr.length)
            {
                $.each(emailArr, function(index, val)
                {   
                    if($("#"+val.replace('@', '_')).length != 0)
                    {

                    }
                    else
                    {
                        if(isEmail(val))
                        {
                            if($("#" +val.replace('@', '_')).length != 0)
                            {
                              //User already exist
                            }
                            else
                            {
                                insert = '<div class="attendees" id="'+val.replace('@', '_')+'"><input type="hidden" name="attendees[]" value="'+val+'">'+val+'<span class="removeParent"> remove</span></div>';
                                $('#selected_attendees').prepend(insert);
                                $('#selectAttendees').val('');
                           }
                        }
                    }
                });
                return false;
            }
        }
    }
});*/
$('#centralContainer').on('click', '.minute', function(event) {
        //alert($(this).find('.minute').length);
        //return false;
        event.preventDefault();
        var mid = $(this).attr('mid');
        loadMinute(mid,'nowMeetingsRight')          
    });
$('#centralContainer').on('click', '.minute_history', function(event) {
    event.preventDefault();
    var mid = $(this).attr('mid');
    popupContentAjaxGet('/minute/view/'+mid);
});
$('#centralContainer').on('click', '.minuteDiv', function(event) {
        //alert($(this).find('.minute').length);
        //return false;
        event.preventDefault();
        var mid = $(this).attr('mid');
        loadMinute(mid,'minuteDiv');
    });
$("#centralContainer").on('click', '#nextMinute', function(event) {
    event.preventDefault();
    meetingId = $(this).attr('mid');
    $.ajax({
        url: '/minute/'+meetingId+'/next',
        type: 'GET',
        dataType: 'html',
    })
    .done(function(htmlData)
    {
        $('#minuteDiv').html(htmlData);
    })
    .fail(function(xhr) {
        checkStatus(xhr.status);
    })
    .always(function(xhr) {
        checkStatus(xhr.status);
    });
});
$('#centralContainer').on('click', '.firstMinute', function(event) {
    event.preventDefault();
    meetingId = $(this).attr('mid');
    $.ajax({
        url: '/minute/first/'+meetingId,
        type: 'GET',
        dataType: 'html',
    })
    .done(function(htmlData)
    {
        $('#popup').html(htmlData);
        $('#popup').show();
    })
    .fail(function(xhr) {
        checkStatus(xhr.status);
    })
    .always(function(xhr) {
        checkStatus(xhr.status);
    });
});
$('#centralContainer').on('click', '#updateMinute', function(event) {
    event.preventDefault();
    $.ajax({
        url: '/minute/'+meetingId+'/next',
        type: 'POST',
        dataType: 'html',
         data: $('#MinuteForm').serialize(),
    })
    .done(function(htmlData)
    {
        $('#minuteDiv').html(htmlData);
    })
    .fail(function(xhr) {
        checkStatus(xhr.status);
    })
    .always(function(xhr) {
        checkStatus(xhr.status);
    });
});
$('#centralContainer').on('click', '#add_more', function(event) {
    event.preventDefault();
    taskBlock = $( ".taskBlock:first").clone().appendTo('#taskAddBlock');
        taskBlock.find(".clearVal").val("");
        taskBlock.find(".type").val("task");
        taskBlock.find('.assignee').remove();
        taskBlock.find(".ideainput").hide();
        taskBlock.find(".taskinput").show();
        selectAssignee();
        nextDateInput();
});
$('#centralContainer').on('change', '.type', function(event) {
    event.preventDefault();
        taskBlock = $(this).parents('.taskBlock');
        if($(this).val() == 'task')
        {
            taskBlock.find('.ideainput').hide();
            taskBlock.find('.taskinput').show();
        }
        else if($(this).val() == 'idea')
        {
            taskBlock.find('.ideainput').show();
            taskBlock.find('.taskinput').hide();
        }
});
$('#centralContainer').on('click', '#save_changes', function(event) {
        event.preventDefault();
        var mid = $(this).attr('mid');
        $.ajax({
            url: '/minute/'+mid+'/draft',
            type: 'POST',
            dataType: 'html',
            data: $('#tasksAddForm').serialize(),
        })
        .done(function() {
            $.notify('Saved!',
                {
                   className:'success',
                   globalPosition:'top center'
                });
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
        
    });
    $('#centralContainer').on('click', '#send_minute', function(event) {
        event.preventDefault();
        var mid = $(this).attr('mid');
        $.ajax({
            url: '/minute/'+mid+'/task',
            type: 'POST',
            dataType: 'json',
            data: $('#tasksAddForm').serialize(),
        })
        .done(function(jsonData) {
            if(jsonData.success == 'no')
                {
                    if(jsonData.hasOwnProperty('validator'))
                    {
                        errorList = '<ul>';
                        $.each(jsonData.validator, function(index, val) {
                            errorList += '<li class="error">'+val+'</li>';
                        });
                        errorList += '</ul>';
                        $('#createTaskError').html(errorList);
                    }
                }
            else if(jsonData.success == 'yes')
                {
                    $.notify('Sent',
                    {
                       className:'success',
                       globalPosition:'top center'
                    });
                    $('#minuteDiv').load('/minute/'+mid);
                }
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
        
    });
 $('#centralContainer').on('click', '.removeTaskFrom ', function(event) {
        event.preventDefault();
        if($('.taskBlock').length > 1)
        {
            $(this).parents('.taskBlock').remove();
        }
    });
  $('#centralContainer').on('click', '.removeTask', function(event) {
        event.preventDefault();
        parentDiv = $(this).parents('.notfiledTaskBlock');
        $('#tasksAddForm').prepend('<input type="hidden" name="removeTask[]" value="'+parentDiv.attr("tid")+'" >');
        parentDiv.remove();
    });
  $('#centralContainer').on('click', '.removeIdea', function(event) {
        event.preventDefault();
        parentDiv = $(this).parents('.notfiledTaskBlock');
        $('#tasksAddForm').prepend('<input type="hidden" name="removeIdea[]" value="'+parentDiv.attr("tid")+'" >');
        parentDiv.remove();
    });
  
 $('#centralContainer').on('click', '.removeabsent', function(event) {
            userName = $(this).parent(".absentees" ).text();
            userId = $(this).parent(".absentees" ).attr('uid').match(/\d+/);
            html = '<div uid="u'+userId+'" class="attendees"><input type="hidden" value="'+userId+'" name="attendees[]">'+userName+'<div class="markabsent"></div></div>';
            $('#attendees').append(html);
            $(this).parent(".absentees" ).remove();
        });
 $('#centralContainer').on('click', '.removeParent', function(event) {
            var selectAssignee = $(this).parents( "p" ).find('.selectAssignee');
            selectAssignee.show();
            selectAssignee.val('');
            $(this).parent( ".assignee" ).remove();
        });