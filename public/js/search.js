var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 1 second
var $input = $('#nowSearch');

//on keyup, start the countdown
$input.on('keyup', function () {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(searchTxt, doneTypingInterval);
});

//on keydown, clear the countdown 
$input.on('keydown', function () {
  clearTimeout(typingTimer);
});

//user is "finished typing," do something
function searchTxt ()
{
     str = $('#nowSearch').val().trim();
     if(str.length >=3)
     {
        $('#nowDiv .box').hide();
        $("#nowDiv .searchTxt:contains('"+str+"')").parents('.box').show();
     }
}
$('#showNowDiv').click(function(event)
{
    $('#nowSearch').val('');
    $('#nowDiv .box').show();
});

var $input1 = $('#historySearch');

//on keyup, start the countdown
$input1.on('keyup', function () {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(searchTxt1, doneTypingInterval);
});

//on keydown, clear the countdown 
$input1.on('keydown', function () {
  clearTimeout(typingTimer);
});

//user is "finished typing," do something
function searchTxt1 ()
{
     str = $('#historySearch').val().trim();
     if(str.length >=3)
     {
        $('#historyDiv .box').hide();
        $("#historyDiv .searchTxt:contains('"+str+"')").parents('.box').show();
     }
}
$('#showHistroyDiv').click(function(event)
{
    $('#historySearch').val('');
    $('#historyDiv .box').show();
});