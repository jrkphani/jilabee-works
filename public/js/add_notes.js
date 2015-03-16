$(document).ready(function($) {
	$(document).on('click', '#save_changes', function(event) {
		$.ajax({
			url: '/notes/draft/'+$('#minuteshistory_id').val(),
			type: 'POST',
			//dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
			data: $('#notes_form').serialize(),
		})
		.done(function() {
			//console.log("success");
			$.notify('Draft saved !',
		    	{
		        	className:'success',
		            globalPosition:'top center'
		          });
			$('#add_more').click();
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	});
	$(document).on('click', '#send_minute', function(event) {
		$.ajax({
			url: '/notes/add/'+$('#minuteshistory_id').val(),
			type: 'POST',
			//dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
			data: $('#notes_form').serialize(),
		})
		.done(function() {
			$.notify('Saved !',
		    	{
		        	className:'success',
		            globalPosition:'top center'
		          });
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	});
	$('#add_more').click(function(event) {
		$('#notes_form').append($('#add_more_div').html());
		//alert($("#notes_form  .notes_form").length);
	});
	$(document).on('click', '.remove_notes_form',function(event) {
		$(this).parents('.notes_form').remove();
		//$(this).parents('.notes_form').css( "background", "yellow" );
		//alert($(this).parent('.notes_form').attr('class').val());
		//alert("Sfsd");
	});
});