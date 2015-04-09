$(document).ready(function($) {
    	$('#stickynotes_close').click(function(event) {
    		$('#stickynotes').hide(500);
    	});
    	$('#stickynotes_open').click(function(event) {
    		$('#stickynotes').show(500);
    	}); 
    });