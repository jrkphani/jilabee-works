$(document).ready(function($)
{
    $('.meeting:first').click();
    $('#adminContent').on('click', '#createMeetingSubmit', function(event) {
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
                location.reload();
                $.notify('Meeting Created Successfully',
                {
                   className:'success',
                   globalPosition:'top center'
                });
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
    $('#adminContent').on('click', '.removeParent', function(event) {
        $(this).parent( ".attendees" ).remove();
    });
	 

            $('.selectMinuters').autocomplete({
            source: "/user/search",
            minLength: 2,
            select: function( event, ui ) {
                var selected_minuters = $(this).prev(".selected_minuters");
                //alert(selected_minuters.find( "div.attendees[uid=u"+ui.item.userId+"]").html());
                if(selected_minuters.find( "div.attendees[uid=u"+ui.item.userId+"]").html())
                {
                    alert('User already exist!');
                    return false;
                }
                else
                {
                    insert = '<div class="attendees" uid="u'+ui.item.userId+'"><input type="hidden" name="minuters[]" value="'+ui.item.userId+'">'+ui.item.value+'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
                    selected_minuters.append(insert);
                    $(this).val("");
                    return false;
                }
                
            }
            });
            $('.selectAttendees').autocomplete({
            source: "/user/search",
            minLength: 2,
            select: function( event, ui ) {
                var selected_minuters = $(this).prev(".selected_attendees");
                //alert(selected_minuters.find( "div.attendees[uid=u"+ui.item.userId+"]").html());
                if(selected_minuters.find( "div.attendees[uid=u"+ui.item.userId+"]").html())
                {
                    alert('User already exist!');
                    return false;
                }
                else
                {
                    insert = '<div class="attendees" uid="u'+ui.item.userId+'"><input type="hidden" name="attendees[]" value="'+ui.item.userId+'">'+ui.item.value+'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
                    selected_minuters.append(insert);
                    $(this).val("");
                    return false;
                }
                
            }
            });
    /*$('.approve').click(function(event) {
       var mid = $(this).attr('mid');
       $.ajax({
           url: '/admin/meetings/approve',
           type: 'POST',
           dataType: 'json',
           data: $('#m'+mid).serialize(),
       })
       .done(function(jsonData) {
           if(jsonData.success == 'no')
                {
                    //console.log("sa");
                    if(jsonData.hasOwnProperty('validator'))
                    {
                        $('#m'+mid).find('.error').html('');
                        $.each(jsonData.validator, function(index, val) {
                            //console.log(index);
                             $('#m'+mid).find('.'+index+'_err').html(val);
                        });
                    }
                }
                else if(jsonData.success == 'yes')
                {
                    $('#meetingBlock'+mid).remove();
                    $.notify('Approved',
                    {
                       className:'success',
                       globalPosition:'top center'
                    });
                }
       })
       .fail(function() {
           //console.log("error");
       })
       .always(function() {
           //console.log("complete");
       });
       
    });
$('.disapprove').click(function(event) {
        var btn = $(this);
        var mid = btn.attr('mid');
        var reason = $('#m'+mid).find('input[name = reason]').val();
        var token =  $('#m'+mid).find('input[name = _token]').val();
        $('#m'+mid).find('.reason_err').html('');
        $.ajax({
           url: '/admin/meetings/disapprove',
           type: 'POST',
           dataType: 'json',
           data: {'mid': mid,'reason':reason,'_token':token},
        })
        .done(function(jsonData) {
           if(jsonData.success == 'no')
                {
                    $('#m'+mid).find('.reason_err').html(jsonData.reason);
                }
                else if(jsonData.success == 'yes')
                {
                    btn.remove();
                    $.notify('Disapproved',
                    {
                       className:'error',
                       globalPosition:'top center'
                    });
                }
        })
        .fail(function() {
           //console.log("error");
        })
        .always(function() {
           //console.log("complete");
        });   
    });*/
});
$('#adminContent').on('click', '.meeting', function(event) {
        event.preventDefault();
        mid = $(this).attr('mid');
        $('.meeting').removeClass('listHighlight1');
        $(this).addClass('listHighlight1');
        $.ajax({
            url: '/admin/meeting/view/'+mid,
            type: 'GET',
            dataType: 'html',
        })
        .done(function(htmlData){
            $('#adminUsersRight').html(htmlData);
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
    });
$('#adminContent').on('click', '#addMeeting', function(event)
{
    event.preventDefault();
    rightContentAjaxGet('/admin/meeting/create');
});
$('#adminContent').on('keyup', '#selectAttendees', function(event) {
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
                                insert = '<div class="col-md-6 attendees" id="'+val.replace('@', '_')+'"><input type="hidden" name="attendees[]" value="'+val+'">'+val+'<span class="removeParent"> remove</span></div>';
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
});
$('#adminContent').on('click', '#editMeeting', function(event) {
    event.preventDefault();
    rightContentAjaxGet('/admin/meetings/edit/'+$(this).attr('mid'));
});