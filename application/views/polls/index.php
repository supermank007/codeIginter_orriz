<!DOCTYPE html>
<html>
<head>
    <title>Orriz - Polls</title>
    <?= $this->load->view('public/headIncludes', null, true) ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/friends.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/polls.css"/>
</head>
<body>
<?= $this->load->view('members/dashboard_top', null, true) ?>
<div class="container">
    <h2>Polls</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="posts-ads-container">
                <ul class="nav nav-tabs polls_tabs" style="width: 96%">
                    <li class="active tab-all">
                        <a class="one-tap" data-toggle="tab" href="#tab_new">New Polls</a>
                    </li>
                    <li>
                        <a class="one-tap" data-toggle="tab" href="#tab_voted">Voted Polls</a>
                    </li>
                    <li>
                        <a class="one-tap" data-toggle="tab" href="#tab_mine">My Polls</a>
                    </li>
                    <li>
                        <a class="one-tap" data-toggle="tab" href="#tab_create">Create Poll</a>
                    </li>
                </ul>

                <!-- main content -->
                <div class="tab-content">

                    <!-- tab all -->
                    <div id="tab_new" class="tab-pane fade in active">
                        <table class="table table-striped">
                            <colgroup>
                                <col width="30px"/>
                                <col width="auto"/>
                                <col width="20%"/>
                            </colgroup>
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Poll Question</th>
                                <th>Created</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php foreach ($new_polls as $index => $poll) : ?>
                                <?php if (!$poll['submitted']) : ?>
                                    <tr>
                                        <td><?php echo $index + 1 ?></td>
                                        <td>
                                            <p class="question"><?php echo $poll['question'] ?></p>
                                            <form method="post" action="<?php echo site_url('polls/create_answer') ?>">
                                                <input type="hidden" name="poll_id" value="<?php echo $poll['id'] ?>"/>
                                                <?php foreach ($poll['options'] as $option): ?>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="answer"
                                                                   value="<?php echo $option['id'] ?>">
                                                            <?php echo $option['text'] ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach ?>
                                            </form>
                                        </td>
                                        <td>
                                            <div>
                                                <div><?php echo $poll['created_at'] ?></div>
                                                <h4>Total votes: <?php echo $poll['votesCount'] ?></h4>
                                            </div>
                                            <p class="text-left">
                                                <a href class="btn btn-success btn-score-poll">Submit</a>
                                            </p>
                                        </td>
                                    </tr>
                                <?php endif ?>
                            <?php endforeach ?>
                            </tbody>

                        </table>
                    </div>
                    <!-- end/tab all -->

                    <!-- tab Voted polls -->
                    <div id="tab_voted" class="tab-pane fade">
                        <table class="table table-striped">
                            <colgroup>
                                <col width="30px"/>
                                <col width="auto"/>
                                <col width="20%"/>
                            </colgroup>
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Poll Question</th>
                                <th>Created</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php foreach ($voted_polls as $index => $poll) : ?>
                                <tr>
                                    <td><?php echo $index + 1 ?></td>
                                    <td>
                                        <p class="question"><?php echo $poll['question'] ?></p>
                                        <?php if ($poll['submitted']) : ?>
                                            <?php foreach ($poll['options'] as $option): ?>
                                                <div class="row option-list">
                                                    <div class="col-sm-2 text-right text-danger">
                                                        <?php echo $option['text'] ?>
                                                    </div>
                                                    <div class="cols-sm-10" style="padding: 0px 10px;">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-danger"
                                                                 role="progressbar"
                                                                 aria-valuenow="60"
                                                                 aria-valuemin="0"
                                                                 aria-valuemax="100"
                                                                 style="width: <?php echo $option['score'] ?>%;">
                                                                <?php echo $option['score'] ?>%
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach ?>
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <div>
                                            <div><?php echo $poll['created_at'] ?></div>
                                            <h4>Total votes: <?php echo $poll['votesCount'] ?></h4>
                                        </div>
                                        <!--                                        --><?php //if (!$poll['submitted']) : ?>
                                        <!--                                            <p class="text-left">-->
                                        <!--                                                <a href class="btn btn-success btn-score-poll">Submit</a>-->
                                        <!--                                            </p>-->
                                        <!--                                        --><?php //endif ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>

                        </table>
                    </div>
                    <!-- end/tab voted polls -->

                    <!-- tab mine -->
                    <div id="tab_mine" class="tab-pane fade">
                        <table class="table table-striped">
                            <colgroup>
                                <col width="30px"/>
                                <col width="auto"/>
                                <col width="20%"/>
                            </colgroup>
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Poll Question</th>
                                <th>Created</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($my_polls as $index => $poll) : ?>
                                <tr>
                                    <td><?php echo $index + 1 ?></td>
                                    <td>
                                        <p class="question"><?php echo $poll['question'] ?></p>

                                        <?php if ($poll['submitted']) : ?>

                                            <?php foreach ($poll['options'] as $option): ?>
                                                <div class="row option-list">
                                                    <div class="col-sm-2 text-right text-danger">
                                                        <?php echo $option['text'] ?>
                                                    </div>
                                                    <div class="cols-sm-10" style="padding: 0px 10px;">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-danger"
                                                                 role="progressbar"
                                                                 aria-valuenow="60"
                                                                 aria-valuemin="0"
                                                                 aria-valuemax="100"
                                                                 style="width: <?php echo $option['score'] ?>%;">
                                                                <?php echo $option['score'] ?>%
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach ?>

                                        <?php else : ?>
                                            <form method="post" action="<?php echo site_url('polls/create_answer') ?>">
                                                <input type="hidden" name="poll_id" value="<?php echo $poll['id'] ?>"/>
                                                <?php foreach ($poll['options'] as $option): ?>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="answer"
                                                                   value="<?php echo $option['id'] ?>">
                                                            <?php echo $option['text'] ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach ?>
                                            </form>
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <div>
                                            <div><?php echo $poll['created_at'] ?></div>
                                            <?php if ($poll['submitted']) : ?>
                                                <h4>Total votes: <?php echo $poll['votesCount'] ?></h4>
                                            <?php endif ?>
                                        </div>
                                        <?php if (!$poll['submitted']) : ?>
                                            <p class="text-left">
                                                <a href class="btn btn-success btn-score-poll">Submit</a>
                                            </p>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- end/tab mine -->

                    <!-- tabe create -->
                    <div id="tab_create" class="tab-pane fade">
                        <form id="form_create_poll" method="post" action="<?php echo site_url('polls/create') ?>"
                              class="form-horizontal">

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Question:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="question" placeholder="Question">
                                </div>
                            </div>

                            <div class="form-group poll-option">
                                <label class="col-sm-2 control-label">Option_1:</label>
                                <div class="col-sm-8 col-option">
                                    <input type="text" class="form-control" name="options[]" placeholder="">
                                </div>
                                <div class="col-sm-2 col-button">
                                    <a href class="btn btn-default btn-small btn-add-option">+</a>
                                    <a href class="btn btn-default btn-small btn-delete-option">-</a>
                                </div>
                            </div>

                            <div class="form-group poll-create-row">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="button" onclick="createPoll()" class="btn btn-primary"
                                            style="margin-right: 20px;">Post Poll
                                    </button>&nbsp;
                                    <a class="btn btn-cancel btn-default">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- end/tabe create -->
                </div>
                <!-- end/main content -->
            </div>
        </div>
    </div>
</div>
<?= $this->load->view('public/footer', null, true) ?>
<script>
    $('.tab-content').on('click', '.btn-score-poll', submitScoreForm);
    $('#form_create_poll').on('click', '.btn-add-option', addPollOption);
    $('#form_create_poll').on('click', '.btn-delete-option', deletePollOption);
    $('.btn-cancel').click(function () {
        $('a[href="#tab_all"]').trigger('click');
        return false;
    })

    function submitScoreForm() {
        var form = $(this).parent().parent().prev().find('form');
        if (!form.find(':checked').length) {
            swal('Error', 'Please select an answer', 'error');
            return false;
        }
        form.submit();
        return false;
    }

    function addPollOption() {

        var template = '<div class="form-group poll-option">\
                            <label class="col-sm-2 control-label">Option_{{number}}:</label>\
                            <div class="col-sm-8 col-option">\
                            <input type="text" class="form-control" name="options[]" placeholder="">\
                            </div>\
                            <div class="col-sm-2 col-button">\
                            <button class="btn-add-option">+</button>\
                            <button>-</button>\
                            </div>\
                        </div>';

        var number = $('#form_create_poll .poll-option').length;

        var optionElem = $(template.replace('{{number}}', number + 1)).insertBefore('#form_create_poll .poll-create-row');
        optionElem.focus();

        return false;
    }

    function deletePollOption() {
        if ($('#form_create_poll .poll-option').length == 1) {
            swal('Error', 'You have at least 1 option', 'error');
            return false;
        }
        var elem = $(this);
        setTimeout(function () {
            elem.parent().parent().remove();
        })
        return false;
    }

    function createPoll() {
        if ($('#form_create_poll [name="question"]').val().trim() == '') {
            swal('Error', 'Please input question', 'error');
            return false;
        }
        $('#form_create_poll').submit();
        return false;
    }

    <?php if($result = trim($this->session->flashdata('result_message'))){
    $message = $this->session->flashdata('myFlashMessage'); ?>
    $(document).ready(function () {
        if (<?= ($result == 'success') ? 1 : 0 ?>) {
            swal('Success', 'Your poll was sent for moderation', 'success');
        }
        else {
            swal('Error', '<?= $message ? $message : "Server error" ?>', 'error');
        }
    });
    <?php } ?>

</script>
</body>
</html>