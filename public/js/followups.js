$(document).ready(function() {
	 $('#centralContainer').on('click', '.followup', function(event){
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
	 $('#centralContainer').on('click', '#followupComment', function(event) {
        event.preventDefault();
        tid = $(this).attr('tid');
        form = 'CommentForm';
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
	 $('#centralContainer').on('click', '#createTask', function(event) {
        event.preventDefault();
        $('#popup').html('loading...');
        $('#popup').load('jobs/draftform',function( response, status, xhr ) {
            checkStatus(xhr.status);
            });
        $('#popup').show();
    });
    $('#centralContainer').on('click', '.followupDraft', function(event) {
        event.preventDefault();
        $('#popup').html('loading...');
        $('#popup').load('jobs/draftform/'+$(this).attr('tid'),function( response, status, xhr ) {
            checkStatus(xhr.status);
            });
        $('#popup').show();
    });
    $('#centralContainer').on('click', '#acceptCompletion', function(event) {
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
        popupContentAjaxGet(path);
        
    });
    $('#centralContainer').on('click', '#rejectCompletion', function(event) {
        event.preventDefault();
        tid = $(this).attr('tid');
        if($(this).attr('mid'))
        {
            //has to be closed in minutes
            //path = '/minute/'+$(this).attr('mid')+'/rejectCompletion/'+tid;
        }
        else
        {
            path = '/jobs/rejectCompletion/'+tid;
        }
        popupContentAjaxGet(path);
        
    });
    $('#centralContainer').on('click', '#cancelTask', function(event) {
        event.preventDefault();
        tid = $(this).attr('tid');
        if($(this).attr('mid'))
        {
            //has to be cancelled in minutes
        }
        else
        {
            path = '/jobs/cancelTask/'+tid;
        }
        if (confirm('Are you sure to cancel task?'))
        {
            $.ajax({
            url: path,
            type: 'GET',
            dataType: 'json',
            })
            .done(function(jsonData){
                if(jsonData.success == 'yes')
                {
                    location.reload();
                }
                else
                {
                 notification('error','Something went wrong');   
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
    $('#centralContainer').on('click', '#deleteTask', function(event) {
        event.preventDefault();
        tid = $(this).attr('tid');
        if($(this).attr('mid'))
        {
            //has to be deleted in minutes
        }
        else
        {
            path = '/jobs/deleteTask/'+tid;
        }
        if (confirm('Are you sure to delete task?'))
        {
            $.ajax({
            url: path,
            type: 'GET',
            dataType: 'json',
            })
            .done(function(jsonData){
                if(jsonData.success == 'yes')
                {
                    location.reload();
                }
                else
                {
                 notification('error','Something went wrong');   
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
    $('#centralContainer').on('click', '#createTaskSave', function(event) {
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
        $('#centralContainer').on('click', '#createTaskSubmit', function(event) {
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
 $('#centralContainer').on('click', '.removeParent', function(event) {
            $(this).parent( ".assignee" ).remove();
            $('#selectAssignee').val('');
            $('#selectAssignee').show();
        });
 $('#centralContainer').on('click', '#editTask', function(event) {
    event.preventDefault();
    tid = $(this).attr('tid');
      path = 'jobs/task/edit/'+tid;
    popupContentAjaxGet(path);
 });
 $('#centralContainer').on('click', '#updateTaskSubmit', function(event) {
    event.preventDefault();
    tid = $(this).attr('tid');
    form = 'updateTaskForm';
    if($(this).attr('mid'))
    {
        path = 'minute/'+$(this).attr('mid')+'/task/update/'+tid;
    }
    else
    {
        path = 'jobs/task/update/'+tid;
    }
    popupContentAjaxPost(path,form);
 });
});