$(document).ready(function($) 
{
		if($(window).width() > 719)
		{
			var width = $(window).width() * 2;
			var string = "width:" + width + "px";
			$('#centralContainer').attr("style",string);
		}
});
function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
function checkStatus(headerStatus) {
	if(headerStatus == '401')
	{
		//alert('not logged in');
		location.reload();
	}
	// else if((headerStatus != '200') || (headerStatus != '300'))
	// {
	// 	alert("something went wrong");
	// }
}