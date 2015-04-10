$(document).ready(function($) {
		$('#content_right').on('click', '.edit_task',function(event)
		{
			var tid =$(this).attr('tid');
				 BootstrapDialog.show({
			 		title: 'Update Minute',
		            message: $('<div id="edit_task_popup"></div>').load('/task/'+tid+'/edit'),
		            buttons: [{
		                label: 'Continue',
		                cssClass: 'btn-primary',
		                action: function(dialogItself){
		                    $.ajax({
		                    	url: '/task/'+tid+'/edit',
		                    	type: 'POST',
		                    	dataType: 'html',
		                    	data: $('#taskEditForm').serialize(),
		                    })
		                    .done(function(output) {
		                    	if(output == 'updated')
		                    	{
		                    		dialogItself.close();
			                        $.notify('Tasked updated !',
			                        {
			                           className:'success',
			                           globalPosition:'top center'
			                        });
		                    	}
		                    	else
		                    	{
		                    		$('#edit_task_popup').html(output);
		                    	}
		                        
		                        //$('#menuMinutes').click();
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
});