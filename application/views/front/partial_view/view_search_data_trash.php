<div class="row">
    <div class="col-lg-12">
        <table class="table table-striped table-bordered" data-alert="" data-all="189">
            <tbody>
                <?php 
                if ($viewArr['image'] != '' || $viewArr['image'] != NULL) 
                { 
                    ?>
                    <tr class="alpha-blue"><td><b>Image</b></td><td><a href="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . $viewArr['image']; ?>" data-popup="lightbox"><img src="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . $viewArr['image']; ?>" style="width: 10%;"></a></td></tr>
                <?php } else { ?>
                <?php 
                if ($viewArr['global_item_image'] != '' || $viewArr['global_item_image'] != NULL) 
                { 
                    ?>
                    <tr class="alpha-blue"><td><b>Image</b></td><td><a href="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . $viewArr['global_item_image']; ?>" data-popup="lightbox"><img src="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . $viewArr['global_item_image']; ?>" style="width: 10%;"></a></td>
                    </tr>
                <?php 
                    } 
                }
                ?>
                
                <?php
                if ($viewArr['user_item_qr_code'] != '' || $viewArr['user_item_qr_code'] != NULL) {
                    ?>
                    <tr>
                        <td><b>Item QR Code</b></td>
                        <td>
                            <?php 
                            if(file_exists(USER_QRCODE_IMAGE_PATH . '/' . $viewArr['user_item_qr_code']))
                            {
                            ?>
                            <a href="<?php echo base_url() . USER_QRCODE_IMAGE_PATH . '/' . $viewArr['user_item_qr_code']; ?>" data-popup="lightbox">
                                <img src="<?php echo base_url() . USER_QRCODE_IMAGE_PATH . '/' . $viewArr['user_item_qr_code']; ?>" style="width: 10%;">
                            </a>
                            <?php } else { ?>
                            <a href="<?php echo base_url() . USER_QRCODE_IMAGE_PATH . '/' . 'no_qr_code.png'; ?>" data-popup="lightbox">
                                <img src="<?php echo base_url() . USER_QRCODE_IMAGE_PATH . '/' . 'no_qr_code.png'; ?>" style="width: 10%;">
                            </a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr class="alpha-blue">
                    <td><b>Global Part No</b></td>
                    <td>
                        <?php
                        if (!empty($viewArr['global_part_no'])) {
                            if (!empty($viewArr['item_link'])) {
                                echo '<a target="_blank" href="' . $viewArr['item_link'] . '">' . $viewArr['global_part_no'] . '</a>';
                            } else {
                                echo $viewArr['global_part_no'];
                            }
                        } else {
                            echo '<b>Non Global Part</b>';
                        }
                        ?>
                    </td>
                </tr>
                <tr><td><b>Item Part No</b></td><td><?php echo $viewArr['part_no']; ?></td></tr>
                <tr class="alpha-blue"><td><b>Alternate Part No or SKU</b></td><td><?php echo $viewArr['internal_part_no']; ?></td></tr>
                <tr><td><b>Item Description</b></td><td><?php echo $viewArr['description']; ?></td></tr>
                <tr class="alpha-blue"><td><b>UPC Barcode</b></td><td><?php if($viewArr['upc_barcode'] != "") { echo $viewArr['upc_barcode']; } else { echo "---"; } ?></td></tr>
                <tr><td><b>Part Location</b></td><td><?= (!empty($viewArr['part_location'])) ? $viewArr['part_location'] : ' --- ' ?></td></tr>
                <tr class="alpha-blue"><td><b>Department</b></td><td><?php echo $viewArr['dept_name']; ?></td></tr>
                <tr><td><b>Vendor</b></td><td><?php echo $viewArr['v1_name']; ?></td></tr>
                <tr class="alpha-blue"><td><b>Cost</b></td><td><?php echo $viewArr['unit_cost']; ?></td></tr>
                <tr><td><b>Price</b></td><td><?php echo $viewArr['retail_price']; ?></td></tr>
                <tr class="alpha-blue"><td><b>Qty In Stock</b></td><td><?php echo ($viewArr['total_quantity'] == '') ? 0 : $viewArr['total_quantity']; ?></td></tr>
                <tr><td><b>Manufacturer</b></td><td><?php
                        $manufacturer = explode(',', $viewArr['manufacturer']);
                        foreach ($manufacturer as $v):
                            echo "<span class='label border-primary label-striped' style='margin-right: 10px;margin-bottom:10px;'>" . $v . "</span>";
                        endforeach;
                        ?>
                    </td>
                </tr>
                <tr class="alpha-blue"><td><b>Low Inventoy Notification Point</b></td><td><?php echo ($viewArr['low_inventory_limit'] > 0) ? $viewArr['low_inventory_limit'] : '---'; ?></td></tr>
            </tbody>
        </table>
    </div>
    <div class="col-lg-12">
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
                            <div class="col-lg-4  td_location">
                                <div class="col-lg-10 col-md-6 col-sm-6 col-xs-6 ">
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
    </div>
    <?php
    if ($viewArr['total_quantity'] > 0) {
        $transponder = '';
        if (!is_null($viewArr['global_part_no'])) {
            $transponder = '&transponder_id=' . base64_encode($viewArr['transponder_id']);
            if (!is_null($viewArr['transponder_id'])) {
                $transponder = '&transponder_id=' . base64_encode($viewArr['transponder_id']);
            } else {
                $transponder = '';
            }
        } else {
            if (!is_null($viewArr['transponder_id'])) {
                $transponder = '&transponder_id=' . base64_encode($viewArr['transponder_id']) . '&type=' . base64_encode(1);
            }
        }
    } 

    if($this->session->userdata('u_location_id') != "" && !empty($this->session->userdata('u_location_id')))
    {
        foreach ($viewArr['locations']  as $key => $l_value) {
            if($l_value['id'] == $this->session->userdata('u_location_id'))
            {
                $est_inv_status = $l_value['location_quantity'];
            }
        }
    } else {
        $est_inv_status = 0;
    }

    ?>
    <?php
    if (!empty($partArr)) {
        ?>
        <div class="col-lg-12 mt-20">
            <div class='panel border-blue location_main'>
                <div class="panel-heading">
                    <h6 class="panel-title">Compatibility Details</h6>
                </div>
                <div class='panel-body row'>
                    <?php
                    $is_data = false;
                    foreach ($partArr as $part_info) {
                        if (!empty($part_info['company']) && !empty($part_info['model']) && !empty($part_info['year'])) {
                            $is_data = true;
                            ?>
                            <div class="col-md-3">
                                <span class='label border-left-primary label-striped mt-5 text-bold compatibility' style=''>
                                    <?= $part_info['company'] ?>&nbsp;<?= $part_info['model'] ?>&nbsp;<?= $part_info['year'] ?></span>
                            </div>
                            <?php
                        }
                    }

                    if ($is_data == FALSE) {
                        ?>
                        <div class="col-md-12 text-center">No part found.</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
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
</style>