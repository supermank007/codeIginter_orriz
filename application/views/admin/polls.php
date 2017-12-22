<!DOCTYPE html>
<html>
<head>
    <title>Polls</title>
    <?= $this->load->view('admin/headIncludes', null, true) ?>
    <style>
        ol {
            counter-reset: list;
            padding-left: 0px;
        }
        ol > li {
            list-style: none;
        }
        ol > li:before {
            content: counter(list) ") ";
            counter-increment: list;
        }

        p.question {
            font-weight: bold;
            font-size: 1.1em;
        }
        ol.option-list {
            margin-left: 10px;
        }
        div.option-list {
            padding-left: 10px;
        }
        .tab-pane button {
            float: none;
        }
        table thead {
            background: #DFDFDF;
        }
        table tbody tr:nth-child(even) {
            background: #DFDFDF;
        }
        table tbody tr:nth-child(odd) {
            background: #F0F0F0;
        }
    </style>
</head>
<body>
<?= $this->load->view('admin/top', null, true) ?>
<div class="container">
    <h2>Polls</h2>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <colgroup>
                    <col width="30px" />
                    <col width="auto" />
                    <col width="20%" />
                </colgroup>
                <thead>
                <tr>
                    <th>No</th>
                    <th>Poll Question</th>
                    <th>Created</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($polls as $index => $poll) : ?>
                    <tr>
                        <td><?php echo $index + 1?></td>
                        <td>
                            <p class="question"><?php echo $poll['question'] ?></p>
                            <ol class="option-list">
                                <?php
                                foreach ($poll['options'] as $option) {
                                    echo "<li>$option</li>";
                                }
                                ?>
                            </ol>
                        </td>
                        <td>
                            <div>
                                <?php echo $poll['created_at'] ?>
                            </div>
                            <p>
                                <?php if ($poll['allow']) : ?>
                                    <span class="label label-success">Approved</span>
                                <?php else : ?>
                                    <button class="btn btn-primary btn-approve" data-id="<?php echo $poll['id']?>">Approve</button>
                                <?php endif ?>
                            </p>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->load->view('admin/footer', null, true) ?>
<script type="text/javascript">
    $('.btn-approve').click(approvePoll);

    function approvePoll() {
        var ajaxUrl = '<?php echo site_url('admin/approve_poll_ajax')?>';
        var btnElem = $(this);
        $.ajax({
            method: "POST",
            data: {id: btnElem.attr('data-id')},
            url: ajaxUrl
        }).done(function (data) {
            btnElem.parent().html('<span class="label label-success">Approved</span>');
        });
    }
</script>
</body>
</html>