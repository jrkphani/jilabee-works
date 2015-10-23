$(document).ready(function($)
{
    $('.meeting:first').click();
    $('#adminContent').on('click', '#createMeetingSubmit', function(event) {
        event.preventDefault();
        $.ajax({
            url: '/admin/meeting/create',
            type: 'POST',
            async:false,
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
            }
            //////console.log("success");
        })
        .fail(function(xhr) {
             checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
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
            alert("This user can not be minuter");
        }
    });
});
$('#adminContent').on('click', '.meeting', function(event) {
        event.preventDefault();
        mid = $(this).attr('mid');
        $('.meeting').removeClass('listHighlight1');
        $(this).addClass('listHighlight1');
        $.ajax({
            url: '/admin/meeting/view/'+mid,
            type: 'GET',
            async:false,
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
$('#adminContent').on('click', '#act_deact_Meeting', function(event) {
    event.preventDefault();
    mid = $(this).attr('mid');
    $.ajax({
            url: '/admin/meeting/activate/'+mid,
            type: 'GET',
            async:false,
            dataType: 'json',
        })
        .done(function(jsonData){
            if(jsonData.success == 'yes')
            {
                if(jsonData.active == '0')
                {
                    $('#act_deact_Meeting').text('Activate');
                    toast('Meeting reactivated');
                }
                else
                {
                    $('#act_deact_Meeting').text('Deactivate');
                    toast('Meeting deactivated');
                }
            }
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
});
$('#adminContent').on('click', '#endMeeting', function(event) {
    event.preventDefault();
    mid = $(this).attr('mid');
    if (confirm('Are you sure to end this meeting?'))
        {
            $.ajax({
            url: '/admin/meeting/delete/'+mid,
            type: 'GET',
            async:false,
            dataType: 'json',
            })
            .done(function(jsonData){
                if(jsonData.success == 'yes')
                {
                    location.reload();
                }
                else
                {
                 toast("Oops! Something Went Wrong!");
                }
            })
            .fail(function(xhr) {
                checkStatus(xhr.status);
            })
            .always(function(xhr) {
                checkStatus(xhr.status);
            });
        }
});
