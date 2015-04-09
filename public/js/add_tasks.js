$(document).ready(function($) {

	$('.dateInput').datepicker({format: "yyyy-mm-dd",startDate: "today",orientation:'auto top',autoclose: true});

	$(document).on('click', '#save_changes', function(event) {
		$.ajax({
			url: '/minute/'+$(this).attr('mhid')+'/tasks/add/draft',
			type: 'POST',
			async:false,
			//dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
			data: $('#tasksAddForm').serialize(),
		})
		.done(function() {
			//console.log("success");
			$.notify('Draft saved !',
		    	{
		        	className:'success',
		            globalPosition:'top center'
		          });
			//$('#add_more').click();
		})
		.fail(function() {
			$.notify('Oops, Something went wrong!',
	        {
	           className:'error',
	           globalPosition:'top center'
	        });
		})
		.always(function() {
			//console.log("complete");
		});
		
	});
	$(document).on('click', '#add_more', function(event) {
		$('#tasksAddForm').append($('#add_more_div').html());
		$('.dateInput').datepicker({format: "yyyy-mm-dd",startDate: "today",orientation:'auto top',autoclose: true});
	});
	$(document).on('click', '.remove_task_form',function(event) {
		$(this).parents('.task_form').remove();
		//$(this).parents('.notes_form').css( "background", "yellow" );
		//alert($(this).parent('.notes_form').attr('class').val());
		//alert("Sfsd");
	});
	$(document).on('click', '#send_minute', function(event) {
		event.preventDefault();
		needToConfirm = false;
		$('#tasksAddForm').submit();
	});
	$(document).on('click', '.edit_note',function(event) {
			var nid =$(this).attr('nid');
	 BootstrapDialog.show({
	 		title: 'Continue Minute',
            message: $('<div id="edit_note_popup"></div>').load('/notes/edit/'+nid),
            buttons: [{
                label: 'Continue',
                cssClass: 'btn-primary',
                action: function(dialogItself){
                    $.ajax({
                    	url: '/notes/edit/'+nid,
                    	type: 'POST',
                    	dataType: 'html',
                    	data: $('#note_edit_form').serialize(),
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
                    		$('#edit_note_popup').html(output);
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