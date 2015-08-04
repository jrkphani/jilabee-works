$(document).ready(function($) 
{
		if($(window).width() > 719)
		{
			var width = $(window).width() * 2;
			var string = "width:" + width + "px";
			$('#centralContainer').attr("style",string);
		}
});
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
function popupContentAjaxGet(path)
{
    $.ajax({
            url: path,
            type: 'GET',
            dataType: 'html',
        })
        .done(function(htmlData) {
            $('#popup').html(htmlData).show();

        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
}
function popupContentAjaxPost(path,form)
{
    $.ajax({
            url: path,
            type: 'POST',
            dataType: 'html',
            data: $('#'+form).serialize()
        })
        .done(function(htmlData) {
            $('#popup').html(htmlData);
        })
        .fail(function(xhr) {
            checkStatus(xhr.status);
        })
        .always(function(xhr) {
            checkStatus(xhr.status);
        });
}
function dateInput()
{
    $('.dateInput').datepicker({format: "yyyy-mm-dd",startDate: "1d",startView: 0,autoclose: true});
}