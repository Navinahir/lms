<div class="row">
    <div class="col-lg-12">
        <table class="table table-striped table-bordered" data-alert="" data-all="189">
            <thead>
                <tr class="alpha-blue">
                    <th>Part No.</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($items)) {
                    foreach ($items as $data) {
                        ?>
                        <tr>
                            <td><?= $data['part_no'] ?></td>
                            <td><?= $data['description'] ?></td>
                            <td>
                                <a href="javascript:void(0);" class="btn_home_item_view btn btn-primary btn-sm" title="View" id="<?= base64_encode($data['id']) ?>">View</a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="3" class="text-center">Result not found.</td>
                    </tr>
                <?php }
                ?>
            </tbody>
        </table>
    </div>
</div>
<style>
    .alpha-blue {
        background-color: #E1F5FE !important;
    }
</style>