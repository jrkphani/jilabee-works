$(document).ready(function($) {
    	$('.onload').click();
    });
    	
    	$('.user_left_menu').click(function(event) {
    		$('.user_left_menu').removeClass('active');
    		$(this).addClass('active');
    		$('#user_left_menu_cont').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
    		$.ajax({
    			url: '/mytask',
    			type: 'POST',
    			async:false,
    			dataType: 'html',
    			data: {_token: $_token },
    		})
    		.done(function(output) {
    			$('#user_left_menu_cont').html(output);
    		})
    		.fail(function() {
    			$.notify('Oops, Something went wrong!',
    			{
				   className:'error',
				   globalPosition:'top center'
				});
				$('#user_left_menu_cont').html('No data to display!');
    		})
    		.always(function() {
    			//console.log("complete");
    		});
    		
    	});