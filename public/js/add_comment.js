$(document).ready(function($) {
	$(document).on('click', '#add_comment', function(event) {
		$.ajax({
			url: '/comments/add/'+$(this).attr('nid').replace ( /[^\d.]/g, '' ),
			type: 'POST',
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
			console.log("complete");
		});
		
	});
});