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
           $('#popup').html('');
           $('#popup').hide();
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
	htmlcontent = '<div class="meetingSettingITem">'+$('#meetingList').find('.meetingSettingITem:first').html()+'</div>'
	$('#meetingList').append(htmlcontent);
});