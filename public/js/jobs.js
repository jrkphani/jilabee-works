$(document).ready(function($)
{
        
    $('#listLeft').on('click', '.removeParent', function(event) {
            $(this).parent( ".assignee" ).remove();
            $('#selectAssignee').val('');
            $('#selectAssignee').show();
        });
    $('#maincontent').on('click', '.task', function(event){
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
    $('#maincontent').on('click', '#accept', function(event) {
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
    $('#maincontent').on('click', '#reject', function(event) {
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
                alert(jsonData.msg);
            }
            else
            {
                //oops
            }
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
    });

    $('#maincontent').on('click', '#taskComment', function(event) {
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

});


function rightContentAjaxGet(path)
{
    $.ajax({
            url: path,
            type: 'GET',
            dataType: 'html',
        })
        .done(function(htmlData) {
            $('#maincontent').html(htmlData);
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
}