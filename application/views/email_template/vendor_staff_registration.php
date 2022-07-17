<tr>
    <td>
        <p><h2>Hello, <?php echo $first_name; ?></h2></p>
</td>
</tr>
<tr>
    <td style="padding: 0px 0 15px 0;">
        <p>You are invited to <strong><?= $vendor_name ?></strong> account and following are your login details.</p>
    </td>
</tr>
<tr>
    <td>
        <!-- <p><span style="width:20%;float:left;"><strong>Username</strong>:</span><span style="width:80%;float:left;word-break: break-all;"><?php echo $username; ?></span> </p> -->
        <p style="clear:both"><span style="width:20%;float:left;"><strong>Email</strong>:</span><span style="width:80%;float:left;  word-break: break-all;"><?php echo $email_id; ?></span> </p>
        <!-- <p style="clear:both"><span style="width:20%;float:left;"><strong>Password</strong>:</span><span style="width:80%;float:left;  word-break: break-all;"><?php echo $password; ?></span> </p> -->
    </td>
</tr>
<!-- <tr>
    <td style="text-align: center;">
        <a href="<?= site_url('/vendor/login') ?>" class="btn_href">Login</a>
    </td>
</tr> -->
<tr>
    <td style="padding-top:10px;padding-bottom: 10px;">
        <p style="clear:both;display: flex;align-items: center;height: 100%;">
            <span style="width:20%;float:left;display: flex;align-items: center;height: 100%;"><strong>Password</strong>:</span>
            <span style="width:80%;float:left;  word-break: break-all;">
                <a href="<?php echo site_url('create_password'); ?>"class="btn_href" style="display: inline-block; padding: 10px;">Create Password</a>
            </span>
        </p>
    </td>
</tr>