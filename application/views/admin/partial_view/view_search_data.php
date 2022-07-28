<div class="row">
    <div class="col-lg-12">
        <table class="table table-striped table-bordered" data-alert="" data-all="189">
            <tbody>
                <?php if ($viewArr['image'] != '' || $viewArr['image'] != NULL) { ?>
                    <tr><td><b>Image</b></td><td><a href="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . $viewArr['image']; ?>" data-popup="lightbox"><img src="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . $viewArr['image']; ?>" style="width: 10%;"></a></td></tr>
                <?php } ?>
                <tr class="alpha-blue"><td><b>Item Part No</b></td><td><?php echo $viewArr['part_no']; ?></td></tr>
                <tr><td><b>Alternate Part No or SKU</b></td><td><?php echo $viewArr['internal_part_no']; ?></td></tr>
                <tr class="alpha-blue"><td><b>Item Description</b></td><td><?php echo $viewArr['description']; ?></td></tr>
                <tr><td><b>Department</b></td><td><?php echo $viewArr['dept_name']; ?></td></tr>
                <tr class="alpha-blue"><td><b>Vendor</b></td><td><?php echo $viewArr['v1_name']; ?></td></tr>
                <tr>
                    <td><b>Manufacturer</b></td><td>
                        <?php
                        $manufacturer = explode(',', $viewArr['manufacturer']);
                        foreach ($manufacturer as $v):
                            echo "<span class='label border-primary label-striped' style='margin-right: 10px; margin-bottom:10px; '>" . $v . "</span>";
                        endforeach;
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php if ($viewArr['compatibility'] != null) { ?>
        <div class="col-lg-12 mt-20">
            <div class='panel border-blue location_main'>
                <div class="panel-heading">
                    <h6 class="panel-title">Compatibility Details</h6>
                </div>
                <div class='panel-body row'>
                    <?php
                    $compatibility = explode(',', $viewArr['compatibility']);
                    foreach ($compatibility as $k => $l) {
                        ?>
                        <div class="col-md-3">
                            <span class='label border-left-primary label-striped mt-5 text-bold compatibility' style=''> <?php echo $l ?></span>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<style>
    .alpha-blue {
        background-color: #E1F5FE !important;
    }
</style>