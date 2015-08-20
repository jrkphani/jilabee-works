$('#adminContent').on('click', '.meeting', function(event) {
        event.preventDefault();
        mid = $(this).attr('mid');
        $.ajax({
            url: '/admin/meeting/view/'+mid,
            type: 'GET',
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