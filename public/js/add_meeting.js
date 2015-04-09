$(document).ready(function(){
		$( "#searchAttendess" ).autocomplete({
			source: "/user/search",
			minLength: 2,
			select: function( event, ui ) {
				if($("#u" + ui.item.id).length != 0)
				{
				  alert('User already exist!');
				  return false;
				}
				else
				{
					insert = '<div class="col-md-6 attendees" id="u'+ui.item.id+'"><input type="hidden" name="attendees[]" value="'+ui.item.id+'">'+ui.item.value+'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
					$('#selected_attendees').append(insert);
					$(this).val("");
	    			return false;
				}
			}
			});
		$( "#searchMinuter" ).autocomplete({
			source: "/user/search",
			minLength: 2,
			select: function( event, ui ) {
				if($("#u" + ui.item.id).length != 0)
				{
				  alert('User already exist!');
				  return false;
				}
				else
				{
					insert = '<div class="col-md-6 attendees" id="u'+ui.item.id+'"><input type="hidden" name="minuters[]" value="'+ui.item.id+'">'+ui.item.value+'<span class="removeParent btn glyphicon glyphicon-trash"></span></div>';
					$('#selected_minuters').append(insert);
					$(this).val("");
	    			return false;
				}
				
			}
			});
		$('#selected_minuters, #selected_attendees').on('click', '.removeParent', function(event) {
			$(this).parent( ".attendees" ).remove();
		});
	});