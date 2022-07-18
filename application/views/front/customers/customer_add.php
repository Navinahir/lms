<?php $this->session->set_userdata('referred_from', current_url()); ?>
<script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.date.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/legacy.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/inputs/formatter.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/ui/fab.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/ui/prism.min.js"></script>
<script type="text/javascript" src="assets/js/jquery_ui.js"></script>

<script type="text/javascript" src="assets/js/plugins/forms/tags/tagsinput.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/tags/tokenfield.min.js"></script>
<script type="text/javascript" src="assets/js/pages/form_tags_input.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/inputs/typeahead/typeahead.bundle.min.js"></script>

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
    $form_action = site_url('customers/edit/' . base64_encode($dataArr['id']));
} else {
    $form_action = site_url('customers/add');
}
?>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('customers') ?>">Customers</a></li>
            <li class="active">
                <?php
                if (isset($dataArr)) {
                    echo "Edit";
                    $title = "Edit Customer";
                } else {
                    echo "Add";
                    $title = "New Customer";
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
            <form method="post" action="<?php echo $form_action; ?>" class="form-horizontal" id="customer_form" enctype="multipart/form-data" >
                <?php $cur = (isset($currency) && !empty($currency)) ? $currency['symbol'] : '$'; ?>
                <div class="panel panel-body">
                    <?php $this->load->view('alert_view'); ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="panel-title mb-20"><span class="text-primary"><?php echo $title ?></span></h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-4 control-label required">Display Name As</label>
                                        <div class="col-md-12 col-lg-8">
                                            <select class="form-control select-size-lg display_name_as" data-placeholder="Make Display Name As" name="display_name_as">        
                                            <?php if($dataArr['display_name_as']){ ?>
                                                    <option class="display_name_as"><?php echo $dataArr['display_name_as']; ?></option>
                                            <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-4 control-label" style="color: red;"><b>Note:</b></label>
                                        <div class="col-md-12 col-lg-8">
                                            <span style="color: red;">Display name as is a combination of first name and last name or company name. This name will displayed in the whole website.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-4 control-label required">First Name</label>
                                        <div class="col-md-12 col-lg-8">
                                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" value="<?= (!empty($dataArr) && !empty($dataArr['first_name'])) ? $dataArr['first_name'] : '' ?>" />
                                            <label id="first_name_error2" class="validation-error-label" for="first_name"><?= form_error('first_name') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-4 control-label required">Last Name</label>
                                        <div class="col-md-12 col-lg-8">
                                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" value="<?= (!empty($dataArr) && !empty($dataArr['last_name'])) ? $dataArr['last_name'] : '' ?>" />
                                            <label id="last_name_error2" class="validation-error-label" for="last_name"><?= form_error('last_name') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-4 control-label">Company Name</label>
                                        <div class="col-md-12 col-lg-8">
                                            <input type="text" class="form-control" name="company_name" id="company_name" placeholder="Company Name" value="<?= (!empty($dataArr) && !empty($dataArr['company'])) ? $dataArr['company'] : '' ?>" />
                                            <label id="company_name_error2" class="validation-error-label" for="company_name"><?= form_error('company_name') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-4 control-label">Phone</label>
                                        <div class="col-md-12 col-lg-8">
                                            <input type="text" class="form-control format-phone-number" name="phone" id="phone" placeholder="Phone" value="<?= (!empty($dataArr) && !empty($dataArr['phone'])) ? $dataArr['phone'] : '' ?>" />
                                            <label id="phone_error2" class="validation-error-label" for="phone"><?= form_error('phone') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-4 control-label">Mobile</label>
                                        <div class="col-md-12 col-lg-8">
                                            <input type="text" class="form-control format-phone-number" name="mobile" id="mobile" placeholder="Mobile" value="<?= (!empty($dataArr) && !empty($dataArr['mobile'])) ? $dataArr['mobile'] : '' ?>" />
                                            <label id="mobile_error2" class="validation-error-label" for="mobile"><?= form_error('mobile') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-4 control-label">Fax</label>
                                        <div class="col-md-12 col-lg-8">
                                            <input type="text" class="form-control" name="fax" id="fax" placeholder="Fax" value="<?= (!empty($dataArr) && !empty($dataArr['fax'])) ? $dataArr['fax'] : '' ?>" />
                                            <label id="fax_error2" class="validation-error-label" for="fax"><?= form_error('fax') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <!-- <label class="col-md-3 control-label">Primary Email</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="email" id="email" placeholder="Primary Email" value="<?= (!empty($dataArr) && !empty($dataArr['email'])) ? $dataArr['email'] : '' ?>" />
                                            <label id="email_error2" class="validation-error-label" for="email"><?= form_error('email') ?></label>
                                        </div> -->
                                        <?php 
                                            $email_list = [];
                                            foreach ($emailArr as $key => $value) {
                                                array_push($email_list, $value['customer_email']);
                                            }
                                            $final_email_list = implode(',', $email_list);
                                        ?>
                                        <label class="col-md-12 col-lg-4 control-label">Multiple Email</label>
                                        <div class="col-md-12 col-lg-8">
                                            <input type="text" value="<?php echo $final_email_list; ?>" class="form-control tags-input" id="multiple_email_list" name="multiple_email">
                                            <span class="invalid_email_alert" style="color: red; display: none;">Invalid email format.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <table class="table part_detail_table table-bordered table_one">
                                            <thead>
                                                <tr>
                                                    <th width="80%">Secondary email</th>
                                                    <th width="10%">Status</th>
                                                    <th width="5%"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="email_div">
                                                <?php if($emailArr != "" && !empty($emailArr)) { 
                                                    foreach ($emailArr as $k => $value) { 
                                                        $key = ++$k;
                                                        ?>
                                                    <tr class='email_tr' id='tr_<?php echo $key ?>' data-value='<?php echo $key ?>'>
                                                        <td><input type="text" class="form-control" name="secondary_email[]" id="secondary_email_<?php echo $key ?>" placeholder="Secondary Email" value="<?php echo $value['customer_email']; ?>" />
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" class="email_status" name="secondary_email_status[]" id="secondary_email_status_<?php echo $key ?>" style="height:25px;width:50px;"  <?php if($value['status'] == 1) { ?> checked="checked" <?php } ?> value="<?php echo $value['status']; ?>" data-attr="<?php echo $key ?>">
                                                        </td>
                                                        <td>
                                                            <span class='remove' id='remove_<?php echo $key ?>' data-attr='<?php echo $key ?>'><i class='icon-trash'></i></span>
                                                        </td>
                                                    </tr>
                                                <?php } } else { ?>
                                                    <tr class='email_tr' id='tr_1' data-value='1'>
                                                        <td><input type="text" class="form-control" name="secondary_email[]" id="secondary_email_1" placeholder="Secondary Email" value="" />
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" class="email_status" name="secondary_email_status[]" id="secondary_email_status_1" style="height:25px;width:50px;" checked="checked" value="1" data-attr="1">
                                                        </td>
                                                        <td>
                                                            <span class='remove' id='remove_1' data-attr='1'><i class='icon-trash'></i></span>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <div class="col-md-3">
                                            <a href="javascript:void(0);" class="btn btn-success add_email">Add Email</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <legend class="text-bold mt-20">Billing Details</legend>

                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-4 control-label">Billing Address</label>
                                        <div class="col-md-12 col-lg-8">
                                            <textarea name="billing_address" id="billing_address" placeholder="Billing Address" class='form-control' rows='4' ><?= (!empty($dataArr) && $dataArr['billing_address']) ? $dataArr['billing_address'] : '' ?></textarea>
                                            <label id="billing_address_error2" class="validation-error-label" for="billing_address"><?= form_error('billing_address') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-md-8 col-lg-6">
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-4 control-label">City</label>
                                        <div class="col-md-12 col-lg-8">
                                            <input type="text" class="form-control" placeholder="City" name="billing_address_city" id="billing_address_city" value="<?= (!empty($dataArr) && $dataArr['billing_address_city']) ? $dataArr['billing_address_city'] : '' ?>" />
                                            <!--<label id="receipt_number_error2" class="validation-error-label" for="receipt_number"><?= form_error('billing_address_city') ?></label>-->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-4 control-label">State</label>
                                        <div class="col-md-12 col-lg-8">
                                            <input type="text" class="form-control" name="billing_address_state" id="billing_address_state" placeholder="State" value="<?= (!empty($dataArr) && $dataArr['billing_address_state']) ? $dataArr['billing_address_state'] : '' ?>" />
                                        <!--<label id="total_receipt_amount_error2" class="validation-error-label" for="total_receipt_amount"><?= form_error('billing_address_state') ?></label>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4 col-lg-6">
                            <div class="form-group has-feedback select_form_group">
                                <label class="col-md-12 col-lg-4 control-label">Zip Code</label>
                                <div class="col-md-12 col-lg-8">
                                    <input type="text" class="form-control" placeholder="Zip Code" name="billing_address_zip" id="billing_address_zip" value="<?= (!empty($dataArr) && $dataArr['billing_address_zip']) ? $dataArr['billing_address_zip'] : '' ?>" />
                                    <!--<label id="receipt_number_error2" class="validation-error-label" for="receipt_number"><?= form_error('billing_address_zip') ?></label>-->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="row">
                        <div class="col-lg-12">
                            <div class="row"> -->
                                <!-- <div class="col-sm-6">
                                    <div class="form-group has-feedback select_form_group">
                                        <label class="col-md-3 control-label">Street</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" placeholder="Street" name="billing_address_street" id="billing_address_street" value="<?= (!empty($dataArr) && $dataArr['billing_address_street']) ? $dataArr['billing_address_street'] : '' ?>" /> -->
                                            <!--<label id="receipt_number_error2" class="validation-error-label" for="receipt_number"><?= form_error('billing_address_street') ?></label>-->
                                        <!-- </div>
                                    </div>
                                </div> -->
                                
                           <!--  </div>
                        </div>
                    </div> -->

                    <legend class="text-bold mt-20">Shipping Details</legend>

                    <div class="row mb-10">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="custom-control custom-checkbox">
                                        <span class="checked">
                                            <input type="checkbox" class="styled" name="checkbox_status" value="0" id="copy_billing_address" />
                                        </span>&nbsp;&nbsp;Same as billing address
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-4 control-label">Shipping Address</label>
                                        <div class="col-md-12 col-lg-8">
                                            <textarea name="shipping_address" id="shipping_address" placeholder="Shipping Address" class='form-control' rows='4' ><?= (!empty($dataArr) && $dataArr['shipping_address']) ? $dataArr['shipping_address'] : '' ?></textarea>
                                            <label id="shipping_address_error2" class="validation-error-label" for="shipping_address"><?= form_error('shipping_address') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-md-8 col-lg-6">
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-4 control-label">City</label>
                                        <div class="col-md-12 col-lg-8">
                                            <input type="text" class="form-control" placeholder="City" name="shipping_address_city" id="shipping_address_city" value="<?= (!empty($dataArr) && $dataArr['shipping_address_city']) ? $dataArr['shipping_address_city'] : '' ?>" />
                                            <!--<label id="receipt_number_error2" class="validation-error-label" for="receipt_number"><?= form_error('shipping_address_city') ?></label>-->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-4 control-label">State</label>
                                        <div class="col-md-12 col-lg-8">
                                            <input type="text" class="form-control" name="shipping_address_state" id="shipping_address_state" placeholder="State" value="<?= (!empty($dataArr) && $dataArr['shipping_address_state']) ? $dataArr['shipping_address_state'] : '' ?>" />
                                        <!--<label id="total_receipt_amount_error2" class="validation-error-label" for="total_receipt_amount"><?= form_error('shipping_address_state') ?></label>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4 col-lg-6">
                            <div class="form-group has-feedback select_form_group">
                                <label class="col-md-12 col-lg-4 control-label">Zip Code</label>
                                <div class="col-md-12 col-lg-8">
                                    <input type="text" class="form-control" placeholder="Zip Code" name="shipping_address_zip" id="shipping_address_zip" value="<?= (!empty($dataArr) && $dataArr['shipping_address_zip']) ? $dataArr['shipping_address_zip'] : '' ?>" />
                                    <!--<label id="receipt_number_error2" class="validation-error-label" for="receipt_number"><?= form_error('shipping_address_zip') ?></label>-->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="row">
                        <div class="col-lg-12">
                            <div class="row"> -->
                                <!-- <div class="col-sm-6">
                                    <div class="form-group has-feedback select_form_group">
                                        <label class="col-md-3 control-label">Street</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" placeholder="Street" name="shipping_address_street" id="shipping_address_street" value="<?= (!empty($dataArr) && $dataArr['shipping_address_street']) ? $dataArr['shipping_address_street'] : '' ?>" /> -->
                                            <!--<label id="receipt_number_error2" class="validation-error-label" for="receipt_number"><?= form_error('shipping_address_street') ?></label>-->
                                        <!-- </div>
                                    </div>
                                </div> -->
                                
                            <!-- </div>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-lg-12 mt-20 button-wrap">
                            <button type="submit" class="btn bg-teal custom_save_button save_draft" name="save">Save</button>
                            <button type="button" class="btn btn-default custom_cancel_button" onclick="if (history.length > 2) {
                                        window.history.back()
                                    } else {
                                        window.location.href = 'orders';
                                    }">Cancel</button>
                            <?php $delete_id = $this->uri->segment(3); ?>
                            &nbsp; &nbsp;
                            <?php if($this->uri->segment(2) == "edit") { ?>
                                <a href="<?php echo base_url('customers/delete/'.$delete_id); ?>" class="btn btn-danger custom_save_button save_draft" onclick="return confirm_alert(this)">Delete</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php $this->load->view('Templates/footer.php'); ?>
</div>
<script>
    var date_format = '<?php echo (isset($format) && !empty($format)) ? $format['name'] : 'Y/m/d' ?>';
    var customer_id = '';
    var uri_edit = '<?php echo $this->uri->segment(2) == 'edit' ? 1 : ''; ?>';
    var checkbox_status = '<?php echo (isset($dataArr) && $dataArr['checkbox_status']) == 1 ? 'checked' : ''; ?>';
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/customers.js?version='<?php echo time();?>'"></script>
<style>
    .plus{width: 50%;float: left;}
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
    .button-wrap{display: -webkit-box;display: -ms-flexbox;display: flex;}
    .button-wrap .custom_save_button{margin-right: 8px;}

    .fab-menu-bottom-right{right: inherit;bottom: inherit;}


    @media(max-width:375px){
        .fab-menu-inner > li{left: -12px;}
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        $('#company_name,#last_name,#first_name').keyup(function(){

            // Replace < & > to blank
            $(this).val($(this).val().replace(/</g, "").replace(/>/g, ""));

            var companyname = $('#company_name').val(); 
            var firstname = $('#first_name').val();
            var lastname = $('#last_name').val();
            var fullname = $('#first_name').val() +' '+ $('#last_name').val();   

            if(companyname !== "") {
                $('.display_name_as').val(companyname).empty();
                $('.display_name_as').val(companyname).append('<option value="'+companyname+'">'+ companyname +'</option>');
                $('.display_name_as').val(fullname).append('<option value="'+fullname+'">'+fullname+'</option>');
            } else {
                $('.display_name_as').val(companyname).empty();
                $('.display_name_as').val(fullname).append('<option value="'+fullname+'">'+fullname+'</option>');
            }

            if(firstname !== "" || lastname !== "")
            {
                $('.display_name_as').val(fullname).empty();
                $('.display_name_as').val(fullname).append('<option value="'+fullname+'">'+fullname+'</option>');
                $('.display_name_as').val(companyname).append('<option value="'+companyname+'">'+companyname+'</option>');
            } else {
                $('.display_name_as').val(fullname).empty();
                $('.display_name_as').val(companyname).append('<option value="'+companyname+'">'+companyname+'</option>');
            } 
        });
    });
</script>