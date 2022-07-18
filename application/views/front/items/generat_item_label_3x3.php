<div id="html-content-holder" class="main-div">
    <table style="padding:10px; width: 100%">
        <tr>
            <td style="width: 50%;">
                <?php 
                if(!empty($itemArr['user_item_qr_code']) && file_exists(USER_QRCODE_IMAGE_PATH . '/' . $itemArr['user_item_qr_code']))
                {
                ?>
                    <img src="<?= site_url(USER_QRCODE_IMAGE_PATH . '/' . $itemArr['user_item_qr_code']) ?>" alt="<?= $itemArr['global_part_no'] ?>" class="qr-image" style="height: 80px; width: 80px;" >
                <?php } else { ?>
                    <img src="<?php echo base_url() . USER_QRCODE_IMAGE_PATH . '/' . 'no_qr_code.png'; ?>" class="qr-image">
                <?php } ?>
            </td>
            <td class="price-td" style="width: 50%;">
                <table>
                    <tr>
                        <td style="font-size: 20px;width: 50%; text-align: right;"><b>Price:</b> $<?= number_format($itemArr['retail_price'], 2) ?></td>
                    </tr>
                </table> 
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 2px;background: #000" colspan="2"></td>
        </tr>
        <tr>
        	<td style="font-size: 13px;font-weight: bold;" colspan="2">Item Part No:</td>
        </tr>
        <tr> 
            <td style="font-size: 13px;font-weight: 500;" colspan="2"><?= $itemArr['part_no'] ?></td>
        </tr>
        <tr>
        	<td style="font-size: 13px;font-weight: bold;" colspan="2">Alternate Part No or SKU #:</td>
        </tr>
        <tr> 
            <td style="font-size: 13px;font-weight: 500;" colspan="2"><?= $itemArr['internal_part_no'] ?></td>
        </tr>
        <tr>
        	<td style="font-size: 13px;font-weight: bold;" colspan="2">Item Description:</td>
        </tr>
        <tr>
            <?php $count_desc = strlen($itemArr['description']); ?>
            <?php 
                if($count_desc > 70) {
            ?>
            <td style="font-size: 13px;font-weight: 500;" colspan="2"><?= substr($itemArr['description'],0,70).'...'; ?></td>
            <?php }  else { ?>
            <td style="font-size: 13px;font-weight: 500;" colspan="2"><?= substr($itemArr['description'],0,70); ?></td>
            <?php } ?>
        </tr>
        <tr>
        	<td style="font-size: 13px;font-weight: bold;width: 42%;" colspan="2">Printed Date:</td>
        </tr>
        <tr>
            <td style="font-size: 13px;font-weight: 500;width: 58%;" colspan="2"><?= date('F dS,Y') ?></td>
        </tr>
    </table>
</div>