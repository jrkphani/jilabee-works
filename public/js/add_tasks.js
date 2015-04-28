$(document).ready(function($) {

	$('.dateInput').datepicker({format: "yyyy-mm-dd",startDate: "today",orientation:'auto top',autoclose: true});

	$(document).on('click', '#save_changes', function(event) {
		$.ajax({
			url: '/minute/'+$(this).attr('mhid')+'/tasks/add/draft',
			type: 'POST',
			async:false,
			//dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
			data: $('#tasksAddForm').serialize(),
		})
		.done(function() {
			//console.log("success");
			$.notify('Draft saved !',
		    	{
		        	className:'success',
		            globalPosition:'top center'
		          });
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
	$(document).on('click', '#add_more', function(event) {
		$('#tasksAddForm').append($('#add_more_div').html());
		$('.dateInput').datepicker({format: "yyyy-mm-dd",startDate: "today",orientation:'auto top',autoclose: true});
	});
	$(document).on('click', '.remove_task_form',function(event) {
		$(this).parents('.task_form').remove();
		//$(this).parents('.notes_form').css( "background", "yellow" );
		//alert($(this).parent('.notes_form').attr('class').val());
		//alert("Sfsd");
	});
	$(document).on('click', '#send_minute', function(event) {
		event.preventDefault();
		needToConfirm = false;
		$('#tasksAddForm').submit();
	});
		$(document).on('change', '.taskidea', function(event) {
		if($(this).val() == 'idea')
		{
			$(this).parents('.task_form').find('.taskinput').hide();
		}
		else
		{
			$(this).parents('.task_form').find('.taskinput').show();	
		}
	});
	$(document).on('click', '.ok_task_form', function(event) {
		event.preventDefault();
		//$(this).parents('.task_form').find('input , textarea , select, .btn').addClass('non-edit');
		non_edit = $(this).parents('.task_form').find('.task_form_non_edit');
		edit = $(this).parents('.task_form_edit');
		if(edit.find("input[name='title[]']").val().length <= 0)
		{
			edit.find("input[name='title[]']").focus();	
			return false;
		}
		output = '<div class="col-md-12" style="min-height: 14px"><a href="" class="edit_icon  glyphicon glyphicon-edit pull-right" style="display:none;"></a></div>' 
				+'<div class="col-md-8">'
					+'<div class="col-md-12">'+edit.find("input[name='title[]']").val()+'</div>'
					+'<div class="col-md-12 margin_top_20" style="overflow-y: scroll;  max-height:100px">'+edit.find("textarea[name='description[]']").val().replace(/\n/g, "<br />")+'</div>'
				+'</div>'
				+'<div class="col-md-4">'
					+'<div class="col-md-12">'
						+'<div class="col-md-6">'+edit.find("select[name='taskidea[]']").val()+'</div>'
						+'<div class="col-md-6">'+edit.find("input[name='due[]']").val()+'</div>'
					+'</div>'
			
					+'<div class="col-md-12">'
						+'<div class="col-md-6  margin_top_20">'+edit.find("select[name='assigner[]']").find('option:selected').text()+'</div>'
						+'<div class="col-md-6  margin_top_20">'+edit.find("select[name='assignee[]']").find('option:selected').text()+'</div>'
					+'</div>'	
				+'</div>';
		non_edit.html(output);
		edit.hide();				
	});
	$(document).on('click', '.edit_icon', function(event) {
		event.preventDefault();
		$(this).parents('.task_form').css('background-color', '#ffffff');
		$(this).parents('.task_form').find('.task_form_edit').show();
		$(this).parents('.task_form_non_edit').html('');

		
	});
	$(document).on('mouseenter', '.task_form_non_edit', function(event) {
		event.preventDefault();
		$(this).parents('.task_form').css('background-color', '#dddddd');
		$(this).find('.edit_icon').show();
	});
	$(document).on('mouseleave', '.task_form_non_edit', function(event) {
		event.preventDefault();
		$(this).parents('.task_form').css('background-color', '#ffffff');
		$(this).find('.edit_icon').hide();
	});
});