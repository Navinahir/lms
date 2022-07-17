<div class="row">
    <div class="col-md-12">
        <label class="alert alert-info col-md-12">
            <!--<strong>This is a preview of the invoice that will be billed on <?= date('M d, Y', $upcoming_invoice['date']) ?>. It may change if the subscription is updated.</strong>-->
            <strong>Any prorated amounts or adjustments will be reflected on your next billing cycle invoice and can be viewed under the company profile page. If you have any questions please contact us at <?php echo SYSTEM_CONTACT_NO; ?>.</strong>
        </label>
    </div>

    <div class="col-md-12">
        <h1>Upcoming invoice for $<?= number_format($upcoming_invoice['total'] / 100, 2) ?></h1>
        <p>Next invoice for subscription will be billed on <?= date('M d, Y', $upcoming_invoice['date']) ?>.</p>
    </div>

    <div class="col-md-12">
        <h3>Summary:</h3>
    </div>
    <div class="col-md-12">
        <p>Invoice No: #<?= $upcoming_invoice['number'] ?></p>
    </div>
</div>
<div class="row">
    <div class="datatable-scroll">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Plan</th>
                    <th>Description</th>
                    <th>Period</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                if (!empty($upcoming_invoice['lines']['data'])) {
                    foreach ($upcoming_invoice['lines']['data'] as $invoice) {
                        ?>
                        <tr>
                            <td><?= $invoice['plan']['nickname'] ?> / ($<?= number_format($invoice['plan']['amount'] / 100, 2) . '/' . ucfirst($invoice['plan']['interval']) ?>)</td>
                            <td><?= $invoice['description'] ?></td>
                            <td><?= date('M d', $invoice['period']['start']) . ' - ' . date('M d, Y', $invoice['period']['end']) ?></td>
                            <td><?= $invoice['quantity'] ?></td>
                            <td>$<?= number_format($invoice['amount'] / 100, 2) ?></td>
                        </tr>
                        <?php
                        $total += $invoice['amount'] / 100;
                    }
                }

                if ($upcoming_invoice['discount']) {
                    if (isset($upcoming_invoice['discount']['coupon']['percent_off'])) {
                        $discount = $total * ($upcoming_invoice['discount']['coupon']['percent_off'] / 100);
                        ?>
                        <tr>
                            <td>Discount</td>
                            <td>&nbsp;</td>
                            <td colspan="2">
                                <?= $upcoming_invoice['discount']['coupon']['id'] ?> 
                                (<?= $upcoming_invoice['discount']['coupon']['percent_off'] ?>% off) 
                            </td>
                            <td>
                                -$<?= number_format($discount, 2) ?>
                            </td>
                        </tr>
                        <?php
                    } else if (isset($upcoming_invoice['discount']['coupon']['amount_off'])) {
                        $discount = $upcoming_invoice['discount']['coupon']['amount_off'] / 100;
                        ?>
                        <tr>
                            <td>Discount</td>
                            <td>&nbsp;</td>
                            <td colspan="2">
                                <?= $upcoming_invoice['discount']['coupon']['id'] ?> 
                                ($<?= number_format($discount, 2) ?> off) 
                            </td>
                            <td>
                                -$<?= number_format($discount, 2) ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>

                <tr>
                    <td colspan="4" class="text-right"><strong>Estimated Invoice Amount</strong></td>
                    <td><strong>$<?php echo number_format(($upcoming_invoice['total'] / 100), 2); ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style>
    @media(max-width:767px){
        .modal-dialog {width: 90%;margin: 10px auto 10px;}
    }
</style>