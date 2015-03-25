	function getcolor()
	{
		$('#colorpicker').colorpicker().on('changeColor', function(ev)
	    {
			$('#label').val(ev.color.toHex());
			$('#label').css({'background-color': ev.color.toHex()});
		});
		$('#resetColor').click(function(event) {
			$('#label').val("");
			$('#label').css({'background-color':""});
		});
	}
	/*$(document).on('click', '#saveminute', function(event) {
		event.preventDefault();
		$.ajax({
			url: 'minute/add',
			type: 'POST',
			dataType: 'html',
			data: $('#minuteform').serialize()
		})
		.done(function() {
            $.notify('Minute saved!',
            {
	            className:'success',
	            globalPosition:'top center'
	            });
	            $('#menuMinutes').click();
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
		
	});*/