<script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.date.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/legacy.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/inputs/formatter.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/ui/fab.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/ui/prism.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/tags/tagsinput.min.js"></script>
<script type="text/javascript" src="assets/js/jquery_ui.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/inputs/formatter.min.js"></script>

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
    $form_action = site_url('orders/edit/' . base64_encode($dataArr['id']));
} else {
    $form_action = site_url('orders/add');
}
?>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('orders') ?>">Orders</a></li>
            <li class="active">
                <?php
                if (isset($dataArr)) {
                    echo "Edit";
                    $title = "Edit Order";
                } else {
                    echo "Add";
                    $title = "New Order";
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
            <form method="post" action="<?php echo $form_action; ?>" class="form-horizontal" id="add_order_form" enctype="multipart/form-data" >
                <?php $cur = (isset($currency) && !empty($currency)) ? $currency['symbol'] : '$'; ?>
                <div class="panel panel-body">
                    <?php $this->load->view('alert_view'); ?>
                    <h4 class="panel-title"><span class="text-primary"><?php echo $title ?></span></h4>
                    <legend class="text-bold mt-20">Order Details</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Date</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="icon-calendar5"></i></span>
                                                <input type="text" class="form-control <?= (!empty($dataArr['current_date'])) ? '' : 'date' ?>" data-value="<?= (!empty($dataArr['current_date'])) ? date('m/d/y', strtotime($dataArr['current_date'])) : date('m/d/y') ?>" placeholder="Date" name='current_date' id="current_date" <?= (!empty($dataArr['current_date'])) ? 'readonly' : '' ?> value="<?= (!empty($dataArr['current_date'])) ? date('m/d/y', strtotime($dataArr['current_date'])) : date('m/d/y') ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Ordered Date</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="icon-calendar5"></i></span>
                                                <input type="text" class="form-control <?= (!empty($dataArr['ordered_date'])) ? '' : 'date' ?>" placeholder="Date" name='ordered_date' id="ordered_date" value="<?= (!empty($dataArr['ordered_date'])) ? date('m/d/y', strtotime($dataArr['ordered_date'])) : date('m/d/y') ?>" <?= (!empty($dataArr['ordered_date'])) ? 'readonly' : '' ?> data-value="<?= (!empty($dataArr['ordered_date'])) ? date('m/d/y', strtotime($dataArr['ordered_date'])) : date('m/d/y') ?>" />
                                            </div>
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
                                    <div class="form-group has-feedback select_form_group">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Taken By</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <select data-placeholder="-Select-" class="select select-size-sm" id="ordered_taken_user_id" name="ordered_taken_user_id">
                                                <option value=''>-Select-</option>
                                                <?php
                                                if (!empty($business_users)) {
                                                    foreach ($business_users as $active_user) {
                                                        if (!empty($dataArr) && $dataArr['order_taken_user_id'] == $active_user['id']) {
                                                            $selected = 'selected';
                                                        } else {
                                                            $selected = '';
                                                        }
                                                        ?>
                                                        <option value="<?= $active_user['id'] ?>" <?= $selected ?>><?= $active_user['first_name'] . ' ' . $active_user['last_name'] ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback select_form_group">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Ordered By</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <select data-placeholder="-Select-" class="select select-size-sm" id="ordered_given_user_id" name="ordered_given_user_id">
                                                <option value=''>-Select-</option>
                                                <?php
                                                if (!empty($business_users)) {
                                                    foreach ($business_users as $active_user) {

                                                        if (!empty($dataArr) && $dataArr['order_given_user_id'] == $active_user['id']) {
                                                            $selected = 'selected';
                                                        } else {
                                                            $selected = '';
                                                        }
                                                        ?>
                                                        <option value="<?= $active_user['id'] ?>" <?= $selected ?> > <?= $active_user['first_name'] . ' ' . $active_user['last_name'] ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php if (!empty($customers) && empty($dataArr)): ?>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group has-feedback has-feedback-left">
                                    <label class="col-md-12 col-lg-3 control-label lb-w-150">Customer List</label>
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
                                                <!-- $customer['id'] -->
                                                <option value="<?php echo $customer['id'] ?>" <?php echo $selected ?>><?= $customer['display_name_as']?></option>
                                                <!-- <option value="<?php echo $customer['id'] ?>" <?php echo $selected ?>><?= $customer['first_name'] . ' ' . $customer['last_name'] ?></option> -->
                                                <?php
                                            endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group has-feedback">
                                <label class="col-md-12 col-lg-3 control-label lb-w-150">Quoted Price</label>
                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?= $currency['symbol'] ?></span>
                                        <input type="text" class="form-control" placeholder="Quoted Price" name='quoted_price' id="quoted_price" value="<?= (!empty($dataArr) && !empty($dataArr['quoted_price'])) ? $dataArr['quoted_price'] : '' ?>" maxlength="10"/>
                                    </div>
                                    <!--<label id="quoted_price_error2" class="validation-error-label" for="quoted_price"><?= form_error('quoted_price') ?></label>-->
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Customer Name</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Customer Name" value="<?= (!empty($dataArr) && !empty($dataArr['customer_name'])) ? $dataArr['customer_name'] : '' ?>" />
                                            <label id="customer_name_error2" class="validation-error-label" for="customer_name"><?= form_error('customer_name') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group has-feedback">
                                <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Customer Phone</label>
                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                    <input type="text" class="form-control format-phone-number" name="customer_phone" placeholder="Customer Phone" id="customer_phone" value="<?= (!empty($dataArr) && !empty($dataArr['customer_phone'])) ? $dataArr['customer_phone'] : '' ?>" />
                                    <label id="customer_phone_error2" class="validation-error-label" for="customer_phone"><?= form_error('customer_phone') ?></label>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Quoted Price</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <div class="input-group">
                                                <span class="input-group-addon"><?= $currency['symbol'] ?></span>
                                                <input type="text" class="form-control" placeholder="Quoted Price" name='quoted_price' id="quoted_price" value="<?= (!empty($dataArr) && !empty($dataArr['quoted_price'])) ? $dataArr['quoted_price'] : '' ?>" maxlength="10"/>
                                            </div>
                                            <label id="quoted_price_error2" class="validation-error-label" for="quoted_price"><?= form_error('quoted_price') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback select_form_group">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Paid For?</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <select data-placeholder="-Select-" class="select select-size-sm" id="paid_for" name="paid_for">
                                                <option value = ''>Select Paid For?</option>
                                                <option value='1' <?= (!empty($dataArr) && $dataArr['paid_for'] == 1) ? 'selected' : '' ?>>Yes</option>
                                                <option value='0' <?= (!empty($dataArr) && $dataArr['paid_for'] == 0) ? 'selected' : '' ?>>No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group has-feedback">
                                <label class="col-md-12 col-lg-3 control-label lb-w-150">Total Receipt Amount</label>
                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?= $currency['symbol'] ?></span>
                                        <input type="text" class="form-control" name="total_receipt_amount" id="total_receipt_amount" placeholder="Total Receipt Amount" value="<?= (!empty($dataArr) && $dataArr['total_receipt_amount']) ? $dataArr['total_receipt_amount'] : '' ?>" />
                                    </div>
                                    <!--<label id="total_receipt_amount_error2" class="validation-error-label" for="total_receipt_amount"><?= form_error('total_receipt_amount') ?></label>-->
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Receipt Number</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" placeholder="Receipt Number" name="receipt_number" id="receipt_number" value="<?= (!empty($dataArr) && $dataArr['receipt_no']) ? $dataArr['receipt_no'] : '' ?>" />
                                            <!--<label id="receipt_number_error2" class="validation-error-label" for="receipt_number"><?= form_error('receipt_number') ?></label>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Vendors Part#</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <?php if (!empty($part_details)) { ?>
                                                <input type="text" class="form-control" name="vendor_part" readonly placeholder="Vendor Part" value="<?= $part_details['part_no'] ?>" />
                                            <?php } else { ?> 
                                                <input type="text" class="form-control" name="vendor_part" id="vendor_part" placeholder="Vendor Part" value="<?= (!empty($dataArr) && $dataArr['vendor_part_no']) ? $dataArr['vendor_part_no'] : '' ?>" />
                                            <?php } ?>
<!--<label id="vendor_part_error2" class="validation-error-label" for="vendor_part"><?= form_error('vendor_part') ?></label>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Payment Methods</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <select data-placeholder="-Select-" class="select select-size-sm" id="payment_method_id" name="payment_method_id">
                                                <option value = ''>Select Payment Method</option>
                                                <?php
                                                if (isset($payment_methods) && !empty($payment_methods)) {
                                                    foreach ($payment_methods as $k => $v) {
                                                        if (!empty($dataArr) && $dataArr['payment_method_id'] == $v['id']) {
                                                            $selected = 'selected';
                                                        } else {
                                                            $selected = '';
                                                        }
                                                        ?>
                                                        <option value="<?php echo $v['id']; ?>" <?= $selected ?>><?php echo $v['name']; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <!--<label id="payment_method_id_error2" class="validation-error-label" for="payment_method_id"><?= form_error('payment_method_id') ?></label>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--items_records-->
                        <div class="col-sm-6">
                            <div class="form-group has-feedback select_form_group">
                                <label class="col-md-12 col-lg-3 control-label lb-w-150">Ordered From</label>
                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                    <select data-placeholder="-Select-" class="select select-size-sm" id="ordered_from_id" name="ordered_from_id">
                                        <option value = ''>Select Vendor</option>
                                        <?php
                                        if (isset($vendors) && !empty($vendors)) {
                                            foreach ($vendors as $vendor) {
                                                if (!empty($dataArr) && $dataArr['vendor_id'] == $vendor['id']) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                }
                                                ?>
                                                <option value="<?= $vendor['id'] ?>" <?= $selected ?>><?= $vendor['name'] ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label id="ordered_from_id_error2" class="validation-error-label" for="ordered_from_id"><?= form_error('ordered_from_id') ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group has-feedback select_form_group">
                                <label class="col-md-12 col-lg-3 control-label lb-w-150">Ordered No #</label>
                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                    <input type="text" name="order_no" placeholder="Order No #" id="order_no" class="form-control" value="<?= (!empty($dataArr) && $dataArr['order_no']) ? $dataArr['order_no'] : $order_no ?>" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group has-feedback select_form_group">
                                <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Status</label>
                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                    <select data-placeholder="-Select-" class="select select-size-sm" id="status" name="status">
                                        <option value=''>Select Status</option>
                                        <?php
                                        if (!empty($statuses)) {
                                            foreach ($statuses as $order_status) {
                                                if (!empty($dataArr) && $dataArr['status_id'] == $order_status['id']) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                }
                                                ?>
                                                <option value="<?= $order_status['id'] ?>" <?= $selected ?> ><?= $order_status['status_name'] ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group has-feedback select_form_group">
                                <label class="col-md-12 col-lg-3 control-label lb-w-150">Ordered Id</label>
                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                    <input type="text" name="order_id" placeholder="Order ID" id="order_id" class="form-control" value="<?= (!empty($dataArr) && $dataArr['order_id']) ? $dataArr['order_id'] : '' ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Description</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <textarea name="description" id="description" placeholder="Description" class='form-control' rows='3' ><?= (!empty($dataArr) && $dataArr['description']) ? $dataArr['description'] : '' ?></textarea>
                                            <label id="description_error2" class="validation-error-label" for="description"><?= form_error('description') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-12 col-lg-3 control-label lb-w-150">Payment Notes</label>
                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                    <textarea name="payment_notes" id="payment_notes" placeholder="Payment Notes" class="form-control" rows="3"><?= (!empty($dataArr) && $dataArr['payment_notes']) ? $dataArr['payment_notes'] : '' ?></textarea>
                                    <!--<label id="payment_notes_error2" class="validation-error-label" for="payment_notes"><?= form_error('payment_notes') ?></label>-->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 mt-20 button-wrap">
                            <button type="submit" class="btn bg-teal custom_save_button save_draft" name="save">Save</button>
                            <button type="button" class="btn btn-default custom_cancel_button" onclick="if (history.length > 2) {
                                        window.history.back()
                                    } else {
                                        window.location.href = 'orders';
                                    }">Cancel</button>
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
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/order.js?version='<?php echo time();?>'"></script>
<style>
    .custom_save_button{width: 133px !important;}
    .save_draft{background-color: #1c2f3b!important;}
    .span_quantity{font-size: x-small;font-weight: 500;}
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
        $('#customer_id').change(function(){
            var custname = $('#customer_id').val();
            $('#customer_name').val(custname);
            
            var customer_id = $(this).find('option:selected').val();

            $.ajax({
                type: 'POST',
                url: site_url+"invoices/get_customer_details",
                data:{
                    customer_id: customer_id
                },
                success: function(data){
                    if(data){
                        data = JSON.parse(data);
                        var customer_name = data.display_name_as;
                        // var customer_name = data.first_name +' '+ data.last_name;
                        $('#customer_name').val(customer_name);

                        var phone = data.phone;
                        $('#customer_phone').val(phone);
                    }
                }
            });

        });
    });
</script>