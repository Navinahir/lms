<tr>
    <td>
        <p><h2>Hello, <?php echo $first_name; ?></h2></p>
</td>
</tr>
<tr>
    <td style="padding: 0px 0 15px 0;">
        <?php
        if (isset($type) && $type == 'invite') {
            echo '<p>You have been invited to use the ' . $business_username . ' account and following are your login credentials.</p>';
        } else {
            echo '<p>Your account has been created and following are your login details.</p>';
        }
        ?>
    </td>
</tr>
<tr>
    <td>
        <!-- <p><span style="width:20%;float:left;"><strong>Username</strong>:</span><span style="width:80%;float:left;word-break: break-all;"><?php echo $username; ?></span> </p> -->
        <p style="clear:both">
            <span style="width:100px;float:left;">
                <strong>Email</strong>:
            </span>
            <span style="width:calc(100% - 100px);float:left;  word-break: break-all;"><?php echo $email_id;?></span>
        </p>
        <!-- <p style="clear:both"><span style="width:20%;float:left;"><strong>Password</strong>:</span><span style="width:80%;float:left;  word-break: break-all;"><?php echo $password; ?></span> </p> -->
    </td>
</tr>
<tr>
    <td style="padding-top:10px;padding-bottom: 10px;">
        <p style="clear:both;display: flex;align-items: center;height: 100%;">
            <span style="width:100px;float:left;display: flex;align-items: center;height: 100%;">
                <strong>Password</strong>:
            </span>
            <span style="width:calc(100% - 100px);float:left;  word-break: break-all;">
                <a href="<?php echo site_url('create_password'); ?>"class="btn_href" style="display: inline-block; padding: 10px;">Create Password</a>
            </span>
        </p>
    </td>
</tr>