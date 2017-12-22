<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Orriz- Roulette</title>
    <?= $this->load->view('public/headIncludes', null, true) ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/roulette.css"/>
</head>
<body class="roulette-page">
<?= $this->load->view('members/dashboard_top', null, true) ?>
<?= $this->load->view('public/scripts/googleAds', null, true) ?>
<div class="roulettemain">
    <div class="roulette container">
        <div class="col-sm-3">

            <div class="instructions hidden-xs">
                <h3>Instructions</h3>
                <div class="instructions-content">
                    <p>Step 1: Place a bid.</p>
                    <p>Step 2: Select your colour.</p>
                    <p>Step 3: Click SPIN.</p>
                    <p class="spec-ins">(You can also convert your WALLET POINTS into CASH and your Cash into POINTS)
                </div>
            </div>
            <div class="instructions-mobile visible-xs">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="instructions-mob-in">
                            <h3 class="ins-m-heading" id="ins-toggle">Instructions</h3>
                            <div class="ins-m-dex">
                                <p>Step 1: Place a bid.</p>
                                <p>Step 2: Select your colour.</p>
                                <p>Step 3: Click SPIN.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="instructions-mob-in">
                            <h3 class="ins-m-heading" id="ins-toggle">Top Rated Players</h3>
                            <div class="ins-m-dex" style="height: 200px; overflow-y: auto;">
                                <ul>
                                    <?php foreach ($topRated as $key => $user) {
//                                        $percent;
//                                        if ($user->wins == 0) {
//                                            $percent = 0;
//                                        } else {
//                                            $percent = round($user->wins / ($user->wins + $user->losts) * 100);
//                                        }
                                        ?>
                                        <li>
                                            #<?= ($key + 1) . ' ' . $user->first_name . ' ' . $user->last_name ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="place-bid hidden-xs">
                <div class="place-bid-input">
                    <input type="text" pattern="\d*" maxlength="6" class="form-control"
                           value="<?= ($money <= 100) ? $money : 100 ?>">
                    <button class="place-bid-btn"><img src="../public/images/round-image.png"/>Bid</button>
                </div>
            </div>
            <div class="select-color-row hidden-xs">
                <div class="select-color">
                    Select Your Color
                    <div class="select-btns">
                        <button class="RedCircle"></button>
                        <button class="BlackCircle"></button>
                    </div>
                </div>
            </div>
            <div class="points-counting-2 hidden-xs">
                Your Available Cash <span
                        class="totalmoney points-number-counting-2">$ <span><?= $money ?></span></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="referral-content-container">
                <div class="referral-number-2">
                    <div class="rte_spinner">
                        <div class="rte_ball"><span></span></div>
                        <div class="platebg"></div>
                        <div class="platetop"></div>
                        <div id="toppart" class="topnodebox">
                            <div class="silvernode"></div>
                            <div class="topnode silverbg"></div>
                            <span class="top silverbg"></span>
                            <span class="right-roulette silverbg"></span>
                            <span class="down silverbg"></span>
                            <span class="left-roulette silverbg"></span>
                        </div>
                        <div id="rcircle" class="pieContainer">
                            <div class="pieBackground"></div>
                        </div>
                    </div>

                </div>
            </div>
            <div id="currentBitAmount" class="text-center">
                Your bid: $<span><?= ($money <= 100) ? $money : 100 ?></span>
            </div>
        </div>
        <div class="place-bid-mobile visible-xs">
            <div class="place-bid-input">
                <input type="text" pattern="\d*" maxlength="6" class="form-control"
                       value="<?= ($money <= 100) ? $money : 100 ?>">
                <button class="place-bid-btn"><img src="../public/images/round-image.png"/>Bid</button>
            </div>
        </div>
        <div class="mobile-col-select visible-xs">
            <div class="col-xs-6">
                <div class="select-color-row">
                    <div class="select-color">
                        Select Your Color
                        <div class="select-btns">
                            <button class="RedCircle"></button>
                            <button class="BlackCircle"></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="points-counting-2">
                    Your Available <br>Cash <span
                            class="totalmoney points-number-counting-2">$ <span><?= $money ?></span></span>
                </div>
            </div>
        </div>
        <div class="col-sm-3 hidden-xs">
            <div class="points-game-container">
                <div>
                    <button class="button points-button-2 btnBuy">Convert Points into Cash</button>
                </div>
                <div>
                    <button class="button points-button-2 btnSell">Convert Cash into Points</button>
                </div>
            </div>
            <div class="top-rated">
                <h3 class="top-rated-title">Top Rated Players</h3>
                <div class="top-rated-list">
                    <ul>
                        <?php foreach ($topRated as $key => $user) {
//                            $percent;
//                            if ($user->wins == 0) {
//                                $percent = 0;
//                            } else {
//                                $percent = round($user->wins / ($user->wins + $user->losts) * 100);
//                            }
                            ?>
                            <li>
                                #<?= ($key + 1) . ' ' . $user->first_name . ' ' . $user->last_name ?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="spin-row">
        <div class="container">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <div class="spin-btn-container">
                    <button id="btnSpin" class="button spin-btn">Spin</button>
                </div>
            </div>
        </div>
    </div>
    <div class="visible-xs">
        <div class="points-game-container">
            <button class="button points-button-2 btnBuy">Convert Points into Cash</button>
            <button class="button points-button-2 btnSell">Convert Cash into Points</button>
        </div>
    </div>

</div>
<!--<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>-->
<script src="<?= base_url('public/js/jquery.keyframes.min.js') ?>"></script>
<script src='<?= base_url('public/js/roulette.js') ?>'></script>

<?= $this->load->view('public/footer', null, true) ?>
<script>
    $(function () {
        $(".ins-m-heading").click(function () {
            $(this).parent().find(".ins-m-dex").slideToggle();
        });
    });
</script>
<div id="totalPoints" style="display: none;"><?= $me->points ?></div>
</body>
</html>
