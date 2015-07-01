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
            path = '/minute/'+$(this).attr('mid')+'/task/'+myid;
        }
        else
        {
            path = '/jobs/task/'+myid;
        }
        $.ajax({
            url: path,
            type: 'GET',
            dataType: 'html',
            //data: {param1: 'value1'},
        })
        .done(function(htmlData) {
            $('#rightContent').html(htmlData);
        })
        .fail(function() {
            
        })
        .always(function() {
            
        });
        
        });
    $('#listLeft').on('click', '.followup', function(event){
        myid = $(this).attr('myid');
        if($(this).attr('mid'))
        {
            path = '/minute/'+$(this).attr('mid')+'/followup/'+myid;
        }
        else
        {
            path = '/jobs/followup/'+myid;
        }
        $.ajax({
            url: path,
            type: 'GET',
            dataType: 'html',
            //data: {param1: 'value1'},
        })
        .done(function(htmlData) {
            $('#rightContent').html(htmlData);
        })
        .fail(function() {
            
        })
        .always(function() {
            
        });
        
        });
    $('#listLeft').on('click', '#refresh', function(event) {
        event.preventDefault();
        $('.jobsMenu.active').click();
    });
});
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