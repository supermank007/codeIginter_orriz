<!DOCTYPE html>
<html>
<head>
    <title>Orriz - Friend Requests</title>
    <?= $this->load->view('public/headIncludes', null, true) ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/browse.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/friends.css"/>
</head>
<body>
<?= $this->load->view('members/dashboard_top', null, true) ?>
<?= $this->load->view('public/scripts/googleAds', null, true) ?>

<div class="container">
    <div class="row">
        <div class="panel-body">
            <ul class="nav nav-tabs r-friend-tabs" style="border-bottom: 1px solid #ddd;" role="tablist">
                <li><a href="<?php echo base_url('dashboard/friends'); ?>" style="background: none; color: #428bca">Friends</a></li>
                <li class="active"><a href="<?php echo base_url('dashboard/friendrequests'); ?>">Friend Requests</a>
                </li>
                <li><a href="<?php echo base_url('dashboard/onlinefriends'); ?>" style="background: none; color: #428bca">Online Friends</a></li>
                <!--                <li><a href="-->
                <?php //echo base_url('dashboard/invitefriends'); ?><!--">Invite Friends</a></li>-->
            </ul>
        </div>
        <div class="col-sm-10">
            <hr>
            <?php if (isset($messages1)) {
                echo $messages1;
            } ?>
            <?php if (isset($friend_requests)) { ?>
                <div class="row row-no-padding">
                    <?php foreach ($friend_requests as $rows) {
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
                                <div class="user-icon-row">
                                    <div class="user-name-icon">
                                        <a class="btn btn-info btn-xs" role="button"
                                           href="<?php if ($rows['status'] == 1) {
                                               echo base_url('dashboard/requestaccept') . '?friend_id=' . $rows['id'];
                                           } ?>"><i class="fa fa-user-plus" aria-hidden="true" title="Accept"></i></a>
                                    </div>

                                    <div class="user-name-icon">
                                        <a class="btn btn-info btn-xs" role="button"
                                           href="<?php if ($rows['status'] == 1) {
                                               echo base_url('dashboard/requestdelete') . '?friend_id=' . $rows['id'];
                                           } ?>"><i class="fa fa-user-times" aria-hidden="true" title="Delete"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div><!--/row-->
        <!--            <div class="col-md-2">-->
        <!--                <div class="productbox">-->
        <!--                    <div class="imgthumb img-responsive">-->
        <!--                        <img src="http://lorempixel.com/250/250/business/?ac=22">-->
        <!--                    </div>-->
        <!--                    <div >-->
        <!--                        <h5>Lorem ipsum dolor</h5>-->
        <!--                        <a href="#" class="btn btn-default btn-xs pull-right" role="button"><i class="glyphicon glyphicon-edit"></i></a> <a href="#" class="btn btn-info btn-xs" role="button">Button</a>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!---->
        <!--            <div class="col-md-2">-->
        <!--                <div class="productbox">-->
        <!--                    <div class="imgthumb img-responsive">-->
        <!--                        <img src="http://lorempixel.com/250/250/business/?af=2">-->
        <!--                    </div>-->
        <!--                    <div >-->
        <!--                        <h5>Lorem ipsum dolor sit amet, consectetur adipisicing elit</h5>-->
        <!--                        <a href="#" class="btn btn-default btn-xs pull-right" role="button"><i class="glyphicon glyphicon-edit"></i></a> <a href="#" class="btn btn-info btn-xs" role="button">Button</a>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!---->
        <!--            <div class="col-md-2">-->
        <!--                <div class="productbox">-->
        <!--                    <div class="imgthumb img-responsive">-->
        <!--                        <img src="http://lorempixel.com/250/250/business/?a=4">-->
        <!--                    </div>-->
        <!--                    <div >-->
        <!--                        <h5>Lorem ipsum dolor sit amet, consectetur adipisicing elit Lorem ipsum dolor sit amet, consectetur adipisicing elit</h5>-->
        <!--                        <a href="#" class="btn btn-default btn-xs pull-right" role="button"><i class="glyphicon glyphicon-edit"></i></a> <a href="#" class="btn btn-info btn-xs" role="button">Button</a>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!---->
        <!--            <div class="col-md-2">-->
        <!--                <div class="productbox">-->
        <!--                    <div class="imgthumb img-responsive">-->
        <!--                        <img src="http://lorempixel.com/250/250/business/?ag=5">-->
        <!--                    </div>-->
        <!--                    <div >-->
        <!--                        <h5>Thumbnail label</h5>-->
        <!--                        <a href="#" class="btn btn-default btn-xs pull-right" role="button"><i class="glyphicon glyphicon-edit"></i></a> <a href="#" class="btn btn-info btn-xs" role="button">Button</a>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!---->
        <!--            <div class="col-md-2">-->
        <!--                <div class="productbox">-->
        <!--                    <div class="imgthumb img-responsive">-->
        <!--                        <img src="http://lorempixel.com/250/250/business/?a=6">-->
        <!--                    </div>-->
        <!--                    <div>-->
        <!--                        <h5>Lorem ipsum dolor sit amet, consectetur adipisicing elit</h5>-->
        <!--                        <div class="btn-group">-->
        <!--                            <button type="button" class="btn btn-info btn-xs" role="button">Left</button>-->
        <!---->
        <!--                        </div>-->
        <!--                        <a href="#" class="btn btn-default btn-xs pull-right" role="button"><i class="glyphicon glyphicon-edit"></i></a>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!---->
        <!--            <div class="col-md-2">-->
        <!--                <div class="productbox">-->
        <!--                    <div class="imgthumb img-responsive">-->
        <!--                        <img src="http://lorempixel.com/250/250/business/?a=7">-->
        <!--                    </div>-->
        <!--                    <div>-->
        <!--                        <h5>Thumbnail label</h5>-->
        <!--                        <s class="text-muted">$2.29</s> <b class="finalprice">$1.2</b> from <b>Amazon</b>-->
        <!--                        <a href="#" class="btn btn-default btn-xs pull-right" role="button"><i class="glyphicon glyphicon-zoom-in"></i></a>-->
        <!--                        <p>-->
        <!--                            <button type="button" class="btn btn-success btn-md btn-block">Get Offer</button>-->
        <!--                        </p>-->
        <!--                    </div>-->
        <!--                    <div class="saleoffrate">-->
        <!--                        <b>90%</b><br>OFF-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
    </div><!--/row-->


    <div class="col-sm-2">
        <div class="sidebar-ads">
            <img src="<?php echo base_url(); ?>public/images/sidebar-ad.jpg" alt="Sidebar Ad"/>
        </div>
    </div>
</div>
</div>

<br>
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