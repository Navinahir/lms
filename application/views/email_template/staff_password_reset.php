<tr>
    <td>
        <p><h2>Hello, <?php echo $first_name; ?></h2></p>
</td>
</tr>
<tr>
    <td style="padding: 0px 0 15px 0;">
        <p>Your password has been reset by Admin <br/> following are your new login details.</p>
    </td>
</tr>
<tr>
    <td>
        <p><span style="width:20%;float:left;"><strong>Username</strong>:</span><span style="width:80%;float:left;word-break: break-all;"><?php echo $username; ?></span> </p>
        <p style="clear:both"><span style="width:20%;float:left;"><strong>Email</strong>:</span><span style="width:80%;float:left;  word-break: break-all;"><?php echo $email_id; ?></span> </p>
        <p style="clear:both"><span style="width:20%;float:left;"><strong>Password</strong>:</span><span style="width:80%;float:left;  word-break: break-all;"><?php echo $password; ?></span> </p>
    </td>
</tr>
<tr>
    <td style="padding:30px 0px 30px 0px;text-align: center;">
        <p><a href="<?= site_url('vendor/login') ?>" class="btn_href">Login</a></p>
    </td>
</tr>