$(document).ready(function($)
{
    // Javascript to enable link to tab
        var url = document.location.toString();
        if (url.match('#'))
        {
            if(url.split('#')[1] == 'meetings')
            {
                $('.user_left_menu').removeClass('active');
                $('#menuMeetings').click();
            }
            else if(url.split('#')[1] == 'followup')
            {
                $('.user_left_menu').removeClass('active');
                $('#menuFolloup').click();
            }
            else if(url.split('#')[1] == 'mytask')
            {
                $('.user_left_menu').removeClass('active');
                $('#menuMytask').click();
            }
        }
        else
        {
            $('.user_left_menu').removeClass('active');
            $('#menuMytask').click();
        }    
});

    	$('#menuMytask').click(function(event) {
    		$('.user_left_menu').removeClass('active');
    		$(this).addClass('active');
    		$('#user_left_menu_cont').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
    		$.ajax({
    			url: '/mytask',
    			type: 'GET',
    			async:false,
    			dataType: 'html',
    			//data: {'sortby': 'duedate' },
    		})
    		.done(function(output) {
    			$('#user_left_menu_cont').html(output);
                var url = document.location.toString();
                if (url.match('#'))
                {
                    if(url.split('#')[1] == 'meetings')
                    {
                        if(url.split('#').length > 2)
                        {
                            $('#'+url.split('#')[2]).click();
                        }
                        else
                        {
                            $("#user_left_menu_cont .mytask:first").click();
                        }
                    }
                    else
                    {
                        $("#user_left_menu_cont .mytask:first").click();
                    }
                }
                else
                {
                    $("#user_left_menu_cont .mytask:first").click();
                }
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
                var url = document.location.toString();
                if (url.match('#'))
                {
                    if(url.split('#')[1] == 'followup')
                    {
                        if(url.split('#').length > 2)
                        {
                            $('#'+url.split('#')[2]).click();
                        }
                        else
                        {
                            $("#user_left_menu_cont .followup:first").click();
                        }
                    }
                    else
                    {
                        $("#user_left_menu_cont .followup:first").click();
                    }
                }
                else
                {
                    $("#user_left_menu_cont .followup:first").click();
                }
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
        $('#menuMeetings').click(function(event) {
            $('.user_left_menu').removeClass('active');
            $(this).addClass('active');
            $('#user_left_menu_cont').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
            $.ajax({
                url: '/meetings',
                type: 'GET',
                async:false,
                dataType: 'html',
                //data: {_token: $_token },
            })
            .done(function(output) {
                $('#user_left_menu_cont').html(output);
                var url = document.location.toString();
                if (url.match('#'))
                {
                    if(url.split('#')[1] == 'meetings')
                    {
                        if(url.split('#').length > 2)
                        {
                            $('#'+url.split('#')[2]).click();
                        }
                        else
                        {
                            $("#user_left_menu_cont .minute:first").click();
                        }
                    }
                    else
                    {
                        $("#user_left_menu_cont .minute:first").click();
                    }
                }
                else
                {
                    $("#user_left_menu_cont .minute:first").click();
                }
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
        $('#user_left_menu_cont').on('click', '.minute', function(event) {
            $('.minute').removeClass('active');
            $(this).addClass('active');
            $('#content_right').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
            $.ajax({
                url: '/minute/'+$(this).attr('id').match(/\d+/)+'/tasks',
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
        $('#user_left_menu_cont').on('click', '.mytask', function(event) {
            $('.mytask').removeClass('active');
            $(this).addClass('active');
            $('#content_right').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
            $.ajax({
                url: '/task/'+$(this).attr('id').match(/\d+/)+'/comments',
                type: 'GET',
                async:false,
                //dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                //data: $('#notes_form').serialize(),
            })
            .done(function(output) {
                $('#content_right').html(output);
            })
            .fail(function() {
                $('#content_right').html('No data to display!');
                $.notify('Oops, Something went wrong!',
                {
                   //className:'error',
                   globalPosition:'top center'
                });
                
            })
            .always(function() {
               // console.log("complete");
            });
        });
        $('#user_left_menu_cont').on('click', '.followup', function(event) {
            $('.followup').removeClass('active');
            $(this).addClass('active');
            $('#content_right').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
            $.ajax({
                url: '/task/'+$(this).attr('id').match(/\d+/)+'/comments',
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
    $('#user_left_menu_cont').on('change', '#mytask_filter', function(event) {
        event.preventDefault();
        $('#user_left_menu_cont').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
            $.ajax({
                url: '/mytask',
                type: 'GET',
                async:false,
                dataType: 'html',
                data: {'sortby': $(this).val() },
            })
            .done(function(output) {
                $('#user_left_menu_cont').html(output);
                 $("#user_left_menu_cont .mytask:first").click();
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
        $('#user_left_menu_cont').on('change', '#followup_filter', function(event) {
        event.preventDefault();
        $('#user_left_menu_cont').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
            $.ajax({
                url: '/followup',
                type: 'GET',
                async:false,
                dataType: 'html',
                data: {'sortby': $(this).val() },
            })
            .done(function(output) {
                $('#user_left_menu_cont').html(output);
                 $("#user_left_menu_cont .followup:first").click();
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
   