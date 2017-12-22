<div class="chat-popup-box">
    <div class="popup-head">
        <div class="popup-head-left"><a href=""><span></span></a></div>
        <div class="popup-head-right"><a href="javascript:void (0)"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div>
        <div style="clear: both"></div>
    </div>
    <div class="popup-messages"></div>
    <div class="sendmessage input-group">
        <input type="text" class="message-chat form-control" placeholder="Send message...">
        <!--        <span class="input-group-btn">-->
        <!--            <button class="sendmsg btn btn-default" type="button" disabled="true">Send</button>-->
        <!--        </span>-->
    </div>
</div>

<div class="row popup-message-row" id="messagePopupTemplate" style="display: none;">
    <div class="col-sm-12">
        <div class="single-popup-message-wrap">
            <span class="single-popup-message"></span>
        </div>
    </div>
</div>

<script>
    $('.chat-popup-box .popup-head-right').click(function () {
        $('.chat-popup-box').fadeOut();
    });

    function addSinglePopupMessage(data) {
        var messagesContainer = $('.chat-popup-box .popup-messages');
        var template = $('#messagePopupTemplate').clone().removeAttr('style').removeAttr('id');
        var templateMessage = $(template.find('.single-popup-message-wrap'));
        var message = emojione.toImage(data.message);
        if (data.receiver_id == data.friendId) {
            templateMessage.addClass('from-me pull-right');
        }
        else {
            templateMessage.addClass('to-me pull-left');
        }
        $(templateMessage.find('.single-popup-message')).html(message);

        messagesContainer.append(template);
    }

    $(document).ready(function () {
        var em = $('.sendmessage .message-chat').emojioneArea({
            pickerPosition: "top",
            tonesStyle: "bullet",
            inline: true,
            shortnames: true,
            useSprite: false,
            autoHideFilters: true,
//            events: {
//                keydown: function (editor, event) {
//                    $('.emojionearea-picker').addClass('hidden');
//                    if (event.which == 13) {
//                        event.preventDefault();
//                        sendPopupMessage();
//                        editor.html('');
//                    }
//                }
//            }
        });
        em[0].emojioneArea.on("keydown", function (el, ev) {
            $('.emojionearea-picker').addClass('hidden');
            $('.emojionearea-button').removeClass('active');
            if (ev.which == 13) {
                ev.preventDefault();
                var message = em[0].emojioneArea.getText();
                sendPopupMessage(message);
                el.html('');
            }
        });

//        $('.chat-popup-box .message-chat').on('keydown', function (e) {
//            if (e.which == 13) {
//                e.preventDefault();
//                sendPopupMessage();
//            }
//        });
    });

    function sendPopupMessage(message) {
        var input = $('.chat-popup-box .message-chat');
        message = emojione.toShort(message);
        var receiver_id = $('.chat-popup-box').attr('friendId');
        if (message && receiver_id) {
            var params = {
                friendId: receiver_id,
                message: message,
                receiver_id: receiver_id
            };
            addSinglePopupMessage(params);

            input.val('');
            $.ajax({
                type: 'POST',
                url: '<?= base_url("messages/add_message") ?>',
                data: {receiver_id: receiver_id, message: message},
                dataType: 'json',
                success: function (data) {
                    if (data.result == 'ok') {
                    } else {
                        swal(
                            'Error',
                            'Please try again later',
                            'error'
                        );
                    }
                    $('.chat-popup-box .popup-messages').animate({scrollTop: $('.chat-popup-box .popup-messages').prop("scrollHeight")}, 500);
                },
                error: function (data) {
                    swal(
                        'Error',
                        'Please try again later',
                        'error'
                    );
                }
            });
        }
    }

    function openMessengerPopup(user) {
        var popup = $('.chat-popup-box');
        popup.attr('friendId', user.friendId);
        $('.chat-popup-box .popup-head-left span').html(user.fname + ' ' + user.lname);
        $('.chat-popup-box .popup-head-left a').attr('href', user.member_url);
        var messages = $(popup.find('.popup-messages'));
        messages.html('');
        popup.fadeIn();
        $.ajax({
            type: 'POST',
            url: '<?= base_url("messages/get_messages") ?>',
            data: {receiver_id: user.friendId},
            dataType: 'json',
            success: function (data) {
                if (data.result == 'ok') {
                    for (var i in data.message) {
                        var params = data.message[i];
                        params.friendId = user.friendId;
                        addSinglePopupMessage(params);
                    }
                    messages.scrollTop(messages.prop("scrollHeight"));
                } else {
                    swal(
                        'Error',
                        'Please try again later',
                        'error'
                    );
                }
                messages.fadeIn();
            },
            error: function (data) {
                swal(
                    'Error',
                    'Please try again later',
                    'error'
                );
            }
        });
    }
</script>