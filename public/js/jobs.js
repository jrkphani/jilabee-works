$(document).ready(function($)
{
    // Javascript to enable link to tab
        var url = document.location.toString();
        if (url.match('#'))
        {
            if(url.split('#')[1] == 'mytask')
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
            .fail(function() {
                
            })
            .always(function() {
                
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
        	.fail(function() {
        		//console.log("error");
        	})
        	.always(function() {
        		//console.log("complete");
        	});
        	
        });
    $('#listLeft').on('change', '#assigneeEmail', function(event) {
        event.preventDefault();
        $('#assignee').val('');
        $('#selectAssignee , .or').hide();
        $('#selected_Assignee').html('');
    });
    $('#listLeft').on('change', '#assignee', function(event) {
        event.preventDefault();
    });
    $('#listLeft').on('click', '.removeParent', function(event) {
            $(this).parent( ".assignee" ).remove();
            $('#selectAssignee , #assigneeEmail, .or').show();
        });
    $('#listLeft').on('click', '.task', function(event){
        myid = $(this).attr('myid');
        if($(this).attr('mid'))
        {
            path = '/minute/task/'+myid;
        }
        else
        {
            path = '/jobs/task/'+myid;
        }
        rightContentAjaxGet(path);
        
        });
    $('#listLeft').on('click', '.followup', function(event){
        myid = $(this).attr('myid');
        if($(this).attr('mid'))
        {
            path = '/minute/followup/'+myid;
        }
        else
        {
            path = '/jobs/followup/'+myid;
        }
        rightContentAjaxGet(path);
        
        });
    $('#listLeft').on('click', '#refresh', function(event) {
        event.preventDefault();
        $('.jobsMenu.active').click();
    });
    $('#listLeft').on('click', '#accept', function(event) {
        event.preventDefault();
        if($(this).attr('mtask'))
        {
            path = 'minute/accept/task/'+$(this).attr('mtask');
        }
        else
        {
            path = 'jobs/accept/task/'+$(this).attr('task');
        }
        rightContentAjaxGet(path);
        
    });

    $('#listLeft').on('click', '#reject', function(event) {
        event.preventDefault();
        if($(this).attr('mtask'))
        {
            form = 'mtaskForm'+$(this).attr('mtask');
            path = 'minute/reject/task/'+$(this).attr('mtask');
        }
        else
        {
            form = 'taskForm'+$(this).attr('task');
            path = 'jobs/reject/task/'+$(this).attr('task');
        }
        rightContentAjaxPost(path,form);
        
    });
    $('#listLeft').on('click', '#postComment', function(event) {
        event.preventDefault();
        if($(this).attr('mtask'))
        {
            form = 'CommentForm'+$(this).attr('mtask');
            path = 'minute/task/'+$(this).attr('mtask')+'/comment';
        }
        else
        {
            form = 'CommentForm'+$(this).attr('task');
            path = 'jobs/task/'+$(this).attr('task')+'/comment';
        }
        rightContentAjaxPost(path,form);
    });
    $('#listLeft').on('change', '#statusChange', function(event) {
        event.preventDefault();
        if($(this).attr('mtask'))
        {
            path = '/minute/status/'+$(this).attr('mtask');
        }
        else
        {
            path = '/jobs/status/'+$(this).attr('task');
        }
        status = $(this).val();
        $.ajax({
            url: path,
            type: 'POST',
            dataType: 'html',
            data: {'_token':$('#_token').val(),'status':status},
        })
        .done(function(htmlData) {
                    $.notify('Status updated',
                    {
                       className:'success',
                       globalPosition:'top center'
                    });
                $('#rightContent').html(htmlData);
        })
        .fail(function() {
            
        })
        .always(function() {
            
        });
        
    });
    $('#listLeft').on('click', '#markComplete', function(event) {
        event.preventDefault();
        tid = $(this).attr('mtask');
        status = 'Completed';
        $.ajax({
            url: '/minute/status/'+tid,
            type: 'POST',
            dataType: 'json',
            data: {'_token':$('#_token').val(),'status':status},
        })
        .done(function(jsonData) {
                if(jsonData.success == 'yes')
                {
                    $.notify('Status updated',
                    {
                       className:'success',
                       globalPosition:'top center'
                    });
                }
                $('#rightContent').load('minute/task/'+tid);
        })
        .fail(function() {
            
        })
        .always(function() {
            
        });
    });
    $('#listLeft').on('click', '#createTaskToggle', function(event) {
        event.preventDefault();
        $('#createTaskForm').load('jobs/taskform');
    });
    $('#listLeft').on('click', '.followupDraft', function(event) {
        event.preventDefault();
        $('#createTaskForm').load('jobs/taskform/'+$(this).attr('tid'));
        $('#createTaskModal').modal('show') ;
    });

});
function rightContentAjaxPost(path,form)
{
    $.ajax({
            url: path,
            type: 'POST',
            dataType: 'html',
            data: $('#'+form).serialize()
        })
        .done(function(htmlData) {
            $('#rightContent').html(htmlData);
        })
        .fail(function() {
            
        })
        .always(function() {
            
        });
}
function rightContentAjaxGet(path)
{
    $.ajax({
            url: path,
            type: 'GET',
            dataType: 'html',
        })
        .done(function(htmlData) {
            $('#rightContent').html(htmlData);
        })
        .fail(function() {
            
        })
        .always(function() {
            
        });
}
$('#mytask').click(function(event)
{
	$('.jobsMenu').removeClass('active');
    $(this).addClass('active');
    $.ajax({
    	url: 'jobs/mytask',
    	type: 'GET',
    	dataType: 'html',
    	//data: {param1: 'value1'},
    })
    .done(function(htmlData) {
    	$('#listLeft').html(htmlData);
        $('#listLeft').find('.task:first').click();
    })
    .fail(function() {
    	//console.log("error");
    })
    .always(function() {
    	//console.log("complete");
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
    .fail(function() {
    	//console.log("error");
    })
    .always(function() {
    	//console.log("complete");
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
    })
    .fail(function() {
    	//console.log("error");
    })
    .always(function() {
    	//console.log("complete");
    });
});
function dateInput()
{
    $('.dateInput').datepicker({format: "yyyy-mm-dd",startDate: "1d",startView: 0,autoclose: true});
}