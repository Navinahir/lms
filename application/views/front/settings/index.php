<script type="text/javascript" src="assets/js/plugins/forms/inputs/formatter.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/wysihtml5.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/toolbar.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/parsers.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/locales/bootstrap-wysihtml5.ua-UA.js"></script>
<script type="text/javascript" src="assets/js/plugins/notifications/jgrowl.min.js"></script>

<style type="text/css">
    .chk-box-wrap .chk-box-container{
        padding-left: 35px !important;
    }
    @media screen and (max-width:767px){
        .chk-box-wrap ~ span{
            font-size: 15px !important;
        }
        .chk-box-wrap .chk-box-container{
            padding-left: 25px !important;
        }
        .chk-box-wrap .chk-box-container .checkmark{
            width: 22px;
            height: 22px;
        }
        .chk-box-wrap .chk-box-container .checkmark:after{
            left: 8px;
            top: 3px;
        }
    }
</style>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Settings</li>
            <li class="active">Customization</li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>
<?php
if (checkUserLogin('R') != 4) {
    $controller = $this->router->fetch_class();
    if (!empty(MY_Controller::$access_method) && (array_key_exists('view', MY_Controller::$access_method[$controller]) && !array_key_exists('edit', MY_Controller::$access_method[$controller]))) {
        $view = 1;
    }
    ?>
    <script>
        view_setting = '<?php echo (isset($view) && $view == 1) ? $view : 0; ?>';
    </script>
<?php } ?>
<div class="content" id="setting_content">
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="<?php echo site_url('settings'); ?>" id="add_setting_form" enctype="multipart/form-data">
                <div class="panel panel-body login-form">
                    <?php $this->load->view('alert_view'); ?>
                    <div id="payment-errors"></div>
                    <legend class="text-bold">Manage Settings</legend>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group has-feedback has-feedback-left">
                                <div class="row">
                                    <label class="col-md-12 col-lg-3 control-label lb-w-150 pl-xs-3">Vendors <span class="text-danger">*</span></label>
                                    <div class='col-md-12 col-lg-9 input-controlstyle-150'>
                                        <select data-placeholder="Select a Vendors" class="select-size-lg" name="vendor_id[]" id='vendor_id' multiple required="required">
                                            <option></option>
                                            <optgroup label="Vendors">
                                                <?php
                                                if (isset($vendors) && !empty($vendors)):
                                                    foreach ($vendors as $s):
                                                        $selected = '';
                                                        if (isset($dataArr) && !empty($dataArr)) {
                                                            if ($dataArr['vendor_id'] == null || $dataArr['vendor_id'] == '') {
                                                                $selected = 'selected="selected"';
                                                            } else {
                                                                $vendor = explode(',', $dataArr['vendor_id']);
                                                                if (in_array($s['id'], $vendor)):
                                                                    $selected = 'selected="selected"';
                                                                endif;
                                                            }
                                                        }else {
                                                            $selected = 'selected="selected"';
                                                        }
                                                        ?>
                                                        <option value="<?php echo $s['id'] ?>" <?php echo $selected ?>><?php echo $s['name'] ?></option>
                                                        <?php
                                                    endforeach;
                                                endif;
                                                ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <br/> -->
                    <div class="row ">
                        <div class="col-sm-12">
                            <div class="form-group has-feedback has-feedback-left">
                                <div class="row">
                                    <label class="col-md-12 col-lg-3 control-label lb-w-150 pl-xs-3">Tools and Equipments <span class="text-danger">*</span></label>
                                    <div class='col-md-12 col-lg-9 input-controlstyle-150'>
                                        <select data-placeholder="Select a Equipments" class="select-size-lg" name="equipment_id[]" id='equipment_id' multiple required="required">
                                            <option></option>
                                            <optgroup label="Equipments">
                                                <?php
                                                if (isset($equipments) && !empty($equipments)):
                                                    foreach ($equipments as $k => $s):
                                                        $selected = '';
                                                        if (isset($dataArr) && !empty($dataArr)) {
                                                            if ($dataArr['equipment_id'] == null || $dataArr['equipment_id'] == '') {
                                                                $selected = 'selected="selected"';
                                                            } else {
                                                                $equipment = explode(',', $dataArr['equipment_id']);
                                                                if (in_array($s['id'], $equipment)):
                                                                    $selected = 'selected="selected"';
                                                                endif;
                                                            }
                                                        }else {
                                                            $selected = 'selected="selected"';
                                                        }
                                                        ?>
                                                        <option value="<?php echo $s['id'] ?>" <?php echo $selected ?>><?php echo $s["equip_name"] . ' (' . $s['manu_name'] . ')'; ?></option>
                                                        <?php
                                                    endforeach;
                                                endif;
                                                ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <legend class="text-bold">Estimation & Invoice Setting</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <div class="row">
                                    <label class="col-md-4 control-label pl-xs-3">Estimation Setting</label>
                                    <div class='col-md-8'>
                                        <?php 
                                        if(isset($fieldArr) && !empty($fieldArr))
                                        {
                                            foreach ($fieldArr as $key => $value) {
                                                $est_checked = "";

                                                if($dataArr['is_edited'] == 1 && $dataArr['estimate_field'] == "") {
                                                    $est_checked = "";
                                                } else {
                                                    if($dataArr['estimate_field'] == "" && $dataArr['estimate_field'] == null){
                                                        $est_checked = "checked";
                                                    } else {
                                                        $est_checked_field = explode(',', $dataArr['estimate_field']);
                                                        if(in_array($value['id'], $est_checked_field)){
                                                            $est_checked = "checked";
                                                        }
                                                    }
                                                }  
                                                ?>
                                                <div class="chk-box-wrap">
                                                    <label class="chk-box-container" id="count_checkbox">
                                                        <input type="checkbox" name="estimate_id[]" <?php echo $est_checked; ?> value="<?php echo $value['id']; ?>" style="height: 25px; width: 25px;">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <span style="vertical-align: super; font-size: 18px;">
                                                    <?php echo '&nbsp;&nbsp;'.$value['field_name'].'<br/>'; ?>
                                                </span>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <div class="row">
                                    <label class="col-md-3 control-label pl-xs-3">Invoice Setting</label>
                                    <div class='col-md-9'>
                                        <?php 
                                        if(isset($fieldArr) && !empty($fieldArr))
                                        {
                                            foreach ($fieldArr as $key => $value) {
                                                $inv_checked = "";

                                                if($dataArr['is_edited'] == 1 && $dataArr['invoice_field'] == "") {
                                                    $inv_checked = "";
                                                } else {
                                                    if($dataArr['invoice_field'] == "" && $dataArr['invoice_field'] == null){
                                                        $inv_checked = "checked";
                                                    } else {
                                                        $inv_checked_field = explode(',', $dataArr['invoice_field']);
                                                        if(in_array($value['id'], $inv_checked_field)){
                                                            $inv_checked = "checked";
                                                        }
                                                    }
                                                } 
                                                ?>
                                                <div class="chk-box-wrap">
                                                    <label class="chk-box-container" id="count_checkbox">
                                                        <input type="checkbox" name="invoice_id[]" <?php echo $inv_checked; ?> value="<?php echo $value['id']; ?>" style="height: 25px; width: 25px;">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <span style="vertical-align: super; font-size: 18px;">
                                                    <?php echo '&nbsp;&nbsp;'.$value['field_name'].'<br/>'; ?>
                                                </span>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <legend class="text-bold">Print Label Size</legend>
                    <div class="print-labeldiv">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="print-wrap">
                                    <div class="width-div">
                                        <label>Label Size</label>
                                        <?php if(isset($dataArr['label_size']) && $dataArr['label_size'] != "") {  } else {  } ?>
                                        <select data-placeholder="Select Label Size" class="select-size-lg" name="label_size" id='label_size'>
                                            <optgroup label="Label Size">
                                            <option value="1 1" <?php if(isset($dataArr['label_size']) && $dataArr['label_size'] == "1 1") { echo "selected"; } else { } ?> >1inch X 1inch</option>
                                            <option value="1 2" <?php if(isset($dataArr['label_size']) && $dataArr['label_size'] == "1 2") { echo "selected"; } else {  } ?>>1inch X 2inch</option>
                                            <option value="1 3" <?php if(isset($dataArr['label_size']) && $dataArr['label_size'] == "1 3") { echo "selected"; } else {  } ?>>1inch X 3inch</option>
                                            <option value="1 4" <?php if(isset($dataArr['label_size']) && $dataArr['label_size'] == "1 4") { echo "selected"; } else {  } ?>>1inch X 4inch</option>
                                            <option value="2 1" <?php if(isset($dataArr['label_size']) && $dataArr['label_size'] == "2 1") { echo "selected"; } else {  } ?>>2inch X 1inch</option>
                                            
                                            <?php if(isset($dataArr['label_size']) && $dataArr['label_size'] != "") { ?>
                                                <option value="2 2" <?php if(isset($dataArr['label_size']) && $dataArr['label_size'] == "2 2") { echo "selected"; } else {  } ?>>2inch X 2inch</option>
                                            <?php } else { ?>
                                                <option value="2 2" selected >2inch X 2inch</option>
                                            <?php } ?>
                                            
                                            <option value="2 3" <?php if(isset($dataArr['label_size']) && $dataArr['label_size'] == "2 3") { echo "selected"; } else {  } ?>>2inch X 3inch</option>
                                            <option value="2 4" <?php if(isset($dataArr['label_size']) && $dataArr['label_size'] == "2 4") { echo "selected"; } else {  } ?>>2inch X 4inch</option>
                                            <option value="3 1" <?php if(isset($dataArr['label_size']) && $dataArr['label_size'] == "3 1") { echo "selected"; } else {  } ?>>3inch X 1inch</option>
                                            <option value="3 2" <?php if(isset($dataArr['label_size']) && $dataArr['label_size'] == "3 2") { echo "selected"; } else {  } ?>>3inch X 2inch</option>
                                            <option value="3 3" <?php if(isset($dataArr['label_size']) && $dataArr['label_size'] == "3 3") { echo "selected"; } else {  } ?>>3inch X 3inch</option>
                                            <option value="3 4" <?php if(isset($dataArr['label_size']) && $dataArr['label_size'] == "3 4") { echo "selected"; } else {  } ?>>3inch X 4inch</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="blankbox"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <legend class="text-bold">Estimation Preference Setting</legend>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group has-feedback has-feedback-left">
                                <div class="row w-auto">
                                    <label class="col-md-12 col-lg-3 control-label lb-w-150 pl-3 pr-3">Terms and Conditions</label>
                                    <div class='col-md-12 col-lg-9 input-controlstyle-150'>
                                        <!-- class = "wysihtml5 wysihtml5-default" -->
                                        <textarea id="estimate_terms_and_condition" class="form-control" name="estimate_terms_condition" id='estimate_terms_condition' placeholder="Your company's Terms and Conditions will be addeded from here. You can show it in the Estimation view below page."><?php echo (isset($dataArr) && !empty($dataArr)) ? $dataArr['estimate_terms_condition'] : null ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <legend class="text-bold" >Invoice Preference Setting</legend>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group has-feedback has-feedback-left one_feedback">
                                <div class="row w-auto">
                                    <label class="col-md-12 col-lg-3 control-label lb-w-150 pl-3 pr-3">Terms and Conditions</label>
                                    <div class='col-md-12 col-lg-9 input-controlstyle-150'>
                                        <!-- class = "wysihtml5 wysihtml5-default" -->
                                        <textarea id="invoice_terms_and_condition" class="form-control" name="invoice_terms_condition" id='invoice_terms_condition' placeholder="Your company's Terms and Conditions will be addeded from here. You can show it in the Invoice view below page."><?php echo (isset($dataArr) && !empty($dataArr)) ? $dataArr['invoice_terms_condition'] : null ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                 <!--    <br/> -->
                    <div class="row save_btn_div">
                        <div class="form-group">
                            <div class="col-lg-12 pl-3 pr-3">
                                <button type="submit" class="btn bg-blue custom_save_button btn_login">Save</button>
                                <button type="button" class="btn btn-default custom_cancel_button" onclick="if (history.length > 2) {
                                            window.history.back()
                                        } else {
                                            window.location.href = 'dashboard';
                                        }">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php $this->load->view('Templates/footer.php'); ?>
</div>
<script type="text/javascript" src="assets/js/custom_pages/front/setting.js?version='<?php echo time();?>'"></script>
<style>
    @media(max-width:480px){
        .select-lg.select2-selection--multiple .select2-selection__choice {white-space: normal;}
    }
</style>
<!-- CK Editer -->
<script src="//cdn.ckeditor.com/4.11.3/full/ckeditor.js"></script>
<script>CKEDITOR.replace('estimate_terms_and_condition');</script>
<script>CKEDITOR.replace('invoice_terms_and_condition');</script>