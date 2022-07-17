<div class="row">
    <div class="col-lg-12">
        <table class="table table-striped table-bordered" data-alert="" data-all="189">
            <tbody>
                <tr>
                    <th colspan="2">
                        <h2><?= (!empty($result['page_title'])) ? $result['page_title'] : '---' ?></h2>
                    </th>
                </tr>
                <tr class="alpha-blue">
                    <th>Page Description</th>
                    <td><?= (!empty($result['page_content'])) ? $result['page_content'] : '---' ?></td>
                </tr>
                <tr>
                    <th>Page Type:</th>
                    <td>
                        <?php if ($result['page_type'] == 'Privacy') { ?>
                            <span class="label label-primary">Privacy Policy</span>
                        <?php } else if ($result['page_type'] == 'Terms') { ?>
                            <span class="label label-info">Terms of Service</span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th>Added Date:</th>
                    <td><?= date('d-m-Y', strtotime($result['created_at'])) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<style>
    .alpha-blue {
        background-color: #E1F5FE !important;
    }
    .table-bordered tr:first-child > td, .table-bordered tr:first-child > th {
        width: 50% !important;
    }
</style>