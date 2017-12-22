<?php include 'header.php'; ?>
    <tr>
        <td bgcolor="#333333" height="400" style="color: white;">
            <img src="<?= base_url('public/images/800x800.png') ?>" alt="Logo"
                 style="width: 150px; margin: 0 auto; display: block;">
            <h1 style="text-align: center; font-weight: normal; font-size: 40px; letter-spacing: 3px;">
                Orriz</h1>
            <h2 style="text-align: center; font-weight: normal;">You got new message from <?= $firstname.' '.$lastname ?>.</h2>
        </td>
    </tr>

    <tr>
        <td bgcolor="white" height="30"></td>
    </tr>

    <tr>
        <td>
            <table style="color: #646464;">
                <tr>
                    <td>
                        <div></div>
                        <span style="display: inline-block; width: 30px; "></span>
                        <span style="display: inline-block; width: 300px; height: 40px;"><h2>Please check your inbox:</h2></span>
                        <div></div>
                        <span style="display: inline-block; width: 35px; "></span>
                        <span style="display: inline-block; width: 600px; word-wrap: break-word; font-size: 14px;"><h2><?= base_url("messages/index") ?></h2></span>
                        <div style="height: 20px;"></div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
<?php include 'footer.php'; ?>