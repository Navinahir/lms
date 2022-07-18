<div class="row">
    <div class="col-lg-12">
        <table class="table table-striped table-bordered" data-alert="" data-all="189">
            <tbody>
                <?php if ($viewArr['image'] != '' || $viewArr['image'] != NULL) { ?>
                    <tr><td><b>Image</b></td><td><a href="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . $viewArr['image']; ?>" data-popup="lightbox"><img src="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . $viewArr['image']; ?>" style="width: 10%;"></a></td></tr>
                <?php } ?>
                <tr class="alpha-blue"><td><b>Global Part No</b></td><td><?php echo $viewArr['global_part_no']; ?></td></tr>
                <tr><td><b>Item Part No</b></td><td><?php echo $viewArr['part_no']; ?></td></tr>
                <tr class="alpha-blue"><td><b>Item Description</b></td><td><?php echo $viewArr['description']; ?></td></tr>
                <tr><td><b>Department</b></td><td><?php echo $viewArr['dept_name']; ?></td></tr>
                <tr class="alpha-blue"><td><b>Vendor</b></td><td><?php echo $viewArr['v1_name']; ?></td></tr>
                <!--<tr class="alpha-blue"><td><b>Part Location</b></td><td><?php echo $viewArr['part_location']; ?></td></tr>-->
                <tr><td><b>Cost</b></td><td><?php echo $viewArr['unit_cost']; ?></td></tr>
                <tr class="alpha-blue"><td><b>Price</b></td><td><?php echo $viewArr['retail_price']; ?></td></tr>
                <tr><td><b>Qty In Stock</b></td><td><?php echo ($viewArr['total_quantity'] == '') ? 0 : $viewArr['total_quantity']; ?></td></tr>
                <tr class="alpha-blue"><td><b>Manufacturer</b></td><td><?php
                        $manufacturer = explode(',', $viewArr['manufacturer']);
                        foreach ($manufacturer as $v):
                            echo "<span class='label border-primary label-striped' style='margin-right: 10px; margin-bottom:10px;'>" . $v . "</span>";
                        endforeach;
                        ?></td></tr>
            </tbody>
        </table>
    </div>
<!--    <div class="col-lg-12">
        <div class='panel border-blue location_main'>
            <div class="panel-heading">
                <h6 class="panel-title">Item Location Details</h6>
            </div>
            <div class='panel-body'>
                <?php
                if (isset($viewArr['locations']) && !empty($viewArr['locations'])) {
                    $b = array_keys(array_column($viewArr['locations'], 'location_quantity'), max(array_column($viewArr['locations'], 'location_quantity')));
                    if (max(array_column($viewArr['locations'], 'location_quantity')) > 0) {
                        $location_id = $viewArr['locations'][$b[0]]['id'];
                    }
                    foreach ($viewArr['locations'] as $l) {
                        ?>
                        <div class="col-lg-4 td_location">
                            <div class="col-lg-10">
                                <span><strong><?php echo $l['name'] ?></strong></span>
                            </div>
                            <div class="col-lg-2">
                                <span><?php echo $l['location_quantity'] ?></span>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>-->
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
</style>