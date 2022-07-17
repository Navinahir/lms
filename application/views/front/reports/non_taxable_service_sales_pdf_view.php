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
                            <h3>Report For Non Taxable Service Sales From <?php echo $from_date; ?> To <?php echo $to_date; ?></h3>
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
                                <th style="padding: 10px;text-align:center;background-color: #313335;color:#fff;">Invoice No</th>
                                <th style="padding: 10px;text-align:center;background-color: #313335;color:#fff;">Service</th>
                                <th style="padding: 10px;text-align:center;background-color: #313335;color:#fff;">Quantity</th>
                                <th style="padding: 10px;text-align:center;background-color: #313335;color:#fff;">Discount</th>
                                <th style="padding: 10px;text-align:center;background-color: #313335;color:#fff;">Tax</th>
                                <th style="padding: 10px;text-align:center;background-color: #313335;color:#fff;">Rate</th>
                                <th style="padding: 10px;text-align:center;background-color: #313335;color:#fff;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($dataArr as $k => $p):
                                ?>
                                <tr>
                                    <td style="padding: 10px;"><?php echo ($k + 1) ?></td>
                                    <td style="padding: 10px;"><?php echo $p['estimate_id']; ?></td>
                                    <td style="padding: 10px;"><?php echo $p['name']; ?></td>
                                    <td style="padding: 10px;"><?php echo $p['qty']; ?></td>
                                    <td style="padding: 10px;"><?php echo ($p['discount_type_id'] == 'p') ? $p['discount']. '%' : $cur . '' . $p['discount']; ?></td>
                                    <td style="padding: 10px;"><?php echo $cur . number_format((float) $p['tax_rate'], 2, '.', '') ?></td>
                                    <td style="padding: 10px;"><?php echo $cur . number_format((float) $p['rate'], 2, '.', '') ?></td>
                                    <td style="padding: 10px;"><?php echo $cur . number_format((float) $p['amount'], 2, '.', '') ?></td>
                                </tr>
                                <?php
                            endforeach;
                            ?>
                            <tr>
                                <td colspan="3" style="text-align: right;padding:5px"><b>Total Non Taxable Service Sales</b>&nbsp;&nbsp;</td>
                                <td><?php echo array_sum(array_column($dataArr, 'qty')) ?></td>
                                <td></td>
                                <td><?php echo $cur . number_format((float) array_sum(array_column($dataArr, 'tax_rate')), 2, '.','') ?></td>
                                <td><?php echo $cur . number_format((float) array_sum(array_column($dataArr, 'rate')), 2, '.','') ?></td>
                                <td><?php echo $cur . number_format((float) array_sum(array_column($dataArr, 'amount')), 2, '.','') ?></td>
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