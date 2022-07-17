<div class="content">
    <section class="pdf_wrapper" >
        <?php if (isset($dataArr) && !empty($dataArr)): ?>
            <div class="panel panel-white">              
                <div class="panel-body no-padding-bottom">
                    <div class="row">

                        <div style="width:100%; text-align: center;display:inline-block; vertical-align:top;">
                            <img src="<?php echo base_url() ?>/assets/images/ark_logo1.png" alt="" style="width:300px; margin-bottom:20px; margin-top:10px;">
                        </div> 
                        <div style="width:100%; text-align: center;display:inline-block; vertical-align:top;">
                            <h2>Report For Inventory Value</h2>
                        </div>
                    </div>

                    <div class="row">
                    </div>
                </div>
                <?php
                $cur = (isset($currency) && !empty($currency)) ? $currency['symbol'] : '$';
                $date_format = (isset($format) && !empty($format)) ? $format['format'] : 'Y/m/d';
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="margin:0 20px;">
                        <thead>
                            <tr>
                                <th style="padding: 7px;text-align:center;background-color: #313335;color:#fff;">#</th>
                                <th class="col-sm-1 th-center" style="padding: 7px;text-align:center;background-color: #313335;color:#fff;">Image</th>
                                <th class="col-sm-1 th-center" style="padding: 7px;text-align:center;background-color: #313335;color:#fff;">Part</th>
                                <th class="col-sm-1 th-center" style="padding: 7px;text-align:center;background-color: #313335;color:#fff;">Description</th>
                                <th class="col-sm-1 th-center" style="padding: 7px;text-align:center;background-color: #313335;color:#fff;">Part location</th>
                                <th class="col-sm-1 th-center" style="padding: 7px;text-align:center;background-color: #313335;color:#fff;">Price</th>
                                <th class="col-sm-1 th-center" style="padding: 7px;text-align:center;background-color: #313335;color:#fff;">Availability</th>
                                <th class="col-sm-1 th-center" style="padding: 7px;text-align:center;background-color: #313335;color:#fff;">Inventory Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_inventory_value = 0;
                            foreach ($dataArr as $k => $p):
                                $total_inventory_value += $p['inventory_value'];
                                ?>
                                <tr>
                                    <td width="3%"><?php echo ($k + 1) ?></td>
                                    <td>
                                        <?php if($p['dimage'] != "" && $p['dimage'] != null) { ?>
                                            <img src="<?php echo base_url('uploads/items/'.$p['dimage']); ?>" style="height: 25px; width: 25px; padding: 5px;">
                                        <?php } else { ?>
                                            <img src="<?php echo base_url('uploads/items/no_image.jpg'); ?>" style="height: 25px; width: 25px; padding: 5px;">
                                        <?php } ?>
                                    </td>
                                    <td><?php if($p['part_no'] != "") { echo $p['part_no']; } else { echo "---"; } ?></td>
                                    <td><?php if($p['description'] != "") { echo $p['description']; } else { echo "---"; } ?></td>
                                    <td><?php if($p['part_location'] != "") { echo $p['part_location']; } else { echo "---"; } ?></td>
                                    <td><?php if($p['retail_price'] != "") { echo $p['retail_price']; } else { echo "---"; } ?></td>
                                    <td><?php if($p['total_quantity'] != "") { echo $p['total_quantity']; } else { echo "---"; } ?></td>
                                    <td><?php if($p['inventory_value'] != "") { echo $cur . number_format((float) $p['inventory_value'], 2, '.', ''); } else { echo "---"; } ?></td>
                                </tr>
                                <?php
                            endforeach;
                            ?>
                            <tr>
                                <td colspan="7" style="text-align: right;padding:5px"><b>Total Sales</b></td>
                                <td>
                                    <?php echo $cur . number_format((float) $total_inventory_value, 2, '.', '') ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>
<script>
    var date_format = '<?php echo (isset($format) && !empty($format)) ? $format['name'] : 'Y/m/d' ?>';
</script>
