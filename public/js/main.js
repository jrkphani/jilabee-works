$(document).ready(function($) 
{
    $.isnotoicationajax = 'no'
	var width = $(window).width() * 2;
    var string = "width:" + width + "px";
    $('#centralContainer').attr("style",string);
     historypage='no';
    var url = document.location.toString();
        if (url.match('&'))
        {
            variables = url.split('&');
            for (var i = 0; i < variables.length; i++)
            {
                var sParameterName = variables[i].split('=');
                if (sParameterName[0] == 'history')
                {
                    historypage='yes';
                    break;
                }
            }
        }
    if(historypage == 'no')
    {   //move to now page
        $("#centralViewer").scrollLeft($('#contentRight').width());
    }
    $('#centralViewer').on('click', '#moveright', function(event) {
        event.preventDefault();
        moveright();
    });
    $('#centralViewer').on('click', '#moveleft', function(event) {
        event.preventDefault();
        moveleft();
    });
    $(document).keydown(function(e)
    {
        if (e.keyCode == 27) {
            if($('#popup').length)
            {
                if($('#popup').is(":visible"))
                {
                    $('#popup').hide();
                }
            }
            if($('#nameMenu').length)
            {
                if($('#nameMenu').is(":visible"))
                {
                    $('#nameMenu').hide();   
                }
            }
            if($('#notifyDiv').length)
            {
                if($('#notifyDiv').is(":visible"))
                {
                    $('#notifyDiv').hide();   
                }
            }
            if($('#notificationDiv').length)
            {
                if($('#notificationDiv').is(":visible"))
                {
                    $('#notificationDiv').hide();   
                }
            }
        }
    });
    $(document).on('click', '.backBtn', function(event) {
        event.preventDefault();
        url = $(this).attr('url');
        divId = $(this).attr('divId');
        $('#'+divId).load(url);
    });
    $(document).click(function(event) {
        // if($('#nameMenu').length)
        //     {
        //         if($('#nameMenu').is(":visible"))
        //         {
        //             $('#nameMenu').hide();   
        //         }
        //     }
    });
    notifications();
    //every 30 secs
    setInterval(notifications,30000);
    $('#toastClose').click(function(event) {
         $('#toastDiv').hide();
    });
})
.bind('ajaxStart', function()
{
    if($.isnotoicationajax == 'yes')
    {
         $('#toastDiv').hide();
    }
    else
    {
        $('#toastmsg').html('loading...');
        $('#toastDiv').show();
    }
})
.bind('ajaxError', function()
{
    $('#toastmsg').html('Oops! Something Went Wrong!');
})
.bind('ajaxComplete', function(jqXHR, textStatus, errorThrown)
 {
    if($.isnotoicationajax == 'no')
    {
        if(textStatus.status == 200)
        {
            //setTimeout(function(){$('#toastDiv').hide(); },1000);
            $('#toastDiv').hide();
            //setTimeout(function(){$('#toastDiv').hide(); },200);
        }
        else
        {
            setTimeout(function(){$('#toastDiv').hide(); },8000);
        }
    }
});
function moveright()
{
    //$("#centralViewer").scrollLeft($('#contentRight').width());
    $("#centralViewer").animate({scrollLeft:'+='+$('#contentRight').width()}, 1000);
    ChangeUrl('/');
}
function moveleft()
{
    //$("#centralViewer").scrollLeft('-'+$('#contentLeft').width());
    $("#centralViewer").animate({scrollLeft:'-='+$('#contentLeft').width()}, 1000);
     ChangeUrl('?&history=yes');
}

function checkStatus(headerStatus) {
	if(headerStatus == '401')
	{
		//alert('not logged in');
		location.reload();
	}
	// else if((headerStatus != '200') || (headerStatus != '300'))
	// {
	// 	alert("something went wrong");
	// }
}
function popupContentAjaxGet(path)
{
    $.ajax({
            url: path,
            type: 'GET',
            async:false,
            dataType: 'html',
        })
        .done(function(htmlData) {
            $('#popup').html(htmlData).show();

        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
}
function rightContentAjaxGet(path)
{
    $.ajax({
            url: path,
            type: 'GET',
            async:false,
            dataType: 'html',
        })
        .done(function(htmlData) {
            $('#adminUsersRight').html(htmlData).show();

        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
}
function popupContentAjaxPost(path,form,msg)
{
    msg = msg||'no';
    $.ajax({
            url: path,
            type: 'POST',
            dataType: 'html',
            async:false,
            data: $('#'+form).serialize()
        })
        .done(function(htmlData) {
            $('#popup').html(htmlData);
            if(msg !='no')
            {
                toast(msg);
            }
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
}
function dateInput()
{
    var d = new Date();
    $('.dateInput').appendDtpicker(
    {
    "autodateOnStart": false,
    "closeOnSelected": true
    });
    //$('.dateInput').datepicker({dateFormat: "yy-mm-dd",minDate: "today",changeMonth: true,changeYear: true});
}
function nextDateInput()
{
    var d = new Date();
    $('.nextDateInput').appendDtpicker(
    {
    "autodateOnStart": false,
    "minDate": d,
    "closeOnSelected": true
    });
    //$('.dateInput').datepicker({dateFormat: "yy-mm-dd",minDate: "today",changeMonth: true,changeYear: true});
}

function isEmail(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};
function notifications()
{
    $.isnotoicationajax = 'yes';
    $.ajax({
            url: '/notifications',
            type: 'GET',
            async:false,
            dataType: 'json',
            beforeSend:function(){
            $('#toastDiv').hide();
            },
        })
        .done(function(jsonDate){
             $.isnotoicationajax = 'no';
            if(jsonDate.success =='yes')
            {
                $('#notifications').html(jsonDate.result.length)
                if(jsonDate.result.length > 0)
                {
                    $('#notifications').show();
                    var t = jsonDate.dateNow.split(/[- :]/);
                    var dateNow = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
                     insert = '<h4>Notifications</h4><button id="allNotifications" class="showAllBtn">Show All</button>';
                    $.each(jsonDate.result, function(key,row)
                    {
                        if(row.objectType == 'jobs')
                        {
                            link = '/jobs/?';
                            if(row.tag == 'history')
                            {
                                link += '&history=yes';    
                            }
                            link += '&tid='+row.objectId;
                            if(row.parentId)
                            {
                                link +='&mid='+row.parentId;
                            }
                        }
                        else if(row.objectType == 'followups')
                        {
                            link = '/followups/?';
                            if(row.tag == 'history')
                            {
                                link += '&history=yes';    
                            }
                            link += '&tid='+row.objectId;
                            if(row.parentId)
                            {
                                link +='&mid='+row.parentId;
                            }
                        }
                        else if(row.objectType == 'meeting')
                        {
                            link = '';
                            row.body = 'New User Added in Meeting';
                        }
                        else
                        {
                            link = '';
                        }
                        var t = row.updated_at.split(/[- :]/);
                        var datePast = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
                        secs = new Date(dateNow -datePast)/1000;
                        updated_at = Math.round(secs)+' sec ago';
                        if(secs >=60)
                        {
                            mins = (secs/60);
                            updated_at = Math.round(mins)+' mins ago';
                            if(mins >=60)
                            {
                                hrs = mins/60;
                                updated_at = Math.round(hrs)+' hrs ago';
                                if(hrs >= 24)
                                {
                                    days = hrs/24;
                                    updated_at = Math.round(days)+' days ago';
                                }
                            }
                        }
                        isRead ='';
                        if(row.isRead == '1')
                        {
                            isRead ='notification_read';
                        }
                        insert +='<div class="notificationItem '+isRead+'">'
                                //+'    <p>'+row.subject+'-'+row.objectType+'</p>'
                                +'    <h6><a href="'+link+'">'+row.body+'</a></h6>'
                                +'    <p>'+updated_at+'</p>'
                                +'</div>';
                    });
                    $('#notifyDiv').html(insert);
                }
                else
                {
                    $('#notifications, #notifyDiv').hide();
                }
            }
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
}
$(document).on('click', '#allNotifications', function(event) {
    event.preventDefault();
     $.ajax({
            url: '/notifications/all',
            type: 'GET',
            dataType: 'html',
            async:false,
        })
        .done(function(htmlData) {
            $('#notification_content').html(htmlData);
            $('#notificationDiv').show();
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
});
function toast(msg,flag,time)
{
    msg = msg || 'Success';
    flag = flag || 'success';
    time = time || '4000';
    $('#toastmsg').html(msg);
    setTimeout(function(){
        $('#toastDiv').show(); 
        setTimeout(function(){$('#toastDiv').hide(); },time);
    },200);
}
