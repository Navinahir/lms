<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
<div class="row">
    <div class="col-lg-12">
       <div class="list-data">
            <ul>
            <?php
            if (!empty($viewArr)) {
                foreach ($viewArr as $data) {
                    ?>
                <li>
                    <div class="list-detail">
                        <div class="img-ped">
                            <?php if ($data['image'] != '' && $data['image'] != NULL) { ?>
                                <tr>
                                    <td>
                                        <a class="img_qr_code img_opn" href="javascript:void(0);" data-imgpath="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . $data['image']; ?>">
                                            <img src="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . $data['image']; ?>" style="height: 100px; width: 100px;">
                                        </a>
                                    </td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td><a class="img_qr_code img_opn" href="javascript:void(0);" data-imgpath="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . 'no_image.jpg' ?>">
                                            <img src="<?php echo base_url() . ITEMS_IMAGE_PATH . '/' . 'no_image.jpg' ?>" style="height: 100px; width: 100px;">
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </div>
                        <div class="list-info">
                            <h6 class="text-primary"> <?php echo $data['description']; ?></h6>
                            <p>Item Part No: <span> <?= $data['part_no']; ?> </span></p>
                            <p>Alternate Part No or SKU:<span><?= $data['internal_part_no'];?></span></p> 
                            <p>Department: <span><?= $data['dept_name']; ?></span></p>
                            <p>Vendor: <span><?= $data['v1_name']; ?></span></p>
                            <p>Manufacturer: <span><?= $data['manufacturer']; ?></span></p>
                        </div>
                    </div>
                    <div class="list-button">
                        <?= (!empty($data['item_link'])) ? '<a class="btn-1" target="_parent" href="' . $data['item_link'] . '">' . 'View' . '</a>' : $data['part_no'] ?>
                        <?php if(!empty($data['id'])) { ?>
                        <a href="javascript:void(0);" class="btn_home_item_view btn btn-primary btn-sm btn-2" title="Compatibility" id="<?= base64_encode($data['id']) ?>" >Compatibility</a>
                        <?php } ?>
                    </div>
                </li>
            <?php  
                }
            }
            ?>
            <?php if(empty($viewArr)) { ?>
                <div class="col-md-12 text-center">No part found.</div>
            <?php } ?> 
            </ul>
        </div>
    </div>
</div>
<style>
    .alpha-blue {
        background-color: #E1F5FE !important;
    }
</style>
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
</script>