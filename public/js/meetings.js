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
            $(this).parent( ".attendees" ).remove();
        });
            $('#listLeft').on('click', '.tempMeeting', function(event) {
            $('#loadMeetingModal').load('/meetings/load/'+$(this).attr('mid'));
            $('#loadMeetingModal').addClass('in');
            $('#loadMeetingModal').show();
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