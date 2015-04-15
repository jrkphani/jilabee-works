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
                    if(url.split('#')[1] == 'mytask')
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
            .fail(function(jqXHR) {
                if(jqXHR.status == '401')
                {
                    location.reload();
                    return;
                }
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
                $('#content_right').html('No data to display!');
            })
            .always(function() {
               // console.log("complete");
            });
        });
    
    $('#user_left_menu_cont').on('change', '#mytask_filter', function(event) {
        event.preventDefault();
        loadMyTask($('#mytask_filter').val(),$('#myTaskSearchInput').val());
        
    });
    $('#user_left_menu_cont').on('change', '#followup_filter', function(event) {
        event.preventDefault();
        loadFollowup($('#followup_filter').val(),$('#folloupSearchInput').val());
    });
    $('#user_left_menu_cont').on('click', '#myTaskSearch', function(event) {
        event.preventDefault();
        loadMyTask($('#mytask_filter').val(),$('#myTaskSearchInput').val());
        
    });
    $('#user_left_menu_cont').on('click', '#folloupSearch', function(event) {
        event.preventDefault();
        loadFollowup($('#followup_filter').val(),$('#folloupSearchInput').val());
    });

    function loadMyTask(sortby,search)
    {
        $('#user_left_menu_cont').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
            $.ajax({
                url: '/mytask',
                type: 'GET',
                async:false,
                dataType: 'html',
                data: {'sortby': sortby,'search': search },
            })
            .done(function(output) {
                $('#user_left_menu_cont').html(output);
                 $("#user_left_menu_cont .mytask:first").click();
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
                $('#user_left_menu_cont').html('No data to display!');
            })
            .always(function() {
                //console.log("complete");
            });
    }
   
   function loadFollowup(sortby,search)
   {
    $('#user_left_menu_cont').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
            $.ajax({
                url: '/followup',
                type: 'GET',
                async:false,
                dataType: 'html',
                data: {'sortby': sortby,'search': search },
            })
            .done(function(output) {
                $('#user_left_menu_cont').html(output);
                 $("#user_left_menu_cont .followup:first").click();
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
                $('#user_left_menu_cont').html('No data to display!');
            })
            .always(function() {
                //console.log("complete");
            });
   }