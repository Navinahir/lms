<div class="row">
    <div class="col-lg-12">
        <table class="table table-striped table-bordered" data-alert="" data-all="189">
            <tbody>
                <tr class="alpha-blue">
                    <th>Name</th>
                    <td><?= $user_details['full_name'] ?></td>
                </tr>
                <tr>
                    <th>Business Name</th>
                    <td><?= $user_details['business_name'] ?></td>
                </tr>
                <tr class="alpha-blue">
                    <th>Phone No.</th>
                    <td><?= $user_details['contact_number'] ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td id="address">
                        <?= $user_details['address'] ?>, 
                        <br><?= $user_details['city'] ?> - <?= $user_details['zip_code'] ?>
                    </td>
                </tr>
                <tr class="alpha-blue">
                    <th>Email</th>
                    <td><?= $user_details['email_id'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>