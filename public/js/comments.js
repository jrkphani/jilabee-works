$(document).ready(function($) {
	$(document).on('click', '#add_comment', function(event) {
		$.ajax({
			url: '/task/'+$(this).attr('tid')+'/comments/add',
			type: 'POST',
			async:false,
			dataType: 'html',
			data: {_token: $_token,'description':$('#description').val() },
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
		})
		.always(function() {
			//console.log("complete");
		});
		
	});
	$(document).on('click', '#accept_task', function(event) {
		$.ajax({
			url: '/task/'+$(this).attr('tid')+'/accept',
			type: 'POST',
			async:false,
			dataType: 'html',
			data: {_token: $_token,'description':$('#description').val() },
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
		})
		.always(function() {
			//console.log("complete");
		});
		
	});
	$(document).on('click', '#reject_task', function(event) {
		$.ajax({
			url: '/task/'+$(this).attr('tid')+'/reject',
			type: 'POST',
			async:false,
			dataType: 'html',
			data: {_token: $_token,'description':$('#description').val() },
		})
		.done(function(output) {
			$('#content_right').html(output);
			 //$('#menuMytask').click();
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
			//console.log("complete");
		});
		
	});
});