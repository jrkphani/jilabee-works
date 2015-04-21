$(document).ready(function($) {
		/*$('#content_right').on('click', '.edit_task',function(event)
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
		                    .fail(function(jqXHR) {
				                if(jqXHR.status == '401')
				                {
				                    location.reload();
				                    return;
				                }
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
		$('#content_right').on('click', '.edit_task',function(event)
		{
			event.preventDefault();
			var tid =$(this).attr('tid');
			$('#task'+tid).html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
			$('#task'+tid).load('/task/'+tid+'/edit');
		});
		$('#content_right').on('click', '.update_task',function(event)
		{	
			event.preventDefault();
			var tid =$(this).attr('tid');
			postdata= $('#task'+tid).find('form').serialize();
			$('#task'+tid).html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
			$.ajax({
		         	url: '/task/'+tid+'/edit',
		            type: 'POST',
		            dataType: 'html',
		            data: postdata,
		            })
		            .done(function(output) {
			           $('#task'+tid).html(output);
		           	})
		            .fail(function(jqXHR) {
				    	if(jqXHR.status == '401')
				        {
				            location.reload();
				            return;
				        }
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
		$('#content_right').on('click', '.cancle_task',function(event)
		{	
			event.preventDefault();
			var tid =$(this).attr('tid');
			$('#task'+tid).html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
			$('#task'+tid).load('/task/'+tid+'/get');
		});
		$('#content_right').on('change', '.changeStatus',function(event)
		{
			event.preventDefault();
			if($(this).val() == 'close')
			{
				var tid =$(this).attr('tid');
				 BootstrapDialog.show({
			 		title: 'Close Task ?',
		            message: $('<div id="close_task_popup"><strong>Are you sure you want to close the task ?</strong></div>'),
		            buttons: [{
		                label: 'Yes',
		                cssClass: 'btn-primary',
		                action: function(dialogItself){
		                    $.ajax({
		                    	url: '/task/'+tid+'/close',
		                    	type: 'POST',
		                    	dataType: 'html',
		                    	data: {'_token':$_token},
		                    })
		                    .done(function(output) {
		                    	$('#task'+tid).html(output);
		                    	dialogItself.close();
		                    })
		                    .fail(function(jqXHR) {
				                if(jqXHR.status == '401')
				                {
				                    location.reload();
				                    return;
				                }
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
		                label: 'No',
		                action: function(dialogItself){
		                    dialogItself.close();
		                }
		            }]
		        });
			}
			
		});
});