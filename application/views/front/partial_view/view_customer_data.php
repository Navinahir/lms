<div class="row">
    <div class="col-lg-12">
        <table class="table table-striped table-bordered" data-alert="" data-all="189">
            <tbody>
                <tr class="alpha-blue">
                    <th>Customer Name</th>
                    <td><?= (!empty($result['first_name']) && !empty($result['last_name'])) ? $result['first_name'] . ' ' . $result['last_name'] : '---' ?></td>
                </tr>
                <tr>
                    <th>Company</th>
                    <td><?= (!empty($result['company'])) ? $result['company'] : '---' ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th>Phone</th>
                    <td><?= (!empty($result['phone'])) ? $result['phone'] : '---' ?></td>
                </tr>
                <tr>
                    <th>Mobile</th>
                    <td><?= (!empty($result['mobile'])) ? $result['mobile'] : '---' ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th>Fax</th>
                    <td><?= (!empty($result['fax'])) ? $result['fax'] : '---' ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= (!empty($result['email'])) ? $result['email'] : '---' ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th colspan="2"><strong>Billing Address</strong></th>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?= (!empty($result['billing_address'])) ? $result['billing_address'] : '---' ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th>Street</th>
                    <td><?= (!empty($result['billing_address_street'])) ? $result['billing_address_street'] : '---' ?></td>
                </tr>
                <tr>
                    <th>City</th>
                    <td><?= (!empty($result['billing_address_city'])) ? $result['billing_address_city'] : '---' ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th>State</th>
                    <td><?= (!empty($result['billing_address_state'])) ? $result['billing_address_state'] : '---' ?></td>
                </tr>
                <tr>
                    <th>Zip Code</th>
                    <td><?= (!empty($result['billing_address_zip'])) ? $result['billing_address_zip'] : '---' ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th colspan="2"><strong>shipping Address</strong></th>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?= (!empty($result['shipping_address'])) ? $result['shipping_address'] : '---' ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th>Street</th>
                    <td><?= (!empty($result['shipping_address_street'])) ? $result['shipping_address_street'] : '---' ?></td>
                </tr>
                <tr>
                    <th>City</th>
                    <td><?= (!empty($result['shipping_address_city'])) ? $result['shipping_address_city'] : '---' ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th>State</th>
                    <td><?= (!empty($result['shipping_address_state'])) ? $result['shipping_address_state'] : '---' ?></td>
                </tr>
                <tr>
                    <th>Zip Code</th>
                    <td><?= (!empty($result['shipping_address_zip'])) ? $result['shipping_address_zip'] : '---' ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th>Created Date</th>
                    <td><?= (!empty($result['created_date'])) ? $result['created_date'] : '---' ?></td>
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