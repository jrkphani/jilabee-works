$(document).ready(function(){
/**Select**/
    $("#menu_button").click(function(){
        show_menu();
    });
    function show_menu() {
        $('#menu_links').animate({width:'toggle'},350);
    }
/**Select
    $('.btn-job-reject').click(function(){
        $(this).prev('.job-status-desc').toggle();
        console.log($(this).prev());
        return false;
    });
 **/

// $('.btn-job-reject').on('click', function() {
//     var button = $(this);

//     if(button.hasClass('long')){
//         e.preventDefault();
//     }
//     if ( button.hasClass('rejected')  ) {
//         button.removeClass('rejected');
//         $(this).prev('.job-status-desc').slideToggle("fast");
//         button.addClass('long');
//         $(this).text('Rejected');
//         $(this).css('background-image','none');
//         $('.btn-job-accept').hide();
//     }
//     else {
//         button.addClass('rejected');
//         $(this).prev('.job-status-desc').slideToggle("fast");
//         button.removeClass('long');
//         $('.btn-job-accept').show();
//     }
// });

// $('.btn-job-accept').on('click', function() {
//     var button = $(this);
//     if ( button.hasClass('accepted')  ) {
//         button.removeClass('accepted');
//         $(this).prev().prev('.job-status-desc').fadeOut();
//         $('.btn-job-reject').hide();
//         $('.btn-job-accept').hide();
//         $.toaster({ priority : 'success', title : 'Task', message : 'Accepted'});
//     }
//     else {
//         button.addClass('accepted');
//         $(this).prev().prev('.job-status-desc').slideToggle("fast");
//         button.removeClass('long');
//         $('.btn-job-reject').show();
//     }
// });

/**Job Horizontalbar**/

    $('.scroll-pane').jScrollPane({
        showArrows: true,
        horizontalGutter: 10,
    });

/***Notification comm***/
    $('#notify-small').click(function(){
        $('#small-comment').slideToggle("fast").css('display','block');
        $(this).css('display','none');
        $('.btn-notify-close').css('display','block');
    })
    1
    $('.btn-notify-close').click(function(){
        $('#small-comment').slideToggle("fast");
        $('#notify-small').css('display','block');
        $('.btn-notify-close').css('display','none');
    })
/****notification**

$('#note_button').click(function() {
    if($('.note-block').css("width") == "540px") {
        $('.note-block').animate({"width": '0'});
        $('.note-copy').css('display','none');
        $('.btn-notify').removeClass('close');
    }
    else {
        $('.note-block').animate({"width": '540'});
        $('.note-copy').css('display','block');
        $('.btn-notify').addClass('close');
    }
});

    $('.jobs-container').click(function() {
        $('.note-block').animate({"width": '0'});
        $('.note-copy').css('display','none');
        $('.btn-notify').removeClass('close');
    });*/

$("#note_button").click(function () {
    $('.btn-notify').toggleClass('close');
    var effect = 'slide';
    var options = { direction: 'right' };
    var duration = 700;
    $('.note-block').toggle(effect, options, duration);
});

/***This Week***/
    $('.this-week').click(function(){
        $("#thisweek").slideToggle();
        $(this).text(function(i, text){
            return text === "+" ? "-" : "+";
        });
        if($('.this-week').text() == "-"){
            $(this).css("fontSize", 48);
        }
        else{
            $(this).css("fontSize", 24);
        }
    });

    $('.week').click(function(){
        if($(this).hasClass('inactive')){
            e.preventDefault();
        }
        else{
            $('.week').removeClass('active');
            $(this).addClass('active');
        }
    });


    $("#dp").datepicker({stepMonths: 0});
    $(".datepick").click(function(){ $("#dp").datepicker("show"); });

});
