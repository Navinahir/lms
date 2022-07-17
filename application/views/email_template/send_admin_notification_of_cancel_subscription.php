<tr>
    <td>
        <p><h2>Hello, Admin</h2></p>
</td>
</tr>
<tr>
    <td style="padding: 0px 0 15px 0;">
        <p style="font-size: 18px;"><strong><?php echo $full_name; ?></strong> has canceled it's subscription.</p>
    </td>
</tr>
<tr>
    <td style="">
        <h3>User Contact Details:</h3><hr>
        <table style="padding: 10px;width: 100%;text-align: left;">
            <tr>
                <th>Email:</th>
                <td><?php echo $email_id; ?></td>
            </tr>
            <tr>
                <th>Phone:</th>
                <td><?php echo $contact_number; ?></td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td style="">
        <h3>Subscription Details:</h3> <hr>
        <table style="padding: 10px;width: 100%; text-align: left;">
            <tr>
                <th>Plan Name:</th>
                <td><?php echo $package_name; ?></td>
            </tr>
            <tr>
                <th>Amount:</th>
                <td>$<?php echo $package_price; ?></td>
            </tr>
            <tr>
                <th>Canceled At:</th>
                <td><?= date('M d,Y') ?></td>
            </tr>
        </table>
    </td>
</tr>