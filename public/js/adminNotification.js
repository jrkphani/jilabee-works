$('#adminContent').on('click', '.meeting', function(event) {
        event.preventDefault();
        mid = $(this).attr('mid');
        $.ajax({
            url: '/admin/meeting/draft/'+mid,
            type: 'GET',
            async:false,
            dataType: 'html',
        })
        .done(function(htmlData){
            $('#popup').html(htmlData);
            $('#popup').show();
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
    });
$('#adminContent').on('click', '.newusers', function(event) {
        event.preventDefault();
        mid = $(this).attr('mid');
        $.ajax({
            url: '/admin/meeting/newusers/'+mid,
            type: 'GET',
            async:false,
            dataType: 'html',
        })
        .done(function(htmlData){
            $('#popup').html(htmlData);
            $('#popup').show();
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
    });
 $('#adminContent').on('click', '#approveMeeting', function(event) {
       var mid = $(this).attr('mid');
       $.ajax({
           url: '/admin/meeting/approve/'+mid,
           type: 'GET',
           async:false,
           dataType: 'json'
       })
       .done(function(jsonData) {
           if(jsonData.success == 'no')
           {
            alert('notificatin: something went wrong');
           }
           else if(jsonData.success == 'yes')
           {
            location.reload();
           }
       })
       .fail(function() {
           checkStatus(xhr.status);
       })
       .always(function() {
            checkStatus(xhr.status);
       });
       
    });
  $('#adminContent').on('click', '#disapproveMeeting', function(event) {
       var mid = $(this).attr('mid');
       var token = $('#_token').val();
       var reason = $('#reason').val();
       $.ajax({
           url: '/admin/meeting/disapprove/'+mid,
           type: 'POST',
           async:false,
           dataType: 'json',
            data: {'reason':reason,'_token':token},
       })
       .done(function(jsonData) {
           if(jsonData.success == 'no')
           {
            $('#reason_err').html(jsonData.reason);
           }
           else if(jsonData.success == 'yes')
           {
            location.reload();
           }
       })
       .fail(function() {
            checkStatus(xhr.status);
       })
       .always(function() {
            checkStatus(xhr.status);
       });
       
    });
  $('#adminContent').on('change', '.roles', function(event) {
        event.preventDefault();
        role = $(this).parents('div.participant').attr('roles');
        if(parseInt($(this).val()) > parseInt(role))
        {
            $(this).val(role);
            alert("This user can not be minuter");
        }
    });
  $('#adminContent').on('click', '.removeParent', function(event) {
        $(this).parent( ".participant" ).remove();
    });