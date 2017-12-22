<!DOCTYPE html>
<html>
<head>
    <title>Orriz - Friends</title>
    <?= $this->load->view('public/headIncludes', null, true) ?>
</head>
<body>
<?= $this->load->view('members/dashboard_top', null, true) ?>
<?= $this->load->view('public/scripts/googleAds', null, true) ?>

<div class="container">
    <div class="row">
        <div class="panel-body">
            <ul class="nav nav-tabs r-friend-tabs" style="border-bottom: 1px solid #ddd;" role="tablist">
                <li class="active"><a href="<?php echo base_url('dashboard/friends'); ?>">Friends</a></li>
                <li>
                    <label class="new-notification-counter" <?= ($notSeenCount->friends < 1) ? 'style="display: none;"' : '' ?>>
                        <?= $notSeenCount->friends ?>
                    </label>
                    <a id="friendRequestButton" href="<?= base_url('dashboard/friendrequests') ?>" style="background: none; color: #428bca">Friend Requests</a>
                </li>
                <li><a href="<?= base_url('dashboard/onlinefriends') ?>" style="background: none; color: #428bca">Online Friends</a></li>
            </ul>
        </div>
        <div class="col-sm-10">
            <hr>
            <?php if (isset($messages)) {
                echo $messages;
            } ?>
            <?php if (isset($friend_list)) { ?>
                <div class="row row-no-padding">
                    <?php foreach ($friend_list as $rows) {
                        $age = isset($rows['birthday']) ? (date('Y') - date('Y', strtotime($rows['birthday']))) : '';
                        $city = isset($rows['city']) ? ' ' . $rows['city'] : '';
                        $country = isset($rows['country']) ? ' ' . $rows['country'] : '';
                        $status = "statusdeactive";
                        if ((time() - strtotime($rows['last_seen'])) < 600 && $rows['is_hidden'] == NULL) {
                            $status = "statusactive";
                        } ?>
                        <div class="col-md-2 col-xs-6">
                            <div class="browse_box">
                                <div class="t-img-wrap">
                                    <div class="<?php echo $status; ?>" title="Online Now"
                                         rel="online">
                                        <img src="<?php echo base_url(); ?>public/images/online_green_button.png">
                                    </div>
                                    <div class="imgthumb img-responsive">
                                        <a href="<?= $rows['member_url'] ?>">
                                            <img src="<?php echo base_url(); ?>public/images/thumb/<?php if (($rows['image']) != null) echo $rows['image']; else echo "avatar-no-image.png"; ?>">
                                        </a>
                                    </div>
                                    <div class="up-text">
                                        <div class="user-name-col">
                                            <p><?php echo $rows['first_name'] . ' ' . $rows['last_name'] ?></p>
                                            <p><?php echo $age; ?> Years</p>
                                            <p><?php echo $city . $country ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="user-icon-row browse_page">
                                    <div class="user-name-icon">
                                        <a class="btn btn-info btn-xs" role="button"
                                           href="<?php if ($rows['status'] == 2) {
                                               echo base_url('dashboard/unfriend') . '?friend_id=' . $rows['id'];
                                           } ?>"><i class="fa fa-user-times" aria-hidden="true"
                                                    title="Unfriend"></i></a>
                                    </div>
                                    <div class="user-name-message">
                                        <a friendid="<?= $rows['id'] ?>"
                                           fname="<?= $rows['first_name'] ?>" lname="<?= $rows['last_name'] ?>"
                                           member_url="<?= $rows['member_url'] ?>" href="javascript:void(0);"
                                           class="msg-system send-message-user"><i class="fa fa-comment-o"
                                                                                   aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div><!--/row-->
        <div class="col-sm-2">
            <div class="sidebar-ads">
                <img src="<?php echo base_url(); ?>public/images/sidebar-ad.jpg" alt="Sidebar Ad"/>
            </div>
        </div>
    </div>
</div>
<?= $this->load->view('public/footer', null, true) ?>
<div id="overlay-back"></div>
<script>
    window.onload;
    {
        var a = 0;
        a =<?php if (!empty($message)) {
            echo 1;
        } else echo 0 ?>;

        if (a === 1) {
            $('#overlay-back').fadeIn(500, function () {
                $('#error').show();
            });
            $("#error_btn").on('click', function () {
                $('#error').hide();
                $('#overlay-back').fadeOut(500);
            });

        } else {
            $('#error').hide();
        }
    }
</script>
</body>
</html>