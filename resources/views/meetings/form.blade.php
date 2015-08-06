<div class="popupWindow">
	<div class="popupHeader">
		<h2><a href="">Meeting</a> / <a href="">Create</a></h2>
		<button onclick="$('#popup').hide();" class="popupClose"></button>
		<div class="clearboth"></div>
	</div>
	<div class="popupContent">
		{!! Form::open(array('id' => 'createMeetingForm')) !!}
		<div class="popupContentLeft">
			{!! Form::label('title', 'Meeting title',['class'=>'control-label']); !!}
        	{!! Form::text('title', '',['class'=>'form-control'])!!}
        	<div id="title_err" class="error"></div>
        	
        	{!! Form::label('description', 'Meeting description',['class'=>'control-label']); !!}
        	{!! Form::textarea('description', '',['class'=>'form-control'])!!}
        	<div id="description_err" class="error"></div>

        	{!! Form::label('selectMinuters', 'Expected Minuters',['class'=>'control-label']); !!}
        	<div id="selected_minuters">
                {!! Form::text('selectMinuters', '',['class'=>'form-control'])!!}
            </div>
        	<div id="minuters_err" class="error"></div>

        	{!! Form::label('selectAttendees', 'Expected Attendees',['class'=>'control-label']); !!}
        	<div id="selected_attendees" class="form-group">
                {!! Form::text('selectAttendees', '',['class'=>'form-control'])!!}
            </div>
        	<div id="attendees_err" class="error"></div>

        	{!! Form::label('venue', 'Venue',['class'=>'control-label']); !!}
        	{!! Form::text('venue', '',['class'=>'form-control'])!!}
        	<div id="venue_err" class="error"></div>
		</div>
		<div class="popupContentRight">
		</div>
		{!!Form::close()!!}
	</div>
	<button id="createMeetingSubmit">Create</button>
</div>
<script type="text/javascript">
$( "#selectMinuters" ).autocomplete({
            source: "/user/search",
            minLength: 2,
            select: function( event, ui ) {
                if($("#" + ui.item.userId).length != 0)
                {
                  alert('User already exist!');
                  return false;
                }
                else
                {
                    insert = '<div class="col-md-6 attendees" id="'+ui.item.userId+'"><input type="hidden" name="minuters[]" value="'+ui.item.userId+'">'+ui.item.value+'<span class="removeParent"> remove</span></div>';
                    $('#selected_minuters').prepend(insert);
                    $(this).val("");
                    return false;
                }
                
            }
            });
 $( "#selectAttendees" ).autocomplete({
            source: "/user/search",
            minLength: 2,
            select: function( event, ui ) {
                if($("#" + ui.item.userId).length != 0)
                {
                  alert('User already exist!');
                  return false;
                }
                else
                {
                    insert = '<div class="col-md-6 attendees" id="'+ui.item.userId+'"><input type="hidden" name="attendees[]" value="'+ui.item.userId+'">'+ui.item.value+'<span class="removeParent"> remove</span></div>';
                    $('#selected_attendees').prepend(insert);
                    $(this).val("");
                    return false;
                }
                
            }
            });
</script>