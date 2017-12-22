<!doctype html>
<html style="min-width: 320px; height: 100%;">
<head>
    <head>
        <title>Orriz - Messages</title>
        <?= $this->load->view('public/headIncludes', null, true) ?>
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/error.css"/>
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/post.css"/>
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/messenger.css"/>
	</head>
<body style="height: 95%">
<?= $this->load->view('members/dashboard_top', null, true) ?>
<?= $this->load->view('public/scripts/googleAds', null, true) ?>
<div class="container" style="height: 97%;">
    <div class="row" style="height: 97%;">
		<div class="visible-xs">
			<i class="fa fa-twitch message_toggle" aria-hidden="true"></i>
		</div>
		
        <div class="chat-sidebar col-sm-3" style="height: 97%; padding: 15px;">
            <?php if (isset($messengerListWithFriends)) { ?>
                <?php foreach ($messengerListWithFriends as $user) { ?>
                    <div class="sidebar-name" userId="<?= $user->id ?>">
                        <a href="javascript:void(0);">
                            <img width="30" height="30"
                                 src="<?= base_url(); ?>public/images/thumb/<?php if (($user->image) != null) echo $user->image; else echo "avatar-no-image.png"; ?>"/>
                            <span><?= $user->first_name . ' ' . $user->last_name ?></span>
                        </a>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
        <div class="col-sm-9 message-chat" style="height: 97%;">
            <div class="row" style="height: 96%;">
				<div class="col-sm-12">
					<div class="profile-top_row hidden-xs">
						<div class="profile_left_part">
							<div class="profile_name">
								<i class="fa fa-circle user_online" aria-hidden="true"></i> Pavlo Sukhorukov
							</div>
							<div class="profile_status">
								Online Now!
							</div>
						</div>
						<div class="profile_right_part"><span class="heart-text-wrapper"><br />
							<img class="heart-icon-space" src="<?php echo base_url(); ?>public/images/heart2.png"/> </span><a href="#" class="view_profile">View Profile</a>
						</div>
					</div>
				</div>
                <div class="col-sm-12" id="messages"></div>
            </div>
            <div class="sendmessage row">
                <div class="col-sm-11">
                    <input type="text" id="messageInput" class="form-control" placeholder="Send message"
                           data-emojiable="true">
                </div>
                <div class="col-sm-1">
                    <input type="submit" id="msgsubmit" userId="" class="btn btn-default btn-message"
                           style="margin-left: -10px;"
                           value="Send" disabled="true">
                </div>
            </div>

        </div>
    </div>
</div>
</div>

<div class="row message-row" id="messageTemplate" style="display: none;">
    <div class="col-sm-12">
        <div class="single-message-wrap">
            <span class="single-message"></span>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.sidebar-name').click(function (e) {
        var userId = $(this).attr('userId');
        $('.selected-friend').removeClass('selected-friend');
        $(this).addClass('selected-friend').removeClass('new-message-received');
        $("#msgsubmit").attr('userId', userId);
        $('#messages').fadeTo(0);
        $('#messages').html('');
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>messages/get_messages',
            data: {receiver_id: userId},
            dataType: 'json',
            success: function (data) {
                if (data.result == 'ok') {
                    for (var i in data.message) {
                        var params = data.message[i];
                        params.userId = userId;
                        addSingleMessage(params);
                    }
                    $('#messages').scrollTop($('#messages').prop("scrollHeight"));
                } else {
                    swal(
                        'Error',
                        'Please try again later',
                        'error'
                    );
                }
                $('#messages').fadeIn();
            },
            error: function (data) {
                $('#messages').fadeIn();
                swal(
                    'Error',
                    'Please try again later',
                    'error'
                );
            }
        });
    });

    function addSingleMessage(data) {
        var messagesContainer = $('#messages');
        var template = $('#messageTemplate').clone().removeAttr('style').removeAttr('id');
        var templateMessage = $(template.find('.single-message-wrap'));
        var message = emojione.toImage(data.message);
        if (data.receiver_id == data.userId) {
            templateMessage.addClass('from-me pull-right');
        }
        else {
            templateMessage.addClass('to-me pull-left');
        }
        $(templateMessage.find('.single-message')).html(message);
        messagesContainer.append(template);
    }

    //    $('#messageInput').on('keyup change', function () {
    //        if ($(this).val().trim().length) {
    //            changeSubmitPostAtt(false);
    //        } else {
    //            changeSubmitPostAtt(true);
    //        }
    //    });

    //    $('#messageInput').on('keydown', function (e) {
    //        if (e.which == 13) {
    //            e.preventDefault();
    //            sendMessage();
    //        }
    //    });

    function changeSubmitPostAtt(attValue) {
        $('#msgsubmit').attr('disabled', attValue);
    }

    $('#msgsubmit').click(function (e) {
        var event = jQuery.Event("keydown");
        event.which = 13;
        $(".emojionearea-editor").trigger(event);
    });

    function sendMessage(message) {
        var receiver_id = $('#msgsubmit').attr('userId');
        message = emojione.toShort(message);
        if (message && receiver_id) {
            var params = {
                userId: receiver_id,
                message: message,
                receiver_id: receiver_id
            };
            addSingleMessage(params);
            $('#messages').animate({scrollTop: $('#messages').prop("scrollHeight")}, 1000);

            $('#messageInput').val('');
            $('#msgsubmit').attr('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url(); ?>messages/add_message',
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

    function setCursorToEnd(el) {

        var range = document.createRange();
        var sel = window.getSelection();
        range.setStart(el, 1);
        range.collapse(true);
        sel.removeAllRanges();
        sel.addRange(range);
        el.focus();
    }

    $(window).load(function () {
        $('.chat-sidebar').children().first().trigger('click');
        $(".emojionearea-editor").on('input', function () {
            setCursorToEnd($(this).get(0));
        });
    });

    $(document).ready(function () {
        var em = $('#messageInput').emojioneArea({
            pickerPosition: "top",
            tonesStyle: "bullet",
            inline: true,
            shortnames: true,
            autoHideFilters: true
        });

        em[0].emojioneArea.on("keydown", function (el, ev) {
            if (em[0].emojioneArea.getText().trim().length) {
                changeSubmitPostAtt(false);
            } else {
                changeSubmitPostAtt(true);
            }
            $('.emojionearea-picker').addClass('hidden');
            $('.emojionearea-button').removeClass('active');
            if (ev.which == 13) {
                ev.preventDefault();
                var message = em[0].emojioneArea.getText().trim();
                sendMessage(message);
                el.html('');
            }
        });
    });
</script>
<script>
$(document).ready(function() {
    $(".selected-friend").click(function () {
        $(".selected-friend").removeClass("active");
        $(this).addClass("active");     
    });
});
</script>

<?php /* = $this->load->view('public/footer', null, true) */?>
</body>
</html>