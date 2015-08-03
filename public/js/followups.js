$(document).ready(function() {
	 $('#maincontent').on('click', '.followup', function(event){
        tid = $(this).attr('tid');
        if($(this).attr('mid'))
        {
            path = '/followups/'+$(this).attr('mid')+'/task/'+tid;
        }
        else
        {
            path = 'followups/task/'+tid;
        }
        popupContentAjaxGet(path);
        
        });
	 $('#maincontent').on('click', '#followupComment', function(event) {
        event.preventDefault();
        tid = $(this).attr('tid');
        form = 'CommentForm'+tid;
        if($(this).attr('mid'))
        {
            path = 'followups/'+$(this).attr('mid')+'/comment/'+tid;
        }
        else
        {
            path = 'followups/comment/'+tid;
        }
        popupContentAjaxPost(path,form);
    });
	 $('#maincontent').on('click', '#createTask', function(event) {
        event.preventDefault();
        $('#popup').html('loading...');
        $('#popup').load('jobs/draftform',function( response, status, xhr ) {
            checkStatus(xhr.status);
            });
        $('#popup').show();
    });
    $('#maincontent').on('click', '.followupDraft', function(event) {
        event.preventDefault();
        $('#popup').html('loading...');
        $('#popup').load('jobs/draftform/'+$(this).attr('tid'),function( response, status, xhr ) {
            checkStatus(xhr.status);
            });
        $('#popup').show();
    });
    $('#maincontent').on('click', '#acceptCompletion', function(event) {
        event.preventDefault();
        tid = $(this).attr('tid');
        if($(this).attr('mid'))
        {
            path = '/minute/'+$(this).attr('mid')+'/acceptCompletion/'+tid;
        }
        else
        {
            path = '/jobs/acceptCompletion/'+tid;
        }
        rightContentAjaxGet(path);
        
    });
    $('#maincontent').on('click', '#rejectCompletion', function(event) {
        event.preventDefault();
        tid = $(this).attr('tid');
        if($(this).attr('mid'))
        {
            path = '/minute/'+mid+'/rejectCompletion/'+tid;
        }
        else
        {
            path = '/jobs/rejectCompletion/'+tid;
        }
        rightContentAjaxGet(path);
        
    });
    $('#maincontent').on('click', '#createTaskSave', function(event) {
            event.preventDefault();
            $.ajax({
                url: '/jobs/draft',
                type: 'POST',
                dataType: 'html',
                data: $('#createTaskForm').serialize(),
            })
            .done(function($htmlData) {
                $('#popup').html($htmlData);
                $.notify('Saved',
                    {
                       className:'success',
                       globalPosition:'top center'
                    });
            })
            .fail(function(xhr) {
                checkStatus(xhr.status);
            })
            .always(function(xhr) {
                checkStatus(xhr.status);
            });
            
        });
        $('#maincontent').on('click', '#createTaskSubmit', function(event) {
        	event.preventDefault();
        	$.ajax({
        		url: '/jobs/createTask',
        		type: 'POST',
        		dataType: 'json',
        		data: $('#createTaskForm').serialize(),
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
                ////console.log("success");
        	})
        	.fail(function(xhr) {
                checkStatus(xhr.status);
            })
            .always(function(xhr) {
                checkStatus(xhr.status);
            });
        	
        });
 $('#maincontent').on('click', '.removeParent', function(event) {
            $(this).parent( ".assignee" ).remove();
            $('#selectAssignee').val('');
            $('#selectAssignee').show();
        });
});