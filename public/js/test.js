$(document).ready(function($)
{
	// $('.accept').click(function(event) {
	// 	event.preventDefault();
 //        tid = $(this).attr('tid');
 //        form = 'Form'+tid;
 //        if($(this).attr('mid'))
 //        {
 //            path = '/minute/'+$(this).attr('mid')+'/acceptTask/'+tid;
 //        }
 //        else
 //        {
 //            path = '/jobs/acceptTask/'+tid;
 //        }
 //        $.ajax({
 //            url: path,
 //            async:false,
 //            type: 'GET',
 //        })
 //        .done(function() {
 //            getNow();
 //            toast('Task accepted!');
 //        })
 //        .fail(function(xhr) {
 //            checkStatus(xhr.status);
 //        })
 //        .always(function(xhr) {
 //            checkStatus(xhr.status);
 //        });
	// });
	$('.reject').click(function(event) {
		event.preventDefault();
        tid = $(this).attr('tid');
        if($(this).attr('mid'))
        {
            path = '/minute/'+$(this).attr('mid')+'/rejectTask/'+tid;
             form = 'Formm'+tid;
        }
        else
        {            
            path = '/jobs/rejectTask/'+tid;
             form = 'Formt'+tid;
        }
        $.ajax({
            url: path,
            type: 'POST',
            dataType: 'json',
            async:false,
            data: $('#'+form).serialize()
        })
        .done(function(jsonData) {
            if(jsonData.success == 'yes')
            {
                //$('#accept, #reject').remove();
                getNow();
                toast('Task rejected!');
            }
            else if(jsonData.success == 'no')
            {
                $('.error').html('');
                $('#err_'+tid).html(jsonData.msg);
                $('#'+form).find('textarea').focus();
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
	});
	$('#taskComment').click(function(event) {
        event.preventDefault();
        tid = $(this).attr('tid');
        $('#CommentForm'+tid).submit();       
    });
});