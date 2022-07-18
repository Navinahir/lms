<tr>
    <td colspan="2" style="line-height: 20px;font-size: 15px;padding: 10px;">
        <p>Hello <b>Admin</b>,</p><?= $name ?> has sent us an inquiry. Please check with below details.
    </td>
</tr>
<tr>
    <td style="padding:5px 10px;width:20%"><b>Name: </b></td>
    <td style="padding:5px 10px;"><?= $name ?></td>
</tr>
<tr>
    <td style="padding:5px 10px;"><b>Email: </b></td>
    <td style="padding:5px 10px;"><?= $email ?></td>
</tr>
<tr>
    <td style="padding:5px 10px;"><b>Phone: </b></td>
    <td style="padding:5px 10px;"><?= $phone ?></td>
</tr>
<tr>
    <td style="padding:5px 10px;"><b>City: </b></td>
    <td style="padding:5px 10px;"><?= $city ?></td>
</tr>
<tr>
    <td style="padding:5px 10px;vertical-align:top"><b>Message: </b></td>
    <td style="padding:5px 10px;width: 100%;word-wrap: break-word;word-break: break-all;"><?= $message ?></td>
</tr>