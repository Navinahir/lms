<body style="margin: 5px; font-family: Arial, sans-serif;">        
    <div class="invoice_section">
        <table class="table" width="850" style="padding:20px; margin:0 auto;"> 
            <tbody>
                <tr>
                    <td align="left">
                        <div style="margin-bottom: 3px;">
                            <span style="font-size:13px;">
                                <?= (!empty($subscription_data['billing_name']) ? $subscription_data['billing_name'] : '') ?>
                            </span>
                        </div>
                        <div style="margin-bottom: 3px;">
                            <span style="font-size:13px;">
                                <?= (!empty($subscription_data['billing_address']) ? $subscription_data['billing_address'] . ',<br>' : '') ?>
                            </span>
                        </div>
                        <div style="margin-bottom: 3px;">
                            <span style="font-size:13px;">
                                <?= (!empty($subscription_data['billing_city']) ? $subscription_data['billing_city'] . ', <br>' : '') ?>
                            </span>
                        </div>
                        <div style="margin-bottom: 3px;">
                            <span style="font-size:13px;">
                                <?= (!empty($subscription_data['state_name']) ? $subscription_data['state_name'] : '') ?>
                                <?= (!empty($subscription_data['billing_zip_code']) ? ' - ' . $subscription_data['billing_zip_code'] . ',<br>' : '') ?>
                            </span>
                        </div>
                        <div style="margin-bottom: 3px;">
                            <span style="font-size:13px;">
                                <?= (!empty($subscription_data['billing_phone']) ? $subscription_data['billing_phone'] . '<br>' : '') ?>
                            </span>
                        </div>
                    </td>
                    <td align="right" style="vertical-align: top;">
                        <div style="margin-bottom: 3px;text-align: left;">
                            <label style="font-size: 15px;">Invoice Number: </label>
                            <span style="font-size:13px;float: right;font-weight:700;"><?= $invoice['number'] ?></span>
                        </div>
                        <div style="margin-bottom: 3px;text-align: left;">
                            <label style="font-size: 15px;">Date of Issue: </label>
                            <span style="font-size:13px;float: right;font-weight:700;"><?= date('M d, Y', $invoice['date']) ?></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td align="left">
                        <div style="margin-bottom: 3px;">
                            <label style="font-weight:700;font-size: 15px;"> Bill To:</label>
                        </div>
                        <div style="margin-bottom: 3px;">
                            <span style="font-size:13px;">
                                <?= (!empty($subscription_data['email_id']) ? $subscription_data['email_id'] : '') ?><br> 
                            </span>
                        </div>
                    </td>
                    <td align="right"></td>
                </tr>

                <tr>
                    <td colspan="2" style="padding: 25px 0;">                        
                        <span style="margin-bottom:0px;font-size: 26px;">$<?= number_format($invoice['total'] / 100, 2) ?> Paid on <?= date('M d, Y', $invoice['date']) ?></span>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <table style="width:100%;border-collapse: collapse;border-color: #ddd;">
                            <thead>
                                <tr>
                                    <th style="text-align: left;padding: 15px;font-size: 15px;border-bottom: 4px solid #b3b3b3;">Description</th>
                                    <th style="text-align: left;padding: 15px;font-size: 15px;border-bottom: 4px solid #b3b3b3;">Date</th>
                                    <th style="text-align: left;padding: 15px;font-size: 15px;border-bottom: 4px solid #b3b3b3;">Amount</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Display The Invoice Items -->
                                <?php
                                $total = 0;
                                foreach ($invoice['lines']['data'] as $item) {
                                    ?>
                                    <tr style="background-color: #f6fcff;">
                                        <td colspan="3" style="text-align: left;font-size:12px;color: grey;padding: 15px;border-bottom: 1px solid #b3b3b3;">
                                            <?= $item['quantity'] ?> × <?= $item['plan']['nickname'] ?> (at <?= '$' . number_format($item['plan']['amount'] / 100, 2) ?> / <?= $item['plan']['interval'] ?>)
                                        </td>
                                    </tr>
                                <?php } ?>

                                <!-- Display The Subscriptions -->
                                <?php
                                foreach ($invoice['lines']['data'] as $subscription) {
                                    $amount = $subscription['amount'] / 100;
                                    $total += $amount;
                                    ?>
                                    <tr>
                                        <td style="text-align: left;font-size:13px;padding: 15px;border-bottom: 1px solid #b3b3b3;">Subscription (<?= $subscription['quantity'] ?>)</td>
                                        <td style="text-align: left;font-size:13px;padding: 15px;border-bottom: 1px solid #b3b3b3;">
                                            <?= date("M d, Y", $subscription['period']['start']) ?> - <?= date("M d, Y", $subscription['period']['end']) ?>
                                        </td>
                                        <td style="text-align: left;font-size:13px;padding: 15px;border-bottom: 1px solid #b3b3b3;">$<?= number_format($subscription['amount'] / 100, 2) ?></td>
                                    </tr>
                                    <?php
                                }

                                //Subscription Discount
                                if (isset($invoice['discount'])) {
                                    if (isset($invoice['discount']['coupon']['percent_off'])) {
                                        $discount = $total * ($invoice['discount']['coupon']['percent_off'] / 100);
                                        ?>
                                        <tr>
                                            <td style="text-align: left;font-size:13px;padding: 15px;border-bottom: 1px solid #b3b3b3;">Discount</td>
                                            <td style="text-align: left;font-size:13px;padding: 15px;border-bottom: 1px solid #b3b3b3;">
                                                <?= $invoice['discount']['coupon']['id'] ?> 
                                                (<?= $invoice['discount']['coupon']['percent_off'] ?>% off) 
                                            </td>
                                            <td style="text-align: left;font-size:15px;padding: 15px;border-bottom: 1px solid #b3b3b3;">
                                                -$<?= number_format($discount, 2) ?>
                                            </td>
                                        </tr>
                                        <?php
                                    } else if (isset($invoice['discount']['coupon']['amount_off'])) {
                                        $discount = $invoice['discount']['coupon']['amount_off'] / 100;
                                        ?>
                                        <tr>
                                            <td style="text-align: left;font-size:13px;padding: 15px;border-bottom: 1px solid #b3b3b3;">Discount</td>
                                            <td style="text-align: left;font-size:13px;padding: 15px;border-bottom: 1px solid #b3b3b3;">
                                                <?= $invoice['discount']['coupon']['id'] ?> 
                                                ($<?= number_format($discount, 2) ?> off) 
                                            </td>
                                            <td style="text-align: left;font-size:15px;padding: 15px;border-bottom: 1px solid #b3b3b3;">
                                                -$<?= number_format($discount, 2) ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="width: 61%;text-align: right;font-size:15px;padding: 15px;">Paid Amount </td>
                    <td style="width: 39%;text-align: center;font-size:15px;padding: 15px;background-color: #d2d4d6;">$<?php echo number_format(($invoice['total'] / 100), 2); ?></td>
                </tr>	
            </tbody>
        </table>            
    </div>
</body>