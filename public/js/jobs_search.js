var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 1 second
var $input = $('#jobNowSearch');

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
     str = $('#jobNowSearch').val().trim();
     if(str.length >=3)
     {
        $('#nowDiv .box').hide();
        $("#nowDiv .searchTxt:contains('"+str+"')").parents('.box').show();
     }
}
$('#showNowDiv').click(function(event)
{
    $('#jobNowSearch').val('');
    $('#nowDiv .box').show();
});

var $input1 = $('#jobHistorySearch');

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
     str = $('#jobHistorySearch').val().trim();
     if(str.length >=3)
     {
        $('#historyDiv .box').hide();
        $("#historyDiv .searchTxt:contains('"+str+"')").parents('.box').show();
     }
}
$('#showHistroyDiv').click(function(event)
{
    $('#jobHistorySearch').val('');
    $('#historyDiv .box').show();
});