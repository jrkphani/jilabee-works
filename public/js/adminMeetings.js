$(document).ready(function($)
{
    $('body').on('click', '#createMeetingSubmit', function(event) {
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
            //////console.log("success");
        })
        .fail(function() {
            ////console.log("error");
        })
        .always(function() {
            ////console.log("complete");
        });
        
    });
    $('body').on('click', '.removeParent', function(event) {
        $(this).parent( ".attendees" ).remove();
    });
	 $( "#selectMinuters" ).autocomplete({
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
                    $('#selected_minuters').append(insert);
                    $(this).val("");
                    return false;
                }
                
            }
            });
 $( "#selectAttendees" ).autocomplete({
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
                    $('#selected_attendees').append(insert);
                    $(this).val("");
                    return false;
                }
                
            }
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
    $('.approve').click(function(event) {
       var mid = $(this).attr('mid');
       $.ajax({
           url: '/admin/meetings/approve',
           type: 'POST',
           dataType: 'json',
           data: $('#'+fromid).serialize(),
       })
       .done(function(jsonData) {
           if(jsonData.success == 'no')
                {
                    //console.log("sa");
                    if(jsonData.hasOwnProperty('validator'))
                    {
                        $$('#m'+mid).find('.error').html('');
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
        var fromid = btn.attr('fromid');
        var reason = $('#'+fromid).find('input[name = reason]').val();
        var token =  $('#'+fromid).find('input[name = _token]').val();
        $('#'+fromid).find('.reason_err').html('');
        $.ajax({
           url: '/admin/meetings/disapprove',
           type: 'POST',
           dataType: 'json',
           data: {'mid': fromid.match(/\d+/),'reason':reason,'_token':token},
        })
        .done(function(jsonData) {
           if(jsonData.success == 'no')
                {
                    $('#'+fromid).find('.reason_err').html(jsonData.reason);
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
    });
});