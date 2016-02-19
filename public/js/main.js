$(document).ready(function($) 
{
    $("#note_button").click(function () {
        $('.btn-notify').toggleClass('close');
        var effect = 'slide';
        var options = { direction: 'right' };
        var duration = 700;
        $('.note-block').toggle(effect, options, duration);
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

function dateInput()
{
    var d = new Date();
    $('.dateInput').datetimepicker({
        format:'Y-m-d H:i',
    });
 
}
function nextDateInput()
{
    $('.nextDateInput').datetimepicker({
        format:'Y-m-d H:i',
        minDate:0,
       
    });
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
                if(jsonDate.unread)
                {
                    $('#notifications').html(jsonDate.unread);
                }
                else
                {
                    $('#notifications').html('*');   
                }
                if(jsonDate.result.length > 0)
                {
                    $('#notifications').show();
                    var t = jsonDate.dateNow.split(/[- :]/);
                    var dateNow = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
                     insert = '<h4>Notifications</h4><button id="allNotifications" class="showAllBtn">Show All</button>';
                    $.each(jsonDate.result, function(key,row)
                    {
                        link='';
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
                            link = '/meetings/?';
                            if(row.tag == 'history')
                            {
                                link += '&history=yes';    
                            }
                            link += '&mid='+row.objectId;
                        }
                        else if(row.objectType == 'meetinguser')
                        {
                            link = '/admin/?';
                            link += '&mid='+row.objectId;
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
                         if(row.isRead == '2')
                         {
                            link='';
                         }
                        insert +='<div class="notificationItem '+isRead+'">';
                        if(link.length)
                        {
                             insert +='<h6><a href="'+link+'">'+row.body+'</a></h6>';
                        }
                        else
                        {
                            insert +='<h6>'+row.body+'</h6>';
                        }
                        insert +='<p>'+updated_at+'</p>'
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