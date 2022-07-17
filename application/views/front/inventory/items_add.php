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
    $form_action = site_url('items/edit/' . base64_encode($dataArr['id']));
} else {
    $form_action = site_url('items/add');
}
?>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('items') ?>">Items</a></li>
            <li class="active">
                <?php
                if (isset($dataArr))
                    echo "Edit";
                else
                    echo "Add";
                ?>
            </li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="<?php echo $form_action; ?>" class="form-horizontal" id="add_item_form" enctype="multipart/form-data" >
                <div class="panel panel-body login-form">
                    <?php $this->load->view('alert_view'); ?>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group has-feedback">
                                <label class="col-md-3 control-label required">Global Part No :</label>
                                <div class="col-md-7">
                                    <input type="hidden" class="form-control" name="txt_referred_item_id" id="txt_referred_item_id" style="text-transform: uppercase;" value="<?php echo (isset($dataArr)) ? $dataArr['referred_item_id'] : set_value('txt_referred_item_id'); ?>">
                                    <input type="hidden" class="form-control" name="txt_global_part_no" id="txt_global_part_no_hidden" style="text-transform: uppercase;" value="<?php echo (isset($dataArr)) ? $dataArr['global_part_no'] : set_value('txt_global_part_no_hidden'); ?>" <?php echo (isset($dataArr)) ? "disabled='disabled'" : ''; ?>>
                                    <input type="text" class="form-control" id="txt_global_part_no" style="text-transform: uppercase;" value="<?php echo (isset($dataArr)) ? $dataArr['global_part_no'] : set_value('txt_global_part_no'); ?>" <?php echo (isset($dataArr)) ? "disabled='disabled'" : ''; ?>>

                                    <?php echo '<label id="txt_global_part_no_error2" class="validation-error-label" for="txt_global_part_no">' . form_error('txt_global_part_no') . '</label>'; ?>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-default bg-blue custom_clear_button hide">Reset</button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="global_part_search_result_div hide">
                        <h6 class="content-group text-semibold">
                            Global Part Search Result
                            <small class="display-block">It shows result of above entered global part details</small>
                        </h6>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-flat">
                                    <table class="table datatable-responsive">
                                        <thead>
                                            <tr>
                                                <th style="width:5%">#</th>
                                                <th>Global Part</th>
                                                <th>Short Description</th>
                                                <th>Vendor</th>
                                                <th>Vendor (Part #)</th>
                                                <th>Select(Any of)</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="inventoy_div hide">
                        <h6 class="content-group text-semibold">
                            Add New Inventory
                            <small class="display-block">It creates new inventory based on selected part details</small>
                        </h6>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 internal_part hide">
                                <div class="form-group has-feedback select_form_group">
                                    <label class="col-md-4 col-xs-12 control-label required">Internal Part:</label>
                                    <div class="col-md-7 col-xs-10">        
                                        <select class="select select-size-sm" data-placeholder="Select a Internal Part..." id="txt_internal_part" name="txt_internal_part">
                                            <option></option>
                                        </select>
                                        <?php echo '<label id="txt_internal_part_error2" class="validation-error-label" for="txt_internal_part">' . form_error('txt_internal_part') . '</label>'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group has-feedback">
                                            <label class="col-md-4 control-label required">Item Part No :</label>
                                            <div class="col-md-7">
                                                <input type="text" class="form-control" name="txt_item_part" id="txt_item_part" style="text-transform: uppercase;" value="<?php echo (isset($dataArr)) ? $dataArr['part_no'] : set_value('txt_item_part'); ?>">
                                                <?php echo '<label id="txt_item_part_error2" class="validation-error-label" for="txt_item_part">' . form_error('txt_item_part') . '</label>'; ?>
                                            </div>
                                        </div>
                                    </div>                            

                                    <div class="col-md-12">
                                        <div class="form-group has-feedback" style="margin-bottom:15px">
                                            <label class="col-md-4 control-label required">Item Description :</label>
                                            <div class="col-md-7">
                                                <textarea class="form-control" name="txt_item_description" id="txt_item_description" rows="4"><?php echo (isset($dataArr)) ? $dataArr['description'] : set_value('txt_item_description'); ?></textarea>
                                                <?php echo '<label id="txt_item_description_error2" class="validation-error-label" for="txt_item_description">' . form_error('txt_item_description') . '</label>'; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group has-feedback">
                                            <label class="col-md-4 control-label">Inventory Cost :</label>
                                            <div class="col-md-7">
                                                <input type="text" class="form-control" name="txt_unit_cost" id="txt_unit_cost" value="<?php echo (isset($dataArr)) ? $dataArr['unit_cost'] : set_value('txt_unit_cost'); ?>">
                                                <?php echo '<label id="txt_unit_cost_error2" class="validation-error-label" for="txt_unit_cost">' . form_error('txt_unit_cost') . '</label>'; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group has-feedback">
                                            <label class="col-md-4 control-label">Inventory Price :</label>
                                            <div class="col-md-7">
                                                <input type="text" class="form-control" name="txt_retail_price" id="txt_retail_price" value="<?php echo (isset($dataArr)) ? $dataArr['retail_price'] : set_value('txt_retail_price'); ?>">
                                                <?php echo '<label id="txt_retail_price_error2" class="validation-error-label" for="txt_retail_price">' . form_error('txt_retail_price') . '</label>'; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback select_form_group">
                                        <label class="col-md-4 col-xs-12 control-label required">Department :</label>
                                        <div class="col-md-7 col-xs-10">        
                                            <select class="select select-size-sm" data-placeholder="Select a Department..." id="txt_department" name="txt_department" required <?php
                                            if (empty($dept_Arr)) {
                                                echo 'disabled';
                                            }
                                            ?>>
                                                <option></option>
                                                <?php
                                                foreach ($dept_Arr as $k => $v) {
                                                    if (isset($dataArr)) {
                                                        if ($dataArr['department_id'] == $v['id']) {
                                                            $selected = 'selected';
                                                        } else {
                                                            $selected = '';
                                                        }
                                                    } else {
                                                        $selected = '';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $v['id']; ?>" <?php echo $selected; ?>><?php echo $v['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php echo '<label id="txt_department_error2" class="validation-error-label" for="txt_department">' . form_error('txt_department') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group has-feedback select_form_group">
                                        <label class="col-md-4 col-xs-12 control-label required">Vendor :</label>
                                        <div class="col-md-7 col-xs-10">        
                                            <select class="select select-size-sm" data-placeholder="Select a Vendor..." id="txt_pref_vendor" name="txt_pref_vendor" required <?php
                                            if (empty($vendor_Arr)) {
                                                echo 'disabled';
                                            }
                                            ?>>
                                                <option></option>
                                                <?php
                                                foreach ($vendor_Arr as $k => $v) {
                                                    if (isset($dataArr)) {
                                                        if ($dataArr['vendor_id'] == $v['id']) {
                                                            $selected = 'selected';
                                                        } else {
                                                            $selected = '';
                                                        }
                                                    } else {
                                                        $selected = '';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $v['id']; ?>" <?php echo $selected; ?>><?php echo $v['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php echo '<label id="txt_pref_vendor_error2" class="validation-error-label" for="txt_pref_vendor">' . form_error('txt_pref_vendor') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>  
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-4 control-label">Quantity In-Stock :</label>
                                        <div class="col-md-7">
                                            <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['qty_on_hand'] : 0; ?>" class="touchspin-empty" name="txt_in_stock" id="txt_in_stock">
                                            <?php echo '<label id="txt_in_stock_error2" class="validation-error-label" for="txt_in_stock">' . form_error('txt_in_stock') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Image</label>
                                        <div class="col-md-7">
                                            <div class="media no-margin-top">
                                                <input type = "hidden" name = 'item_image_hidden' id = "item_image_hidden" value = ''/>
                                                <div class="media-left" id="image_preview_div">
                                                    <?php
                                                    if (isset($dataArr) && $dataArr['image'] && file_exists(ITEMS_IMAGE_PATH . '/' . $dataArr['image'])) {
                                                        $required = '';
                                                        ?>
                                                        <img name='item_image' class='item_image' src="<?php echo ITEMS_IMAGE_PATH . '/' . $dataArr['image'] ?>" style="width: 58px; height: 58px; border-radius: 2px;" alt="">
                                                        <?php
                                                    } else {
                                                        $required = 'required';
                                                        ?>
                                                        <img src="assets/images/placeholder.jpg" style="width: 58px; height: 58px; border-radius: 2px;" alt="">
                                                    <?php } ?>
                                                </div>
                                                <div class="media-body">
                                                    <input type="file" name="image_link" id="image_link" class="file-styled" onchange="readURL(this);">
                                                    <span class="help-block">Accepted formats: png, jpg. Max file size 2Mb</span>
                                                </div>
                                            </div>
                                            <?php
                                            if (isset($menu_item_image_validation))
                                                echo '<label id="image_link-error" class="validation-error-label" for="image_link">' . $item_image_validation . '</label>';
                                            ?>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn bg-teal custom_save_button">Save</button>
                                <button type="button" class="btn btn-default custom_cancel_button" onclick="if (history.length > 2) {
                                            window.history.back()
                                        } else {
                                            window.location.href = 'inventory';
                                        }">Cancel</button>
                            </div>
                        </div>
                    </div>
                    <div class="row hide" id="div_no_parts">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <!--<h6 class="text-semibold">Parts Details</h6>-->
                                <h6 class="panel-title" style="font-weight: 400!important;color: #908e8e !important;"><span class='error_message'>No Such Data Found.</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php $this->load->view('Templates/footer.php'); ?>
</div>

<script>
    var ITEMS_IMAGE_PATH = '<?php echo ITEMS_IMAGE_PATH ?>';
    var edit_div = '<?php echo $edit_div ?>';
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/items.js"></script>
<style>
    .modal-open{ padding-right:3px !important; }
    .hide{ display: none!important }
    .custom_clear_button:hover, .custom_clear_button[class*=bg-]:focus, .custom_clear_button[class*=bg-].focus {
        background-color: #03A9F4;
        border-color: #03A9F4;
    }
</style>