<div class="row">
    <div class="col-lg-12">
        <table class="table table-striped table-bordered" data-alert="" data-all="189">
            <tbody>
                <tr class="alpha-blue">
                    <th>Order No #</th>
                    <td><?= $result['order_no'] ?></td>
                </tr>
                <tr>
                    <th>Order Date:</th>
                    <td><?= date($format['format'], strtotime($result['ordered_date'])) ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th>Order Description</th>
                    <td><?= (!empty($result['description'])) ? $result['description'] : '---' ?></td>
                </tr>
                <tr>
                    <th>Customer Name</th>
                    <td><?= (!empty($result['customer_name'])) ? $result['customer_name'] : '---' ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th>Customer Phone</th>
                    <td><?= (!empty($result['customer_phone'])) ? $result['customer_phone'] : '---' ?></td>
                </tr>
                <tr>
                    <th>Order Given Name</th>
                    <td><?= (!empty($result['order_given_name'])) ? $result['order_given_name'] : '---' ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th>Order Taken Name</th>
                    <td><?= (!empty($result['order_taken_name'])) ? $result['order_taken_name'] : '---' ?></td>
                </tr>
                <tr>
                    <th>Paid For?</th>
                    <td>
                        <?php
                        if ($result['paid_for'] == 1) {
                            echo 'Yes';
                        } else {
                            echo 'No';
                        }
                        ?>
                    </td>
                </tr>
                <tr class="alpha-blue">
                    <th>Vendor Name</th>
                    <td><?= (!empty($result['vendor_name'])) ? $result['vendor_name'] : '---' ?></td>
                </tr>
                <tr>
                    <th>Vendor Part No #</th>
                    <td><?= (!empty($result['vendor_part_no'])) ? $result['vendor_part_no'] : '---' ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th>Item Description</th>
                    <td><?= (!empty($result['item_description'])) ? $result['item_description'] : '---' ?></td>
                </tr>
                <tr>
                    <th>Quoted Price</th>
                    <td><?= (!empty($result['quoted_price'])) ? '$' . number_format($result['quoted_price'], 2) : '---' ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th>Receipt No</th>
                    <td><?= (!empty($result['receipt_no'])) ? $result['receipt_no'] : '---' ?></td>
                </tr>
                <tr>
                    <th>Total Receipt Amount</th>
                    <td><?= (!empty($result['total_receipt_amount'])) ? '$' . number_format($result['total_receipt_amount'], 2) : '---' ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th>Order Status</th>
                    <td><?= (!empty($result['status_name'])) ? $result['status_name'] : '---' ?></td>
                </tr>
                <tr>
                    <th>Order Id</th>
                    <td><?= (!empty($result['order_id'])) ? $result['order_id'] : '---' ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th>Current Date</th>
                    <td><?= (!empty($result['current_date'])) ? date($format['format'], strtotime($result['current_date'])) : '---' ?></td>
                </tr>
                <tr>
                    <th>Total Receipt Amount</th>
                    <td><?= (!empty($result['total_receipt_amount'])) ? '$' . number_format($result['total_receipt_amount'], 2) : '---' ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th>Payment Method</th>
                    <td><?= (!empty($result['payment_method_name'])) ? $result['payment_method_name'] : '---' ?></td>
                </tr>
                <tr>
                    <th>Payment Notes</th>
                    <td><?= (!empty($result['payment_notes'])) ? $result['payment_notes'] : '---' ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<style>
    .td_location{
    }
    .location_main{
        margin: 30px 0px 0px 0px;
    }
    .alpha-blue {
        background-color: #E1F5FE !important;
    }
    .compatibility{
        white-space: normal;
        text-align: left;
        font-size: smaller;
    }
    .table-bordered tr:first-child > td, .table-bordered tr:first-child > th {
        width: 50% !important;
    }
</style>