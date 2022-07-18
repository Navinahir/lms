<script src="assets/ckeditor/ckeditor.js"></script>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Package</li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <?php $this->load->view('alert_view'); ?>
        <div class="col-md-4" id="year_form_row">
            <?php if (isset($dataArr) && !empty($dataArr)): ?>
                <div class="panel panel-flat">
                    <div class="panel-heading" style="background-color: #26A69A;border-color: #26A69A; color: #fff;padding: 10px;font-weight:bold">
                        <h3 class="panel-title text-center"><?php echo $dataArr['name'] ?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="media">
                            <div class="media-body">
                                <div class="text-center" style="text-align:center;margin:10px">
                                    <?php
                                    $amount = '';
                                    
                                    if (isset($dataArr) && !empty($dataArr)):
                                        $on = 'Discount on :';
                                        if ($dataArr['amount_off'] == 0 && $dataArr['percent_off'] == 0):
                                            $on = 'No Discount Amount';
                                            $amount = '---';
                                        elseif ($dataArr['percent_off'] != 0):
                                            $amount = "<strong>" . $dataArr['percent_off'] . "%</strong>";
                                            $on.=' Pecentage is ';
                                        else:
                                            $amount .= '<strong>$' . $dataArr['amount_off'] . '</strong>';
                                            $on.=' Amount is ';
                                        endif;
                                    endif;
                                    ?>
                                </div>
                                <h3><?php echo $on; ?><?php echo $amount ?></h3>
                                <h5><strong>Valid for No. of Months : </strong><?php echo $dataArr['no_of_months'] ?><?php echo($dataArr['no_of_months'] == 1) ? ' Month' : ' Months' ?></h5>
                                <h5><strong>Expiry on : </strong><?php echo $dataArr['expiry_date'] ?></h5>
                                <h5><strong>Description : </strong><?php echo $dataArr['description'] ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>