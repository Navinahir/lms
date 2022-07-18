<script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.date.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/legacy.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/inputs/formatter.min.js"></script>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('invoices') ?>">Invoices</a></li>
            <li class="active">View</li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>
<?php
$edit = 1;
$center_class = 'div-center';
$right_class = 'div-right';
if (checkUserLogin('R') != 4) {
    $controller = $this->router->fetch_class();
    if (!empty(MY_Controller::$access_method) && array_key_exists('edit', MY_Controller::$access_method[$controller])) {
        $edit = 1;
        if (($edit = 1) && (isset($estimate) && !empty($estimate)) && $estimate['is_sent'] == 0) {
            $center_class = 'div-center';
            $right_class = 'div-right';
        } else {
            $center_class = 'div-non-center';
            $right_class = 'div-non-right';
        }
    } else {
        $edit = 0;
        $center_class = 'div-non-center';
        $right_class = 'div-non-right';
    }
} else {
    if ((isset($estimate) && !empty($estimate)) && $estimate['is_sent'] == 1) {
        $center_class = 'div-non-center';
        $right_class = 'div-non-right';
    }
}
?>
<div class="content">
    <?php $this->load->view('alert_view'); ?>
    <?php if (isset($estimate) && !empty($estimate)): ?>
        <div class="panel panel-white">
            <div class="panel-heading pt-20 div-main">
                <div class="div-left">
                    <button  type="button" onclick="window.location.href = '<?php echo base_url() . 'invoices' ?>'" class="btn btn-default btn-xs heading-btn btn-back"><i class="icon-arrow-left52 position-left"></i> Back</button>
                </div>
                <div class="<?php echo $center_class ?>">
                    <h6 class="panel-title text-center text-uppercase text-semibold">Invoice Details</h6>
                </div>
                <div class="<?php echo $right_class ?>">
                    <?php if ($edit == 1) { ?>
                        <button  type="button" onclick="window.location.href = '<?php echo base_url() . 'invoices/edit/' . base64_encode($estimate['id']) ?>'" class ="btn btn-default btn-xs heading-btn btn-edit mt-1 mb-1 mt-lg-0 mb-lg-0"><i class="icon-pencil position-left"></i> Edit</button>
                    <?php } ?>
                    <!-- <button type="button" onclick="window.location.href = '<?php echo base_url() . 'invoices/send_pdf/' . base64_encode($estimate['id']) ?>'" class="btn btn-default btn-xs heading-btn"><i class="icon-file-check position-left"></i> Send</button>-->
                    <!-- <button type="button" onclick="window.location.href = '<?php echo base_url() . 'invoices/print_pdf/' . base64_encode($estimate['id']) ?>'" class="btn btn-default btn-xs heading-btn"><i class="icon-printer position-left"></i> Print</button> -->

                    <a target="_blanck" href="<?php echo base_url() . 'invoices/print_pdf/' . base64_encode($estimate['id']) ?>" class="btn btn-default btn-xs heading-btn mt-1 mb-1 mt-lg-0 mb-lg-0"><i class="icon-printer position-left"></i> Print</a>
                
                </div>
            </div>
            <div class="panel-body no-padding-bottom">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-lg-4 content-group">
                        <?php
                        if (isset($UserInfo) && !empty($UserInfo['profile_pic'])) {
                            $image = base_url('uploads/profile') . '/' . $UserInfo['profile_pic'];
                        } else {
                            $image = 'assets/images/ark_logo1.png';
                        }
                        ?>
                        <img src="<?php echo $image ?>" class="content-group mt-10" alt="" style="width: 200px;">
                        <!--                        <ul class="list-condensed list-unstyled">
                                                    <li class="text-bold">steven@arksecurity.com</li>
                                                    <li>47 E Main st</li>
                                                    <li>Rexburg 83440</li>
                                                    <li>U.S.A</li>
                                                </ul>-->

                        <div tyle="width:100%;display:block;">
                            <span class="text-muted">Invoice To:</span>
                            <ul class="list-condensed list-unstyled">
                                <li style="font-weight: bold"><?php echo $estimate['cust_name'] ?></li>
                                <?php
                                if ($estimate['address'] != '') {
                                    echo "<li>" . $estimate['address'] . " </li>";
                                }
                                if ($estimate['email'] != '') {
                                    echo "<li>" . str_replace(',', ',<br>', $estimate['email']) . " </li>";
                                }
                                if ($estimate['phone_number'] != '') {
                                    echo "<li>" . $estimate['phone_number'] . " </li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                   <!--  <div class="col-sm-2 content-group content-group-right">
                    </div> -->
                    <div class="col-sm-12 col-md-8 col-lg-8 content-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="invoice-details invoice-detail-right">
                                    <h1 class="text-uppercase text-semibold" >Invoice</h1>
                                    <h5 class="text-uppercase text-semibold"># <?php echo $estimate['estimate_id']; ?></h5>
                                </div>
                            </div>
                            <div class='col-md-6 col-lg-12 col-xs-12  content-group estimate-details'>
                                <ul class="list-condensed list-unstyled invoice-payment-details">
                                    <li class="text-semibold li_right">Invoice Date <span class="text-right text-semibold"><?= date($format['format'], strtotime($estimate['estimate_date'])) ?></span></li>
                                    <?php
                                    if ($estimate['expiry_date'] != '') {
                                        echo "<li class='text-semibold li_right'>Due Date <span>" . date($format['format'], strtotime($estimate['expiry_date'])) . "</span></li>";
                                    }
                                    if ($estimate['full_name'] != '') {
                                        echo "<li class='text-semibold li_right'>Representative <span>" . $estimate['full_name'] . "</span> </li>";
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $cur = (isset($currency) && !empty($currency)) ? $currency['symbol'] : '$'; ?>

            <?php if($estimate['make_name'] != "" || $estimate['modal_name'] != "" || $estimate['year_name'] != "" || $estimate['color_name'] != "" || $estimate['vin_id'] != "" || $estimate['lic_plate_id'] != "" || $estimate['po_number'] != "" || $estimate['stock'] != "" || $estimate['work_order'] != "" || $estimate['reference'] != "" || $estimate['tracking'] != "") { ?>
                <hr style="margin: 10px 0;">
            <?php } ?>

            <div class="row">
                <div class="col-md-12">
                    <?php
                    if($estimate['make_name'] != '' || $estimate['modal_name'] != '' || $estimate['year_name'] != '') 
                    {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?php if($estimate['make_name'] != '') { ?>
                                    <div class="col-xs-12 col-sm-12 col-md-4">
                                        <div class="form-group has-feedback select_form_group">
                                            <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>Make</strong></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                                <div class="estimates-details"><?php echo $estimate['make_name']; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if($estimate['modal_name'] != '') { ?>
                                    <div class="col-xs-12 col-sm-12 col-md-4">
                                        <div class="form-group has-feedback select_form_group">
                                            <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>Model</strong></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                                <div class="estimates-details"><?php echo $estimate['modal_name']; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if($estimate['year_name'] != '') { ?>
                                    <div class="col-xs-12 col-sm-12 col-md-4">
                                        <div class="form-group has-feedback select_form_group">
                                            <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>Year</strong></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                                <div class="estimates-details"><?php echo $estimate['year_name']; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php
                    $est_checked_field = '';
                    if($vehicleArr['invoice_field'] != "")
                    {
                        $est_checked_field = explode(',', $vehicleArr['invoice_field']);
                    }
                    if($est_checked_field != "") { 
                        if(isset($fieldArr) && !empty($fieldArr)){
                            foreach ($fieldArr as $key => $fieldvalue) {
                                if(in_array($fieldvalue['id'],$est_checked_field)){
                                ?>                                    
                                    <?php if($fieldvalue['field_name'] == "Color") { 
                                            if($estimate['color_name'] != '') {
                                        ?>
                                        <div class="col-xs-12 col-sm-12 col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>Color</strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                                    <div class="estimates-details"><?php echo $estimate['color_name'];?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } } ?>

                                    <?php if($fieldvalue['field_name'] == "VIN#") { 
                                            if($estimate['vin_id'] != '') {
                                        ?>
                                        <div class="col-xs-12 col-sm-12 col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>VIN#</strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                                    <div class="estimates-details"><?php  echo $estimate['vin_id']; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } } ?>

                                    <?php if($fieldvalue['field_name'] == "License plate#") { 
                                            if($estimate['lic_plate_id'] != '') {
                                        ?>
                                        <div class="col-xs-12 col-sm-12 col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>License plate#</strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                                    <div class="estimates-details"><?php  echo $estimate['lic_plate_id']; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } } ?>

                                    <?php if($fieldvalue['field_name'] == "PO#") {
                                            if($estimate['po_number'] != '') { 
                                        ?>
                                            <div class="col-xs-12 col-sm-12 col-md-4">
                                                <div class="form-group has-feedback select_form_group">
                                                    <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>PO#</strong></label>
                                                    <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                                        <div class="estimates-details"><?php echo $estimate['po_number']; ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php } } ?>

                                    <?php if($fieldvalue['field_name'] == "Stock#") { 
                                            if($estimate['stock'] != '') { 
                                        ?>
                                        <div class="col-xs-12 col-sm-12 col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>Stock#</strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                                   <div class="estimates-details"> <?php echo $estimate['stock']; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } } ?>

                                    <?php if($fieldvalue['field_name'] == "Work Order#") { 
                                            if($estimate['work_order'] != '') { 
                                        ?>
                                        <div class="col-xs-12 col-sm-12 col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>Work Order#</strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                                    <div class="estimates-details"><?php echo $estimate['work_order']; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } } ?>

                                    <?php if($fieldvalue['field_name'] == "Reference#") { 
                                            if($estimate['reference'] != '') { 
                                        ?>
                                        <div class="col-xs-12 col-sm-12 col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>Reference#</strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                                    <div class="estimates-details"><?php echo $estimate['reference']; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } } ?>

                                    <?php if($fieldvalue['field_name'] == "Tracking#") { 
                                        if($estimate['tracking'] != '') {
                                        ?>
                                        <div class="col-xs-12 col-sm-12 col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>Tracking#</strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                                    <div class="estimates-details"><?php  echo $estimate['tracking']; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } } ?>

                                <?php 
                                }
                            }
                        } 
                    } else { 
                        ?>

                    <?php if($estimate['color_name'] != '') { ?>
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group has-feedback select_form_group">
                                    <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>Color</strong></label>
                                    <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                        <div class="estimates-details"><?php echo $estimate['color_name'];?></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if($estimate['vin_id'] != '') { ?>
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group has-feedback select_form_group">
                                    <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>VIN#</strong></label>
                                    <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                        <div class="estimates-details"><?php  echo $estimate['vin_id']; ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if($estimate['lic_plate_id'] != '') { ?>
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group has-feedback select_form_group">
                                    <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>License plate#</strong></label>
                                    <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                        <div class="estimates-details"><?php  echo $estimate['lic_plate_id']; ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if($estimate['po_number'] != '') { ?>
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group has-feedback select_form_group">
                                    <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>PO#</strong></label>
                                    <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                        <div class="estimates-details"><?php echo $estimate['po_number']; ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if($estimate['stock'] != '') { ?>
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group has-feedback select_form_group">
                                    <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>Stock#</strong></label>
                                    <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                       <div class="estimates-details"> <?php echo $estimate['stock']; ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if($estimate['work_order'] != '') { ?>
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group has-feedback select_form_group">
                                    <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>Work Order#</strong></label>
                                    <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                        <div class="estimates-details"><?php echo $estimate['work_order']; ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if($estimate['reference'] != '') { ?>
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group has-feedback select_form_group">
                                    <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>Reference#</strong></label>
                                    <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                        <div class="estimates-details"><?php echo $estimate['reference']; ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if($estimate['tracking'] != '') { ?>
                            <div class="col-xs-12 col-sm-12 col-md-4">
                                <div class="form-group has-feedback select_form_group">
                                    <label class="col-xs-4 col-sm-4 col-md-4 control-label"><strong>Tracking#</strong></label>
                                    <div class="col-xs-8 col-sm-8 col-md-8" style="text-align: right;">
                                        <div class="estimates-details"><?php  echo $estimate['tracking']; ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        
                    <?php } ?>    
                </div>
            </div>
            
            <?php if($estimate['make_name'] != "" || $estimate['modal_name'] != "" || $estimate['year_name'] != "" || $estimate['color_name'] != "" || $estimate['vin_id'] != "" || $estimate['lic_plate_id'] != "" || $estimate['po_number'] != "" || $estimate['stock'] != "" || $estimate['work_order'] != "" || $estimate['reference'] != "" || $estimate['tracking'] != "") { ?>
                <hr style="margin: 10px 0;">
            <?php } ?>

            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <?php
                    $dis_part_colspan = 2;
                    foreach ($estimate['parts'] as $k => $p):
                        if($p['discount_rate'] !=  0 && $p['discount_rate'] !=  "" && $p['discount_rate'] !=  null)
                        { 
                            $dis_part_colspan = 0;
                        } 
                    endforeach; 
                    
                    $tax_part_colspan = 3;
                    foreach ($estimate['parts'] as $k => $p):
                        if($p['individual_part_tax'] !=  "" && $p['individual_part_tax'] !=  null) 
                        { 
                            $tax_part_colspan = 0;
                        }
                    endforeach;

                    $tax = 0.00;
                    if (isset($estimate['parts']) && !empty($estimate['parts'])):
                        ?>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Location</th>
                                <th class="text-center" style="text-align:left;">Parts</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center" colspan="<?php echo $dis_part_colspan; ?>">Rate</th>
                                <?php if($dis_part_colspan == 0) { ?>
                                    <th class="text-center">Discount</th>
                                <?php } ?>
                                <?php if($tax_part_colspan == 0) { ?>
                                    <th class="text-center" colspan="2">Tax</th>
                                <?php } ?>
                                <th class="text-center" colspan="<?php echo $tax_part_colspan; ?>">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($estimate['parts'] as $k => $p):
                                ?>
                                <tr>
                                    <td style="border-bottom:1px solid #ddd;"><?php echo ($k + 1) ?></td>
                                    <td width="15%" style="border-bottom:1px solid #ddd;"><?php echo $p['location_name']; ?></td>
                                    <td width="35%" style="border-bottom:1px solid #ddd;">
                                        <div class="media-left media-middle">
                                            <?php if($p['global_part_image'] != '' && $p['global_part_image'] != null) { ?>
                                            <img src="<?php echo ($p['global_part_image'] != '') ? base_url() . ITEMS_IMAGE_PATH . '/' . $p['global_part_image'] : base_url() . ITEMS_IMAGE_PATH . "/no_image.jpg" ?>" style="height:40px;width:50px;max-width:60px;min-width:unset;display:block;"/>
                                            <?php } else if($p['image'] != '' && $p['image'] != null) { ?>
                                                <img src="<?php echo ($p['image'] != '') ? base_url() . ITEMS_IMAGE_PATH . '/' . $p['image'] : base_url() . ITEMS_IMAGE_PATH . "/no_image.jpg" ?>" style="height:40px;width:50px;max-width:60px;min-width:unset;display:block;"/>
                                            <?php } else { ?>
                                                <img style="height:40px;width:50px;max-width:60px;min-width:unset;display:block;" src="<?php echo base_url() . ITEMS_IMAGE_PATH . "/no_image.jpg" ?>"/>
                                            <?php }  ?>
                                        </div>

                                        <div class="media-body text-left">
                                            <?php echo $p['part_no']; ?>
                                            <div class="text-muted text-size-small">
                                                <span class="status-mark border-success position-left"></span> 
                                                <?php echo $p['description'] ?>
                                            </div>
                                            <?php if($p['item_note'] != "" && $p['item_note'] != null) { ?>
                                                <div class="text-muted text-size-small">
                                                    <span class="status-mark border-primary position-left"></span> <?php echo $p['item_note'] ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </td>
                                    <td style="border-bottom:1px solid #ddd;"><?php echo $p['quantity']; ?></td>
                                    <td style="border-bottom:1px solid #ddd;" colspan="<?php echo $dis_part_colspan; ?>"><?php echo number_format((float) $p['rate'], 2, '.', ''); ?></td>
                                    <?php if($p['discount_rate'] > 0) { ?>
                                        <td style="border-bottom:1px solid #ddd;"><?php echo ($p['discount_type_id'] == 'p') ? $p['discount']. '%' : $cur . '' . $p['discount']; ?></td>
                                    <?php } else if($dis_part_colspan == 0) { ?>
                                        <td style="border-bottom:1px solid #ddd;">---</td>
                                    <?php } else { ?>
                                    
                                    <?php } ?>
                                    <?php if($p['individual_part_tax'] !=  "" && $p['individual_part_tax'] !=  null) { 
                                        ?>
                                        <td colspan="2" style="border-bottom:1px solid #ddd;">                             
                                        <select class="select select-size-sm select-tax-data" multiple="multiple" disabled>
                                        <?php
                                            if (isset($taxes) && !empty($taxes)) {
                                                foreach ($taxes as $k => $val) {
                                                $tax_selected_id = explode(',', $p['tax_id']);
                                                $selected = '';
                                                $selected = (in_array($val['id'], $tax_selected_id)) ? 'selected' : '';
                                                ?>
                                                <option value="<?php echo $val['id']; ?>" data-id="<?php echo $val["rate"]; ?>" <?php echo $selected; ?>><?php echo $val['name'] . ' ( ' . $val["rate"] . '%)'; ?></option>
                                                    <?php
                                                }
                                            } 
                                            ?> 
                                        </select>
                                        <?php  if ($p['tax_id'] != 0) { ?>
                                        <span class="text-semibold" style="float: right;">
                                            <?php
                                                $tax = ($tax + number_format((float) $p['tax_rate'], 2, '.', ''));
                                                echo number_format((float) $p['tax_rate'], 2, '.', '');
                                            ?>
                                        </span>
                                        <?php } else { ?> 
                                            <span class="text-semibold" style="float: right;">0.00</span>
                                        <?php } ?>
                                        </td>
                                    <?php } else if($tax_part_colspan == 3) { ?>

                                    <?php } else { ?>
                                        <td colspan="2" style="border-bottom:1px solid #ddd;">---</td>
                                    <?php } ?>
                                    <td style="border-bottom:1px solid #ddd;" colspan="<?php echo $tax_part_colspan; ?>"><span class="text-semibold"><?php echo number_format((float) $p['amount'], 2, '.', ''); ?></span></td>
                                </tr>
                                <?php
                            endforeach;
                            ?>
                        </tbody>
                        <?php
                    endif;

                    $dis_srv_colspan = 2;
                    foreach ($estimate['services'] as $k => $p):
                       if($p['discount_rate'] !=  0 && $p['discount_rate'] !=  "" && $p['discount_rate'] !=  null)
                        { 
                            $dis_srv_colspan = 0;
                        } 
                    endforeach; 
                    
                    $tax_srv_colspan = 3;
                    foreach ($estimate['services'] as $k => $p):
                        if($p['individual_service_tax'] !=  "" && $p['individual_service_tax'] !=  null) 
                        { 
                            $tax_srv_colspan = 0;
                        }
                    endforeach; 

                    $s_tax = 0.00;
                    if (isset($estimate['services']) && !empty($estimate['services'])):
                        ?>
                        <thead>
                            <?php 
                            if (isset($estimate['parts']) && !empty($estimate['parts'])){
                            ?>
                            <tr>
                                <td colspan="5" style="padding-top:50px; border:0; border-spacing:0;"></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <th class="text-center">#</th>
                                <th colspan="2" class="text-center" style="text-align:left;">Service</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center" colspan="<?php echo $dis_srv_colspan; ?>">Rate</th>
                                <?php if($dis_srv_colspan == 0) { ?>
                                    <th class="text-center">Discount</th>
                                <?php } ?>
                                <?php if($tax_srv_colspan == 0) { ?>
                                    <th class="text-center" colspan="2">Tax</th>
                                <?php } ?>
                                <th class="text-center" colspan="<?php echo $tax_srv_colspan; ?>">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($estimate['services'] as $k => $p):
                                ?>

                                <tr>
                                    <td><?php echo ($k + 1) ?></td>
                                    <td colspan="2" class="<?php echo (isset($EstimateArr) && !empty($EstimateArr)) ? '' : 'no_parts' ?>" style="text-align:left;">
                                        <?php echo $p['service_name'] ?>
                                        <?php if($p['service_note'] != "" && $p['service_note'] != null) { ?>
                                            <div class="text-muted text-size-small">
                                                <span class="status-mark border-primary position-left"></span> 
                                                <?php echo $p['service_note'] ?>
                                            </div>
                                        <?php } ?>        
                                    </td>
                                    <td><?php echo $p['qty']; ?></td>
                                    <td colspan="<?php echo $dis_srv_colspan; ?>"><?php echo number_format((float) $p['rate'], 2, '.', ''); ?></td>
                                    <?php if($p['discount_rate'] > 0) { ?>
                                        <td><?php echo ($p['discount_type_id'] == 'p') ? $p['discount'] . '%' : $cur . '' . $p['discount']; ?></td>
                                    <?php } else if($dis_srv_colspan == 0) { ?>
                                        <td>---</td>
                                    <?php } else { ?>
                                    
                                    <?php } ?>
                                    
                                    <?php if($p['individual_service_tax'] !=  "" && $p['individual_service_tax'] !=  null) { 
                                        ?>
                                    <td colspan="2">
                                        <select data-placeholder="" class="select select-size-sm" multiple="multiple" disabled="">
                                        <?php
                                        if (isset($taxes) && !empty($taxes)) {
                                            foreach ($taxes as $k => $val) {
                                            $tax_selected_id = explode(',', $p['tax_id']);
                                            $selected = '';
                                            $selected = (in_array($val['id'], $tax_selected_id)) ? 'selected' : '';
                                            ?>
                                            <option value="<?php echo $val['id']; ?>" data-id="<?php echo $val["rate"]; ?>" <?php echo $selected; ?>><?php echo $val['name'] . ' ( ' . $val["rate"] . '%)'; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>    
                                        </select>
                                        <?php  if ($p['tax_id'] != 0) { ?>
                                        <span class="text-semibold" style="float: right;">
                                            <?php
                                                $tax = ($tax + number_format((float) $p['tax_rate'], 2, '.', ''));
                                                echo number_format((float) $p['tax_rate'], 2, '.', '');
                                            ?>
                                        </span>
                                        <?php } else { ?> 
                                            <span class="text-semibold" style="float: right;">0.00</span>
                                        <?php } ?>
                                    </td>
                                    <?php } else if($tax_srv_colspan == 3) { ?>

                                    <?php } else { ?>
                                        <td colspan="2">---</td>
                                    <?php } ?>
                                    <td  colspan="<?php echo $tax_srv_colspan; ?>"><span class="text-semibold"><?php echo number_format((float) $p['amount'], 2, '.', ''); ?></span></td>
                                </tr>
                                <?php
                            endforeach;
                            ?>
                        </tbody>
                        <?php
                    endif;
                    ?>
                </table>
            </div>
            <div class="panel-body">
                <div class="row invoice-payment">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 invoice-notes">
                        <div class="note-boxs">
                            <?php if ($estimate['notes'] != '') { ?>
                                <h6>Notes</h6>
                                <p class="text-muted"><?php echo $estimate['notes'] ?></p>
                            <?php } ?>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-lg-6">
                                <?php if ($estimate['payment_method_id'] != 0) { ?>
                                <legend class='payment_details text-semibold'>Payment Details</legend>
                                <div class="row">
                                    <div class="form-group has-feedback select_form_group">
                                        <label class="col-md-2 control-label text-semibold">Method</label>
                                        <div class="col-md-10">
                                            <span style="float: right;"><?php echo $estimate['payment_name'] ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label text-semibold">Reference</label>
                                        <div class="col-md-10">
                                            <span style="float: right;"><?php echo $estimate['payment_reference'] ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="col-xs-12 col-lg-6">
                                <?php 
                                    if (!empty($estimate) && !empty($estimate['signature_attachment']) && file_exists(FCPATH . 'uploads/signatures/' . $estimate['signature_attachment'])) 
                                    {
                                        if(getimagesize(base_url('uploads/signatures/' . $estimate['signature_attachment'])))
                                    { ?>
                                    <div class="text-center signature_div">
                                        <div class="content-group">
                                            <img src="<?= site_url('uploads/signatures/' . $estimate['signature_attachment'] . '?=' . time()) ?>" alt="<?= $estimate['signature_attachment'] ?>" width="196px" height="86px" >
                                        </div>
                                    </div>
                                <?php } } ?>
                            </div>
                        </div>
                    </div>

                    <?php
                    if($estimate['parts'] != "")
                    {
                        $ind_part_tax = array_column($estimate['parts'],'individual_part_tax');
                        $ind_part_id = array_column($estimate['parts'],'tax_id');
                        $part_final_array = [];
                        
                        foreach($ind_part_id as $key=>$val){
                            if($val != 0)
                            {
                                $val = explode(",",$val);
                                $tax_val = explode("," , $ind_part_tax[$key]);
                                foreach($val as $k1=>$v1){
                                    if(array_key_exists($v1, $part_final_array)){
                                    $part_final_array[$v1] = $part_final_array[$v1] + $tax_val[$k1];
                                    } else {
                                        $part_final_array[$v1] = $tax_val[$k1];
                                    }
                                }
                            }
                        }
                    }

                    if($estimate['services'] != "")
                    {
                        $ind_srv_tax = array_column($estimate['services'],'individual_service_tax');
                        $ind_srv_id = array_column($estimate['services'],'tax_id');
                        $srv_final_array = [];
                        
                        foreach($ind_srv_id as $key=>$val){
                            if($val != 0)
                            {
                                $val = explode(",",$val);
                                $tax_val = explode("," , $ind_srv_tax[$key]);
                                foreach($val as $k1=>$v1){
                                    if(array_key_exists($v1, $srv_final_array)){
                                    $srv_final_array[$v1] = $srv_final_array[$v1] + $tax_val[$k1];
                                    } else {
                                        $srv_final_array[$v1] = $tax_val[$k1];
                                    }
                                }
                            }
                        }
                    }

                    $part_tax_possion = 'hide';
                    $service_tax_possion = 'hide';
                    $part_service_possion = 'hide';

                    if($part_final_array != "" && !empty($part_final_array))
                    {
                        $part_tax_possion = '';
                    }

                    if($srv_final_array != "" && !empty($srv_final_array))
                    {
                        $service_tax_possion = '';
                    }

                    if($part_final_array != "" && !empty($part_final_array) || $srv_final_array != "" && !empty($srv_final_array))
                    {
                        $part_service_possion = '';
                    }
                    ?>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 invoice-totaldue">
                        <div class="content-group">
                            <h6><b>Total due</b></h6>
                            <div class="table-responsive no-border">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th class="border-bottom"><b>Subtotal:</b></th>
                                            <td class="text-right"><?php echo number_format((float) $estimate['sub_total'], 2, '.', ''); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="table-tax-amount">
                                                <table class="table">
                                                    <tr>
                                                        <?php
                                                        if($part_final_array != "" && !empty($part_final_array))
                                                        {
                                                        ?>
                                                        <td class="text-left <?php echo $part_tax_possion; ?>">
                                                            <label class="text-semibold"><b>Part tax:</b></label>
                                                            <?php
                                                                $service_tax = $estimate['individual_service_tax'];
                                                                $print_tax = explode(",",$service_tax);
                                                                foreach ($part_final_array as $key => $value) {
                                                                    foreach ($taxes as $k => $v) {
                                                                        if($key == $v['id'])
                                                                        {
                                                                        ?>
                                                                    <div class="tax-filed-label">
                                                                        <span><?php echo $v['name'] . ' (' . $v["rate"] . '%)'; ?></span><br>
                                                                        <span class="ml-auto"><?php echo $value;?></span>
                                                                    </div>
                                                                    <?php
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                        <?php } ?>
                                                        <?php 
                                                        if($srv_final_array != "" && !empty($srv_final_array))
                                                        {
                                                        ?>
                                                            <td class="text-left <?php echo $service_tax_possion; ?>">
                                                                <label class="text-semibold"><b>Labor And Services tax:</b></label>
                                                                <?php
                                                                $service_tax = $estimate['individual_service_tax'];
                                                                $print_tax = explode(",",$service_tax);
                                                                foreach ($srv_final_array as $key => $value) {
                                                                    foreach ($taxes as $k => $v) {
                                                                        if($key == $v['id'])
                                                                        {
                                                                        ?>
                                                                    <div class="tax-filed-label">
                                                                        <span><?php echo $v['name'] . ' (' . $v["rate"] . '%)'; ?></span>
                                                                        <span class="ml-auto"><?php echo $value;?></span>
                                                                    </div>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            </td>
                                                        <input type="hidden" name="final_tax_rate" id="final_tax_rate">
                                                        <?php } ?>
                                                    </tr>
                                                </table>
                                            </td>                                                    
                                        </tr>           
                                        <tr>
                                            <th><b>Total Tax:</b></th>
                                            <td class="text-right"><?php echo number_format((float) ($tax + $s_tax), 2, '.', ''); ?></td>
                                        </tr>
                                        <?php
                                        if($estimate['shipping_display_status'] == 1)
                                        {
                                        ?>
                                        <tr>
                                            <th><b>Shipping Charge:</b></th>
                                            <td class="text-right"><?php echo number_format((float) $estimate['shipping_charge'], 2, '.', ''); ?></td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <th><b>Total (<span class='text-semibold'><?php echo $cur ?></span>):</b></th>
                                            <td class="text-right text-primary"><h5 class="text-semibold"><?php echo $cur . "" . number_format((float) $estimate['total'], 2, '.', ''); ?></h5></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-right">
                                <button type="button" class="btn btn-primary btn-labeled" onclick="window.location.href = '<?php echo base_url() . 'invoices/send_pdf/' . base64_encode($estimate['id']) ?>'"><b><i class="icon-paperplane"></i></b> Send Invoice</button>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($estimation_attachments) && sizeof($estimation_attachments) > 0) { ?>
                    <div class="row mb-20 mt-10">
                        <div class="col-md-12">
                            <legend><strong>Attachments:</strong></legend>
                            <?php
                            foreach ($estimation_attachments as $key => $file) {
                                if (file_exists('uploads/attachments/' . $file['file_name'])) {
                                    $file_url = base_url('/uploads/attachments/' . $file['file_name']);
                                    if ($file['type'] == 'Image') {
                                        ?> 
                                        <div class="col-sm-4" id="attachment_div_<?= $key ?>">
                                            <div class="attachment_div">
                                                <div class="img-wrap">
                                                    <a href="<?= $file_url . '?=' . time() ?>" data-popup="lightbox">
                                                        <img src="<?= $file_url . '?=' . time() ?>" class="attachment-img">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else if ($file['type'] == 'PDF') { ?>
                                        <div class="col-sm-4" id="attachment_div_<?= $key ?>">
                                            <div class="attachment_div">
                                                <div class="img-wrap">
                                                    <a href="<?= $file_url . '?=' . time() ?>" download>
                                                        <img src="<?= base_url('assets/images/pdf_icon.png') ?>" class="pdf-img">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php } ?>

                

                <h6>Terms & Conditions</h6>
                <span class="text-muted"><?php echo (isset($terms_condition) && $terms_condition != null) ? $terms_condition : "Your company's Terms and Conditions will be displayed here. You can add it in the Estimation Preference under Settings." ?></span>
            </div>
        </div>
    <?php endif; ?>
    <?php $this->load->view('Templates/footer.php'); ?>
</div>

<script>
    var date_format = '<?php echo (isset($format) && !empty($format)) ? $format['name'] : 'Y/m/d' ?>';
    var signature_attachment = '';
    var edit_pdf_preview = '';
    var invoice_notification = "<?= ($this->session->userdata('invoice_notification') ? $this->session->userdata('invoice_notification') : '0')  ?>"; 
    var item_notification = "<?= ($this->session->userdata('item_notification') ? $this->session->userdata('item_notification') : '0')  ?>";
    var segment = '<?php echo $this->uri->segment(3);?>';
    var print_preview_url_invoice = '';
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/invoice.js?version='<?php echo time();?>'"></script>
<style>
    .custom_save_button{
        width: 120px !important;
    }
    .save_draft{
        background-color: #1c2f3b!important;
    }
    .span_quantity{
        /*margin-right: 10px;*/
        font-size: x-small;
        font-weight: 500;
    }
    .plus{
        width: 50%;
        float: left;
    }
    select,select:focus {
        border-style: unset !important;
        outline: none !important;
        outline-color: none !important;
    }
    .input_discount{
        width:100px !important;
    }
    .li_right{
        font-size: 110%;
    }
    .li_right span{
        font-weight: 400 !important;       
    }
    .invoice-payment-details{
        /*text-align: center !important;*/
    }
    .th-center{
        text-align:center !important;
    }
    .div-main{
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
    }

    /*.div-non-center{
        display: inline;
        width: 80%;
    }*/
    .div-center h6,.div-non-center h6{
        font-size: x-large;
    }

    .estimate-details{
        float: right !important;
        max-width: 420px;
    }

    .no_parts{
        width: 60%;
    }

    .signature_div{border: 1px solid #e2e2d6;box-shadow: 3px 3px 3px #ededed;width: 100%;margin-top: 15px;}

    .attachment_div{border: 1px solid #e2e2d6;box-shadow: 0 0 8px 2px #ededed; text-align:center;margin-bottom: 15px;}
    .attachment-img{width: 182px !important;height: 182px !important;}
    .pdf-img{width: 139px !important;}
    .img-wrap {position: relative;}
    .img-wrap .close { position: absolute;top: 7px;right: 7px;z-index: 100;color: white;}

    @media(min-width:1940px){
        .estimate-details {width: 60%;}
        .invoice-details {float: none;}
    }
    @media(min-width:990px) and (max-width:1250px){
        .estimate-details{width: 100%;}
    }

    @media screen and (max-width:1199px){
        .invoice-payment{
            display: flex;
            flex-wrap: wrap;
        }
        .invoice-notes {
            order: 2;
        }
        /*.invoice-totaldue {
            order: 1;
        }*/
    }

    @media screen and (max-width:991px){
        .div-center h6, .div-non-center h6{
            font-size: 18px;
        }
        .invoice-payment-details h5{
            font-size: 14px;
        }
    }

    @media(max-width:990px){ 
        .div-right  button.btn.btn-default.btn-xs.heading-btn {margin-top: 5px;}
    }
    @media(max-width:900px){
        .content-group-right{width: 100%;}
        .invoice-details{float: left;text-align: left;}
        .li_right span {float: right;}
    }

    @media screen and (max-width:768px){
        .estimate-details {padding: 20px 10px 0px;}
        .panel .div-main {display: block;}
        .panel-heading .div-center h6,
        .panel-heading .div-non-center h6 {text-align: center !important;margin: 10px 0 !important;}
        .panel-heading .div-right,
        .panel-heading .div-non-right{
            position: absolute;
            top: 8px; 
            right: 15px;
        }
    }

    @media(max-width:767px) {
        .invoice-details{padding-right: 15px;}
    }

    @media screen and (max-width:575px){
        .li_right span{
            font-size: 13px;
            padding-left: 15px;
        }
    }

    @media screen and (max-width:359px){
        .panel-heading .div-center h6,
        .panel-heading .div-non-center h6{
            margin:10px 0px;
        }
        .panel-heading .div-right,
        .panel-heading .div-non-right{
            position: relative;
            text-align: center;
            top: 0px;
            right: 0px;
        }   
    }
</style>