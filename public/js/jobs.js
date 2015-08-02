$(document).ready(function($)
{
    // Javascript to enable link to tab
        var url = document.location.toString();
        if (url.match('#'))
        {
            if(url.split('#')[1] == 'jobs')
            {
                $('#mytask').click();
            }
            else if(url.split('#')[1] == 'followups')
            {
                $('#followups').click();
            }
            else if(url.split('#')[1] == 'history')
            {
                $('#history').click();
            }
        }
        else
        {
            $('#mytask').click();
        }
        $('#listLeft').on('click', '#createTaskSave', function(event) {
            event.preventDefault();
            $.ajax({
                url: '/jobs/draft',
                type: 'POST',
                dataType: 'html',
                data: $('#createTaskForm').serialize(),
            })
            .done(function($htmlData) {
                $('#createTaskForm').html($htmlData);
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
        $('#listLeft').on('click', '#createTaskSubmit', function(event) {
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
                    $('.error').html('');
                    $('#createTaskModal').modal('hide');
                    $('#createTaskForm').find(':input').each(function()
                        {
                            $(this).val('');
                        });
                    $('.jobsMenu.active').click()
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
    $('#listLeft').on('click', '.removeParent', function(event) {
            $(this).parent( ".assignee" ).remove();
            $('#selectAssignee').val('');
            $('#selectAssignee').show();
        });
    $('#jobspage').on('click', '.task', function(event){
        tid = $(this).attr('tid');
        if($(this).attr('mid'))
        {
            path = '/minute/'+$(this).attr('mid')+'/task/'+tid;
        }
        else
        {
            path = '/jobs/task/'+tid;
        }
        popupContentAjaxGet(path);
        });
    $('#listLeft').on('click', '.followup', function(event){
        tid = $(this).attr('tid');
        if($(this).attr('mid'))
        {
            path = '/minute/'+$(this).attr('mid')+'/followup/'+tid;
        }
        else
        {
            path = '/jobs/followup/'+tid;
        }
        rightContentAjaxGet(path);
        
        });
    $('#listLeft').on('click', '#refresh', function(event) {
        event.preventDefault();
        $('.jobsMenu.active').click();
    });
    $('#listLeft').on('click', '.historyTask', function(event){
        tid = $(this).attr('tid');
        if($(this).attr('mid'))
        {
            path = '/minute/'+$(this).attr('mid')+'/history/'+tid;
        }
        else
        {
            path = '/jobs/history/'+tid;
        }
        rightContentAjaxGet(path);
        
        });
    $('#jobspage').on('click', '#accept', function(event) {
        event.preventDefault();
        tid = $(this).attr('tid');
        form = 'Form'+tid;
        if($(this).attr('mid'))
        {
            path = 'minute/'+$(this).attr('mid')+'/acceptTask/'+tid;
        }
        else
        {
            path = 'jobs/acceptTask/'+tid;
        }
        rightContentAjaxGet(path);
        
    });
    $('#jobspage').on('click', '#reject', function(event) {
        event.preventDefault();
        tid = $(this).attr('tid');
        if($(this).attr('mid'))
        {
            path = 'minute/'+$(this).attr('mid')+'/rejectTask/'+tid;
             form = 'Form'+$(this).attr('mid')+tid;
        }
        else
        {            
            path = 'jobs/rejectTask/'+tid;
             form = 'Form'+tid;
        }
        rightContentAjaxPost(path,form);
        
    });

    $('#jobspage').on('click', '#taskComment', function(event) {
        event.preventDefault();
        tid = $(this).attr('tid');
        form = 'CommentForm'+tid;
        if($(this).attr('mid'))
        {
            path = 'minute/'+$(this).attr('mid')+'/task/'+tid+'/taskComment';
        }
        else
        {
            path = 'jobs/task/'+tid+'/taskComment';
        }
        popupContentAjaxPost(path,form);
    });
    $('#listLeft').on('click', '#followupComment', function(event) {
        event.preventDefault();
        tid = $(this).attr('tid');
        form = 'CommentForm'+tid;
        if($(this).attr('mid'))
        {
            path = 'minute/'+$(this).attr('mid')+'/task/'+tid+'/followupComment';
        }
        else
        {
            path = 'jobs/task/'+tid+'/followupComment';
        }
        rightContentAjaxPost(path,form);
    });
    $('#listLeft').on('click', '#acceptCompletion', function(event) {
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
    $('#listLeft').on('click', '#rejectCompletion', function(event) {
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
    $('#listLeft').on('click', '#markComplete', function(event) {
        event.preventDefault();
        tid = $(this).attr('tid');
        if($(this).attr('mid'))
        {
            path = '/minute/'+$(this).attr('mid')+'/markComplete/'+tid;
        }
        else
        {
            path = '/jobs/markComplete/'+tid;
        }
        rightContentAjaxGet(path);
    });
    $('#listLeft').on('click', '#createTaskToggle', function(event) {
        event.preventDefault();
        $('#createTaskForm').html('loading...');
        $('#createTaskForm').load('jobs/taskform');
    });
    $('#listLeft').on('click', '.followupDraft', function(event) {
        event.preventDefault();
        $('#createTaskForm').html('loading...');
        $('#createTaskForm').load('jobs/taskform/'+$(this).attr('tid'),function( response, status, xhr ) {
            checkStatus(xhr.status);
            });
        $('#createTaskModal').modal('show') ;
    });

});
function popupContentAjaxPost(path,form)
{
    $.ajax({
            url: path,
            type: 'POST',
            dataType: 'html',
            data: $('#'+form).serialize()
        })
        .done(function(htmlData) {
            $('#popup1').html(htmlData);
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
}
function popupContentAjaxGet(path)
{
    $.ajax({
            url: path,
            type: 'GET',
            dataType: 'html',
        })
        .done(function(htmlData) {
            $('#popup1').html(htmlData);
            toggle_visibility('popup1');
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
}
$('#mytask').click(function(event)
{
    $.ajax({
    	url: 'jobs/mytask',
    	type: 'GET',
    	dataType: 'html',
    	//data: {param1: 'value1'},
    })
    .done(function(htmlData) {
    	$('#jobspage').html(htmlData);
        //$('#listLeft').find('.task:first').click();
    })
    .fail(function(xhr) {
        checkStatus(xhr.status);
    })
    .always(function(xhr) {
        checkStatus(xhr.status);
    });
    
});
$('#followups').click(function(event)
{
	$('.jobsMenu').removeClass('active');
    $(this).addClass('active');
    $.ajax({
    	url: 'jobs/followups',
    	type: 'GET',
    	dataType: 'html',
    	//data: {param1: 'value1'},
    })
    .done(function(htmlData) {
    	$('#listLeft').html(htmlData);
        $('#listLeft').find('.followup:first').click();
    })
    .fail(function(xhr) {
        checkStatus(xhr.status);
    })
    .always(function(xhr) {
        checkStatus(xhr.status);
    });
});
$('#history').click(function(event)
{
	$('.jobsMenu').removeClass('active');
    $(this).addClass('active');
    $.ajax({
    	url: 'jobs/history',
    	type: 'GET',
    	dataType: 'html',
    	//data: {param1: 'value1'},
    })
    .done(function(htmlData) {
    	$('#listLeft').html(htmlData);
        $('#listLeft').find('.historyTask:first').click();
    })
    .fail(function(xhr) {
        checkStatus(xhr.status);
    })
    .always(function(xhr) {
        checkStatus(xhr.status);
    });
});
function dateInput()
{
    $('.dateInput').datepicker({format: "yyyy-mm-dd",startDate: "1d",startView: 0,autoclose: true});
}