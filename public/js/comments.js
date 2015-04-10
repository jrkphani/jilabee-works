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
	$(document).on('click', '#accept_task', function(event) {
		$.ajax({
			url: '/task/'+$(this).attr('tid')+'/accept',
			type: 'POST',
			async:false,
			dataType: 'html',
			data: {_token: $_token,'description':$('#description').val() },
		})
		.done(function(output) {
			$.notify('Task accepted!',
                {
                   className:'success',
                   globalPosition:'top center'
                });
			 $('#content_right').html(output);
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
	$(document).on('click', '#reject_task', function(event) {
		$.ajax({
			url: '/task/'+$(this).attr('tid')+'/reject',
			type: 'POST',
			async:false,
			dataType: 'html',
			data: {_token: $_token,'description':$('#description').val() },
		})
		.done(function(output) {
			$.notify('Task rejected!',
                {
                   className:'success',
                   globalPosition:'top center'
                });
			 $('#content_right').html(output);
			 //$('#menuMytask').click();
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
});