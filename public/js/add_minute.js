$(document).ready(function($) {
	    $('#colorpicker').colorpicker().on('changeColor', function(ev)
	    {
			$('#label').val(ev.color.toHex());
			$('#label').css({'background-color': ev.color.toHex()});
		});
		$('#resetColor').click(function(event) {
			$('#label').val("");
			$('#label').css({'background-color':""});
		});
});