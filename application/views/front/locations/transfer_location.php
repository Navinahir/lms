<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('items'); ?>">Items</a></li>
            <li class="active">Move Inventory</li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>
<div class="content">
    <div class="row">
        <?php $this->load->view('alert_view'); ?>
        <div class="col-md-12" id="tax_form_row">
            <form method="post" class="form-validate-jquery" id="transfer_inventory_form" name="transfer_inventory_form" action='<?php echo site_url('inventory/transfer_inventory') ?>'>
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Manage Inventory Between Locations</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row mt-15">                            
                            <div class="col-md-7 col-lg-6">
                                <div class="col-sm-12">
                                    <div class="row form-group has-feedback select_form_group select-error">
                                        <label class="col-md-2 col-xs-12 control-label required pl-2 pl-lg-0">Items:</label>
                                        <div class="col-md-10 col-xs-12 margin-set pl-md-4-p pl-2 pl-lg-0">        
                                            <select class="select select-size-sm" data-placeholder="Select a Item..." id="txt_item_id" name="txt_item_id" required>
                                                <option></option>
                                                <?php
                                                foreach ($items as $k => $v) {
                                                    if ($v['internal_part_no'] != null) {
                                                        $internal = ' , Internal Part : ' . $v['internal_part_no'];
                                                    } else {
                                                        $internal = '';
                                                    }
                                                    $selected = (isset($defult_item_id) && $defult_item_id == $v['id']) ? 'selected="selected"' : '';
                                                    ?>
                                                    <option value="<?php echo $v['id']; ?>" <?php echo $selected; ?>><?php echo $v['part_no'] . ' (Vendor : ' . $v['vendor_name'] . $internal . ' , UPC Barcode: ' . $v['upc_barcode'] . ' )'; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php echo '<label id="txt_items_error2" class="validation-error-label" for="txt_txt_item_id">' . form_error('txt_item_id') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                            <div class="col-sm-3 col-md-2 col-lg-1 item-camera-btn">
                                <div class="form-group has-feedback select_form_group select-error">
                                    <button type="button" title="Scan Items Using QR Code" class="btn btn-warning" id="scan-item-qr-code" title="Scan"><i class="icon-camera"></i></button>
                                </div>
                            </div>                            
                            <div class="col-md-4 total_qty hide">
                                <div class="row pl-2 pr-2 form-group">
                                    <div class="col-sm-12">
                                        <label class="control-label"><span class='text-semibold'>Total available quantities</span></label>
                                        <div class="total_quantity_qnt">  
                                            <span class='total_quantity text-semibold'>0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="transfer_div hide ">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row pl-2 pr-2 form-group has-feedback select_form_group">
                                        <label class="col-md-12 col-lg-12 control-label required">From Location:</label>
                                        <div class="col-md-12 col-lg-12">        
                                            <select class="select select-size-sm" data-placeholder="Select a From Location..." id="txt_from_location_id" name="txt_from_location_id" required>
                                                <option></option>
                                                <?php
                                                foreach ($locations as $k => $v) {
                                                    ?>
                                                    <option value="<?php echo $v['id']; ?>" disabled="disabled"><?php echo $v['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="help-block current_qty hide text-semibold">Current Quantities<span class='current_qty_amount text-right'>0</span></span>
                                            <?php echo '<label id="txt_from_location_id_error2" class="validation-error-label" for="txt_from_location_id">' . form_error('txt_from_location_id') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                    <div class="row pl-2 pr-2 form-group has-feedback select_form_group">
                                        <label class="col-md-12 col-lg-12 control-label required">To Location:</label>
                                        <div class="col-md-12 col-lg-12">        
                                            <select class="select select-size-sm" data-placeholder="Select a To location..." id="txt_to_location_id" name="txt_to_location_id" required>
                                                <option></option>
                                                <?php
                                                foreach ($locations as $k => $v) {
                                                    ?>
                                                    <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="help-block to_current_qty hide text-semibold">Current Quantities<span class='to_current_qty_amount text-right'>0</span></span>
                                            <?php echo '<label id="txt_to_location_id_error2" class="validation-error-label" for="txt_to_location_id">' . form_error('txt_to_location_id') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                    <div class="row pl-2 pr-2 form-group has-feedback select_form_group">
                                        <label class="col-md-12 col-lg-12 control-label required">Quantity:</label>
                                        <div class="col-md-12 col-lg-12">        
                                            <select class="select select-size-sm" data-placeholder="Select a inventory Quantity..." id="txt_quantity" name="txt_quantity" required>
                                                <option></option>
                                            </select>
                                            <?php echo '<label id="txt_quantity_error2" class="validation-error-label" for="txt_quantity">' . form_error('txt_quantity') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="row pl-3 pr-2 form-group has-feedback select_form_group">
                                        <label class="col-md-12 col-lg-12 control-label required">Notes:</label>
                                        <div class="col-md-12 col-lg-12" style="margin-left: -4px;">
                                            <textarea name='notes' class='form-control' placeholder="Please enter notes.." rows='4'></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row text-right">
                                <div class="col-lg-12">
                                    <div class="col-lg-12 pl-2 pr-2">
                                        <button type="submit" class="btn bg-blue custom_save_button">Save</button>
                                        <button type="button" class="btn btn-default custom_cancel_button" onclick="cancel_click()">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<!-- View modal -->
<div id="partno_scan_webcam_modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Scan QR Code</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar">
                <video id="webcam-preview" width="100%"></video>
            </div>
        </div>
    </div>
</div>
<style>
    .current_qty_amount, .to_current_qty_amount{
        margin-left: 12px;
        color: #33a9f4;
        font-weight: 500;
    }
    .current_qty, .to_current_qty{
        color: #1c2f3b;
    }
    span.total_quantity.text-semibold {
        color: #33a9f4;
    }
    .select-error .form-control-feedback {
        margin-top: -25px;
    }
    .form-group{overflow: hidden;}
    .total_qty .total_quantity_qnt {display: inline-block;margin-left: 15px;}
    .total_qty .form-group{padding: 0 10px;}
</style>
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script type="text/javascript" src="assets/js/custom_pages/front/inventory.js?version='<?php echo time();?>'"></script>