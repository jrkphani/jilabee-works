$(document).ready(function($) {
    	$('#stickynotes_close').click(function(event) {
    		/*$('#stickynotes').animate({
						    right: "-530px",
						  }, 'fast', function() {
						    $('#stickynotes').hide();
						  });*/
        $('#stickynotes').hide(500);
    	});
    	$('#stickynotes_open').click(function(event) {
    		$('#stickynotes').show(500);
    		// $("#stickynotes").animate({right: "15px"});
    	});
    	$('#stickynotes_content').on('click', '#add_stick_notes', function(event) {
        event.preventDefault();
        $('#stick_notes_loading').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
        $.ajax({
            url: '/stickynotes',
            type: 'POST',
            async:false,
            //dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
            data: $('#sticknotes_form').serialize(),
            })
            .done(function(data) {
                $('#stickynotes_content').html(data);
                $.notify('Notes saved!',
                {
                    className:'success',
                    globalPosition:'top center'
                });
            })
            .fail(function() {
                $.notify('Oops, Something went wrong!',
                {
                   className:'error',
                   globalPosition:'top center'
                });
            })
            .always(function() {
                $('#stick_notes_loading').html('');
            });
        
    });
    $('#stickynotes_content').on('click', '.removeSticky', function(event) {
        event.preventDefault();
        $.ajax({
            url: '/stickynotes/remove/'+$(this).attr('sid'),
            type: 'GET',
            async:false,
            })
            .done(function(data) {
                $('#stickynotes_content').html(data);
                $.notify('Removed successfully !',
                {
                    className:'success',
                    globalPosition:'top center'
                });
            })
            .fail(function() {
                $.notify('Oops, Something went wrong!',
                {
                   className:'error',
                   globalPosition:'top center'
                });
            })
            .always(function() {
            });
        
    });
    });