<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="span4">
                    <img src="<?= site_url('assets/images/logo.png') ?>" alt="">
                    <?php
                    if ($subscription_data) {
                        ?>
                        <address>
                            <strong><?= (!empty($subscription_data['billing_name']) ? $subscription_data['billing_name'] : '') ?></strong><br>
                            <?= (!empty($subscription_data['billing_address']) ? $subscription_data['billing_address'] . ',<br>' : '') ?>
                            <?= (!empty($subscription_data['billing_city']) ? $subscription_data['billing_city'] . ', <br>' : '') ?>
                            <?= (!empty($subscription_data['state_name']) ? $subscription_data['state_name'] : '') ?>
                            <?= (!empty($subscription_data['billing_zip_code']) ? ' - ' . $subscription_data['billing_zip_code'] . ',<br>' : '') ?>
                            <?= (!empty($subscription_data['billing_phone']) ? '<br>' . $subscription_data['billing_phone'] . '<br>' : '') ?>
                            <?= (!empty($subscription_data['email_id']) ? $subscription_data['email_id'] : '') ?>
                        </address>
                    <?php } ?>
                </div>
                <div class="span4 well">
                    <table class="invoice-head">
                        <tbody>
                            <tr>
                                <td class="pull-right"><strong>Invoice Number</strong></td>
                                <td><?php echo $invoice['number']; ?></td>
                            </tr>
                            <tr>
                                <td class="pull-right"><strong>Date</strong></td>
                                <td><?php echo date('M j, Y', $invoice['date']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="span8">
                    <h2>Invoice</h2>
                </div>
            </div>
            <div class="row">
                <div class="span8 well invoice-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;

                            foreach ($invoice['lines']['data'] as $subscription) {
                                echo '<tr>';
                                $amount = $subscription['amount'] / 100;
                                echo '<td>' . $subscription['plan']['nickname'] . ' ($' . number_format($subscription['plan']['amount'] / 100, 2) . '/' . $subscription['plan']['interval'] . ')</td>';
                                echo '<td>' . date('M j, Y', $subscription['period']['start']) . ' - ' . date('M j, Y', $subscription['period']['end']) . '</td>';
                                echo '<td>$' . number_format($amount, 2) . '</td>';
                                $total += $amount;
                                echo '</tr>';
                            }
                            if (isset($invoice['discount'])) {
                                echo '<tr>';
                                echo '<td>' . $invoice['discount']['coupon']['id'] . ' (' . $invoice['discount']['coupon']['percent_off'] . '% off)</td>';
                                $discount = $total * ($invoice['discount']['coupon']['percent_off'] / 100);
                                echo '<td>&nbsp;</td>';
                                echo '<td>-$' . number_format($discount, 2) . '</td>';
                                echo '</tr>';
                            }
                            ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td><strong>Total</strong></td>
                                <td><strong>$<?php echo number_format(($total / 100), 2); ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>