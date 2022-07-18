<div id="html-content-holder" class="main-div">
    <table style="padding:5px; width: 100%">
        <tr>
            <td width="130" style="padding: 0 10px 0 0;">
                <?php
                if(!empty($itemArr['user_item_qr_code']) && file_exists(USER_QRCODE_IMAGE_PATH . '/' . $itemArr['user_item_qr_code']))
                {
                ?>
                    <img src="<?= site_url(USER_QRCODE_IMAGE_PATH . '/' . $itemArr['user_item_qr_code']) ?>" alt="<?= $itemArr['global_part_no'] ?>" class="qr-image" style="height: 120px; width: 120px;">
                <?php } else { ?>
                    <img src="<?php echo base_url() . USER_QRCODE_IMAGE_PATH . '/' . 'no_qr_code.png'; ?>" class="qr-image">
                <?php } ?>
            </td>
            <td style="border-left: solid 5px #000; padding: 0 0 0 10px;">
                <p style="font-size: 18px; font-weight: bold;">Price:</p>
                <p style="font-size: 18px;">$<?= number_format($itemArr['retail_price'], 2) ?></p>
                <p style="font-size: 18px; font-weight: bold;">Part No:</p>
                <p style="font-size: 18px;"><?= $itemArr['part_no']; ?></p>
                <p style="font-size: 18px; font-weight: bold;">Description:</p>
                <?php $count_desc = strlen($itemArr['description']); ?>
                <?php 
                    if($count_desc > 15) {
                ?>
                    <p style="font-size: 18px;"><?= substr($itemArr['description'],0,15).'...'; ?></p>
                <?php }  else { ?>
                    <p style="font-size: 18px;"><?= substr($itemArr['description'],0,15); ?></p>
                <?php } ?>
                <p style="font-size: 18px; font-weight: bold;">Printed Label Date:</p>
                <p style="font-size: 18px;"><?= date('F dS,Y'); ?></p>
            </td>
        </tr>
    </table>
</div>