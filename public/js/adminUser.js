$(document).ready(function()
{
	$('.user:first').click();
});
$('#adminContent').on('click', '.user', function(event)
{
	event.preventDefault();
	$('.user').removeClass('listHighlight1');
	$(this).addClass('listHighlight1');
	uid = $(this).attr('uid');
	$.ajax({
		url: '/admin/user/view/'+uid,
		type: 'GET',
		dataType: 'html',
	})
	.done(function(htmlData) {
		$('#adminUsersRight').html(htmlData);
	})
	.fail(function(xhr) {
        checkStatus(xhr.status);
    })
    .always(function(xhr) {
        checkStatus(xhr.status);
    });
	
});
$('#adminContent').on('click', '#addUser', function(event)
{
	event.preventDefault();
	$.ajax({
		url: '/admin/user/add/',
		type: 'GET',
		dataType: 'html',
	})
	.done(function(htmlData) {
		$('#adminUsersRight').html(htmlData);
	})
	.fail(function(xhr) {
        checkStatus(xhr.status);
    })
    .always(function(xhr) {
        checkStatus(xhr.status);
    });
});
$('#adminContent').on('click', '#addUserSubmit', function(event) {
	event.preventDefault();
	$.ajax({
		url: '/admin/user/add',
		type: 'POST',
		dataType: 'json',
		data: $('#addUserForm').serialize(),
	})
	.done(function(jsonData) {
		if(jsonData.success == 'no')
        {
            if(jsonData.hasOwnProperty('validator'))
            {
                $.each(jsonData.validator, function(index, val) {
                    //console.log(index);
                     $('#'+index+'_err').html(val);
                });
            }
        }
        else if(jsonData.success == 'yes')
        {
           location.reload();
        }
        else
        {
        	errorNotification('some this worng');
        }
	})
	.fail(function(xhr) {
        checkStatus(xhr.status);
    })
    .always(function(xhr) {
        checkStatus(xhr.status);
    });
	
});
$('#adminContent').on('click', '#addmeeting', function(event) {
	event.preventDefault();
	htmlcontent = '<div class="meetingSettingITem meetingItem">'+$('#meetingList').find('.meetingItem:first').html()+'</div>'
	$('#meetingList').append(htmlcontent);
});
$('#adminContent').on('click', '.removeMeeting', function(event) {
    if($(this).attr('mid'))
    {
        $(this).parent('.meetingParent').html('<input name="removeMeetings[]" type="hidden" value="'+$(this).attr('mid')+'">');
    }
    else
    {
        $(this).parent('.meetingItem').remove();
    }
});
$('#adminContent').on('click', '#editUser', function(event) {
	$('#adminUsersRight').load('/admin/user/edit/'+$(this).attr('uid'));
});
$('#adminContent').on('click', '#editUserSubmit', function(event) {
	uid = $(this).attr('uid');
	event.preventDefault();
	$.ajax({
		url: '/admin/user/edit/'+uid,
		type: 'POST',
		dataType: 'json',
		data: $('#addUserForm').serialize(),
	})
	.done(function(jsonData) {
		if(jsonData.success == 'no')
        {
            if(jsonData.hasOwnProperty('validator'))
            {
                $.each(jsonData.validator, function(index, val) {
                    //console.log(index);
                     $('#'+index+'_err').html(val);
                });
            }
        }
        else if(jsonData.success == 'yes')
        {
        	$('#adminUsersRight').load('/admin/user/view/'+uid);
        }
        else
        {
        	notification('some this worng');
        }
	})
	.fail(function(xhr) {
        checkStatus(xhr.status);
    })
    .always(function(xhr) {
        checkStatus(xhr.status);
    });
});
$('#adminContent').on('change', '.roles', function(event) {
        event.preventDefault();
        overrule = 0;
        if($('#role').length)
        {
            //new user
            if(parseInt($(this).val()) > parseInt($('#role').val()))
            {
                $(this).val($('#roles').val());
                overrule = 1;
            }
        }
        if($('#prerole').length)
        {
            //edit user
            if(parseInt($(this).val()) > parseInt($('#prerole').val()))
            {
                $(this).val($('#preroles').val());
                overrule = 1;
            }
        }
        if(overrule)
        {
            alert("This user does not have this permision!");
        }
    });