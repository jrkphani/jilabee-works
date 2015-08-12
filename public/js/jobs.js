$(document).ready(function($)
{
        
    $('#listLeft').on('click', '.removeParent', function(event) {
            $(this).parent( ".assignee" ).remove();
            $('#selectAssignee').val('');
            $('#selectAssignee').show();
        });
    $('#centralContainer').on('click', '.task', function(event){
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
    $('#centralContainer').on('click', '#accept', function(event) {
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
        $.ajax({
            url: path,
            type: 'GET',
        })
        .done(function() {
            location.reload();
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
        
    });
    $('#centralContainer').on('click', '#reject', function(event) {
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
        $.ajax({
            url: path,
            type: 'POST',
            dataType: 'json',
            data: $('#'+form).serialize()
        })
        .done(function(jsonData) {
            if(jsonData.success == 'yes')
            {
                $('#accept, #reject').remove();
            }
            else if(jsonData.success == 'no')
            {
                $('.error').html('');
                $('#err_'+tid).html(jsonData.msg);
                $('#'+form).find('textarea').focus();
            }
            else
            {
                //notification(status,message);
            }
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
    });

    $('#centralContainer').on('click', '#taskComment', function(event) {
        event.preventDefault();
        tid = $(this).attr('tid');
        form = 'CommentForm'+tid;
        if($(this).attr('mid'))
        {
            path = 'minute/'+$(this).attr('mid')+'/comment/'+tid;
        }
        else
        {
             path = 'jobs/comment/'+tid;
        }
        popupContentAjaxPost(path,form);
    });
    
    
    $('#centralContainer').on('click', '#markComplete', function(event) {
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
        $.ajax({
            url: path,
            type: 'GET',
        })
        .done(function() {
            location.reload();
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
    });

});


function rightContentAjaxGet(path)
{
    $.ajax({
            url: path,
            type: 'GET',
            dataType: 'html',
        })
        .done(function(htmlData) {
            $('#centralContainer').html(htmlData);
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
}