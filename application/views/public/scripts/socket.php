<script>
    var basepath = '<?= base_url() ?>';

    var socket = io.connect('<?= rtrim(base_url(), '/') ?>:7682');//rtrim is used to delete trailing slash
    socket.on("newMessage", function (data) {
//        console.log(data);
        var pathArray = window.location.pathname.split('/');
        if (pathArray[1] == 'messages') {
            $('.chat-sidebar .sidebar-name').each(function () {
                if ($(this).attr('userId') == data.senderId) {
                    if ($(this).hasClass('selected-friend')) {
                        var params = {};
                        params.message = data.message;
                        params.friendId = null;
                        params.receiver_id = <?= isset($me->id) ? $me->id : -1 ?>;
                        addSingleMessage(params);
                        $("#messages").animate({scrollTop: $('#messages').prop("scrollHeight")}, 1000);
                    } else {
                        $(this).addClass('new-message-received').prependTo('.chat-sidebar');
                    }
                    return false;
                }
            });
        } else {
            var friendId = $('.chat-popup-box').attr('friendId');
            if ($('.chat-popup-box').is(':visible') && friendId == data.senderId) {
                var params = {};
                params.message = data.message;
                params.friendId = null;
                params.receiver_id = <?= isset($me->id) ? $me->id : -1 ?>;
                addSinglePopupMessage(params);
                var messagesArea = $('.chat-popup-box .popup-messages');
                messagesArea.animate({scrollTop: messagesArea.prop("scrollHeight")}, 1000);
            }
        }
//        socket.emit('my other event', {my: 'data'});
    });
</script>