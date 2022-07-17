<div id="html-content-holder" class="main-div">
    <table style="padding:10px; width: 100%">
        <tr>
            <td style="width: 50%;">
                <?php 
                if(!empty($itemArr['user_item_qr_code']) && file_exists(USER_QRCODE_IMAGE_PATH . '/' . $itemArr['user_item_qr_code']))
                {
                ?>
                    <img src="<?= site_url(USER_QRCODE_IMAGE_PATH . '/' . $itemArr['user_item_qr_code']) ?>" alt="<?= $itemArr['global_part_no'] ?>" class="qr-image">
                <?php } else { ?>
                    <img src="<?php echo base_url() . USER_QRCODE_IMAGE_PATH . '/' . 'no_qr_code.png'; ?>" class="qr-image">
                <?php } ?>
            </td>
            <td class="price-td" style="width: 50%;">
                <table>
                    <tr>
                        <td style="font-size: 15px;width: 50%; text-align: right;"><b>Price:</b> $<?= number_format($itemArr['retail_price'], 2) ?></td>
                    </tr>
                </table> 
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 2px;background: #000"></td>
        </tr>
        <tr> 
            <td style="font-size: 13px;font-weight: bold;width: 30%; padding: 5px 0px;">Part No:</td>
            <td style="font-size: 13px;font-weight: 500;width: 70%;text-align: right; padding: 5px 0px;"><?= $itemArr['part_no'] ?></td>
        </tr>
        <tr>
            <td style="font-size: 13px;font-weight: bold;width: 30%; padding: 5px 0px;">Description:</td>
            <?php $count_desc = strlen($itemArr['description']); ?>
            <?php 
                if($count_desc > 10) {
            ?>
            <td style="font-size: 13px;font-weight: 500;width: 70%;text-align: right; padding: 5px 0px;"><?= substr($itemArr['description'],0,10).'...'; ?></td>
            <?php }  else { ?>
            <td style="font-size: 13px;font-weight: 500;width: 70%;text-align: right; padding: 5px 0px;"><?= substr($itemArr['description'],0,10); ?></td>
            <?php } ?>
        </tr>
        <tr>
            <td style="font-size: 13px;font-weight: bold;width: 42%; padding: 5px 0px;">Printed Date:</td>
            <td style="font-size: 13px;font-weight: 500;width: 58%;text-align: right; padding: 5px 0px;"><?= date('F dS,Y') ?></td>
        </tr>
    </table>
</div>