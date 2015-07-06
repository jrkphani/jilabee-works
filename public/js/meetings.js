$(document).ready(function($)
{
    // Javascript to enable link to tab
        var url = document.location.toString();
        if (url.match('#'))
        {
            if(url.split('#')[1] == 'minutes')
            {
                $('#minutes').click();
            }
            else if(url.split('#')[1] == 'history')
            {
                $('#history').click();
            }
        }
        else
        {
            $('#minutes').click();
        }

        $('#listLeft').on('click', '#createMeetingSubmit', function(event) {
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
                    $('.error').html('');
                    $('#createMeetingModal').modal('hide');
                    $('#selected_attendees , #selected_minuters').html('');
                    $('#createMeetingForm').find(':input').each(function()
                        {
                            $(this).val('');
                        });
                    $('.meetingMenu.active').click();
                    //top bar notification
                    $.notify('Sent',
                    {
                       className:'success',
                       globalPosition:'top center'
                    });
                }
                ////console.log("success");
            })
            .fail(function() {
                //console.log("error");
            })
            .always(function() {
                //console.log("complete");
            });
            
        });
$('#listLeft').on('click', '#loadMeetingSubmit', function(event) {
            event.preventDefault();
            mid = $(this).attr('mid');
            $.ajax({
                url: '/meetings/update/'+mid,
                type: 'POST',
                dataType: 'html',
                data: $('#loadMeetingForm').serialize(),
            })
            .done(function(htmlData) {
                if(htmlData == 'success')
                {
                    $('#loadMeetingModal').modal('hide');
                    $('#loadMeetingModal').html('');
                     $.notify('Sent',
                    {
                       className:'success',
                       globalPosition:'top center'
                    });
                }
                else
                {
                   $('#loadMeetingModal').html(htmlData) 
                }
            })
            .fail(function() {
                //console.log("error");
            })
            .always(function() {
                //console.log("complete");
            });
            
        });
        $('#listLeft').on('click', '.removeParent', function(event) {
            $(this).parent(".attendees" ).remove();
        });
            $('#listLeft').on('click', '.tempMeeting', function(event) {
            $('#loadMeetingModal').load('/meetings/load/'+$(this).attr('mid'));
            $('#loadMeetingModal').addClass('in');
            $('#loadMeetingModal').show();
         });
            $('#listLeft').on('click', '.minute', function(event) {
                //alert($(this).find('.minute').length);
                //return false;
                event.preventDefault();
                var mid = $(this).attr('mid');
                loadMinute(mid)          
            });
            $('#listLeft').on('click', '.minutePopup', function(event) {
                event.preventDefault();
                var mid = $(this).attr('mid');
                $('#loadTaskModal').load('/minute/view/'+mid);
                $('#loadTaskModal').addClass('in');
                $('#loadTaskModal').show();         
            });
            $('#listLeft').on('click', '.meetings', function(event) {
                //alert($(this).find('.minute').length);
                //return false;
                event.preventDefault();
                if($(this).find('.minute').length)
                {
                   // mid = $(this).find('.minute:first').attr('mid');
                }
                else
                {
                   loadMinute($(this).attr('mid'));
                }          
            });
            $('#listLeft').on('click', '#createMinute', function(event) {
                event.preventDefault();
                //createMinuteForm
                $('#createMinuteError').html('');
                var mid = $(this).attr('mid');
                $.ajax({
                    url: '/minute/'+mid,
                    type: 'POST',
                    dataType: 'json',
                    data: $('#createMinuteForm').serialize(),
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
                            $('#createMinuteError').html(errorList);
                        }
                    }
                    else if(jsonData.success == 'error')
                    {
                        $('#createMinuteError').html('<span class="error">'+jsonData.msg+'</span>');
                    }
                    else if(jsonData.success == 'yes')
                    {
                        //top bar notification
                        $.notify('Session created',
                        {
                           className:'success',
                           globalPosition:'top center'
                        });
                        //$('#nextMinute').remove();
                        $('#rightContent').load('/minute/'+jsonData.mid);
                        
                    }
                    //console.log("success");
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });
                
            });
    $('#listLeft').on('click', '#editMinute', function(event) {
        event.preventDefault();
        $('.updateMinute').prop('disabled',false);
        $('.removeAttendees , .removeAbsentees').show();
        $('#updateMinute, #canleMinute').show();
        $(this).hide();
    });
    $('#listLeft').on('click', '#updateMinute', function(event) {
        event.preventDefault();
        $('#updateMinuteError').html('');
            var mid = $(this).attr('mid');
            $.ajax({
                url: '/minute/'+mid+'/update',
                type: 'POST',
                dataType: 'json',
                data: $('#updateMinuteForm').serialize(),
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
                        $('#updateMinuteError').html(errorList);
                    }
                }
                else if(jsonData.success == 'error')
                {
                    $('#updateMinuteError').html('<span class="error">'+jsonData.msg+'</span>');
                }
                else if(jsonData.success == 'yes')
                {
                    //top bar notification
                    $.notify('Updated',
                    {
                       className:'success',
                       globalPosition:'top center'
                    });
                    $('.updateMinute').prop('disabled',true);
                    $('.removeAttendees , .removeAbsentees').hide();
                    $('#editMinute').show();
                    $('#updateMinute, #canleMinute').hide();
                    
                }
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });

    });
     $('#listLeft').on('click', '#canleMinute', function(event) { 
        $('.updateMinute').prop('disabled',true);
        $('.removeAttendees , .removeAbsentees').hide();
        $('#editMinute').show();
        $('#updateMinute, #canleMinute').hide();
     });

    $('#listLeft').on('click', '.removeAttendees', function(event) {
            userName = $(this).parent(".attendees" ).text();
            userId = $(this).parent(".attendees" ).attr('uid').match(/\d+/);
            html = '<div uid="u'+userId+'" class="col-md-2 absentees"><input type="hidden" value="'+userId+'" name="absentees[]">'+userName+'<span style="" class="removeAbsentees btn glyphicon glyphicon-trash"></span></div>';
            $('#absentees').append(html);
            $(this).parent(".attendees" ).remove();
        });
    $('#listLeft').on('click', '.removeAbsentees', function(event) {
        userName = $(this).parent(".absentees").text();
        userId = $(this).parent(".absentees").attr('uid').match(/\d+/);
        html = '<div uid="u'+userId+'  " class="col-md-2 attendees"><input type="hidden" value="'+userId+'" name="attendees[]">'+userName+'<span style="" class="removeAttendees btn glyphicon glyphicon-trash"></span></div>';
        $('#attendees').append(html);
        $(this).parent( ".absentees" ).remove();
        });
    $('#listLeft').on('click', '#add_more', function(event) {
        event.preventDefault();
        taskBlock = $( ".taskBlock:first").clone().appendTo('#taskAddBlock').find(".form-control").val("");
        //$('#taskAddBlock').append('<div class="row taskBlock">'+taskBlock+'</div>');
        dateInput();
    });
    $('#listLeft').on('click', '.removeTaskFrom ', function(event) {
        event.preventDefault();
        if($('.taskBlock').length > 1)
        {
            $(this).parents('.taskBlock').remove();
        }
    });
    $('#listLeft').on('click', '#save_changes', function(event) {
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
        .fail(function() {
            //console.log("error");
        })
        .always(function() {
            //console.log("complete");
        });
        
    });
    $('#listLeft').on('click', '#send_minute', function(event) {
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
                    //$('#rightContent').load('/minute/'+$('#meetingId').val());
                    $('.meetingMenu.active').click();
                }
        })
        .fail(function() {

        })
        .always(function() {

        });
        
    });
    $('#listLeft').on('click', '#nextMinute', function(event) {
        event.preventDefault();
        $('#minuteBlock').toggle();
        //$('#createMinuteForm').find('input[name="venue"]').focus();
         $("html, body").animate({ scrollTop: $(document).height() }, 1000);
    });
    $('#listLeft').on('click', '#refresh', function(event) {
        event.preventDefault();
        $('.meetingMenu.active').click();
    });
});
$('#minutes').click(function(event)
{
	$('.meetingMenu').removeClass('active');
    $(this).addClass('active');
    $.ajax({
    	url: 'meetings/myminutes',
    	type: 'GET',
    	dataType: 'html',
    	//data: {param1: 'value1'},
    })
    .done(function(htmlData) {
    	$('#listLeft').html(htmlData);
        if($('#listLeft').find('.minute').length)
        {
           $('#listLeft').find('.minute:first').click();
        }
        else
        {
           $('#listLeft').find('.meetings:first').click();
        } 
    })
    .fail(function() {
    	//console.log("error");
    })
    .always(function() {
    	//console.log("complete");
    });
    
});
$('#history').click(function(event)
{
	$('.meetingMenu').removeClass('active');
    $(this).addClass('active');
    $.ajax({
    	url: 'meetings/history',
    	type: 'GET',
    	dataType: 'html',
    	//data: {param1: 'value1'},
    })
    .done(function(htmlData) {
    	$('#listLeft').html(htmlData);
    })
    .fail(function() {
    	//console.log("error");
    })
    .always(function() {
    	//console.log("complete");
    });
});
function dateInput()
{
    $('.dateInput').datepicker({format: "yyyy-mm-dd",startDate: "1d",startView: 0,autoclose: true});
}
function loadMinute(mid)
{
    $.ajax({
        url: '/minute/'+mid,
        type: 'GET',
        dataType: 'html',
        //data: {param1: 'value1'},
    })
    .done(function(htmlData) {
        //console.log("success");
        $('#rightContent').html(htmlData);
    })
    .fail(function() {
        //console.log("error");
    })
    .always(function() {
        //console.log("complete");
    });
}