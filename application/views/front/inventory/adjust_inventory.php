<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('items'); ?>">Items</a></li>
            <li class="active">Adjust Inventory</li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>
<div class="content">
    <div class="row">
        <?php $this->load->view('alert_view'); ?>
        <div class="col-md-12" id="tax_form_row">
            <form method="post" class="form-validate-jquery" id="adjust_inventory_form" name="adjust_inventory_form" action='<?php echo site_url('inventory/adjust_inventory') ?>'>
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Adjust Inventory For Items</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">                            
                            <div class="col-md-6">
                                <div class="form-group has-feedback select_form_group select-form-mt-0">
                                    <label class="col-md-2 col-xs-12 control-label required lb-w-125">Items:</label>
                                    <div class="col-md-10 col-xs-12 input-controlstyle">        
                                        <select class="select select-size-sm" data-placeholder="Select a Item..." id="txt_adjust_item_id" name="txt_adjust_item_id" required>
                                            <option></option>

                                            <?php
                                            foreach ($items as $k => $v) {
                                                if ($v['internal_part_no'] != null) {
                                                    $internal = ' , Internal Part : ' . $v['internal_part_no'];
                                                } else {
                                                    $internal = '';
                                                }
                                                $selected = (isset($item_id) && $item_id == $v['id']) ? 'selected="selected"' : '';
                                                ?>
                                                <option value="<?php echo $v['id']; ?>" <?php echo $selected; ?>><?php echo $v['part_no'] . ' (Vendor : ' . $v['vendor_name'] . $internal . ',UPC Barcode: '.$v['upc_barcode'].')'; ?></option>
                                            <?php } ?>
                                        </select>
                                        <?php echo '<label id="txt_items_error2" class="validation-error-label" for="txt_adjust_item_id">' . form_error('txt_adjust_item_id') . '</label>'; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-1">
                                <div class="col-sm-12">
                                    <div class="form-group has-feedback select_form_group select-error">
                                        <button type="button" title="Scan Items Using QR Code" class="btn btn-warning" id="scan-item-qr-code" title="Scan"><i class="icon-camera"></i></button>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="adjust_div hide">
                            <div class="row">
                                <?php
                                foreach ($locations as $k => $v) {
                                    ?>
                                    <div class="col-md-6 ">
                                        <div class="form-group has-feedback">
                                            <label class="col-md-2 control-label text-semibold text-left lb-w-125"><?php echo $v['name'] ?></label>
                                            <div class="col-md-10 input-controlstyle">
                                                <input type="hidden" class="" name="txt_new_inventory_hidden[]" value="<?php echo $v['id'] ?>">
    <!--                                                <select class="select select-size-sm adjust_inventory" id="txt_in_stock_<?php echo $v['id'] ?>" name="txt_new_inventory[]" required data-id="<?php echo $v['id'] ?>">
                                                    <option></option>
                                                </select>-->
                                                <input type="number" value="0" class="touchspin-empty txt_new_inventory adjust_inventory" name="txt_new_inventory[]" id="txt_in_stock_<?php echo $v['id'] ?>" data-id="<?php echo $v['id'] ?>" min="0">
                                                <?php echo '<label id="txt_in_stock_error2" class="validation-error-label" for="txt_in_stock">' . form_error('txt_in_stock') . '</label>'; ?>
                                            </div>
                                        </div> 
                                    </div>
                                <?php } ?>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-2 control-label required lb-w-125">Notes:</label>
                                        <div class="col-md-10 input-controlstyle">
                                            <textarea name='notes' class='form-control' placeholder="Please enter notes.." rows='4'></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-20 col-md-12">
                                <h6 class="panel-title"><span class='error_message hide'>No Such Data Found.</span></h6>
                            </div>
                            <!--                            <div class="row mt-20">
                                                            <div class="col-md-6 mt-20">
                                                                <label class="col-md-2 control-label text-bold text-left">Total</label>
                                                                <div class="col-md-10">
                                                                    <input type="text" value="0" class="touchspin-empty" name="total_inventory" id="total_inventory" disabled="disabled">
                                                                </div>
                                                            </div>
                                                        </div>-->
                            <div class="row text-right">
                                <div class="col-lg-12">
                                    <div class="col-lg-12">
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
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script type="text/javascript" src="assets/js/custom_pages/front/inventory.js"></script>
<style>
    .error_message{    
        font-weight: 500!important;
        color: #33a9f4;
        font-weight: 400;
        font-size: 17.8px;
        font-family: 'Roboto Light';
        margin-left: 11px;
        text-align: left;
    }
    .select_form_group .form-control-feedback {
        margin-top: -25px;
    }
    .form-group {float: left;width: 100%;margin-bottom: 10px;}
    .input-group-btn > .btn {height: 36px;}
    label.control-label.text-semibold {margin: 0;}
    .select-form-mt-0 .form-control-feedback{
        margin-top: 0px;
    }
    @media(min-width:2034px){ 
        .form-group {overflow: hidden;margin-bottom: 10px;}
    }
</style>