<!doctype html>
<html style="min-width: 320px; height: 100%;">
<head>
    <head>
        <title>Orriz - Messages</title>
        <?= $this->load->view('public/headIncludes', null, true) ?>
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/error.css"/>
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/post.css"/>
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/messenger.css?v=2"/>
    </head>
<body style="height: 95%">
<?= $this->load->view('members/dashboard_top', null, true) ?>
<?= $this->load->view('public/scripts/googleAds', null, true) ?>
<?= $this->load->view('public/scripts/luv', null, true) ?>
<div class="container" style="height: 97%;">
    <div class="row" style="height: 97%;">
		<div class="chat-sidebar active col-sm-3" style="height: 97%; padding: 15px;">
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
			<div class="n-section visible-xs">
				<i class="fa fa-long-arrow-left message_toggle" aria-hidden="true"></i>
				<span class="u-name">Enter the name here</span>
            </div>
			<div class="row" style="height: 75%;">
                <div class="col-sm-12">
                    <div class="profile-top_row hidden-xs">
                        <div class="profile_left_part hide">
                            <div class="profile_name" id="chat_user_name">
                                <i class="fa fa-circle user_online" aria-hidden="true"></i>
                                <span></span>
                                <a></a>
                            </div>
                            <div class="profile_status" id="chat_user_online_status">
                                Online Now!
                            </div>
                        </div>
                        <div class="profile_right_part hide">
                            <div userId="" class="heart heart-top" title="Give Luv" id="chat_user_luv">
                                <img src="<?= base_url() ?>public/images/heart.png" style="max-width: 100%;">
                                <div class=""
                                     style="position: absolute; color: #00CC00; top: 10px; width: 70px;left: -20px;">
                                    Luv given!
                                </div>
                            </div>
                            <a href="" class="view_profile" id="chat_profile_url">View Profile</a>
                            <a href="" class="view_profile" id="addFriend">Add friend</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" id="messages"></div>
            </div>
            <div class="sendmessage row">
                <div class="col-sm-11 col-xs-9">
                    <input type="text" id="messageInput" class="form-control" placeholder="Send message"
                           data-emojiable="true">
                </div>
                <div class="col-sm-1 col-xs-3">
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
                    if(data.user) {
                        var $userName = $('#chat_user_name');
                        $('#chat_user_luv').attr('userId', userId).parent().removeClass('hide');
                        $('#chat_user_luv').children('div').attr('class', 'luv-give' + userId + ' success');
                        
                        if(data.user.isOnline) {
                            $('#chat_user_online_status').text('Online now!').addClass('profile_status');
                            $userName.children('i').show();
                        } else {
                            $('#chat_user_online_status').text('Last seen ' + data.user.last_seen).removeClass('profile_status');
                            $userName.children('i').hide();
                        }
                        
                        $userName.children('span').hide().next()
                                .text(data.user.first_name + ' ' + data.user.last_name)
                                .attr('href', data.user.profile_url)
                                .show();
                        if(data.user.friendStatus == 2) {
                            $('#chat_profile_url').attr('href', data.user.profile_url).show();
                            $('#addFriend').hide();
                        } else {
//                            $userName.children('span').show().text(data.user.first_name + ' ' + data.user.last_name)
//                                .next().hide();
                            $('#chat_profile_url').hide();
                            $('#addFriend').show();
                            if(data.user.friendStatus == 1) {
                                $('#addFriend').attr('disabled', 'disabled').text('Friend request sent');
                            } else {
                                $('#addFriend').removeAttr('disabled').text('Add friend').attr('user_id', userId);
                            }
                        }
                        $userName.parent().removeClass('hide');
                    }
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
    
    function getFZ(i) {
        return i < 10 ? '0' + i : i;
    }

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

    $(window).on('load', function () {
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
    
    $('#addFriend').click(function(e){
        e.preventDefault();
        var t = $(this);
        if(t.attr('disabled')) {
            return false;
        }
        var friend_id = t.attr('user_id');
        $.ajax({
            url: "<?= base_url('dashboard/sentrequest') ?>",
            cache: false,
            data: {
                friend_id: friend_id,
            },
            type: 'POST',
            success: function (data) {
                t.html('Friend request sent');
            }
        });
        t.html('Friend request sent').attr('disabled', 'disabled');
    });

</script>
<script>
    $(document).ready(function () {
        $(".selected-friend").click(function () {
            $(".selected-friend").removeClass("active");
            $(this).addClass("active");
        });
    });
</script>
<script>
	$(function(){
		$(".n-section").click(function(){
			$(".chat-sidebar").addClass("active");
			$(".message-chat").removeClass("active");
		});
		$(".sidebar-name").click(function(){
			$(".chat-sidebar").removeClass("active");
				$(".message-chat").addClass("active");	
				var currentname  = $(this).find("span").text();
			$(".u-name").html(currentname);
		});
	});
	
	setTimeout(function(){
		$(".chat-sidebar").addClass("active");
		$(".message-chat").removeClass("active");
	}, 2000);	

</script>
<div id="myLuvInformation" points="<?= $me->points ?>" freeLuv="<?= $me->free_luv ?>" style="display: none;"></div>
<?php /* = $this->load->view('public/footer', null, true) */ ?>
</body>
</html>