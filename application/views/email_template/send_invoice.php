
<tr>
    <td>
        <p><h2>Hello, <?php echo $first_name; ?></h2></p>
</td>
</tr>
<tr>
    <td style="padding: 0px 0 15px 0;">
        <p>Thank you for your business. Your invoice is attached and can be viewed, downloaded, or printed as a PDF.</p>
    </td>
</tr>
<tr>
    <td style='float: center'>
        <table style="margin:0; padding:0; border:0; border-spacing:0; width:100%;">
            <tr>
                <td style="width:36%; font-weight:700; padding:2px 0;">Invoice No</td>
                <td style="width:64%; word-break: break-all; padding:2px 0;"><?php echo $estimate_id; ?></td>
            </tr>
            <tr>
                <td style="width:36%; font-weight:700;padding:2px 0;">Invoice Date</td>
                <td style="width:64%; word-break: break-all;padding:2px 0;"><?php echo $estimate_date; ?></td>
            </tr>
            <tr>
                <td style="width:36%; font-weight:700;padding:2px 0;">Invoice Amount</td>
                <td style="width:64%; word-break: break-all; padding:2px 0;"><?php echo $estimate_total; ?></td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td style="padding:30px 0px 30px 0px;text-align: center;">
        <p><a href="<?php echo $url; ?>" class="btn_href">Download Invoice</a></p>
    </td>
</tr>