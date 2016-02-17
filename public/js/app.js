$(document).ready(function(){
/**Select**/
    $("#menu_button").click(function(){
        show_menu();
    });
    function show_menu() {
        $('#menu_links').animate({width:'toggle'},350);
    };

    $("#participant_email").click(function(){
        $("#participant_email").tagsInput({width:'auto'});
    });
    $("#meeting_id").click(function(){
        $("#meeting_id").selectbox();
    });
    $("#sort_id").click(function(){
        $("#sort_id").selectbox();
    });

$('.btn-job-reject').on('click', function() {
    var button = $(this);

    if(button.hasClass('long')){
        e.preventDefault();
    }
    if ( button.hasClass('rejected')  ) {
        button.removeClass('rejected');
        $(this).prev('.job-status-desc').slideToggle("fast");
        button.addClass('long');
        $(this).text('Rejected');
        $(this).css('background-image','none');
        $('.btn-job-accept').hide();
    }
    else {
        button.addClass('rejected');
        $(this).prev('.job-status-desc').slideToggle("fast");
        button.removeClass('long');
        $('.btn-job-accept').show();
    }
});

$('.btn-job-accept').on('click', function() {
    var button = $(this);
    if ( button.hasClass('accepted')  ) {
        button.removeClass('accepted');
        $(this).prev().prev('.job-status-desc').fadeOut();
        $('.btn-job-reject').hide();
        $('.btn-job-accept').hide();
        $.toaster({ priority : 'success', title : 'Task', message : 'Accepted'});
    }
    else {
        button.addClass('accepted');
        $(this).prev().prev('.job-status-desc').slideToggle("fast");
        button.removeClass('long');
        $('.btn-job-reject').show();
    }
});

/**Job Horizontalbar**/

    var totjobcols = $('.jobs-container').children('.job-col').length;
    var jobscont = totjobcols * 303;
    if(jobscont < 1000){
        $(".jobs-container").css('width','100%');
    }
    else{
        $(".jobs-container").width(jobscont);
    }

    $("#horz-scroll").mCustomScrollbar({
        axis:"yx",
        scrollButtons:{enable:true},
        theme:"3d",
        scrollbarPosition:"outside",
        autoDraggerLength:"false",
    });

/***Notification comm***/
    $('#notify-small').click(function(){
        $('#small-comment').slideToggle("fast").css('display','block');
        $(this).css('display','none');
        $('.btn-notify-close').css('display','block');
    })

    $('.btn-notify-close').click(function(){
        $('#small-comment').slideToggle("fast");
        $('#notify-small').css('display','block');
        $('.btn-notify-close').css('display','none');
    })
/****notification***/

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


/***Signup form***/
(function($,W,D)
{
    var JQUERY4U = {};

    JQUERY4U.UTIL =
    {
        setupFormValidation: function()
        {
            //form validation rules
            $("#register-form").validate({
                rules: {
                    username: "required",
                    password: {
                        required: true,
                        minlength: 5
                    },
                },
                messages: {
                    username: "Please enter your username",
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 5 characters long"
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        }
    }

    //when the dom has loaded setup form validation rules
    $(D).ready(function($) {
        JQUERY4U.UTIL.setupFormValidation();
    });

})(jQuery, window, document);