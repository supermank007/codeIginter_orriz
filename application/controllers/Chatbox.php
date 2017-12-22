<!DOCTYPE html>
<html>
<head>
    <title>Orriz - Chatbox</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php

    class Chatbox extends Members_Controller {

    public function __construct()
    {
        parent::__construct();
        if ($this->ion_auth->logged_in() == false) {
            redirect(base_url());
        }
          $this->load->model('member_model');
        // $this->load->model('photos_model');
        date_default_timezone_set('UTC');
    }

    public function index ()
    {
    $friend_list=$this->member_model->friend_list($this->session->userdata('user_id'));
    ?>
    <?= $this->load->view('public/headIncludes', null, true) ?>
    <link rel="stylesheet" href="http://www.orriz.com/public/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="http://www.orriz.com/public/css/screen.css"/>
    <link rel="stylesheet" href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css"/>
    <link rel="stylesheet" href="http://www.orriz.com/public/css/style.css"/>
    <script src="http://www.orriz.com/public/js/jquery-1.11.3.min.js"></script>
    <script src="http://www.orriz.com/public/js/bootstrap.min.js"></script>
    <script src="http://www.orriz.com/public/js/sweetalert2.min.js"></script>
    <link type="text/css" href="http://www.orriz.com/public/css/sweetalert2.min.css" rel="stylesheet"/>
</head>
<body class="chatbox_page">
<?php echo $this->load->view('members/dashboard_top', ['friend_list'=>$friend_list], true); ?>
<?= $this->load->view('public/footer', null, true) ?>

<script type="text/javascript">
    window.setInterval(function () {
        iamonline();
    }, 30000);
    function iamonline() {
        $.ajax({
            url: "<?php echo base_url('members/iamonline'); ?>"
        });
    }

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

<script>
    $(document).ready(function () {
        $("#success-alert").slideUp(500, function () {
            $("#success-alert").slideUp(500);
        });
    });
</script>


<div class="alert alert-success" id="success-alert" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>Success! </strong>
    You have reported the user succesfully!
</div>
<?php


$prenume = $this->me->first_name;
$nume = $this->me->last_name;
$iduser = $this->session->userdata('user_id');
//   echo  $_SESSION['last_namee'];
//  echo  $_SESSION['first_namee'];

//  echo $_SESSION['usernamea'];
echo '  <style type="text/css">
            body, html
            {
                margin: 0; padding: 0; height: 100%; overflow: hidden;
            }

            #content
            {
                position:absolute; left: 0; right: 0; bottom: 0; top: 0px;width:86%; 
            }
        </style>';
//echo '<iframe class="responsive-chat" src="http://www.orriz.com/testingF?nume='.$nume.'&prenume='.$prenume.'&execTime='.$iduser.'" height="100%" width="62%" style="    margin-left: 19%;" frameborder="0"></iframe>';

}
}