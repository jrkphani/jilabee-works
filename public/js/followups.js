$(document).ready(function() {
    var url = document.location.toString();
        if (url.match('&'))
        {
            variables = url.split('&');
           mid = tid = clickDiv=0;
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
                 clickDiv =  $('.followup[mid='+mid+'][tid='+tid+']');
            }
            else if(tid)
            {
                clickDiv = $('.followup[tid='+tid+']:first');
            }
            if(clickDiv)
            {
                clickDiv.trigger( "click" );
            }
            //alert("Dfvd");
        }
        else
        {
            //do nothing
        } 
    });
	 $('#centralContainer').on('click', '.followup', function(event){
        tid = $(this).attr('tid');
        if($(this).attr('mid'))
        {
            path = '/followups/'+$(this).attr('mid')+'/task/'+tid;
        }
        else
        {
            path = '/followups/task/'+tid;
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
        $('#popup').load('followups/draftform',function( response, status, xhr ) {
            checkStatus(xhr.status);
            });
        $('#popup').show();
    });
    $('#centralContainer').on('click', '.followupDraft', function(event) {
        event.preventDefault();
        $('#popup').html('loading...');
        $('#popup').load('followups/draftform/'+$(this).attr('tid'),function( response, status, xhr ) {
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
            path = '/minute/'+$(this).attr('mid')+'/rejectCompletion/'+tid;
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
            async:false,
            dataType: 'json',
            })
            .done(function(jsonData){
                if(jsonData.success == 'yes')
                {
                    $('#popup').hide();
                    getNow();
                    getHistory();
                    toast('Task cancelled and sent to history!');
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
            async:false,
            dataType: 'json',
            })
            .done(function(jsonData){
                if(jsonData.success == 'yes')
                {
                    $('#popup').hide();
                    getNow();
                    getHistory();
                    toast('Task deleted!');
                }
                else
                {
                    toast('Oops! Something Went Wrong!');  
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
    $('#centralContainer').on('click', '#deleteDraft', function(event) {
        event.preventDefault();
        tid = $(this).attr('tid');
        if($(this).attr('mid'))
        {
            //has to be deleted in minutes
        }
        else
        {
            path = '/followups/deleteDraft/'+tid;
        }
        if (confirm('Are you sure to discard task?'))
        {
            $.ajax({
            url: path,
            type: 'GET',
            async:false,
            dataType: 'json',
            })
            .done(function(jsonData){
                if(jsonData.success == 'yes')
                {
                    getNow();
                    toast('Draft discarded!');
                }
                else
                {
                    toast('Oops! Something Went Wrong!');
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
                url: '/followups/draft',
                type: 'POST',
                async:false,
                dataType: 'html',
                data: $('#createTaskForm').serialize(),
            })
            .done(function($htmlData) {
                $('#popup').html($htmlData);
                toast('Draft saved!');
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
                async:false,
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
                    $('#popup').hide();
                    toast("Task sent");
                    getNow();
                   //location.reload();
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
    popupContentAjaxPost(path,form,'Task modified!');
 });
 $('#centralContainer').on('keypress', '#followupCommentText', function(event)
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
            $('#followupComment').click();
        }
    }  
 });
 $('#centralContainer').on('change', '#nowsortby', function(event) {
    event.preventDefault();
    getNow();
});
$('#centralContainer').on('change', '#days', function(event) {
    event.preventDefault();
    getHistory();
});
$('#centralContainer').on('change', '#historysortby', function(event) {
    event.preventDefault();
    getHistory();
});
$('#centralContainer').on('click', '#showNowDiv', function(event) {
    event.preventDefault();
    $('#nowsortby').val('timeline');
    $('#nowSearch').val('');
    getNow();
});
$('#centralContainer').on('click', '#showHistroyDiv', function(event) {
    event.preventDefault();
    $('#historysortby').val('timeline');
    $('#days').val('7');
    $('#historySearch').val('');
    getHistory();
});
 function getNow()
{
    params = '&sortby='+$('#nowsortby').val();
    if($('#nowSearch').val().trim().length > 0)
    {
        params = params +'&nowsearchtxt='+$('#nowSearch').val();
    }
    $.ajax({
            url: '/followups/now?'+params,
            type: 'GET',
            async:false,
            dataType: 'html',
        })
        .done(function(htmlData) {
            $('#nowDiv').html(htmlData);
            ChangeUrl('?'+params);
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
}
function getHistory()
{
    params = '&history=yes';
    if($('#historysortby').val())
    {
        params = params +'&historysortby='+$('#historysortby').val();
    }
    if($('#historySearch').val().trim().length > 0)
    {
        params = params +'&historysearchtxt='+$('#historySearch').val();
    }
    if($('#days').val().trim().length > 0)
    {
        params = params +'&days='+$('#days').val();
    }
     $.ajax({
            url: '/followups/history?'+params,
            type: 'GET',
            async:false,
            dataType: 'html',
        })
        .done(function(htmlData) {
            $('#historyDiv').html(htmlData);
            ChangeUrl('?'+params);
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
}
 function ChangeUrl(url) {
    if (typeof (history.pushState) != "undefined") {
        var obj = { Title: '', Url: '/followups'+url };
        history.pushState(obj, obj.Title, obj.Url);
    } else {
        //alert("Browser does not support HTML5.");
    }
}