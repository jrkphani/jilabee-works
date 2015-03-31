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
	$(document).ready(function(){
		$( "#searchUser" ).autocomplete({
			source: "/user/search",
			minLength: 2,
			select: function( event, ui ) {

				insert = '<div class="col-md-6 attendees" id=u"'+ui.item.id+'"><input type="hidden" name="attendees[]" value="'+ui.item.id+'">'+ui.item.value+'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
				$('#selected_attendees').append(insert);
				$(this).val("");
    			return false;
			}
			});
		$( "#searchMinuter" ).autocomplete({
			source: "/user/search",
			minLength: 2,
			select: function( event, ui ) {

				insert = '<div class="col-md-6 attendees" id=u"'+ui.item.id+'"><input type="hidden" name="minuters[]" value="'+ui.item.id+'">'+ui.item.value+'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
				$('#selected_minuter').append(insert);
				$(this).val("");
    			return false;
			}
			});
		$('#selected_minuter, #selected_attendees').on('click', '.removeParent', function(event) {
			$(this).parent( ".attendees" ).remove();
		});
	});


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