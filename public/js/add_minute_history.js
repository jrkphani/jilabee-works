$('#continue_minute').click(function(event) {
	var mid =$(this).attr('mid');
	 BootstrapDialog.show({
	 		title: 'Continue Minute',
            message: $('<div></div>').load('/minutehistory/add/'+mid),
            buttons: [{
                label: 'Continue',
                cssClass: 'btn-primary',
                action: function(){
                    $.ajax({
                    	url: '/minutehistory/add/'+mid,
                    	type: 'POST',
                    	dataType: 'html',
                    	data: $('#minute_history_form').serialize(),
                    })
                    .done(function(output) {
                    	// console.log("success");
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
});