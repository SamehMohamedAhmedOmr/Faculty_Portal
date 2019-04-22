$(document).ready(function()
{
    // define route for send Mail
    $('#sendMail').click(function () {
        // $(this).addClass('active');
        // $('#inboxMail').removeClass('active');
        // $('.inboxMail').fadeOut(800);
        // $('.SendMail').fadeIn(2500);
        location.href='/Email/Send';
    });
    // define route for inbox mail
    $('#inboxMail').click(function () {
        // $(this).addClass('active');
        // $('#sendMail').removeClass('active');
        // $('.inboxMail').fadeIn(800);
        // $('.SendMail').fadeOut(2500);
        location.href='/Email/inbox';
    });

    // close message (arrow back button)
    $('#message .fa-backward').click(function () {
       $('#message').hide({ direction: "right" }, 1500);
        $("#overlay").remove();
    });
    // cursor on hover on messages records
    $('.MailRecord').hover(function () {
        $(this).css('cursor', 'pointer');
    });
    // on click on mailRecords open message related to this record
    $('.MailRecord').click(function () {

        overlay = jQuery('<div id="overlay"> </div>');
        overlay.appendTo(document.body);

        $('#message').show({ direction: "right" }, 1500);

        type_of_message=$(this).find('.type').val();

        //get Message Details
        subject = $(this).find('.subject').text();
        body = $(this).find('.body').text();
        date = $(this).find('.date').text();

        // get My details
        myName = $('.myName').val();
        myEmail = $('.myEmail').val();

        // display data on message box
        $('#message .header .headerText').html('').append(subject);
        $('#message .body').html('').append(body);
        $('#message .header .date').html('').append(date);
        $('#message .details .date').html('').append(date);

        if(type_of_message=='inbox')
        {
            senderName = $(this).find('.senderName').text();
            senderEmail = $(this).find('.senderEmail').text();

            $('#message .header .from').html('').append(senderName);
            // document.getElementById('inboxfrom').title='fffffffff';
            // jQuery("#inboxfrom[title]").tooltip();


            $('#message .header .to').html('').append('Me');

            $('#message .details .from').html('').append(senderName);
            $('#message .details .to').html('').append('Me');
        }
        else if(type_of_message=='send')
        {
            receiverName = $(this).find('.receiverName').text();
            receiverEmail = $(this).find('.receiverEmail').text();

            $('#message .header .from').html('').append('Me');
            $('#message .header .to').html('').append(receiverName);

            $('#message .details .from').html('').append('Me');
            $('#message .details .to').html('').append(receiverName);
        }
    });

    // on click open compose Message
    $('.Mailcompose-button').click(function () {
        overlay = jQuery('<div id="overlay"> </div>');
        overlay.appendTo(document.body);
        $('.composeMessage').show();
    });
    // on click hide compose message
    $('.composeMessage .close').click(function () {
        $("#overlay").remove();
       $('.composeMessage').hide({ direction: "right" }, 1500);
    });


});