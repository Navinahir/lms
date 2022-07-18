<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>

<div class="row">
    <?php
    if (!empty($partArr)) {
        ?>
        <div class="col-lg-12 mt-20">
            <div class='panel border-blue location_main'>
                <div class="panel-heading">
                    <h6 class="panel-title">Compatibility Details</h6>
                </div>
                <div class='panel-body'>
                    <?php
                    $is_data = false;
                    foreach ($partArr as $part_info) {
                        if (!empty($part_info['company']) && !empty($part_info['model']) && !empty($part_info['year'])) {
                            $is_data = true;
                            ?>
                            <div class="col-sm-3">
                                <div class="capa-list-wrap">
                                <span class='label label-striped text-bold compatibility' style=''>
                                    <?= $part_info['company'] ?>&nbsp;<?= $part_info['model'] ?>&nbsp;<?= $part_info['year'] ?></span>
                                </div>
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

    <div class="col-lg-12">
        <table class="table table-striped table-bordered" data-alert="" data-all="189">
            <tbody>
                <?php if (!empty($viewArr['image']) && file_exists(ITEMS_IMAGE_PATH . '/' . $viewArr['image'])) { ?>
                    <tr>
                        <td><b>Image</b></td>
                        <td>
                            <a class="img_opn" href="javascript:void(0);" data-imgpath="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . $viewArr['image']; ?>"><img src="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . $viewArr['image']; ?>" style="width: 10%;"></a>
                        </td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td><b>Image</b></td>
                        <td>
                            <a class="img_opn" href="javascript:void(0);" data-imgpath="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . 'no_image.jpg' ?>"><img src="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . 'no_image.jpg' ?>" style="width: 10%;"></a>
                        </td>
                    </tr>
                <?php } ?>
                <?php if ($viewArr['item_qr_code'] != '' || $viewArr['item_qr_code'] != NULL) { ?>
                    <tr>
                        <td><b>QR Code</b></td>
                        <td>
                            <a class="img_qr_code" href="javascript:void(0);" data-qrcode="<?php echo base_url() . QRCODE_IMAGE_PATH . '/' . $viewArr['item_qr_code']; ?>">
                                <img src="<?php echo base_url() . QRCODE_IMAGE_PATH . '/' . $viewArr['item_qr_code']; ?>" style="width: 10%;">
                            </a>
                        </td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td><b>QR Code</b></td>
                        <td>
                            <a class="img_opn" href="javascript:void(0);" data-imgpath="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . 'no_image.jpg' ?>"><img src="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . 'no_image.jpg' ?>" style="width: 10%;"></a>
                        </td>
                    </tr>
                <?php } ?>
                <tr class="alpha-blue"><td><b>Item Part No</b></td><td><?= (!empty($viewArr['item_link'])) ? '<a target="_parent" href="' . $viewArr['item_link'] . '">' . $viewArr['part_no'] . '</a>' : $viewArr['part_no'] ?></td></tr>
                <tr><td><b>Alternate Part No or SKU</b></td><td><?php echo $viewArr['internal_part_no']; ?></td></tr>
                <tr class="alpha-blue"><td><b>Item Description</b></td><td><?php echo $viewArr['description']; ?></td></tr>
                <tr><td><b>Department</b></td><td><?php echo $viewArr['dept_name']; ?></td></tr>
                <tr class="alpha-blue"><td><b>Vendor</b></td><td><?php echo $viewArr['v1_name']; ?></td></tr>
                <tr>
                    <td><b>Manufacturer</b></td><td>
                        <?php
                        $manufacturer = explode(',', $viewArr['manufacturer']);
                        foreach ($manufacturer as $v):
                            echo "<span class='label border-primary label-striped' style='margin-right: 10px; margin-bottom: 10px;'>" . $v . "</span>";
                        endforeach;
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    $(document).on('click', '.img_opn', function () {
    var imgpath = $(this).attr('data-imgpath');
    swal({
            title: '',
            imageUrl: imgpath,
            imageWidth: 400,
            imageHeight: 400,
            imageAlt: 'Custom image',
            animation: true
        });
    });
    $(document).on('click', '.img_qr_code', function () {
    var qrpath = $(this).attr('data-qrcode');
    swal({
            title: '',
            imageUrl: qrpath,
            imageWidth: 400,
            imageHeight: 400,
            imageAlt: 'Custom image',
            animation: true
        });
    });
</script>