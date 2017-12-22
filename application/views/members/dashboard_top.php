<?= $this->load->view('public/scripts/ga', null, true) ?>
<?= $this->load->view('public/scripts/socket', null, true) ?>
<?= $this->load->view('public/scripts/sendMessagePopup', null, true) ?>

<div class="yellow-line"></div>
<div class="menu_responsive visible-xs">
    <ul class="m_menu">
		<li>
		
		<a href="<?php echo base_url('dashboard'); ?>"><img src="<?php echo base_url(); ?>public/images/home.png"
                                                                alt="Home" title="Home" class="unhover-image"/></a>
								
		</li>
        
        <li>
            <label class="new-notification-counter mobile-counter" <?= ($notSeenCount->messages < 1 || strpos(current_url(), 'messages')) ? 'style="display: none;"' : '' ?>>
                <?= $notSeenCount->messages ?>
            </label>
            <a href="<?php echo base_url('messages/index'); ?>"><img
                        src="<?php echo base_url(); ?>public/images/message-icon.png" alt="Home" title="Messages"
                        class="unhover-image"/></a>
        </li>
        <li>
            <label class="new-notification-counter mobile-counter" <?= ($notSeenCount->friends < 1) ? 'style="display: none;"' : '' ?>>
                <?= $notSeenCount->friends ?>
            </label>
            <a href="<?php echo base_url('dashboard/friends'); ?>"><img
                        src="<?php echo base_url(); ?>public/images/friends.png" alt="Friends" title="Friends"
                        class="unhover-image"/></a>
        </li>
        <li>
            <label class="new-notification-counter mobile-counter" <?= ($notSeenCount->luv < 1) ? 'style="display: none;"' : '' ?>>
                <?= $notSeenCount->luv ?>
            </label>
            <a href="<?php echo base_url('luv'); ?>"><img src="<?php echo base_url(); ?>public/images/luv.png" alt="Luv"
                                                          title="Luv" class="unhover-image"/></a>
        </li>
		
		<li class="roulette_menu">
		<li><a href="<?php echo base_url('dashboard/myprofile'); ?>">
                                <img src="<?php echo base_url() . 'public/images/thumb/' . (!empty($me->image) ? $me->image : 'no.png'); ?>"
                                     alt="Image" class="profile_menu_image">
                                <?= $me->first_name ?>
                            </a>								
		</li>
			 <!--<label class="new-notification-counter roulette-temporary-label">Game</label>
          <a href="<?= base_url('games/roulette') ?>">Roulette</a> 
          
			-->				
		</li>
		
        <!--        <li class="mobile_roulette"><a href="-->
        <? //= base_url('games/roulette') ?><!--">Roulette</a></li>-->
        <!--<li><a href="<?php //echo base_url('/chatbox'); ?>" class="chatbox-icon">Chatbox</a></li>-->
        <li><a href="#" class="m-toggle"><i class="fa fa fa-bars togglestar" aria-hidden="true"></i></a></li>
    </ul>
</div>
<div class="header-inner-page">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="orriz-logo hidden-xs">
                    <a href="<?php echo base_url('dashboard'); ?>"><img
                                src="<?php echo base_url(); ?>public/images/orriz.png" alt="Home"
                                class="orriz-logo-img"/></a>
                </div>
                <div class="navigations">
                    <ul>

<li class="mobile_version">
                            <!-- <label class="new-notification-counter roulette-temporary-label">Game</label>
                            <a href="<?= base_url('games/roulette') ?>">Roulette</a></li> -->
                            <a href="<?= base_url('polls') ?>">Polls</a></li>





		<li class="profile_nav hidden-xs"><a href="<?php echo base_url('dashboard/myprofile'); ?>">
                                <img src="<?php echo base_url() . 'public/images/thumb/' . (!empty($me->image) ? $me->image : 'no.png'); ?>"
                                     alt="Image" class="profile_menu_image">
                                <?= $me->first_name ?>
                            </a>								
		</li>
                        <li class="iconmenu-icon hidden-xs"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
                        <li class="iconmenu-icon hidden-xs"><a
                                    href="<?php echo base_url('dashboard/wallet'); ?>">Wallet</a></li>
                        <li class="iconmenu-icon hidden-xs">
                            <label class="new-notification-counter" <?= ($notSeenCount->friends < 1) ? 'style="display: none;"' : '' ?>>
                                <?= $notSeenCount->friends ?>
                            </label>
                            <a href="<?php echo base_url('dashboard/friends'); ?>">Friends</a>
                        </li>
                        <li class="hidden-xs">
                            <label class="new-notification-counter" <?= ($notSeenCount->messages < 1 || strpos(current_url(), 'messages')) ? 'style="display: none;"' : '' ?>>
                                <?= $notSeenCount->messages ?>
                            </label>
                            <a id="navigationMessagesButton" href="javascript: void(0)">Message</a>
                            <div id="MessengerFriendsList" class="container">
                                <?php if (isset($messengerList) && count($messengerList)) {
                                    $i = 0;
                                    foreach ($messengerList as $user) {
                                        $i++;
                                        if ($i > 10) {
                                            break;
                                        } ?>
                                        <div class="navigation-messenger-friend send-message-user row"
                                             friendId="<?= $user->id ?>"
                                             fname="<?= $user->first_name ?>" lname="<?= $user->last_name ?>"
                                             member_url="<?= $user->member_url ?>">
                                            <div class="col-sm-3">
                                                <img style="max-width: 40px; max-height: 40px;"
                                                     src="<?= base_url() ?>public/images/thumb/<?= isset($user->image) ? $user->image : "avatar-no-image.png" ?>"/>
                                            </div>
                                            <div class="col-sm-9 friend-name">
                                                <span><?= $user->first_name . ' ' . $user->last_name ?></span>
                                            </div>
                                        </div>
                                    <?php }
                                } else { ?>
                                    <div class="row">
                                        <div class="text-center col-sm-12">
                                            <a href="<?= base_url('dashboard/browsefriends') ?>">Find friends</a>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="text-center col-sm-12">
                                        <a href="<?php echo base_url('messages/index'); ?>">See all messages</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="iconmenu-icon hidden-xs">
                            <label class="new-notification-counter" <?= ($notSeenCount->luv < 1) ? 'style="display: none;"' : '' ?>>
                                <?= $notSeenCount->luv ?>
                            </label>
                            <a id="navigationLuvButton" href="<?= base_url('luv') ?>">Luv</a>
                        </li>
                        <li><a href="<?php echo base_url('images/gallery'); ?>">Photos</a></li>
                        <!--<li class="des-menu"><a href="<?php //echo base_url('/chatbox'); ?>">Chatbox</a></li>-->
                        <li class="iconmenu-icon visible-xs"><a href="<?php echo base_url('dashboard/wallet'); ?>">Wallet</a>
                        </li>
                        <li class="hidden-xs">
                            <!-- <label class="new-notification-counter roulette-temporary-label">Game</label>
                            <a href="<?= base_url('games/roulette') ?>">Roulette</a></li> -->
                            <a href="<?= base_url('polls') ?>">Polls</a></li>
                        <li><a href="<?= base_url('dashboard/browsefriends') ?>">Browse People</a></li>
                        <!--                        <li class="dropdown-submenu">-->
                        <!--                            <div class="dropdown">-->
                        <!--                                <a id="dLabel" role="button" data-toggle="dropdown" data-target="#">Browse <span-->
                        <!--                                            class="caret"></span></a>-->
                        <!--                                <ul class="dropdown-menu btn-block" role="menu" aria-labelledby="dropdownMenu">-->
                        <!--                                    <li class="btn-block"><a style="color:black"-->
                        <!--                                                             href="-->
                        <?php //echo base_url('dashboard/browsefriends'); ?><!--">Browse-->
                        <!--                                            Friends</a></li>-->
                        <!--                                    <li class="btn-block"><a style="color:black"-->
                        <!--                                                             href="-->
                        <?php //echo base_url('dashboard/browse'); ?><!--">Search</a>-->
                        <!--                                    </li>-->
                        <!--                                </ul>-->
                        <!--                            </div>-->
                        <!--                        </li>-->
                    </ul>
                </div>
                <div class="right-menu">
                    <div class="right-menu-button-container">
                        <button class="right-menu-button">
                            <img src="<?php echo base_url(); ?>public/images/setting-icon.png" alt="Settings"/>
                        </button>
                    </div>
                    <div class="pop-over-menu">
                        <ul>
                            <li style="height: 55px;">My Available Points:
                                <div class="text-center" style="font-size: 18px;"><?= $me->points ?></div>
                            </li>
                            <li><a href="<?php echo base_url('members/edit_profile'); ?>">Account Setting</a></li>
                            <li class="status_online">
                                <input type="checkbox" name="status_online" id="status_online">
                                <label for="status_online">My online status</label>
                                <div class="hidden_status">
                                    <input type="radio" class="is_hidden" name="is_hidden"
                                           value="available" <?php if ($me->is_hidden == NULL) echo "checked"; ?>
                                           id="available">
                                    <label for="available">Available</label>
                                    <input type="radio" class="is_hidden" name="is_hidden"
                                           value="invisible" <?php if ($me->is_hidden != NULL) echo "checked"; ?>
                                           id="invisible">
                                    <label for="invisible">Invisible</label>
                                </div>
                            </li>
                            <li><a href="<?php echo base_url('members/logout'); ?>">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<script type="text/javascript">
    window.setInterval(function () {
        iamonline();
    }, 30000);

    function iamonline() {
        $.ajax({
            url: "<?php echo base_url('members/iamonline'); ?>"
        });
    }

    $('#navigationMessagesButton').click(function () {
        var pathArray = window.location.pathname.split('/');
        if (pathArray[1] == 'messages') {
            window.location.reload();
            return;
        }
        var list = $('#MessengerFriendsList');
        if (list.is(':visible')) {
            list.fadeOut();
        } else {
            list.fadeIn();
        }
    });

    $(document).click(function (e) {
        var self = $(e.target);
        if (self.attr('id') != 'navigationMessagesButton') {
            $('#MessengerFriendsList').fadeOut();
        }
    });

    $(".right-menu-button").click(function () {
        $(".pop-over-menu").slideToggle(500);
    });

    $('.is_hidden').click(function () {
        var is_hidden = $("input[type=radio][name=is_hidden]:checked").val();
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>members/iamhidden',
            dataType: 'json',
            data: {is_hidden: is_hidden},
            success: function (data) {
                if (data.result == 'ok') {
                }
            },
            error: function (data) {
            }
        });
        $(".pop-over-menu").slideUp(500);
    });

    $(document).on('click', function (e) {
        e.stopPropagation();
        if (!$(e.target).closest(".right-menu").length) {
            $(".pop-over-menu").slideUp(500);
        }
    });
    $(document).ready(function () {
        $('.send-message-user').click(function (e) {
            e.preventDefault();
            var self = $(e.currentTarget);
            var user = {};
            user.friendId = self.attr('friendId');
            user.fname = self.attr('fname');
            user.lname = self.attr('lname');
            user.member_url = self.attr('member_url');
            if (user.friendId != <?= $me->id ? $me->id : 0 ?>) {
                openMessengerPopup(user);
            }
        });

        $('li a').click(function (e) {
            var self = $(e.currentTarget);
            var notificationCount = self.siblings('.new-notification-counter');
            if (notificationCount.length) {
                notificationCount.hide();
                if (self.attr('id') == "navigationMessagesButton") {
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url("messages/update_notifications_ajax") ?>',
//                        data: {}
                    });
                }
            }
        });

        $('#resend_activation_email').click(function () {
            var self = $(this);
            self.html('');
            $.ajax({
                type: 'POST',
                url: '<?= base_url('members/resend_activation_email') ?>',
                data: {},
                success: function (data) {
                    self.html("Email sent");
                },
                error: function (data) {
                    self.html("Error sendig email");
                }
            });
        });

        $('#resend_activation_email a').click(function (e) {
            e.preventDefault();
        });
    });

    <?php if ($this->config->item('active') === '0') { ?>
    $(document).ready(function () {
        var url = window.location.href;
        if (url.search(/memberdetail/) > 0 || url.search(/aboutyourself/) > 0 || url.search(/uploadimage/) > 0) {
            return false;
        }
        swal({
            title: 'Hello <?= isset($me) ? $me->first_name : '' ?>',
            html: "<h3>Welcome to Orriz!</h3>" +
            "<p>Your account is not verified yet. " +
            "Please check your email and click on the activation link to verify your account.</p>" +
            "<p class='pull-right' id='resend_activation_email'><a href='#'>Resend activation email</a></p>",
            showCancelButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false
        });
    });
    <?php } ?>

    $(document).ready(function () {
        $(".m-toggle").click(function (e) {
            e.preventDefault();
            $(".header-inner-page").slideToggle("slow");
        });

        $(".close_wall").click(function () {
            $("#myModal").removeClass("in");
            $("#myModal").hide();
            $("#luvDetails").hide();
            $("#myModalForAvatar").hide();
            $(".modal-backdrop").remove();
            $("body").removeClass("modal-open");
            $("body").css("padding-right", "0px");
        });

        $("#mobile_ads").click(function (event) {
            event.preventDefault();
            $(".form_t_data").slideToggle();
        });
    });

    <?php if(!isset($_SESSION['timezoneOffset'])) {?>
    var time = new Date();
    var timezoneOffset = -time.getTimezoneOffset();
    $.ajax({
        type: "GET",
        url: "<?=base_url()?>members/set_timezone_offset",
        data: {timezoneOffset: timezoneOffset},
        success: function () {
        }
    });
    location.reload();
    <?php } ?>
</script>