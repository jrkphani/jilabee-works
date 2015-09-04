$(document).ready(function($) 
{
	var width = $(window).width() * 2;
    var string = "width:" + width + "px";
    $('#centralContainer').attr("style",string);
    //$('#centralViewer').show();
    $("#centralViewer").scrollLeft($('#contentRight').width());
    //moveright();
    $('#moveright').click(function(event) {
        moveright();
    });
    $('#moveleft').click(function(event) {
        //alert("vf");
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
});
function moveright()
{
    //$("#centralViewer").scrollLeft($('#contentRight').width());
    $("#centralViewer").animate({scrollLeft:'+='+$('#contentRight').width()}, 1000);
}
function moveleft()
{
    //$("#centralViewer").scrollLeft('-'+$('#contentLeft').width());
    $("#centralViewer").animate({scrollLeft:'-='+$('#contentLeft').width()}, 1000);
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
function popupContentAjaxPost(path,form)
{
    $.ajax({
            url: path,
            type: 'POST',
            dataType: 'html',
            data: $('#'+form).serialize()
        })
        .done(function(htmlData) {
            $('#popup').html(htmlData);
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
function notification(status,message){
    $.notify(message,
            {
               className:status,
               globalPosition:'top center'
            });
}
function isEmail(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};
function notifications()
{
    $.ajax({
            url: '/notifications',
            type: 'GET',
            dataType: 'json'
        })
        .done(function(jsonDate){
            if(jsonDate.success =='yes')
            {
                $('#notifications').html(jsonDate.result.length)
                if(jsonDate.result.length > 0)
                {
                    var t = jsonDate.dateNow.split(/[- :]/);
                    var dateNow = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
                     insert = '<h4>Notifications</h4>';
                    +'<button class="showAllBtn">Show All</button>';
                    $.each(jsonDate.result, function(key,row)
                    {
                        if(row.objectType == 'Task')
                        {
                            link = '/jobs?&tid='+row.objectId;
                            if(row.parentId)
                            {
                                link +=link+'&mid='+row.parentId;
                            }
                        }
                        else if(row.objectType == 'Minute')
                        {
                            //link = '';
                        }
                        var t = row.updated_at.split(/[- :]/);
                        var datePast = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
                        secs = new Date(dateNow -datePast)/1000;
                        updated_at = secs.toFixed(2)+' sec ago';
                        if(secs >=60)
                        {
                            mins = (secs/60);
                            updated_at = mins.toFixed(2)+' mins ago';
                            if(mins >=60)
                            {
                                hrs = mins/60;
                                updated_at = hrs.toFixed(2)+' hrs ago';
                                if(hrs >= 24)
                                {
                                    days = hrs/24;
                                    updated_at = days+' hrs ago';
                                }
                            }
                        }
                        insert += '<a href="'+link+'">'
                                +'<div class="notificationItem">'
                                +'    <p>'+row.subject+'-'+row.objectType+'</p>'
                                +'    <h6>'+row.body+'</h6>'
                                +'    <p>'+updated_at+'</p>'
                                +'</div></a>';
                    });
                    $('#notifyDiv').html(insert);
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