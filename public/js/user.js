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
                console.log("complete");
            });
        });
        $(document).on('click', '.note', function(event) {
            $('#content_right').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
            $.ajax({
                url: '/notes/'+$(this).attr('nid'),
                type: 'GET',
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
                console.log("complete");
            });
        });
        /*$(document).on('click', '#addminute', function(event) {
            $('#content_right').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
            $.ajax({
                url: '/minute/add',
                type: 'GET',
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
                console.log("complete");
            });
        });*/
        $(document).on('click', '.add_first_minute', function(event){
            $.get('/minutehistory/add/'+$(this).attr('mid'), function(data) {
                $('#content_right').html(data);
            });
        });