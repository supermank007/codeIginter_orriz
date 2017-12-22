<?php include 'header.php'; ?>
    <tr>
        <td bgcolor="#333333" height="400" style="color: white;">
            <img src="<?= base_url('public/images/800x800.png') ?>" alt="Logo"
                 style="width: 150px; margin: 0 auto; display: block;">
            <h1 style="text-align: center; font-weight: normal; font-size: 40px; letter-spacing: 3px;">
                Orriz</h1>
            <h2 style="text-align: center; font-weight: normal;"><?= $me->first_name.' '.$me->last_name ?> gave you a luv</h2>
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
                        <span style="display: inline-block; width: 800px; color: black; text-align: center;"><h2>Your Luv Information:</h2></span>
                        <div></div>
                        <span style="display: inline-block; width: 30px;"></span>
                        <span style="display: inline-block; width: 160px;"><h2>Luv Reaches:</h2></span>
                        <span style="display: inline-block; width: 200px;"><h2><?= $members->luv_points ?> points</h2></span>
                        <div></div>
                        <span style="display: inline-block; width: 30px;"></span>
                        <span style="display: inline-block; width: 160px;"><h2>Your Luv link:</h2></span>
                        <span style="display: inline-block; width: 400px; word-wrap: break-word;"><h2><?= base_url("luv") ?></h2></span>
                        <div></div>
                        <div style="height: 20px;"></div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
<?php include 'footer.php'; ?>