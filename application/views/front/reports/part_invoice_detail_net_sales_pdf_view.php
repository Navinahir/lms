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
                            <h2>Report For Part Net Sales <?php echo $from_date; ?> To <?php echo $to_date; ?></h2>
                        </div>
                    </div>
                </div>
                <?php
                $cur = (isset($currency) && !empty($currency)) ? $currency['symbol'] : '$';
                $date_format = (isset($format) && !empty($format)) ? $format['format'] : 'Y/m/d';
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="margin:0 0px;">
                        <thead>
                            <tr>
                                <th style="padding: 10px;text-align:center;background-color: #313335;color:#fff;">#</th>
                                <th style="padding: 10px;text-align:center;background-color: #313335;color:#fff;">Date</th>
                                <th style="padding: 10px;text-align:center;background-color: #313335;color:#fff;">Invoice No</th>
                                <th style="padding: 10px;text-align:center;background-color: #313335;color:#fff;">Representative</th>
                                <th style="padding: 10px;text-align:center;background-color: #313335;color:#fff;">Discount</th>
                                <th style="padding: 10px;text-align:center;background-color: #313335;color:#fff;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_discount = 0;
                            foreach ($dataArr as $k => $p):
                                $discount_count = $k; 
                                $total_discount += $p['part_discount'];
                                ?>
                                <tr>
                                    <td style="padding: 10px;"><?php echo ($k + 1) ?></td>
                                    <td style="padding: 10px;"><?php echo date($format['format'],strtotime($p['estimate_date'])); ?></td>
                                    <td style="padding: 10px;"><?php echo $p['estimate_id']; ?></td>
                                    <td style="padding: 10px;"><?php echo $p['full_name']; ?></td>
                                    <td style="padding: 10px;"><?php print_r(number_format((float)$p['part_discount'], 2, '.', ''));?></td>
                                    <td style="padding: 10px;"><?php echo number_format((float) $p['part_amount'], 2, '.', '') ?></td>
                                </tr>
                                <?php
                            endforeach;
                            ?>
                            <tr>
                                <td colspan="4" style="text-align: right;padding:5px"><b>Total Part Net Sales</b>&nbsp;&nbsp;</td>
                                <td><?php echo $cur . number_format((float) $total_discount, 2, '.','') ?></td>
                                <!-- <td><?php echo $cur . number_format((float) array_sum($total_discount), 2, '.','') ?></td> -->
                                <td><?php echo $cur . number_format((float) array_sum(array_column($dataArr, 'part_amount')), 2, '.','') ?></td>
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