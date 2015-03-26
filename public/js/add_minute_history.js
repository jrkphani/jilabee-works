/*$('#continue_minute, #add_first_minute').click(function(event) {
	var mid =$(this).attr('mid');
	 BootstrapDialog.show({
	 		title: 'Continue Minute',
            message: $('<div></div>').load('/minutehistory/add/'+mid),
            buttons: [{
                label: 'Continue',
                cssClass: 'btn-primary',
                action: function(dialogItself){
                    $.ajax({
                    	url: '/minutehistory/add/'+mid,
                    	type: 'POST',
                    	dataType: 'html',
                    	data: $('#minute_history_form').serialize(),
                    })
                    .done(function() {
                        dialogItself.close();
                        $.notify('Seesion started !',
                        {
                           className:'success',
                           globalPosition:'top center'
                        });
                        $('#menuMinutes').click();
                    })
                    .fail(function() {
                    	$.notify('Oops, Something went wrong!',
		                {
		                   className:'error',
		                   globalPosition:'top center'
		                });
                    })
                    .always(function() {
                    	// console.log("complete");
                    });
                    
                }
            	},
            	{
                label: 'Close',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });
});*/

$(document).on('click', '#continue_minute', function(event) {
    event.preventDefault();
    $.ajax({
        url: '/minutehistory/add/'+$(this).attr('mid'),
        type: 'POST',
        dataType: 'html',
        data: $('#minute_history_form').serialize(),
    })
    .done(function(output) {
        $('#content_right').html(output);
    })
    .fail(function() {
        $.notify('Oops, Something went wrong!',
        {
           className:'error',
           globalPosition:'top center'
        });
    })
    .always(function() {
        // console.log("complete");
    });
});