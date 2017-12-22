<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <?= $this->load->view('admin/headIncludes', null, true) ?>
    <link href="<?= base_url('public/libraries/bootstrap3-editable-1.5.1/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/bootstrap-wysihtml5-0.0.2.css') ?>" rel="stylesheet" type="text/css" />
    <script src="<?= base_url('public/libraries/bootstrap3-editable-1.5.1/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/wysihtml5-0.3.0.min.js') ?>"></script>
    <script src="<?= base_url('public/libraries/bootstrap3-editable-1.5.1/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/bootstrap-wysihtml5-0.0.2.min.js') ?>"></script>
    <script src="<?= base_url('public/libraries/bootstrap3-editable-1.5.1/inputs-ext/wysihtml5/wysihtml5.js') ?>"></script>

</head>
<body>
<?= $this->load->view('admin/top', null, true) ?>
<div class="container">
    <h1 style="text-align: center; margin-top: 50px;">Reports</h1>
    <div style="margin-top: 200px;">
       <a class="btn btn-success" href="reportsChat">Chat Reports</a>
    </div>
</div>

<?= $this->load->view('public/footer', null, true) ?>


</body>
</html>