$(document).ready(function($)
{
    var url = document.location.toString();
        if (url.match('&'))
        {
            variables = url.split('&');
            mid = tid = 0;
            for (var i = 0; i < variables.length; i++)
            {
                var sParameterName = variables[i].split('=');
                if (sParameterName[0] == 'mid')
                {
                    mid = sParameterName[1];
                }
                if (sParameterName[0] == 'tid')
                {
                    tid = sParameterName[1];   
                }
            }
            if(mid)
            {
                 clickDiv =  $(".task[mid='"+mid+"'][tid='"+tid+"']");
            }
            else if(tid)
            {
                clickDiv = $('.task[tid='+tid+']');
            }
            //alert(clickDiv.html());
            clickDiv.first().trigger( "click" );
            //alert("Dfvd");
        }
        else
        {
            //do nothing
        } 
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
$('#centralContainer').on('keypress', '#taskCommentText', function(event)
 {
    if(event.which == 13)
    {
        if(event.shiftKey)
        {
            //alert("shiftKey");
            //do nothing
        }
        else
        {
            $('#taskComment').click();
        }
    }  
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