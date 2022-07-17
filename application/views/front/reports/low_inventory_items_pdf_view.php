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
                            <h2>Report For Low Inventory Items</h2>
                        </div>
                    </div>

                    <div class="row">
                    </div>
                </div>
                <?php $cur = (isset($currency) && !empty($currency)) ? $currency['symbol'] : '$'; ?>

                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="margin:0 20px;">
                        <thead>
                            <tr>
                                <th style="padding: 7px;text-align:center;background-color: #313335;color:#fff;">#</th>
                                <th style="padding: 7px;text-align:left;background-color: #313335;color:#fff;">Parts</th>
                                <th class="col-sm-1 th-center" style="padding: 7px;text-align:center;background-color: #313335;color:#fff;">Department</th>
                                <th class="col-sm-1 th-center" style="padding: 7px;text-align:center;background-color: #313335;color:#fff;">Vendor</th>
                                <th class="col-sm-1 th-center" style="padding: 7px;text-align:center;background-color: #313335;color:#fff;">Cost</th>
                                <th class="col-sm-1 th-center" style="padding: 7px;text-align:center;background-color: #313335;color:#fff;">Availability</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($dataArr as $k => $p):
                                ?>
                                <tr>
                                    <td width="3%"><?php echo ($k + 1) ?></td>
                                    <td width="30%" style="padding:7px;">
                                        <table style="width:100%;border-collapse: collapse;">
                                            <tbody>
                                                <tr>
                                                    <td class="media-body text-left" style="border:none; width:80%;">
                                                        <p class="display-inline-block text-default letter-icon-title" style="color:#313335;"><?php echo ($p['global_part_no'] != null) ? "<b>Global Part No: </b>" . $p['global_part_no'] : '<b>Non Global Part</b>' ?></p>
                                                        <div class="text-muted text-size-small"><span class="status-mark border-success position-left"></span><b>Part No: </b><?php echo $p['part_no'] ?></div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td><?php echo $p['dept_name']; ?></td>
                                    <td><?php echo $p['pref_vendor_name']; ?></td>
                                    <td><?php echo $cur . '' . number_format((float) $p['retail_price'], 2, '.', ''); ?></td>
                                    <td><?php echo ($p['total_quantity'] == 0) ? "Out of stock" : $p['total_quantity'] ?></td>
                                </tr>
                                <?php
                            endforeach;
                            ?>
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
