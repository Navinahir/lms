<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

<script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.date.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/legacy.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/inputs/formatter.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/ui/fab.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/ui/prism.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/tags/tagsinput.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/tags/tokenfield.min.js"></script>
<script type="text/javascript" src="assets/js/pages/form_tags_input.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/inputs/typeahead/typeahead.bundle.min.js"></script>

<!-- timezone CDN -->
<!-- console moment.tz.guess(); -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.32/moment-timezone-with-data.min.js"></script>

<script src="<?php //echo base_url('node_modules/socket.io-client/dist/socket.io.js');?>"></script>

<?php
if (checkUserLogin('R') != 4) {
    $add = 0;
    $edit = 0;
    $controller = $this->router->fetch_class();
    if (!empty(MY_Controller::$access_method) && array_key_exists('add', MY_Controller::$access_method[$controller])) {
        $add = 1;
    }
    if (!empty(MY_Controller::$access_method) && array_key_exists('edit', MY_Controller::$access_method[$controller])) {
        $edit = 1;
    }
    if (isset($dataArr)) {
        if ($edit == 0) {
            echo $this->load->view('front/error403', null, true);
            die;
        }
    } else {
        if ($add == 0) {
            echo $this->load->view('front/error403', null, true);
            die;
        }
    }
}
$edit_div = 0;
if (isset($dataArr)) {
    $edit_div = 1;
    $form_action = site_url('estimates/edit/' . base64_encode($dataArr['id']));
} else {
    $form_action = site_url('estimates/add');
}
?>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('estimates') ?>">Estimates</a></li>
            <li class="active">
                <?php
                if (isset($dataArr)) {
                    echo "Edit";
                    $title = "Edit Estimate";
                } else {
                    echo "Add";
                    $title = "New Estimate";
                }
                ?>
            </li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="<?php echo $form_action; ?>" class="form-horizontal" id="add_estimate_form" enctype="multipart/form-data" >
                <?php $cur = (isset($currency) && !empty($currency)) ? $currency['symbol'] : '$'; ?>
                <div class="panel panel-body">
                    <?php $this->load->view('alert_view'); ?>
                    
                    <?php
                        if(!empty($dataArr)) {
                            if(!empty($dataArr['is_save_draft']) == 0){
                                $is_checked = 'checked';
                                $toggale_value = 1;
                            
                            }else {
                                $is_checked = '';
                                $toggale_value = 0;
                            }
                        }else{
                            $is_checked = 'checked';
                            $toggale_value = 1;
                        } 
                    ?> 
                    <h4 class="panel-title"><span class="text-primary"><?php echo $title ?></span>&nbsp;&nbsp;
                        <input type="checkbox" name="invoice_type" data-on="Estimate" data-off="Draft" id="invoice_type" data-toggle="toggle" class="btntoggle invoice_type" <?= $is_checked ?> value="<?= $toggale_value ?>">
                    </h4>
                    <legend class="text-bold mt-20">Customer Details</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <?php if (!empty($customers) && empty($dataArr)): ?>
                                    <div class="col-md-12">
                                        <!-- <div class="form-group has-feedback has-feedback-left"> -->
                                        <div class="form-group has-feedback">
                                            <label class="col-md-12 col-lg-3 control-label lb-w-150">Customer</label>
                                            <div class="col-md-12 col-lg-9 input-controlstyle-150"> 
                                                <select data-placeholder="Select a Customer" class="select-size-lg" name="customer_id" id='customer_id'>
                                                    <option></option>
                                                    <option value="">Select Customer</option>
                                                    <?php
                                                    foreach ($customers as $customer):
                                                        $selected = '';
                                                        if (!empty($dataArr)) {
                                                            if ($customer['id'] == $dataArr['customer_id']):
                                                                $selected = 'selected="selected"';
                                                            endif;
                                                        }
                                                        ?>
                                                        <option value="<?php echo $customer['id'] ?>" <?php echo $selected ?>><?= $customer['display_name_as']; ?></option>
                                                        <!-- <option value="<?php echo $customer['id'] ?>" <?php echo $selected ?>><?= $customer['first_name'] . ' ' . $customer['last_name'] ?></option> -->
                                                        <?php
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Customer Name</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_cust_name" id="txt_cust_name" value="<?php echo (isset($dataArr)) ? $dataArr['cust_name'] : set_value('txt_cust_name'); ?>">
                                            <?php /* echo '<label id="txt_cust_name_error2" class="validation-error-label" for="txt_cust_name">' . form_error('txt_cust_name') . '</label>'; */ ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Phone Number</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control format-phone-number" name="txt_phone_number" id="txt_phone_number" value="<?php echo (isset($dataArr)) ? $dataArr['phone_number'] : set_value('txt_phone_number'); ?>">
                                            <?php echo '<label id="txt_phone_number_error2" class="validation-error-label" for="txt_phone_number">' . form_error('txt_phone_number') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback" >
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Notes</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <textarea class="form-control" name="txt_notes" id="txt_notes" rows="3"><?php echo (isset($dataArr)) ? $dataArr['notes'] : set_value('txt_notes'); ?></textarea>
                                            <?php echo '<label id="txt_notes_error2" class="validation-error-label" for="txt_notes">' . form_error('txt_notes') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label class="col-md-12 col-lg-3 control-label lb-w-150 control-label email_required pl-lg-5">Email</label>
                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                    <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['email'] : set_value('txt_email'); ?>" class="form-control tags-input" id="multiple_email_list" name="txt_email">
                                    <!-- <select class="form-control select-size-lg" data-placeholder="Select Email" name="email_id" id='email_id' multiple required="required">
                                        <option></option>
                                    </select> -->
                                    <!-- <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['email'] : set_value('txt_email'); ?>" class="form-control" name="txt_email" id="txt_email"> -->
                                    <!-- <?php echo '<label id="txt_email_error2" class="validation-error-label" for="txt_email">' . form_error('txt_email') . '</label>'; ?> -->
                                    <span class="invalid_email_alert" style="color: red; display: none;">Invalid email format.</span>
                                </div>
                            </div>
                            <!-- <input type="text" name="email-tags" id="email-tags" /> -->
                            <div class="form-group has-feedback" >
                                <label class="col-md-12 col-lg-3 control-label lb-w-150 control-label pl-lg-5">Address</label>
                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                    <textarea class="form-control" name="txt_address" id="txt_address" rows="4"><?php echo (isset($dataArr)) ? $dataArr['address'] : set_value('txt_address'); ?></textarea>
                                    <?php echo '<label id="txt_address_error2" class="validation-error-label" for="txt_address">' . form_error('txt_address') . '</label>'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <legend class="text-bold mt-20">Other Details</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback" >
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Estimate#</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="hidden" value="<?php echo (isset($dataArr)) ? $dataArr['estimate_id'] : $estimate_id ?>" class="form-control" name="hidden_estimate_id" id="hidden_estimate_id">
                                            <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['estimate_id'] : $estimate_id ?>" class="form-control" name="estimate_id" id="estimate_id" disabled="disabled">
                                            <?php echo '<label id="estimate_id_error2" class="validation-error-label" for="estimate_id">' . form_error('estimate_id') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Estimate Date</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <div class="input-group esti-date-error">
                                                <span class="input-group-addon"><i class="icon-calendar5"></i></span>
                                                
                                                <input type="text" class="form-control estimate_date" placeholder="Estimate Date" name='estimate_date' data-value="<?php echo (isset($dataArr)) ? $dataArr['estimate_date'] : date('m/d/y') ?>" >

                                                <!-- <input type="text" class="form-control estimate_date" placeholder="Estimate Date" name='estimate_date' value="<?= (!empty($dataArr['estimate_date'])) ? date('m/d/y', strtotime($dataArr['estimate_date'])) : date('m/d/y') ?>" data-value="<?php echo (isset($dataArr)) ? $dataArr['estimate_date'] : date('m/d/y') ?>" > -->

                                            </div>
                                            <?php echo '<label id="txt_estimate_date_error2" class="validation-error-label" for="txt_estimate_date">' . form_error('txt_estimate_date') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="">
                                <!-- <div class="form-group has-feedback has-feedback-left"> -->
                                <div class="form-group has-feedback has-feedback-right">
                                    <label class="col-md-12 col-lg-3 control-label lb-w-150 required pl-lg-5">Representative</label>
                                    <div class="col-md-12 col-lg-9 input-controlstyle-150">

                                        <select data-placeholder="Select a Representative" class="select-size-lg" name="sales_person" id='sales_person'>
                                            <option></option>
                                            <optgroup label="Representative">
                                                <?php
                                                if (isset($users) && !empty($users)):
                                                    foreach ($users as $u):
                                                        $selected = '';
                                                        if (isset($dataArr)) {
                                                            if ($u['id'] == $dataArr['sales_person']):
                                                                $selected = 'selected="selected"';
                                                            endif;
                                                        }
                                                        ?>
                                                        <option value="<?php echo $u['id'] ?>" <?php echo $selected ?>><?php echo $u['full_name'] ?></option>
                                                        <?php
                                                    endforeach;
                                                endif;
                                                ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group has-feedback">
                                    <label class="col-md-12 col-lg-3 control-label lb-w-150 pl-lg-5">Expiry Date</label>
                                    <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="icon-calendar5"></i></span>
                                            <input type="text" class="form-control expiry_date" placeholder="Expiry Date" name='expiry_date' data-value="<?php echo (isset($dataArr)) ? $dataArr['expiry_date'] : '' ?>" >
                                        </div>
                                        <span class="expiry_date_alert" style="color: red; display: none;">Please select an expiry date greater than or equal to the estimate date.</span>
                                        <?php echo '<label id="expiry_date_error2" class="validation-error-label" for="expiry_date">' . form_error('expiry_date') . '</label>'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <legend class="text-bold mt-20">Vehicle Details</legend>
                    <div class="col-sm-12">
                    <div class="row">
                            <?php
                            $est_checked_field = '';
                            if($vehicleArr['estimate_field'] != "")
                            {
                                $est_checked_field = explode(',', $vehicleArr['estimate_field']);
                            }

                            if($est_checked_field == "" && $vehicleArr['is_edited'] == 1) {
                            ?>
                                <div class="col-md-4">
                                    <div class="form-group has-feedback select_form_group">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Make</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <select data-placeholder="Select a Company..." class="select select-size-sm" id="txt_make_name" name="txt_make_name">
                                                <option></option>
                                                <?php
                                                foreach ($companyArr as $k => $v) {
                                                    ?>
                                                    <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group has-feedback select_form_group">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Model</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <select data-placeholder="Select a Model..." class="select select-size-sm" id="txt_model_name" name="txt_model_name">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group has-feedback select_form_group">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Year</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <select data-placeholder="Select a Year..." class="select select-size-sm" id="txt_year_name" name="txt_year_name">
                                                <option></option>
                                                <?php
                                                if($itemArr_multi['items_list'] != "")
                                                {
                                                    $year_multiple = array_column($itemArr_multi['items_list'],'item_year_id')[0];
                                                }
                                                foreach ($yearArr as $k => $v) {
                                                    $selected = '';

                                                    if (isset($dataArr['year_id']) && $v['id'] == $dataArr['year_id']):
                                                        $selected = 'selected="selected"';
                                                    else:
                                                        if (isset($itemArr) && !empty($itemArr)) {
                                                            if (isset($itemArr['item_year_id']) && $v['id'] == $itemArr['item_year_id']) {
                                                                $selected = 'selected="selected"';
                                                            }
                                                        }
                                                        if (isset($year_multiple) && !empty($year_multiple)) {
                                                            if (isset($year_multiple) && $v['id'] == $year_multiple) {
                                                                $selected = 'selected="selected"';
                                                            }
                                                        }
                                                    endif;
                                                    ?>
                                                    <option value="<?php echo $v['id']; ?>" <?php echo $selected; ?>><?php echo $v['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php } else if($est_checked_field != "" && $vehicleArr['is_edited'] == 1) { ?>
                                <!-- <div class="row"> -->
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback select_form_group">
                                            <label class="col-md-12 col-lg-3 control-label lb-w-150">Make</label>
                                            <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                <select data-placeholder="Select a Company..." class="select select-size-sm" id="txt_make_name" name="txt_make_name">
                                                    <option></option>
                                                    <?php
                                                    foreach ($companyArr as $k => $v) {
                                                        ?>
                                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback select_form_group">
                                            <label class="col-md-12 col-lg-3 control-label lb-w-150">Model</label>
                                            <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                <select data-placeholder="Select a Model..." class="select select-size-sm" id="txt_model_name" name="txt_model_name">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" class="drop_status" value="<?php echo $drop_status; ?>">
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback select_form_group">
                                            <label class="col-md-12 col-lg-3 control-label lb-w-150">Year</label>
                                            <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                <select data-placeholder="Select a Year..." class="select select-size-sm" id="txt_year_name" name="txt_year_name">
                                                    <option></option>
                                                    <?php
                                                    if($itemArr_multi['items_list'] != "")
                                                    {
                                                        $year_multiple = array_column($itemArr_multi['items_list'],'item_year_id')[0];
                                                    }
                                                    foreach ($yearArr as $k => $v) {
                                                        $selected = '';

                                                        if (isset($dataArr['year_id']) && $v['id'] == $dataArr['year_id']):
                                                            $selected = 'selected="selected"';
                                                        else:
                                                            if($drop_status != 1)
                                                            {
                                                                if (isset($itemArr) && !empty($itemArr)) {
                                                                    if (isset($itemArr['item_year_id']) && $v['id'] == $itemArr['item_year_id']) {
                                                                        $selected = 'selected="selected"';
                                                                    }
                                                                }
                                                            }
                                                            if (isset($year_multiple) && !empty($year_multiple)) {
                                                                if (isset($year_multiple) && $v['id'] == $year_multiple) {
                                                                    $selected = 'selected="selected"';
                                                                }
                                                            }
                                                        endif;
                                                        ?>
                                                        <option value="<?php echo $v['id']; ?>" <?php echo $selected; ?>><?php echo $v['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                <!-- </div> -->
                                <?php 
                                if(isset($fieldArr) && !empty($fieldArr)){
                                    foreach ($fieldArr as $key => $fieldvalue) {
                                        if(in_array($fieldvalue['id'],$est_checked_field)){
                                        ?>
                                            <?php if($fieldvalue['field_name'] == "Color") { ?>
                                                <div class="col-md-4">
                                                    <div class="form-group has-feedback select_form_group">
                                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Color</label>
                                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                            <select data-placeholder="Select a Color..." class="select select-size-sm" id="txt_color_id" name="txt_color_id">
                                                                <option></option>
                                                                <?php
                                                                foreach ($colors as $k => $v) {
                                                                    $selected = '';
                                                                    if (isset($dataArr) && $v['id'] == $dataArr['color_id']):
                                                                        $selected = 'selected="selected"';
                                                                    endif;
                                                                    ?>
                                                                    <option value="<?php echo $v['id']; ?>" <?php echo $selected; ?>><?php echo $v['name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <?php if($fieldvalue['field_name'] == "VIN#") { ?>
                                                <div class="col-md-4">
                                                    <div class="form-group has-feedback select_form_group">
                                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">VIN#</label>
                                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                            <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['vin_id'] : set_value('vin_id');?>" class="form-control" name="vin_id" id="vin_id">
                                                            <?php echo '<label id="vin_id_error2" class="validation-error-label" for="vin_id">' . form_error('vin_id') . '</label>'; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <?php if($fieldvalue['field_name'] == "License plate#") { ?>
                                                <div class="col-md-4">
                                                    <div class="form-group has-feedback select_form_group">
                                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">License plate#</label>
                                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                            <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['lic_plate_id'] : set_value('lic_plate_id'); ?>" class="form-control" name="lic_plate_id" id="lic_plate_id">
                                                            <?php echo '<label id="lic_plate_id_error2" class="validation-error-label" for="lic_plate_id">' . form_error('lic_plate_id') . '</label>'; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <?php if($fieldvalue['field_name'] == "PO#") { ?>
                                                <div class="col-md-4">
                                                    <div class="form-group has-feedback select_form_group">
                                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">PO#</label>
                                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                            <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['po_number'] : set_value('po_number'); ?>" class="form-control" name="po_number" id="po_number">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>


                                            <?php if($fieldvalue['field_name'] == "Stock#") { ?>
                                                <div class="col-md-4">
                                                    <div class="form-group has-feedback select_form_group">
                                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Stock#</label>
                                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                            <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['stock'] : set_value('stock'); ?>" class="form-control" name="stock" id="stock" >
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <?php if($fieldvalue['field_name'] == "Work Order#") { ?>
                                                <div class="col-md-4">
                                                    <div class="form-group has-feedback select_form_group">
                                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Work Order#</label>
                                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                            <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['work_order'] : set_value('work_order'); ?>" class="form-control" name="work_order" id="work_order">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <?php if($fieldvalue['field_name'] == "Reference#") { ?>
                                                <div class="col-md-4">
                                                    <div class="form-group has-feedback select_form_group">
                                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Reference#</label>
                                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                            <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['reference'] : set_value('reference'); ?>" class="form-control" name="reference" id="reference">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <?php if($fieldvalue['field_name'] == "Tracking#") { ?>
                                                <div class="col-md-4">
                                                    <div class="form-group has-feedback select_form_group">
                                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Tracking#</label>
                                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                            <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['tracking'] : set_value('tracking'); ?>" class="form-control" name="tracking" id="tracking" >
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                        <?php 
                                        }
                                    }
                                } 
                            } else {
                                ?>
                                <div class="row">
                                    <!-- <div class="col-md-12"> -->
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-md-12 col-lg-3 control-label lb-w-150">Make</label>
                                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                    <select data-placeholder="Select a Company..." class="select select-size-sm" id="txt_make_name" name="txt_make_name">
                                                        <option></option>
                                                        <?php
                                                        foreach ($companyArr as $k => $v) {
                                                            ?>
                                                            <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-md-12 col-lg-3 control-label lb-w-150">Model</label>
                                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                    <select data-placeholder="Select a Model..." class="select select-size-sm" id="txt_model_name" name="txt_model_name">
                                                        <option></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-md-12 col-lg-3 control-label lb-w-150">Year</label>
                                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                    <select data-placeholder="Select a Year..." class="select select-size-sm" id="txt_year_name" name="txt_year_name">
                                                        <option></option>
                                                        <?php
                                                        if($itemArr_multi['items_list'] != "")
                                                        {
                                                            $year_multiple = array_column($itemArr_multi['items_list'],'item_year_id')[0];
                                                        }
                                                        foreach ($yearArr as $k => $v) {
                                                            $selected = '';

                                                            if (isset($dataArr['year_id']) && $v['id'] == $dataArr['year_id']):
                                                                $selected = 'selected="selected"';
                                                            else:
                                                                if (isset($itemArr) && !empty($itemArr)) {
                                                                    if (isset($itemArr['item_year_id']) && $v['id'] == $itemArr['item_year_id']) {
                                                                        $selected = 'selected="selected"';
                                                                    }
                                                                }
                                                                if (isset($year_multiple) && !empty($year_multiple)) {
                                                                    if (isset($year_multiple) && $v['id'] == $year_multiple) {
                                                                        $selected = 'selected="selected"';
                                                                    }
                                                                }
                                                            endif;
                                                            ?>
                                                            <option value="<?php echo $v['id']; ?>" <?php echo $selected; ?>><?php echo $v['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    <!-- </div> -->
                                    <!-- <div class="col-md-12"> -->
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-md-12 col-lg-3 control-label lb-w-150">Color</label>
                                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                    <select data-placeholder="Select a Color..." class="select select-size-sm" id="txt_color_id" name="txt_color_id">
                                                        <option></option>
                                                        <?php
                                                        foreach ($colors as $k => $v) {
                                                            $selected = '';
                                                            if (isset($dataArr) && $v['id'] == $dataArr['color_id']):
                                                                $selected = 'selected="selected"';
                                                            endif;
                                                            ?>
                                                            <option value="<?php echo $v['id']; ?>" <?php echo $selected; ?>><?php echo $v['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-md-12 col-lg-3 control-label lb-w-150">VIN#</label>
                                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                    <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['vin_id'] : set_value('vin_id'); ?>" class="form-control" name="vin_id" id="vin_id" >
                                                    <?php echo '<label id="vin_id_error2" class="validation-error-label" for="vin_id">' . form_error('vin_id') . '</label>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-md-12 col-lg-3 control-label lb-w-150">License plate#</label>
                                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                    <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['lic_plate_id'] : set_value('lic_plate_id'); ?>" class="form-control" name="lic_plate_id" id="lic_plate_id">
                                                    <?php echo '<label id="lic_plate_id_error2" class="validation-error-label" for="lic_plate_id">' . form_error('lic_plate_id') . '</label>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <!-- </div> -->
                                    <!-- <div class="col-md-12"> -->
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-md-12 col-lg-3 control-label lb-w-150">PO#</label>
                                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                    <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['po_number'] : set_value('po_number'); ?>" class="form-control" name="po_number" id="po_number">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-md-12 col-lg-3 control-label lb-w-150">Stock#</label>
                                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                    <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['stock'] : set_value('stock'); ?>" class="form-control" name="stock" id="stock" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-md-12 col-lg-3 control-label lb-w-150">Work Order#</label>
                                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                    <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['work_order'] : set_value('work_order'); ?>" class="form-control" name="work_order" id="work_order">
                                                </div>
                                            </div>
                                        </div>
                                    <!-- </div> -->
                                    <!-- <div class="col-md-12"> -->
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-md-12 col-lg-3 control-label lb-w-150">Reference#</label>
                                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                    <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['reference'] : set_value('reference'); ?>" class="form-control" name="reference" id="reference">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-md-12 col-lg-3 control-label lb-w-150">Tracking#</label>
                                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                    <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['tracking'] : set_value('tracking'); ?>" class="form-control" name="tracking" id="tracking" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        <!-- </div> -->
                    <!-- </div> -->
                    
                    <?php
                    if (isset($taxes) && !empty($taxes)) {
                        foreach ($taxes as $k => $v) {
                            ?>
                            <input type='hidden' id='tax_hidden_<?php echo $v['id']; ?>' value='<?php echo $v['rate']; ?>'/>
                            <?php
                        }
                    }
                    ?>
                    <?php
                    if (isset($services) && !empty($services)) {
                        foreach ($services as $k => $v) {
                            ?>
                            <input type='hidden' id='service_hidden_<?php echo $v['id']; ?>' value='<?php echo $v['rate']; ?>'/>
                            <?php
                        }
                    }
                    ?>
                    <legend class="text-bold mt-20">Part Details 
                        <?php
                        $class = 'hide';
                        if (isset($itemArr) && !empty($itemArr)) {
                            $class = '';
                        }
                        if (isset($EstimateArr) && !empty($EstimateArr)) {
                            $class = '';
                        }
                        if (isset($itemArr_multi) && !empty($itemArr_multi)) {
                            $class = '';
                        }
                        ?>
                        <span class="part_list <?php echo $class ?>"><button type="button" class="btn bg-blue-300" title="Compatible Parts"><i class="icon-plus3"></i></button></span>
                    </legend>
                    <?php
                    $tax = 0.00;
                    $s_tax = 0.00;
                    ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table part_detail_table table-bordered table_one">
                                    <thead>
                                        <tr>
                                            <th width='18%'>Locations</th>
                                            <th class="white-space-nowrap" width='75%'>Part Details</th>
                                            <th width='200px'>Quantity</th>
                                            <th width='1%'>Rate</th>
                                            <th width='2%'>Discount</th>
                                            <th width='20%'>Tax</th>
                                            <th width='1%'>Amount</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class='part_div text-right'>
                                        <?php
                                        if (isset($dataArr) && !empty($dataArr['parts'])):
                                            $tax = 0.00;
                                            foreach ($dataArr['parts'] as $k => $v):
                                                $key = ++$k;
                                                $loc_id = $this->session->userdata('u_location_id');
                                                ?>
                                                <tr class='<?php echo $estimate_id ?>' id='tr_<?php echo $key; ?>' data-value='<?php echo $key; ?>' data-part_tax_id ="<?php echo $key; ?>">
                                                    <td class='location' id='td_location_id_<?php echo $key; ?>'>
                                                        <input type="hidden" name="" id="session_val" value="<?php echo $loc_id; ?>">
                                                        <select class="select select-location-data" id="txt_location_id_<?php echo $key; ?>" name='location_id[]'>
                                                            <?php
                                                            if (isset($locations) && !empty($locations)):
                                                                foreach ($locations as $k => $value) {
                                                                    // (in_array($value['id'], $v['location_inventory'])) ? ""
                                                                    $disabled = ($value['id'] == $v['location_id']) ? "" : "disabled='disabled'";
                                                                    $selected = ($value['id'] == $v['location_id']) ? "selected='selected'" : "";
                                                                    echo '<option value="' . $value['id'] . '" ' . $selected . ' ' . $disabled . '>' . $value['name'] . '</option>';
                                                                }
                                                            endif;
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td class='' id='td_part_no_<?php echo $key; ?>'>
                                                        <div class='row'>
                                                            <div class='col-md-11'>
                                                                <input type='hidden' name='hidden_part_id[]' value='<?php echo $v['part_id'] ?>' id='part_no_<?php echo $key; ?>'/>
                                                                <div class='select2-result-title text-left white-space-nowrap'><?php echo $v['part_no'] . " (Vendor : " . $v['v1_name'] . " )"; ?> </div>
                                                                <div class='select2-result-description mt-10'> 
                                                                    <input type='text' class='form-control' name='description[]' value='<?php echo $v['description']; ?>'/>
                                                                </div>
                                                                <div class='select2-result-description mt-10'> 
                                                                    <input type='text' class='form-control' name='item_note[]' placeholder="Item note" value='<?php echo $v['item_note']; ?>'/>
                                                                </div>
                                                            </div>
                                                            <div class='col-md-1 mt-2'>
                                                                <span class='text-right cancel-part'><i class='icon-cancel-square text-danger'></i></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td id='td_quantity_<?php echo $key; ?>'>
                                                        <input type='hidden' value='<?php echo $v['quantity']; ?>' name='quantity[]' id='quantity_<?php echo $key; ?>'/>
                                                        <div class='row mt-3'>
                                                            <div class='col-xs-4 col-sm-4 col-md-4 text-left'>
                                                                <span class='plus' id='plus_<?php echo $key; ?>' data-part_tax_id ="<?php echo $key; ?>"><i class='icon-plus3 text-primary'></i></span>
                                                                <span class='minus' id='minus_<?php echo $key; ?>' data-part_tax_id ="<?php echo $key; ?>"><i class='icon-minus3 text-primary'></i></span>
                                                            </div>
                                                            <div class='col-xs-8 col-sm-8 col-md-8'><span id='div_quantity_<?php echo $key; ?>'><?php echo $v['quantity']; ?></span>
                                                                <br/><span id='span_quantity_<?php echo $key; ?>' class='span_quantity'><?php echo ($v['location_quantity']); ?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><input type='hidden' value='<?php echo number_format((float) $v['rate'], 2, '.', ''); ?>' name='rate[]' id='rate_<?php echo $key; ?>'/><span id='td_rate_<?php echo $key; ?>' class="td_rate"><?php echo number_format((float) $v['rate'], 2, '.', ''); ?></span></td>
                                                    <td id='td_discount_<?php echo $key; ?>'>
                                                        <input type='hidden' value='<?php echo number_format((float) $v['discount'], 2, '.', ''); ?>' name='discount[]' id='discount_<?php echo $key; ?>'/>
                                                        <input type='hidden' value='<?php echo number_format((float) $v['discount_rate'], 2, '.', ''); ?>' name='discount_rate[]' id='discount_rate_<?php echo $key; ?>'/>
                                                        <?php
                                                        $partdiscountchecked = '';
                                                        $partdiscounthide = '';
                                                        if($v['discount_rate'] > 0)
                                                        {   
                                                            $partdiscountchecked = 'checked';
                                                            $partdiscounthide = '';
                                                        } else {
                                                            $partdiscountchecked = '';
                                                            $partdiscounthide = 'hide';
                                                        }
                                                        ?>
                                                        <label class="chk-box-container custom-check">
                                                            <input type="checkbox" name="" id="part_dis_checkbox_<?php echo $key; ?>" class="part_dis_checkbox" <?php echo $partdiscountchecked; ?>>
                                                        <span class="checkmark"></span>
                                                        </label>
                                                        <div id="discount_part_hide_<?php echo $key; ?>" class="<?php echo $partdiscounthide; ?>">
                                                            <div class='row'>
                                                                <div class='col-md-6 mt-3 div_discount text-center' id='div_discount_<?php echo $key; ?>'><?php echo number_format((float) $v['discount'], 2, '.', ''); ?></div>
                                                                <div class='col-md-6'>
                                                                    <select name="discount_type_id[]" id='discount_type_id_<?php echo $key; ?>' class='discount_type_id'>
                                                                        <option value="p" <?php echo ($v['discount_type_id'] == 'p') ? 'selected=""' : ''; ?>>%</option>
                                                                        <option value="r" <?php echo ($v['discount_type_id'] != 'p') ? 'selected=""' : ''; ?>><?php echo $cur; ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class='row discount_rate'>
                                                                <span id='span_discount_rate_<?php echo $key; ?>' class='span_discount_rate'>
                                                                    <?php echo ($v['discount'] != 0) ? number_format((float) $v['discount_rate'], 2, '.', '') : '0.00'; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
<td id='td_tax_<?php echo $key; ?>'>
    <?php 
        $tax = ($tax + number_format((float) $v['tax_rate'], 2, '.', '')); 
        $parttaxchecked = '';
        $dparttaxhide = '';
        if($v['individual_part_tax'] != '')
        {   
            $parttaxchecked = 'checked';
            $dparttaxhide = '';
        } else {
            $parttaxchecked = '';
            $dparttaxhide = 'hide';
        }
    ?>
    <input type='hidden' value='<?php echo number_format((float) $v['tax_rate'], 2, '.', ''); ?>' name='tax[]' id='tax_<?php echo $key; ?>'/>
    <label class="chk-box-container custom-check">
        <input type="checkbox" name="" id="part_tax_checkbox_<?php echo $key; ?>" class="part_tax_checkbox" <?php echo $parttaxchecked;?>>
    <span class="checkmark"></span>
    </label>
    <div id="tax_part_hide_<?php echo $key; ?>" class="<?php echo $dparttaxhide; ?>">
        <div class='row'>
            <select data-placeholder="Select a Tax..." class="tax_multiple select select-size-sm select-tax-data tax_select_multiple" id="tax_id_<?php echo $key; ?>" multiple="multiple" name="tax_id[]" data-part_tax_id ="<?php echo $key; ?>">
                <?php
                if (isset($taxes) && !empty($taxes)) {
                    foreach ($taxes as $k => $val) {
                        $selected = '';
                        $tax_selected_id = explode(',', $v['tax_id']);
                        $selected = (in_array($val['id'], $tax_selected_id)) ? 'selected' : '';
                        ?>
                        <option value="<?php echo $val['id']; ?>" <?php echo $selected; ?>><?php echo $val['name'] . ' (' . $val["rate"] . '%)'; ?></option>
                        <?php
                    }
                }
                ?>
            </select>
            <input type="hidden" id="hidden_part_tax_id_<?php echo $key; ?>" name="hidden_part_tax_id[hidden_part_tax_id_<?php echo $key; ?>][]" value="<?php echo $v['tax_id']; ?>">
            <input type="hidden" id="hidden_part_tax_amount_<?php echo $key; ?>" name="hidden_part_tax_amount[hidden_part_tax_amount_<?php echo $key; ?>][]" value="<?php echo $v['individual_part_tax']; ?>">
            <input type="hidden" id="hidden_pname_tax_id_<?php echo $key; ?>" name="hidden_pname_tax_id[hidden_pname_tax_id_<?php echo $key; ?>][]" value="<?php echo $v['tax_list']; ?>">
        </div>
        <div class='col-md-12 tax_rate'>
            <span id='span_tax_rate_<?php echo $key; ?>' class='span_tax_rate'><?php echo ($v['tax_id'] != 0) ? number_format((float) $v['tax_rate'], 2, '.', '') : ''; ?></span>
        </div>
    </div>
</td>
                                                    <td><input type='hidden' value='<?php echo number_format((float) $v['amount'], 2, '.', ''); ?>' name='amount[]' id='amount_<?php echo $key; ?>'/><span id='td_amount_<?php echo $key; ?>' class='td_amount'><?php echo number_format((float) $v['amount'], 2, '.', ''); ?></span></td>
                                                    <td id='td_remove_<?php echo $key; ?>'><span class='remove' id='remove_<?php echo $key; ?>'><i class='icon-trash'></i></span></td>
                                                </tr>
                                                <?php
                                            endforeach;
                                        else:

                                            // Start Dashboard search multiple add items
                                            if (isset($itemArr_multi['items_list']) && !empty($itemArr_multi['items_list'])) {
                                                $key = 0;
                                                foreach ($itemArr_multi['items_list'] as $part_list_multiple => $itemArr_mul) {
                                                    $key ++;
                                                    $loc_id = $this->session->userdata('u_location_id');
                                                    ?>
                                                    
                                                    <tr class='<?php echo $estimate_id ?>' id='tr_<?php echo $key; ?>' data-value='<?php echo $key; ?>' data-part_tax_id ="<?php echo $key; ?>">
                                                    <td class='location' id='td_location_id_<?php echo $key; ?>'>
                                                        <input type="hidden" name="" id="session_val" value="<?php echo $loc_id; ?>">
                                                        <select class="select select-location-data" id="txt_location_id_<?php echo $key; ?>" name='location_id[]'>
                                                            <?php
                                                            if (isset($locations) && !empty($locations)):
                                                                foreach ($locations as $k => $value) {
                                                                    $selected = (isset($itemArr_mul['location_id']) && $value['id'] == $itemArr_mul['location_id']) ? "selected='selected'" : "";
                                                                        if($loc_id != "" && $loc_id != null)
                                                                        {
                                                                            if($value['id']  == $loc_id)
                                                                            {
                                                                            echo '<option value="' . $value['id'] . '" ' . $selected . '>' . $value['name'] . '</option>';
                                                                            }
                                                                        } else {
                                                                            echo '<option value="' . $value['id'] . '" ' . $selected . '>' . $value['name'] . '</option>';
                                                                        }
                                                                    }
                                                            endif;
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td class='' id='td_part_no_<?php echo $key; ?>'>
                                                        <div class='row'>
                                                            <div class='col-md-11'>
                                                                <input type='hidden' name='hidden_part_id[]' value='<?php echo $itemArr_mul['id'] ?>' id='part_no_<?php echo $key; ?>'/>
                                                                <div class='select2-result-title text-left white-space-nowrap'><?php echo $itemArr_mul['part_no'] . " (Vendor : " . $itemArr_mul['v1_name'] . " )"; ?> </div>
                                                                <div class='select2-result-description mt-10'> 
                                                                    <input type='text' class='form-control' name='description[]' value='<?php echo $itemArr_mul['description']; ?>'/>
                                                                </div>
                                                                <div class='select2-result-description mt-10'> 
                                                                    <input type='text' class='form-control item_note' name='item_note[]' value='' placeholder = 'Item note' autocomplete='off' />
                                                                </div>
                                                            </div>
                                                            <div class='col-md-1 mt-2'>
                                                                <span class='text-right cancel-part'><i class='icon-cancel-square text-danger'></i></span>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td id='td_quantity_<?php echo $key; ?>'>
                                                        <input type='hidden' value='1' name='quantity[]' id='quantity_<?php echo $key; ?>'/>
                                                        <div class='row mt-3'>
                                                            <div class='col-xs-4 col-sm-4 col-md-4 text-left'>
                                                                <span class='plus' id='plus_<?php echo $key; ?>' data-part_tax_id ="<?php echo $key; ?>"><i class='icon-plus3 text-primary'></i></span>
                                                                <span class='minus' id='minus_<?php echo $key; ?>' data-part_tax_id ="<?php echo $key; ?>"><i class='icon-minus3 text-primary'></i></span>
                                                            </div>
                                                            <div class='col-xs-8 col-sm-8 col-md-8'><span id='div_quantity_<?php echo $key; ?>'>1</span>
                                                                <br/>
                                                            <span id='span_quantity_<?php echo $key; ?>' class='span_quantity'>
                                                            <?php
                                                            if(!empty($itemArr_multi['AllParts']))
                                                            {
                                                                foreach ($itemArr_multi['AllParts'] as $part_key => $part_value) {
                                                                    if($itemArr_mul['id'] == $part_value['id'])
                                                                    {
                                                                        echo $part_value['total_quantity'];
                                                                    }
                                                                }
                                                            } else {
                                                                echo $itemArr_mul['total_quantity'];
                                                            }
                                                            // if(!empty($itemArr_mul['total_quantity']))
                                                            // {
                                                            //     echo $itemArr_mul['total_quantity'];   
                                                            // }
                                                            ?>
                                                            </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><input type='hidden' value='<?php echo number_format((float) $itemArr_mul['retail_price'], 2, '.', ''); ?>' name='rate[]' id='rate_<?php echo $key; ?>'/><span id='td_rate_<?php echo $key; ?>' class="td_rate"><?php echo number_format((float) $itemArr_mul['retail_price'], 2, '.', ''); ?></span></td>
                                                    <td id='td_discount_<?php echo $key; ?>'>
                                                        <input type='hidden' value='0.00' name='discount[]' id='discount_<?php echo $key; ?>'/>
                                                        <input type='hidden' value='0.00' name='discount_rate[]' id='discount_rate_<?php echo $key; ?>'/>
                                                        <?php
                                                        $partdiscountchecked = '';
                                                        $partdiscounthide = '';
                                                        if($v['discount_rate'] > 0)
                                                        {   
                                                            $partdiscountchecked = 'checked';
                                                            $partdiscounthide = '';
                                                        } else {
                                                            $partdiscountchecked = '';
                                                            $partdiscounthide = 'hide';
                                                        }
                                                        ?>
                                                        <label class="chk-box-container custom-check">
                                                            <input type='checkbox' name='' id='part_dis_checkbox_<?php echo $key; ?>' class='part_dis_checkbox' <?php echo $partdiscountchecked; ?>>
                                                        <span class="checkmark"></span>
                                                        </label>
                                                        <div id="discount_part_hide_<?php echo $key; ?>" class="<?php echo $partdiscounthide; ?>">
                                                            <div class='row'>
                                                                <div class='col-md-6 mt-3 div_discount text-center' id='div_discount_<?php echo $key; ?>'>0.00</div>
                                                                <div class='col-md-6'>
                                                                    <select name="discount_type_id[]" id='discount_type_id_<?php echo $key; ?>' class='discount_type_id'>
                                                                        <option value="p">%</option>
                                                                        <option value="r"><?php echo $cur; ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class='row discount_rate'>
                                                                <span id='span_discount_rate_<?php echo $key; ?>' class='span_discount_rate'></span>
                                                            </div>
                                                        </div>
                                                    </td>
<!-- Dashboard search result -->
<td id='td_tax_<?php echo $key; ?>' class="td_dropdown_cstm">
    <?php 
        $parttaxchecked = '';
        $dparttaxhide = '';
        if($v['individual_part_tax'] != '')
        {   
            $parttaxchecked = 'checked';
            $dparttaxhide = '';
        } else {
            $parttaxchecked = '';
            $dparttaxhide = 'hide';
        }
    ?>
    <input type='hidden' value='0.00' name='tax[]' id='tax_<?php echo $key; ?>'/>
    <label class="chk-box-container custom-check">
        <input type="checkbox" name="" id="part_tax_checkbox_<?php echo $key; ?>" class="part_tax_checkbox" <?php echo $parttaxchecked;?>>
    <span class="checkmark"></span>
    </label>
    <div id="tax_part_hide_<?php echo $key; ?>" class="<?php echo $dparttaxhide; ?>">
        <div class='row'>
            <select data-placeholder="Select a Tax..." class="tax_multiple select select-size-sm select-tax-data tax_select_multiple" multiple="multiple" id="tax_id_<?php echo $key; ?>" name="tax_id[]" data-part_tax_id ="<?php echo $key; ?>">
                <?php
                if (isset($taxes) && !empty($taxes)) {
                    foreach ($taxes as $k => $val) {
                        ?>
                        <option value="<?php echo $val['id']; ?>"><?php echo $val['name'] . ' (' . $val["rate"] . '%)'; ?></option>
                        <?php
                    }
                }
                ?>
            </select>
            <input type="hidden" id="hidden_part_tax_id_<?php echo $key; ?>" name="hidden_part_tax_id[hidden_part_tax_id_<?php echo $key; ?>][]" value="<?php echo $v['tax_id']; ?>">
            <input type="hidden" id="hidden_part_tax_amount_<?php echo $key; ?>" name="hidden_part_tax_amount[hidden_part_tax_amount_<?php echo $key; ?>][]" value="<?php echo $v['individual_part_tax']; ?>">
            <input type="hidden" id="hidden_pname_tax_id_<?php echo $key; ?>" name="hidden_pname_tax_id[hidden_pname_tax_id_<?php echo $key; ?>][]" value="<?php echo $v['tax_list']; ?>">
        </div>
        <div class='col-md-12 tax_rate'>
            <span id='span_tax_rate_<?php echo $key; ?>' class='span_tax_rate'></span>
        </div>
    </div>
</td>

                                                    <td><input type='hidden' value='<?php echo number_format((float) $itemArr_mul['retail_price'], 2, '.', ''); ?>' name='amount[]' id='amount_<?php echo $key; ?>'/><span id='td_amount_<?php echo $key; ?>' class='td_amount'><?php echo number_format((float) $itemArr_mul['retail_price'], 2, '.', ''); ?></span></td>
                                                    <td id='td_remove_<?php echo $key; ?>'><span class='remove' id='remove_<?php echo $key; ?>'><i class='icon-trash'></i></span></td>
                                                </tr>    
                                                    <?php
                                                }
                                            }
                                            // End Dashboard search multiple add items   

                                            // Dashboard search items add
                                            if (isset($itemArr) && !empty($itemArr)) {
                                                $key = 1;
                                                $loc_id = $this->session->userdata('u_location_id');
                                                ?>
                                                <tr class='<?php echo $estimate_id ?>' id='tr_<?php echo $key; ?>' data-value='<?php echo $key; ?>'>
                                                    <td class='location' id='td_location_id_<?php echo $key; ?>'>
                                                        <input type="hidden" name="" id="session_val" value="<?php echo $loc_id; ?>">
                                                        <select class="select select-location-data" id="txt_location_id_<?php echo $key; ?>" name='location_id[]'>
                                                            <?php
                                                            if (isset($locations) && !empty($locations)):
                                                                foreach ($locations as $k => $value) {
                                                                    $selected = (isset($itemArr['location_id']) && $value['id'] == $itemArr['location_id']) ? "selected='selected'" : "";
                                                                    if($loc_id != "" && $loc_id != null)
                                                                    {
                                                                        if($value['id']  == $loc_id)
                                                                        {
                                                                        echo '<option value="' . $value['id'] . '" ' . $selected . '>' . $value['name'] . '</option>';
                                                                        }
                                                                    } else {
                                                                        echo '<option value="' . $value['id'] . '" ' . $selected . '>' . $value['name'] . '</option>';
                                                                    }
                                                                }
                                                            endif;
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td class='' id='td_part_no_<?php echo $key; ?>'>
                                                        <div class='row'>
                                                            <div class='col-md-11'>
                                                                <input type='hidden' name='hidden_part_id[]' value='<?php echo $itemArr['id'] ?>' id='part_no_<?php echo $key; ?>'/>
                                                                <div class='select2-result-title text-left white-space-nowrap'><?php echo $itemArr['part_no'] . " (Vendor : " . $itemArr['v1_name'] . " )"; ?> </div>
                                                                <div class='select2-result-description mt-10'> 
                                                                    <input type='text' class='form-control' name='description[]' value='<?php echo $itemArr['description']; ?>'/>
                                                                </div>
                                                                <div class='select2-result-description mt-10'> 
                                                                    <input type='text' class='form-control item_note' name='item_note[]' value='' placeholder = 'Item note' autocomplete='off' />
                                                                </div>
                                                            </div>
                                                            <div class='col-md-1 mt-2'>
                                                                <span class='text-right cancel-part'><i class='icon-cancel-square text-danger'></i></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td id='td_quantity_<?php echo $key; ?>'>
                                                        <input type='hidden' value='1' name='quantity[]' id='quantity_<?php echo $key; ?>'/>
                                                        <div class='row mt-3'>
                                                            <div class='col-xs-4 col-sm-4 col-md-4 text-left'>
                                                                <span class='plus' id='plus_<?php echo $key; ?>' data-part_tax_id ="<?php echo $key; ?>"><i class='icon-plus3 text-primary'></i></span>
                                                                <span class='minus' id='minus_<?php echo $key; ?>' data-part_tax_id ="<?php echo $key; ?>"><i class='icon-minus3 text-primary'></i></span>
                                                            </div>
                                                            <div class='col-xs-8 col-sm-8 col-md-8'><span id='div_quantity_<?php echo $key; ?>'>1</span>
                                                                <br/>
                                                                <span id='span_quantity_<?php echo $key; ?>' class='span_quantity'>
                                                                <?php 
                                                                if(!empty($itemArr['AllParts']))
                                                                {    
                                                                    foreach ($itemArr['AllParts'] as $keys => $value) {
                                                                        if($value['id'] == $itemArr['id'])
                                                                        {
                                                                            echo($value['total_quantity']);
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo ($itemArr['total_quantity']); 
                                                                }
                                                                // if($this->session->userdata('u_location_id') != "")
                                                                // {
                                                                //     echo ($itemArr['location_quantity']); 
                                                                // } else {
                                                                //     echo ($itemArr['total_quantity']); 
                                                                // }
                                                                ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><input type='hidden' value='<?php echo number_format((float) $itemArr['retail_price'], 2, '.', ''); ?>' name='rate[]' id='rate_<?php echo $key; ?>'/><span id='td_rate_<?php echo $key; ?>' class="td_rate"><?php echo number_format((float) $itemArr['retail_price'], 2, '.', ''); ?></span></td>
                                                    <td id='td_discount_<?php echo $key; ?>'>
                                                        <input type='hidden' value='0.00' name='discount[]' id='discount_<?php echo $key; ?>'/>
                                                        <input type='hidden' value='0.00' name='discount_rate[]' id='discount_rate_<?php echo $key; ?>'/>
                                                        <label class="chk-box-container custom-check">
                                                            <input type="checkbox" name="" id="part_dis_checkbox_<?php echo $key; ?>" class="part_dis_checkbox">
                                                        <span class="checkmark"></span>
                                                        </label>
                                                        <div id="discount_part_hide_<?php echo $key; ?>">
                                                            <div class='row'>
                                                                <div class='col-md-6 mt-3 div_discount text-center' id='div_discount_<?php echo $key; ?>'>0.00</div>
                                                                <div class='col-md-6'>
                                                                    <select name="discount_type_id[]" id='discount_type_id_<?php echo $key; ?>' class='discount_type_id'>
                                                                        <option value="p">%</option>
                                                                        <option value="r"><?php echo $cur; ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class='row discount_rate'>
                                                                <span id='span_discount_rate_<?php echo $key; ?>' class='span_discount_rate'></span>
                                                            </div>
                                                        </div>
                                                    </td>
<!-- Single add to invoice from dashboard result -->
<td id='td_tax_<?php echo $key; ?>'>
    <input type='hidden' value='0.00' name='tax[]' id='tax_<?php echo $key; ?>'/>
    <label class="chk-box-container custom-check">
        <input type="checkbox" name="" id="part_tax_checkbox_<?php echo $key; ?>" class="part_tax_checkbox" >
    <span class="checkmark"></span>
    </label>
    <div id="tax_part_hide_<?php echo $key; ?>">
        <div class='row'>
            <select data-placeholder="Select a Tax..." class="tax_multiple select select-size-sm select-tax-data tax_select_multiple" multiple="multiple" id="tax_id_<?php echo $key; ?>" name="tax_id[]" data-part_tax_id ="<?php echo $key; ?>">
                <?php
                if (isset($taxes) && !empty($taxes)) {
                    foreach ($taxes as $k => $val) {
                        ?>
                        <option value="<?php echo $val['id']; ?>"><?php echo $val['name'] . ' (' . $val["rate"] . '%)'; ?></option>
                        <?php
                    }
                }
                ?>
            </select>
            <input type="hidden" id="hidden_part_tax_id_<?php echo $key; ?>" name="hidden_part_tax_id[hidden_part_tax_id_<?php echo $key; ?>][]" value="<?php echo $v['tax_id']; ?>">
            <input type="hidden" id="hidden_part_tax_amount_<?php echo $key; ?>" name="hidden_part_tax_amount[hidden_part_tax_amount_<?php echo $key; ?>][]" value="<?php echo $v['individual_part_tax']; ?>">
            <input type="hidden" id="hidden_pname_tax_id_<?php echo $key; ?>" name="hidden_pname_tax_id[hidden_pname_tax_id_<?php echo $key; ?>][]" value="<?php echo $v['tax_list']; ?>">
        </div>
        <div class='col-md-12 tax_rate'>
            <span id='span_tax_rate_<?php echo $key; ?>' class='span_tax_rate'></span>
        </div>
    </div>
</td>
                                                    <td><input type='hidden' value='<?php echo number_format((float) $itemArr['retail_price'], 2, '.', ''); ?>' name='amount[]' id='amount_<?php echo $key; ?>'/><span id='td_amount_<?php echo $key; ?>' class='td_amount'><?php echo number_format((float) $itemArr['retail_price'], 2, '.', ''); ?></span></td>
                                                    <td id='td_remove_<?php echo $key; ?>'><span class='remove' id='remove_<?php echo $key; ?>'><i class='icon-trash'></i></span></td>
                                                </tr>
                                            <!-- Dashboard search items end -->
                                                <?php
                                            } else {
                                                ?>
                                                <?php 
                                                if (!isset($itemArr_multi['items_list']) && empty($itemArr_multi['items_list'])) 
                                                {
                                                $loc_id = $this->session->userdata('u_location_id');
                                                ?>
                                                <tr class='<?php echo $estimate_id ?>' id='tr_1' data-value='1' data-part_tax_id ="0">
                                                    <td class='location' id='td_location_id_1'>
                                                        <input type="hidden" name="" id="session_val" value="<?php echo $loc_id; ?>">
                                                        <select class="select select-location-data" id="txt_location_id_1" name='location_id[]'>
                                                            <?php
                                                            if (isset($locations) && !empty($locations)):
                                                                foreach ($locations as $key => $value) {
                                                                    if($loc_id != "" && $loc_id != null)
                                                                    {
                                                                        if($value['id']  == $loc_id)
                                                                        {
                                                                        echo '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
                                                                        }
                                                                    } else {
                                                                        echo '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
                                                                    }
                                                                }
                                                            endif;
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td class='select_part estimates-select2' id='td_part_no_1'>
                                                        <select class="select-part-data" id="txt_part_no_1">
                                                            <option value="" selected="selected">Type to select Part</option>
                                                        </select>
                                                    </td>
                                                    <td id='td_quantity_1'>
                                                        <input type='hidden' value='1' name='quantity[]' id='quantity_1'/>
                                                        <div class='row mt-3'>
                                                            <div class='col-xs-4 col-sm-4 col-md-4 text-left'>
                                                                <span class='plus' id='plus_1' data-part_tax_id="0"><i class='icon-plus3 text-primary'></i></span>
                                                                <span class='minus' id='minus_1' data-part_tax_id="0"><i class='icon-minus3 text-primary'></i></span>
                                                            </div>
                                                            <div class='col-xs-8 col-sm-8 col-md-8'><span id='div_quantity_1'>1</span>
                                                                <br/><span id='span_quantity_1' class='span_quantity'></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><input type='hidden' value='0.00' name='rate[]' id='rate_1'/><span id='td_rate_1' class="td_rate">0.00</span></td>
                                                    <td id='td_discount_1'>
                                                        <input type='hidden' value='0.00' name='discount[]' id='discount_1'/>
                                                        <input type='hidden' value='0.00' name='discount_rate[]' id='discount_rate_1'/>
                                                        <label class="chk-box-container custom-check">
                                                            <input type="checkbox" name="" id="part_dis_checkbox_1" class="part_dis_checkbox">
                                                        <span class="checkmark"></span>
                                                        </label>
                                                        <div id="discount_part_hide_1">
                                                            <div class='row'>
                                                                <div class='col-md-6 mt-3 div_discount text-center' id='div_discount_1'>0.00</div>
                                                                <div class='col-md-6'>
                                                                    <select name="discount_type_id[]" id='discount_type_id_1' class='discount_type_id'>
                                                                        <option value="p" selected="">%</option>
                                                                        <option value="r"><?php echo $cur; ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class='row discount_rate'>
                                                                <span id='span_discount_rate_1' class='span_discount_rate'></span>
                                                            </div>
                                                        </div>
                                                    </td>
<!-- ====== Start Multiple tax First line ======  -->
<td id='td_tax_1'>
    <input type='hidden' value='0.00' name='tax[]' id='tax_1'/>
    <label class="chk-box-container custom-check">
        <input type="checkbox" name="" id="part_tax_checkbox_1" class="part_tax_checkbox">
    <span class="checkmark"></span>
    </label>
    <div id="tax_part_hide_1">
        <div class="row">
            <select data-placeholder="Select a Tax..." class="tax_multiple select select-size-sm select-tax-data tax_select_multiple" multiple="multiple" id="tax_id_1" name="tax_id[tax_id_0][]" data-part_tax_id="0">
                <?php
                if (isset($taxes) && !empty($taxes)) {
                    foreach ($taxes as $k => $v) {
                        $selected = '';
                        if (isset($dataArr) && !empty($dataArr) && $v['id'] == $dataArr['tax_id']):
                            $selected = 'selected="selected"';
                        endif;
                        ?>
                        <option value="<?php echo $v['id']; ?>" class="form-group" <?php echo $selected; ?>><?php echo $v['name'] . ' ( ' . $v["rate"] . ' %)'; ?></option>
                        <?php
                    }
                }
                ?>
            </select> 
            <input type="hidden" id="hidden_part_tax_id_0" name="hidden_part_tax_id[hidden_part_tax_id_0][]" value="">
            <input type="hidden" id="hidden_part_tax_amount_0" name="hidden_part_tax_amount[hidden_part_tax_amount_0][]" value="">
            <input type="hidden" id="hidden_pname_tax_id_0" name="hidden_pname_tax_id[hidden_pname_tax_id_0][]" value="">
        </div>
        <div class='col-md-12 tax_rate'>
            <span id='span_tax_rate_1' class='span_tax_rate'></span>
        </div>
    </div>
</td>
<!-- ========= End Multiple tax First line =============  -->
                                                    <td><input type='hidden' value='0.00' name='amount[]' id='amount_1'/><span id='td_amount_1'>0.00</span></td>
                                                    <td id='td_remove_1'><span class='remove' id='remove_1'><i class='icon-trash'></i></span></td>
                                                </tr>
                                                <?php
                                                }   
                                            }
                                        endif;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php echo '<label id="hidden_part_id_error2" class="validation-error-label" for="hidden_part_id">' . form_error('hidden_part_id') . '</label>'; ?>
                        </div>
                    </div>
                    <div class="row mt-20">
                        <div class="col-lg-12">
                            <a href="javascript:void (0)" class='add_line text-primary'><i class='icon-plus-circle2'></i><span style="margin-left: 10px;">Add another line</span></a>
                        </div>
                    </div>
                    <legend class="text-bold mt-20">Labor and Service Details </legend>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered checkbox-center">
                                    <thead>
                                        <tr>
                                            <th width='35%'>Service Details</th>
                                            <th width='27%'>Service Note</th>
                                            <th width='7%'>Quantity</th>
                                            <th width='5%'>Rate</th>
                                            <th width='5%'>Discount</th>
                                            <th width='16%'>Tax</th>
                                            <th width='5%'>Amount</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class='service_div text-right'>
                                        <?php
                                        if (isset($dataArr) && !empty($dataArr['services'])):
                                            $s_tax = 0.00;
                                            foreach ($dataArr['services'] as $k => $v):
                                                $key = ++$k;
                                                ?>
                                                <tr class='service_<?php echo $estimate_id ?>' id='service_tr_<?php echo $key ?>' data-value='<?php echo $key ?>' data-service_tax_id ="<?php echo $key; ?>">
                                                    <td class='' id='td_service_no_<?php echo $key ?>'>
                                                        <select class="select select-size-sm select-service-data" id="service_id_<?php echo $key ?>" name="service_id[]" data-placeholder="Select a Service..." >
                                                            <option></option>
                                                            <?php
                                                            if (isset($services) && !empty($services)) {
                                                                foreach ($services as $k => $val) {
                                                                    $selected = '';
                                                                    if ($val['id'] == $v['service_id']):
                                                                        $selected = 'selected="selected"';
                                                                    endif;
                                                                    ?>
                                                                    <option value="<?php echo $val['id']; ?>" <?php echo $selected; ?>><?php echo $val['name'] . ' : ' . $cur . '' . $val["rate"]; ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td class='' id='td_service_note_<?php echo $key; ?>'>
                                                        <input type='text' class='form-control service_note' name='service_note[]' value='<?php echo $v['service_note']; ?>' placeholder = 'Service note' autocomplete='off' />
                                                    </td>
                                                    <td id='td_srv_quantity_<?php echo $key; ?>'>
                                                        <input type='hidden' value='<?php echo $v['qty']; ?>' name='srvquantity[]' id='srvquantity_<?php echo $key; ?>'/>
                                                        <div class='row mt-3'>
                                                            <div class='col-md-4 text-left'>
                                                                <span class='srvplus' id='srvplus_<?php echo $key; ?>' data-service_tax_id ="<?php echo $key; ?>"><i class='icon-plus3 text-primary'></i></span>
                                                                <span class='srvminus' id='srvminus_<?php echo $key; ?>' data-service_tax_id ="<?php echo $key; ?>"><i class='icon-minus3 text-primary'></i></span>
                                                            </div>
                                                            <div class='col-md-8 mt-3'><span class="srvquantity"    id='div_srv_quantity_<?php echo $key; ?>'><?php echo $v['qty']; ?></span>
                                                                <br/><span id='span_srvquantity_<?php echo $key; ?>' class='span_quantity'><?php echo ($v['location_quantity']); ?></span>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td><input type='hidden' value='<?php echo number_format((float) $v['rate'], 2, '.', ''); ?>' name='service_rate[]' id='service_rate_<?php echo $key ?>'/><span id='td_service_rate_<?php echo $key ?>' class="td_service_rate"><?php echo number_format((float) $v['rate'], 2, '.', ''); ?></span></td>
                                                    <td id='td_discount_1'>
                                                        <input type='hidden' value='<?php echo number_format((float) $v['discount'], 2, '.', ''); ?>' name='service_discount[]' id='service_discount_<?php echo $key ?>'/>
                                                        <input type='hidden' value='<?php echo number_format((float) $v['discount_rate'], 2, '.', ''); ?>' name='service_discount_rate[]' id='service_discount_rate_<?php echo $key ?>'/>
                                                        <?php
                                                        $servicediscountchecked = '';
                                                        $servicediscounthide = '';
                                                        if($v['discount_rate'] > 0)
                                                        {   
                                                            $servicediscountchecked = 'checked';
                                                            $servicediscounthide = '';
                                                        } else {
                                                            $servicediscountchecked = '';
                                                            $servicediscounthide = 'hide';
                                                        }
                                                        ?>
                                                        <label class="chk-box-container custom-check">
                                                            <input type='checkbox' name='' id='service_dis_checkbox_<?php echo $key ?>' class='service_dis_checkbox' <?php echo $servicediscountchecked; ?>>
                                                        <span class="checkmark"></span>
                                                        </label>
                                                        <div id="discount_service_hide_<?php echo $key ?>" class="<?php echo $servicediscounthide; ?>">
                                                            <div class='row'>
                                                                <div class='col-md-6 mt-3 div_service_discount text-center' id='div_service_discount_<?php echo $key ?>'><?php echo number_format((float) $v['discount'], 2, '.', ''); ?></div>
                                                                <div class='col-md-6'>
                                                                    <select name="service_discount_type_id[]" id='service_discount_type_id_<?php echo $key ?>' class='service_discount_type_id'>
                                                                        <option value="p" <?php echo ($v['discount_type_id'] == 'p') ? 'selected=""' : ''; ?>>%</option>
                                                                        <option value="r" <?php echo ($v['discount_type_id'] != 'p') ? 'selected=""' : ''; ?>><?php echo $cur; ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class='service_discount_rate'>
                                                                <span id='span_service_discount_rate_<?php echo $key ?>' class='span_service_discount_rate'><?php echo number_format((float) $v['discount_rate'], 2, '.', ''); ?></span>
                                                            </div>
                                                        </div>
                                                    </td>
<td id='td_tax_1'>
    <?php 
        $s_tax = ($s_tax + number_format((float) $v['tax_rate'], 2, '.', '')); 
        $servicetaxchecked = '';
        $dservicetaxhide = '';
        if($v['individual_service_tax'] != '')
        {   
            $servicetaxchecked = 'checked';
            $dservicetaxhide = '';
        } else {
            $servicetaxchecked = '';
            $dservicetaxhide = 'hide';
        }
    ?>
    <input type='hidden' value='<?php echo number_format((float) $v['tax_rate'], 2, '.', ''); ?>' name='service_tax[]' id='service_tax_<?php echo $key ?>'/>
    <label class="chk-box-container custom-check">
        <input type="checkbox" name="" id="service_tax_checkbox_<?php echo $key ?>" class="service_tax_checkbox"<?php echo $servicetaxchecked;?>>
    <span class="checkmark"></span>
        </label>
    <div id="tax_service_hide_<?php echo $key ?>" class="<?php echo $dservicetaxhide; ?>">
        <div class='row'>
            <select data-placeholder="Select a Tax..." class="service_tax_multiple select select-size-sm select-service-tax-data service_select_multiple" id="service_tax_id_<?php echo $key ?>" multiple="multiple" name="service_tax_id[]" data-service_tax_id ="<?php echo $key; ?>">
                <?php
                if (isset($taxes) && !empty($taxes)) {
                    foreach ($taxes as $k => $val) {
                    $tax_selected_id = explode(',', $v['tax_id']);
                    $selected = '';
                    $selected = (in_array($val['id'], $tax_selected_id)) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $val['id']; ?>" data-id="<?php echo $val["rate"]; ?>" <?php echo $selected; ?>><?php echo $val['name'] . ' ( ' . $val["rate"] . ' %)'; ?></option>
                        <?php
                    }
                }
                ?>    
            </select>
            <input type="hidden" id="hidden_tax_id_<?php echo $key; ?>" name="hidden_tax_id[hidden_tax_id_<?php echo $key; ?>][]" value="<?php echo $v['tax_id']; ?>">
            <input type="hidden" id="hidden_service_tax_amount_<?php echo $key; ?>" name="hidden_service_tax_amount[hidden_service_tax_amount_<?php echo $key; ?>][]" value="<?php echo $v['individual_service_tax']; ?>">
            <input type="hidden" id="hidden_sname_tax_id_<?php echo $key; ?>" name="hidden_sname_tax_id[hidden_sname_tax_id_<?php echo $key; ?>][]" value="<?php echo $v['tax_list']; ?>">
        </div>
            <div class='col-md-12 service_tax_rate'>
                <span id='span_service_tax_rate_<?php echo $key ?>' class='span_service_tax_rate'><?php echo number_format((float) $v['tax_rate'], 2, '.', ''); ?></span>
            </div>
    </div>
</td>
                                                    <td><input type='hidden' value='<?php echo number_format((float) $v['amount'], 2, '.', ''); ?>' name='service_amount[]' id='service_amount_<?php echo $key ?>'/><span id='td_service_amount_<?php echo $key ?>' class='td_service_amount'><?php echo number_format((float) $v['amount'], 2, '.', ''); ?></span></td>
                                                    <td id='td_service_remove_1'><span class='service_remove' id='service_remove_<?php echo $key ?>'><i class='icon-trash'></i></span></td>
                                                </tr>
                                                <?php
                                            endforeach;
                                        else:
                                            ?>
                                            <tr class='service_<?php echo $estimate_id ?>' id='service_tr_1' data-value='1' data-service_tax_id ="0">
                                                <td class='select_service' id='td_service_no_1'>
                                                    <select class="select select-size-sm select-service-data" id="service_id_1" name="service_id[]" data-cntval="1" data-placeholder="Select a Service..." >
                                                        <option></option>
                                                        <?php
                                                        if (isset($services) && !empty($services)) {
                                                            foreach ($services as $k => $v) {
                                                                ?>
                                                                <option value="<?php echo $v['id']; ?>"><?php echo $v['name'] . ' : ' . $cur . '' . $v["rate"]; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td class='' id='td_service_note_1'>
                                                    <input type='text' class='form-control service_note min-w-150' name='service_note[]' value='' placeholder = 'Service note' autocomplete='off' />
                                                </td>
                                                <td id='td_srv_quantity_1'>
                                                    <input type='hidden' value='1' name='srvquantity[]' id='srvquantity_1'/>
                                                    <div class='row mt-3'>
                                                        <div class='col-md-4 text-left'>
                                                            <span class='srvplus' id='srvplus_1' data-service_tax_id ="0"><i class='icon-plus3 text-primary'></i></span>
                                                            <span class='srvminus' id='srvminus_1' data-service_tax_id ="0"><i class='icon-minus3 text-primary'></i></span>
                                                        </div>
                                                        <div class='col-md-8 mt-3'><span class="srvquantity" id='div_srv_quantity_1'>1</span>
                                                            <br/><span id='span_srvquantity_1' class='span_srvquantity'></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                
                                                <td><input type='hidden' value='0.00' name='service_rate[]' id='service_rate_1'/><span id='td_service_rate_1' class="td_service_rate">0.00</span></td>
                                                <td id='td_discount_1'>
                                                    <input type='hidden' value='0.00' name='service_discount[]' id='service_discount_1'/>
                                                    <input type='hidden' value='0.00' name='service_discount_rate[]' id='service_discount_rate_1'/>
                                                    <label class="chk-box-container custom-check">
                                                        <input type="checkbox" name="" id="service_dis_checkbox_1" class="service_dis_checkbox">
                                                    <span class="checkmark"></span>
                                                    </label>
                                                    <div id="discount_service_hide_1">
                                                        <div class='row'>
                                                            <div class='col-md-6 mt-3 div_service_discount text-center' id='div_service_discount_1'>0.00</div>
                                                            <div class='col-md-6'>
                                                                <select name="service_discount_type_id[]" id='service_discount_type_id_1' class='service_discount_type_id'>
                                                                    <option value="p" selected="">%</option>
                                                                    <option value="r"><?php echo $cur; ?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class='service_discount_rate'>
                                                            <span id='span_service_discount_rate_1' class='span_service_discount_rate'></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                
<td id='td_tax_1'>
    <input type='hidden' value='0.00' name='service_tax[]' id='service_tax_1'/>
    <label class="chk-box-container custom-check">
        <input type="checkbox" name="" id="service_tax_checkbox_1" class="service_tax_checkbox">
    <span class="checkmark"></span>
    </label>
    <div id="tax_service_hide_1">
        <div class='row'>
            <select data-placeholder="Select a Tax..." class="service_tax_multiple select select-size-sm select-service-tax-data service_select_multiple" multiple="multiple" id="service_tax_id_1" name="service_tax_id[service_tax_id_0][]" data-service_tax_id ="0">
                <?php
                if (isset($taxes) && !empty($taxes)) {
                    foreach ($taxes as $k => $v) {
                        ?>
                        <option data-text_sname="<?php echo $v['name']; ?>" value="<?php echo $v['id']; ?>"><?php echo $v['name'] . ' ( ' . $v["rate"] . ' %)'; ?></option>
                        <?php
                    }
                }
                ?>
            </select>
            <input type="hidden" id="hidden_tax_id_0" name="hidden_tax_id[hidden_tax_id_0][]" value="">
            <input type="hidden" id="hidden_service_tax_amount_0" name="hidden_service_tax_amount[hidden_service_tax_amount_0][]" value="">
            <input type="hidden" id="hidden_sname_tax_id_0" name="hidden_sname_tax_id[hidden_sname_tax_id_0][]" value="">
        </div>
        <div class='col-md-12 service_tax_rate'>
            <span id='span_service_tax_rate_1' class='span_service_tax_rate test'></span>
        </div>
    </div>
</td>
                                                <td><input type='hidden' value='0.00' name='service_amount[]' id='service_amount_1'/><span id='td_service_amount_1' class='td_service_amount'>0.00</span></td>
                                                <td id='td_service_remove_1'><span class='service_remove' id='service_remove_1'><i class='icon-trash'></i></span></td>
                                            </tr>
                                        <?php
                                        endif;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-20">
                        <div class="col-lg-12">
                            <a href="javascript:void (0)" class='add_service_line text-primary'><i class='icon-plus-circle2'></i><span style="margin-left: 10px;">Add another Service</span></a>
                        </div>
                    </div>
                    <div class="row mt-20">
                        <div class="col-lg-12">
                            <div class="col-sm-4 col-md-4 col-lg-6">

                            </div>
                            <div class="col-sm-8 col-md-7 col-md-offset-1 col-lg-6 col-lg-offset-0">
                                <div class="content-group">
                                    <h6><b>Total due</b></h6>
                                    <div class="table-responsive no-border">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td class="border-bottom"><b>Sub Total:</b></td>
                                                    <td class="text-right">
                                                        <?php
                                                        if (isset($dataArr) && !empty($dataArr)) {
                                                            $sub_total = number_format((float) $dataArr['sub_total'], 2, '.', '');
                                                            $total = number_format((float) $dataArr['total'], 2, '.', '');
                                                        } else if (isset($itemArr) && !empty($itemArr)){
                                                            if (isset($itemArr) && !empty($itemArr)) {
                                                                $sub_total = number_format((float) $itemArr['retail_price'], 2, '.', '');
                                                                $total = number_format((float) $itemArr['retail_price'], 2, '.', '');
                                                            } else {
                                                                $sub_total = 0.00;
                                                                $total = 0.00;
                                                            }
                                                        } else {
                                                            // Multipul Items total
                                                            if(isset($itemArr_multi)&& !empty($itemArr_multi))
                                                            {
                                                                $array_sub_multipul = array_sum(array_column($itemArr_multi['items_list'], 'retail_price'));
                                                                $sub_total =  number_format((float) $array_sub_multipul, 2, '.', '');
                                                                $total =  number_format((float) $array_sub_multipul, 2, '.', '');
                                                            } else {
                                                                $sub_total = 0.00;
                                                                $total = 0.00;
                                                            }
                                                        }
                                                        ?>
                                                        <input type='hidden' value='<?php echo $sub_total ?>' name='sub_total' class='sub_total'/>
                                                        <span id='sub_total'><?php echo $sub_total ?></span>
                                                    </td>
                                                </tr>
                                                <?php 
                                                if($dataArr['parts'] != "")
                                                {
                                                    $ind_part_tax = array_column($dataArr['parts'],'individual_part_tax');
                                                    $ind_part_id = array_column($dataArr['parts'],'tax_id');
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

                                                if($dataArr['services'] != "")
                                                {
                                                    $ind_srv_tax = array_column($dataArr['services'],'individual_service_tax');
                                                    $ind_srv_id = array_column($dataArr['services'],'tax_id');
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
                                                
                                                if($part_final_array != "" && !empty($part_final_array))
                                                {
                                                    $part_tax_possion = '';
                                                }

                                                if($srv_final_array != "" && !empty($srv_final_array))
                                                {
                                                    $service_tax_possion = '';
                                                }
                                                ?>
                                                <tr class="">
                                                    <td colspan="2" class="table-tax-amount taxamount-calculate">
                                                        <table class="table">
                                                            <tr>
                                                                <td class="text-right hide_show_part_tax_amount <?php echo $part_tax_possion; ?>">
                                                                    <strong>Part tax Amount: </strong>
                                                                    <?php
                                                                    if($part_final_array != "" && !empty($part_final_array))
                                                                    {
                                                                        $str = $dataArr['individual_part_tax'];
                                                                        $print_tax = explode(",",$str);
                                                                        if(isset($dataArr['individual_part_tax']) && !empty($dataArr['individual_part_tax']))
                                                                        { 
                                                                            if (isset($taxes) && !empty($taxes)) {
                                                                            foreach ($taxes as $k => $v) {
                                                                                foreach ($print_tax as $i => $j) {
                                                                                    if($k == $i){
                                                                                ?> 
                                                                                <div class="tax-filed-label">
                                                                                    <span><?php echo $v['name'] . ' (' . $v["rate"] . '%)'; ?></span>
                                                                                    <input type='text' id='tax_hidden_total_<?php echo $v['id']; ?>' value='<?php echo $j;?>' class="form-control total_tax_part" name="individual_part_tax[]" readonly/>
                                                                                </div>
                                                                                <?php
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        } else {
                                                                           if (isset($taxes) && !empty($taxes)) {
                                                                            foreach ($taxes as $k => $v) {
                                                                                ?> 
                                                                                <div class="tax-filed-label">
                                                                                    <span class="hidden" id="span_tax_hidden_parttotal_service<?php echo $v['id']; ?>"><?php echo $v['name'] . ' (' . $v["rate"] . '%)'; ?></span>
                                                                                    <input type='text' id='tax_hidden_total_<?php echo $v['id']; ?>' value='0.00' class="hidden form-control total_tax_part" name="individual_part_tax[]" readonly/>
                                                                                </div>
                                                                                <?php
                                                                                }
                                                                            } 
                                                                        }
                                                                    } else {
                                                                        if (isset($taxes) && !empty($taxes)) {
                                                                            foreach ($taxes as $k => $v) {
                                                                                ?> 
                                                                                <div class="tax-filed-label">
                                                                                    <span class="hidden" id="span_tax_hidden_parttotal_service<?php echo $v['id']; ?>"><?php echo $v['name'] . ' (' . $v["rate"] . '%)'; ?></span>
                                                                                    <input type='text' id='tax_hidden_total_<?php echo $v['id']; ?>' value='0.00' class="hidden form-control total_tax_part" name="individual_part_tax[]" readonly/>
                                                                                </div>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td class="text-right hide_service_part_tax_amount <?php echo $service_tax_possion; ?>">
                                                                    <strong>Labor And Services tax Amount: </strong>
                                                                    <?php
                                                                    if($srv_final_array != "" && !empty($srv_final_array))
                                                                    {
                                                                        $str = $dataArr['individual_service_tax'];
                                                                        $print_tax = explode(",",$str);
                                                                        if(isset($dataArr['individual_service_tax']) && !empty($dataArr['individual_service_tax']))
                                                                        {   
                                                                        if (isset($taxes) && !empty($taxes)) {
                                                                            foreach ($taxes as $k => $v) {
                                                                                foreach ($print_tax as $i => $j) {
                                                                                    if($k == $i){
                                                                            ?> 
                                                                            <div class="tax-filed-label">
                                                                                <span><?php echo $v['name'] . ' (' . $v["rate"] . '%)'; ?></span>
                                                                                <input type='text' id='tax_hidden_total_service<?php echo $v['id']; ?>' value='<?php echo $j;?>' class="form-control total_tax_service" name="individual_service_tax[]" readonly/>
                                                                            </div>
                                                                                <?php
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        } else {
                                                                          if (isset($taxes) && !empty($taxes)) {
                                                                            foreach ($taxes as $k => $v) {
                                                                            ?> 
                                                                            <div class="tax-filed-label">
                                                                                <span class="hidden" id="span_tax_hidden_total_service<?php echo $v['id']; ?>"><?php echo $v['name'] . ' (' . $v["rate"] . '%)'; ?></span>
                                                                                <input type='text' id='tax_hidden_total_service<?php echo $v['id']; ?>' value='0.00' class="hidden form-control total_tax_service" name="individual_service_tax[]" readonly/>
                                                                            </div>
                                                                            <?php
                                                                                }
                                                                            }  
                                                                        } 
                                                                    } else {
                                                                    if (isset($taxes) && !empty($taxes)) {
                                                                        foreach ($taxes as $k => $v) {
                                                                        ?> 
                                                                        <div class="tax-filed-label">
                                                                            <span class="hidden" id="span_tax_hidden_total_service<?php echo $v['id']; ?>"><?php echo $v['name'] . ' (' . $v["rate"] . '%)'; ?></span>
                                                                            <input type='text' id='tax_hidden_total_service<?php echo $v['id']; ?>' value='0.00' class="hidden form-control total_tax_service" name="individual_service_tax[]" readonly/>
                                                                        </div>
                                                                            <?php
                                                                                    
                                                                                }
                                                                            }
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <input type="hidden" name="final_tax_rate" id="final_tax_rate">
                                                            </tr>
                                                        </table>
                                                    </td>     
                                                </tr>
                                                <tr>
                                                    <td>
                                                       <b>Total Tax :</b>
                                                    </td>     
                                                    <td class="text-right">
                                                        <span id='span_total_tax'><?php 
                                                        if(isset($dataArr) && !empty($dataArr)) 
                                                        {
                                                            $tax = number_format((float) ($tax + $s_tax), 2, '.', '');
                                                        }  
                                                        else 
                                                        { 
                                                            $tax = 0.00; 
                                                        }
                                                        echo $tax; 
                                                        ?></span>
                                                    </td>
                                                    <input type="hidden" name="final_tax_rate" id="final_tax_rate">
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="table-shipping-charge">
                                                        <table class="table">
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex">
                                                                        <label><b>Shipping Charge:</b></label>
                                                                        <?php
                                                                        if(!empty($dataArr)) {
                                                                            if(!empty($dataArr['shipping_display_status']) == 1){
                                                                                $is_checked = 'checked';
                                                                                $toggale_value = 1;
                                                                            
                                                                            } else {
                                                                                $is_checked = '';
                                                                                $toggale_value = 0;
                                                                            }
                                                                        } else {
                                                                            $is_checked = '';
                                                                            $toggale_value = 0;
                                                                        }
                                                                        ?>
                                                                        <div class="form-group">
                                                                            <input type="hidden" class="shipping_status" name="shipping_status" value="<?= $toggale_value ?>">
                                                                            <label class="chk-box-container custom-check">
                                                                                <input type="checkbox" class="change_shipping_status" <?= $is_checked ?>>
                                                                            <span class="checkmark"></span>
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <input type='text' class='form-control' value='<?php echo (isset($dataArr) && !empty($dataArr)) ? number_format((float) $dataArr['shipping_charge'], 2, '.', '') : '0.00'; ?>' name='shipping_charge' id='shipping_charge'/>
                                                                            <div class="shipping_alert hide">
                                                                               <span style="color:#2196f3; font-size: 12px;">
                                                                                    Shipping charge is more than total amount.
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="text-right">
                                                                    <span id='span_shipping_charge'><?php echo (isset($dataArr) && !empty($dataArr)) ? number_format((float) $dataArr['shipping_charge'], 2, '.', '') : '0.00'; ?></span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td><b>Total (<span class='text-semibold'><?php echo $cur ?></span>):</b></td>
                                                    <td class="text-right text-primary">
                                                        <input type='hidden' value='<?php echo $total ?>' name='total' class='total'/>
                                                        <h5 class="text-semibold"><span id='total'><?php echo $total ?></span></h5>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-20">
                        <div class="col-lg-12">
                            <legend class='payment_details'>Upload Signature:</legend>
                        </div>
                        <!-- <div class="col-md-12"> -->
                            <div class="col-sm-6 col-md-4">
                                <div class="content-group">
                                    <input type="hidden" name="signature_attachment" id="signature_attachment" />
                                    <script type="text/javascript" src="assets/js/custom_pages/front/signature_pad.js"></script>
                                </div>
                            </div>
                            <?php 
                            if (!empty($dataArr) && !empty($dataArr['signature_attachment']) && file_exists(FCPATH . 'uploads/signatures/' . $dataArr['signature_attachment'])) { 
                                ?>
                                <div class="col-sm-3 text-center signature_div manage_signatures_shadow">
                                    <div class="content-group">
                                        <img src="<?= base_url('uploads/signatures/' . $dataArr['signature_attachment'] . '?=' . time()) ?>" alt="<?= $dataArr['signature_attachment'] ?>" width="196px" height="86px" onerror="imgError(this);">
                                    </div>
                                </div>
                                <script type="text/javascript">
                                    function imgError(image) {
                                        image.onerror = "";
                                        $('.manage_signatures_shadow').removeClass('signature_div');
                                        image.src = "<?= base_url('uploads/signatures/plain_image.png') ?>";
                                        return true;
                                    }
                                </script>
                            <?php } ?>
                        <!-- </div> -->
                    </div>
                    <div class="row mt-20">
                        <!-- <div class="col-lg-12"> -->
                        <div class="col-sm-12">
                            <legend class='payment_details'>Attachments:
                                <span class="help-block">Accepted formats: PNG, JPG, JPEG, PDF</span>
                            </legend>
                        </div>
                        <!-- </div> -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 mb-20">
                                <div class="content-group mb-0">
                                    <span class="help-block col-sm-12">Attachment 1</span>
                                    <div class="uploader-wrap col-xs-12 col-sm-12">
                                        <div class="uploader">
                                            <input type="file" name="attachments[]" id="attachments_1" data-no="1" class="file-styled-primary estimate_attachments" accept="image/jpeg,image/png,application/pdf">
                                            <span class="filename" style="-webkit-user-select: none;"></span>
                                            <span class="action btn bg-info-400" style="-webkit-user-select: none;">Choose File</span>
                                        </div>
                                        <span id="message_1"></span>
                                    </div>
                                    <div class="image_wrapper_1 col-sm-2"></div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 mb-20">
                                <div class="content-group mb-0">
                                    <span class="help-block col-sm-12">Attachment 2</span>
                                    <div class="uploader-wrap col-xs-12 col-sm-12">
                                        <div class="uploader">
                                            <input type="file" name="attachments[]" id="attachments_2" data-no="2" class="file-styled-primary estimate_attachments" accept="image/jpeg,image/png,application/pdf">
                                            <span class="filename" style="-webkit-user-select: none;"></span>
                                            <span class="action btn bg-info-400" style="-webkit-user-select: none;">Choose File</span>
                                        </div>
                                        <span id="message_2"></span>
                                    </div>
                                    <div class="image_wrapper_2 col-sm-2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="content-group mb-0">
                                    <span class="help-block col-sm-12">Attachment 3</span>
                                    <div class="uploader-wrap col-sm-12">
                                        <div class="uploader">
                                            <input type="file" name="attachments[]" id="attachments_3" data-no="3" class="file-styled-primary estimate_attachments" accept="image/jpeg,image/png,application/pdf">
                                            <span class="filename" style="-webkit-user-select: none;"></span>
                                            <span class="action btn bg-info-400" style="-webkit-user-select: none;">Choose File</span>
                                        </div>
                                        <span id="message_3"></span>
                                    </div>
                                    <div class="image_wrapper_3 col-sm-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-20">
                        <!-- <div class="col-sm-12"> -->
                            <div class="col-lg-12 mt-20 button-wrap">
                                <?php if (isset($dataArr)) { ?>
                                    <button type="submit" class="btn bg-teal custom_save_button save_draft mb-3" id="save" name="save">Save</button>
                                <?php } else { ?>
                                    <!-- Save As Draft Button-->
                                    <button type="submit" class="btn bg-teal custom_save_button save_draft mb-3" name="save_draft" value="1">Save</button>
                                <?php } ?>
                                <div id="hidden_submit_value"></div>

                                <button type="button" class="btn btn-secondary custom_cancel_button m-r-10 mb-3 lightgray-btn" onclick="if (history.length > 2){
                                    window.history.back()
                                } else {
                                    window.location.href = 'estimates';
                                }">Cancel</button>
                                
                                <!-- <?php if(isset($dataArr['id'])) { ?>
                                    <a href="<?php echo base_url() . 'estimates/print_pdf_preview/' . base64_encode($dataArr['id']) ?>" class="btn btn-success heading-btn m-r-10">Preview</a>
                                <?php } else { ?>
                                    <a href="javascript:void(0);" class="btn btn-success estimate-action heading-btn m-r-10" onclick="<?php echo base_url() . 'estimates/print_pdf/' . base64_encode($dataArr['id']) ?>" data-value="2" >Preview</a>
                                <?php } ?> -->

                                <!-- <a href="javascript:void(0);" class="btn btn-success estimate-action heading-btn m-r-10" onclick="<?php echo base_url() . 'estimates/print_pdf/' . base64_encode($dataArr['id']) ?>" data-value="2" >Preview</a> -->

                                <a href="javascript:void(0);" class="btn btn-success estimate-action heading-btn m-r-10 mb-3" data-value="3" >Preview</a>

                                <!-- btn bg-indigo-300 -->
                                <button type="button" class="btn btn-primary save_send estimate-action m-r-10 disable-target mb-3" data-value="1">Email</button>
                                <!-- bg-pink-300 -->

                                <?php if(isset($dataArr['id'])) { ?>
                                    <a target="_blanck" class="btn btn-warning heading-btn m-r-10 mb-3" href="<?php echo base_url() . 'estimates/print_pdf/' . base64_encode($dataArr['id']) ?>"> Print</a>
                                <?php } else { ?>
                                    <a target="_blanck" class="btn btn-warning save_send estimate-action m-r-10 mb-3" id="print-invoice" data-value="2">Print</a>
                                <?php } ?>
                            </div>
                        <!-- </div> -->
                    </div>
                </div>
            </form>
        </div>

        <?php if (!empty($estimation_attachments) && sizeof($estimation_attachments) > 0) { ?>
            <div class="col-md-12">
                <div class="panel panel-body">
                    <h4 class="panel-title">
                        <legend class="text-bold mt-20">Attachments</legend>
                    </h4>
                    <div class="col-md-12">
                        <?php
                        foreach ($estimation_attachments as $key => $file) {
                            if (file_exists('uploads/attachments/' . $file['file_name'])) {
                                $file_url = base_url('/uploads/attachments/' . $file['file_name']);
                                if ($file['type'] == 'Image') {
                                    ?> 
                                    <div class="col-sm-6 col-md-4 col-lg-4" id="attachment_div_<?= $key ?>">
                                        <div class="attachment_div">
                                            <div class="img-wrap">
                                                <span class="close btn btn-danger btn-sm remove-attachement" data-id="<?= base64_encode($file['id']) ?>" data-divno="<?= $key ?>">&times;</span>
                                                <a href="<?= $file_url . '?=' . time() ?>" data-popup="lightbox">
                                                    <img src="<?= $file_url . '?=' . time() ?>" class="attachment-img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else if ($file['type'] == 'PDF') { ?>
                                    <div class="col-sm-6 col-md-4 col-lg-4" id="attachment_div_<?= $key ?>">
                                        <div class="attachment_div">
                                            <div class="img-wrap">
                                                <span class="close btn btn-danger btn-sm remove-attachement" data-id="<?= base64_encode($file['id']) ?>" data-divno="<?= $key ?>">&times;</span>
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
            </div>
        <?php } ?>
    </div>
    <?php $this->load->view('Templates/footer.php'); ?>
</div>
</div>

<!-- Preview Modal -->
<div class="modal fade bd-example-modal-lg pdfviewer-modal" id="preview_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe src="<?php echo $this->session->userdata('edit_print_preview'); ?>"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- View modal -->
<div id="part_list_modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Compatible Part Details</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar" id="part_list_view">
                <?php
                if (isset($itemArr['AllParts']) && !empty($itemArr['AllParts'])) {
                    foreach ($itemArr['AllParts'] as $p) {
                        ?>
                        <div class="col-sm-6">
                            <div class="panel invoice-grid">
                                <div class="panel-body border-top-primary text-center">
                                    <div class="row">
                                        <div class="col-sm-5 text-left">
                                            <h6 class="text-semibold no-margin-top"><a href="javascript:void(0)" class="btn_home_item_view" id="<?php echo base64_encode($p['id']) ?>"><?php echo $p['part_no'] ?></a></h6>
                                            <ul class="list list-unstyled">
                                                <li>Global Part:<?php echo $p['global_part'] ?></li>
                                            </ul>
                                        </div>

                                        <div class="col-sm-7">
                                            <h6 class="text-semibold text-right no-margin-top"><?php echo $cur . '' . $p['retail_price'] ?></h6>
                                            <ul class="list list-unstyled text-right">
                                                <li>Department: <span class="text-semibold"><?php echo $p['dept_name'] ?></span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-footer panel-footer-condensed">
                                    <div class="heading-elements">
                                        <span class="heading-text">
                                            <span class="status-mark border-blue position-left"></span> Vendor: <span class="text-semibold"><?php echo $p['name'] ?></span>
                                        </span>
                                        <ul class="list-inline list-inline-condensed heading-text pull-right">
                                            <li class="dropdown">
                                                <?php
                                                if ($p['total_quantity'] > 0) {
                                                    echo "<span class='text-semibold label bg-blue'>" . $p['total_quantity'] . "- In stock</span>";
                                                } else {
                                                    echo "<span class='text-semibold label bg-danger'>Out of Stock</span>";
                                                }
                                                ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<a id="scroll-top-button"></a>

<!-- View modal -->
<div id="dash_view_modal1" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Item Details</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar" id="dash_view_body1" style="height: 500px;overflow: hidden;overflow-y: scroll;"></div>
        </div>
    </div>
</div>
<?php
if (isset($services) && !empty($services)) {
    echo '<script>var services =' . json_encode($services) . '</script>';
} else {
    echo '<script>var services = ""</script>';
}

$print_preview_session_value = "";
if($this->session->userdata('edit_print_preview') != "") 
{ 
    $print_preview_session_value = $this->session->userdata('edit_print_preview'); 
    $this->session->unset_userdata('edit_print_preview');
}

?>
<script type="text/javascript">
    var edit_pdf_preview = '<?php echo $print_preview_session_value; ?>';
    var calendar_date = '<?php echo (isset($dataArr)) ? date('Y-m-d', strtotime($dataArr['estimate_date'])) : date('Y/m/d') ?>';
    var ITEMS_IMAGE_PATH = '<?php echo ITEMS_IMAGE_PATH ?>';
    var edit_div = '<?php echo $edit_div ?>';
    var date_format = '<?php echo (isset($format) && !empty($format)) ? $format['name'] : 'Y/m/d' ?>';
    var make_id = '<?php echo (isset($dataArr) && !empty($dataArr)) ? $dataArr['make_id'] : '' ?>';
    var modal_id = '<?php echo (isset($dataArr) && !empty($dataArr)) ? $dataArr['modal_id'] : '' ?>';
    var item_make_id = '<?php echo (isset($itemArr['item_make_id']) && !empty($itemArr)) ? $itemArr['item_make_id'] : '' ?>';
    var item_modal_id = '<?php echo (isset($itemArr['item_model_id']) && !empty($itemArr)) ? $itemArr['item_model_id'] : '' ?>';

    // Multiple item_make_id and item_modal_id
    var item_make_id_multiple = '<?php echo (isset($itemArr_multi['items_list']) && !empty($itemArr_multi)) ? array_column($itemArr_multi['items_list'],'item_make_id')[0] : '' ?>';
    var item_modal_id_multiple = '<?php echo (isset($itemArr_multi['items_list']) && !empty($itemArr_multi)) ? array_column($itemArr_multi['items_list'],'item_model_id')[0] : '' ?>';

    var estimate_id = '<?php echo $estimate_id ?>';
    var locations = <?php echo (isset($locations) && !empty($locations)) ? json_encode($locations) : '' ?>;
    var taxes = <?php echo (isset($taxes) && !empty($taxes)) ? json_encode($taxes) : '' ?>;
    var item_arr = '<?php echo (isset($itemArr) && !empty($itemArr)) ? json_encode($itemArr) : '' ?>';
    var item_id = '<?php echo (isset($itemArr) && !empty($itemArr)) ? $itemArr['id'] : '' ?>';
    var currency = '<?php echo (isset($currency) && !empty($currency)) ? $currency['symbol'] : '$' ?>';
    var signature_attachment = '<?= (!empty($dataArr) && !empty($dataArr['signature_attachment'])) ? base_url('uploads/signatures/' . $dataArr['signature_attachment']) : '' ?>';
    var segment = '<?php echo $this->uri->segment(3);?>';

    var authUrl = "<?= (!empty($_SESSION['authUrl'])) ? $_SESSION['authUrl'] : ""; ?>";
    var estimate_notification = "<?= ($this->session->set_userdata('estimate_notification') ? $this->session->set_userdata('estimate_notification') : '0')  ?>";
    var print_preview_url = '<?php echo $this->session->userdata('print_preview_url'); ?>';
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/estimates.js?version='<?php echo time();?>'"></script>
<style>
    .custom_save_button{width: 133px !important;}
    .save_draft{background-color: #1c2f3b!important;}
    .span_quantity{font-size: x-small;font-weight: 500;}
    .plus{/*width: 50%;*//*float: left;*/display: block;}
    select,select:focus {border-style: unset !important;outline: none !important;outline-color: none !important;}
    .input_discount{width:100px !important;}
    .payment_details{font-size: 14px !important;text-transform: capitalize !important;margin-top: 1px !important;font-weight: 400 !important;}
    .disabled_div {background: #e0e0e047;}
    .fab-menu {position: relative;display: inherit;}
    /*    ul.fab-menu-inner li {width: 445px !important;}*/
    .discount_rate{margin-top: 5px;margin-right: 5px;}
    .tax_rate{margin-top: 7px;margin-left: 18px;}
    .fab-menu-bottom-right[data-fab-state="open"] .fab-menu-inner > li:nth-child(1) {top: -45px !important;}
    .fab-menu-bottom-right[data-fab-state="open"] .fab-menu-inner > li:nth-child(2) {top: -90px !important;}
    .fab-menu-bottom-right[data-fab-state="open"] .fab-menu-inner > li:nth-child(3) {top: -136px !important;}
    .part_list{margin-left: 20px;}
    #part_list_modal .panel-footer-condensed {padding-top: 2px;padding-bottom: 9px;}
    #part_list_modal .panel {border-color: #33a9f5;}
    #part_list_view{min-height: 500px;overflow: hidden;overflow-y: scroll;}
    .input_rate,.input_service_rate,.input_service_discount,input_discount { display:inline-block;min-width: 90px;width: 90px;max-width: 100px; }

    .part_detail_table .table > tbody > tr > td .row .col-md-8.text-left{display: inline-block;float: left;width: 80%;padding: 0;}
    .part_detail_table .table > tbody > tr > td .row .col-md-4.mt-3 {width: inherit;padding: 0;float: none;} 

    .button-wrap{display: -webkit-box;display: -ms-flexbox;display: flex;flex-wrap: wrap;}
    .button-wrap .custom_save_button{margin-right: 8px;}

    .fab-menu-bottom-right{right: inherit;bottom: inherit;}

    .signature_div{border: 1px solid #e2e2d6;box-shadow: 3px 3px 3px #ededed;}
    .attachment_div{border: 1px solid #e2e2d6;box-shadow: 0 0 8px 2px #ededed; text-align:center;margin-bottom: 
        15px;}
    .attachment-img{width: 182px !important;height: 182px !important;}
    .pdf-img{width: 139px !important;}
    .error{color: red;}
    .img-wrap {position: relative;}
    .img-wrap .close { position: absolute;top: 7px;right: 7px;z-index: 100;color: white;opacity: 1;}

    @media(max-width:991px){
        .attachment_div img {width: 100%; height: auto;}
    }
    @media(max-width:990px){
        .part_detail_table.table > tbody > tr > td .row .col-md-8 {padding: 0 !important;float: left !important;}
        .part_detail_table.table > tbody > tr > td .row .col-md-8  span#plus_1 { margin-right: 10px !important;}
        .part_detail_table.table > tbody > tr > td .row .col-md-4{padding: 0 !important;float: left !important;margin-left: 0px!important;}           
    }
    @media(max-width:768px){
        .attachment_div{margin-bottom: 30px;}
    }
    @media(max-width:767px){
        td#td_part_no_1 span {width: 250px !important;}
    }
    @media(max-width:375px){
        .fab-menu-inner > li{left: -12px;}
    }
</style>