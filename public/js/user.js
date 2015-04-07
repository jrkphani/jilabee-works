    	$('#menuMytask').click(function(event) {
    		$('.user_left_menu').removeClass('active');
    		$(this).addClass('active');
    		$('#user_left_menu_cont').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
    		$.ajax({
    			url: '/notes',
    			type: 'GET',
    			async:false,
    			dataType: 'html',
    			//data: {_token: $_token },
    		})
    		.done(function(output) {
    			$('#user_left_menu_cont').html(output);
                 $("#user_left_menu_cont .note:first").click();
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
        $('#menuFolloup').click(function(event) {
            $('.user_left_menu').removeClass('active');
            $(this).addClass('active');
            $('#user_left_menu_cont').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
            $.ajax({
                url: '/followup',
                type: 'GET',
                async:false,
                dataType: 'html',
                //data: {_token: $_token },
            })
            .done(function(output) {
                $('#user_left_menu_cont').html(output);
                 $(".note:first").click();
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
        $('#menuMinutes').click(function(event) {
            $('.user_left_menu').removeClass('active');
            $(this).addClass('active');
            $('#user_left_menu_cont').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
            $.ajax({
                url: '/minute',
                type: 'GET',
                async:false,
                dataType: 'html',
                //data: {_token: $_token },
            })
            .done(function(output) {
                $('#user_left_menu_cont').html(output);
                $(".minutehistory:first").click();
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
        $(document).on('click', '.minutehistory', function(event) {
            $('#content_right').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
            $.ajax({
                url: '/minutehistory/'+$(this).attr('mhid'),
                type: 'GET',
                async:false,
                //dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                //data: $('#notes_form').serialize(),
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
                $('#content_right').html('No data to display!');
            })
            .always(function() {
               // console.log("complete");
            });
        });
        $(document).on('click', '.note', function(event) {
            $('#content_right').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
            $.ajax({
                url: '/notes/'+$(this).attr('nid'),
                type: 'GET',
                async:false,
                //dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                //data: $('#notes_form').serialize(),
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
                $('#content_right').html('No data to display!');
            })
            .always(function() {
               // console.log("complete");
            });
        });
        $(document).on('click', '.add_next_minute', function(event){
            $.get('/minutehistory/add/'+$(this).attr('mid'), function(data) {
                $('#content_right').html(data);
            });
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