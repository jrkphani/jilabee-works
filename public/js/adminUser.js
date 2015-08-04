$(document).ready(function()
{
	$('.user:first').click();
});
$('#adminContent').on('click', '.user', function(event)
{
	event.preventDefault();
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