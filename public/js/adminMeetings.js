$(document).ready(function($)
{
    $('.meeting:first').click();
    $('#adminContent').on('click', '#createMeetingSubmit', function(event) {
        event.preventDefault();
        $.ajax({
            url: '/admin/meeting/create',
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
        $(this).parent( ".participant" ).remove();
    });
    $('#adminContent').on('change', '.roles', function(event) {
        event.preventDefault();
        role = $(this).parents('div.participant').attr('roles');
        if(parseInt($(this).val()) > parseInt(role))
        {
            $(this).val(role);
            alert("Non jotter user can not be minuter");
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

$('#adminContent').on('click', '#editMeeting', function(event) {
    event.preventDefault();
    rightContentAjaxGet('/admin/meeting/edit/'+$(this).attr('mid'));
});