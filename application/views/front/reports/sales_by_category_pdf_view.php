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
                            <h2>Report For Sales By Category</h2>
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
                                <th class="col-sm-1 th-center" style="padding: 7px;text-align:center;background-color: #313335;color:#fff;">Category</th>
                                <th class="col-sm-1 th-center" style="padding: 7px;text-align:center;background-color: #313335;color:#fff;">Completed Invoice</th>
                                <th class="col-sm-1 th-center" style="padding: 7px;text-align:center;background-color: #313335;color:#fff;">Total Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($dataArr as $k => $p):
                                ?>
                                <tr>
                                    <td width="3%"><?php echo ($k + 1) ?></td>
                                    <td width="50%" style="padding:7px;">
                                        <table style="width:100%;border-collapse: collapse;">
                                            <tbody>
                                                <tr>
                                                    <td class="media-body text-left" style="border:none; width:80%;">
                                                        <p class="display-inline-block text-default letter-icon-title" style="color:#313335;"><b>Name: </b><?php echo $p['cat_name'] ?></p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td><?php echo $p['total']; ?></td>
                                    <td><?php echo $cur . number_format((float) $p['estimate_rate'], 2, '.', '') ?></td>
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
