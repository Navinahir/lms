<div id="html-content-holder" class="main-div">
    <table style="padding:5px; width: 100%">
        <tr>
            <td style="width: 50%;">
                <?php 
                if(!empty($itemArr['user_item_qr_code']) && file_exists(USER_QRCODE_IMAGE_PATH . '/' . $itemArr['user_item_qr_code']))
                {
                ?>
                    <img src="<?= site_url(USER_QRCODE_IMAGE_PATH . '/' . $itemArr['user_item_qr_code']) ?>" alt="<?= $itemArr['global_part_no'] ?>" class="qr-image" style="height: 70px; width: 70px;" >
                <?php } else { ?>
                    <img src="<?php echo base_url() . USER_QRCODE_IMAGE_PATH . '/' . 'no_qr_code.png'; ?>" class="qr-image" style="height: 70px; width: 70px;">
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 2px;background: #000" colspan="2"></td>
        </tr>
        <tr>
        	<td style="font-size: 10px;font-weight: bold;" colspan="2"><b>Price:</b> </td>
        </tr>
        <tr> 
            <td style="font-size: 10px;font-weight: 500;" colspan="2">$<?= number_format($itemArr['retail_price'], 2) ?></td>
        </tr>
        <tr>
        	<td style="font-size: 10px;font-weight: bold;" colspan="2">Item Part No:</td>
        </tr>
        <tr> 
            <td style="font-size: 10px;font-weight: 500;" colspan="2"><?= $itemArr['part_no'] ?></td>
        </tr>
        <tr>
        	<td style="font-size: 10px;font-weight: bold;" colspan="2">Alternate Part No or SKU #:</td>
        </tr>
        <tr> 
            <td style="font-size: 10px;font-weight: 500;" colspan="2"><?= $itemArr['internal_part_no'] ?></td>
        </tr>
        <tr>
        	<td style="font-size: 10px;font-weight: bold;" colspan="2">Description:</td>
        </tr>
        <tr>
            <?php $count_desc = strlen($itemArr['description']); ?>
            <?php 
                if($count_desc > 20) {
            ?>
            <td style="font-size: 10px;font-weight: 500;" colspan="2"><?= substr($itemArr['description'],0,20).'...'; ?></td>
            <?php }  else { ?>
            <td style="font-size: 10px;font-weight: 500;" colspan="2"><?= substr($itemArr['description'],0,20); ?></td>
            <?php } ?>
        </tr>
        <tr>
        	<td style="font-size: 10px;font-weight: bold;width: 42%;" colspan="2">Printed Date:</td>
        </tr>
        <tr>
            <td style="font-size: 10px;font-weight: 500;width: 58%;" colspan="2"><?= date('F dS,Y') ?></td>
        </tr>
    </table>
</div>