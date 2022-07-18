<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="text/javascript" src="assets/js/plugins/forms/tags/tagsinput.min.js"></script>
<script src="assets/js/intro.js"></script>
<script type="text/javascript" src="assets/js/jquery_ui.js"></script>
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
$add_item = 0;
if (isset($dataArr)) {
    $edit_div = 1;
    $form_action = site_url('items/edit/' . base64_encode($dataArr['id']));
} else if (isset($itemArr)) {
    $add_item = 1;
    $form_action = site_url('items/add_non_global');
} else {
    $form_action = site_url('items/add_non_global');
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
                <div class="panel panel-body">
                    <?php $this->load->view('alert_view'); ?>
                    <div class="inventoy_div">
                        <h6 class="content-group text-semibold">
                            <?php echo (isset($dataArr)) ? "Edit" : "Add New"; ?> Non Global Item
                            <small class="display-block"><?php echo (isset($dataArr)) ? '' : 'It creates new item details' ?></small>
                        </h6>
                        <hr>
                        <div class="col-sm-12 pl-sm-0 pr-sm-0">
                            <div class="row">
                                <div class="col-md-6 pl-xs-0 pr-xs-0">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback">
                                                <label class="col-md-12 col-lg-4 control-label required">Item Part No :</label>
                                                <div class="col-md-12 col-lg-8">
                                                    <input type="text" class="form-control" name="txt_item_part" id="txt_item_part" style="text-transform: uppercase;" value="<?php
                                                    if (isset($dataArr)) {
                                                        echo $dataArr['part_no'];
                                                    } else {
                                                        set_value('txt_item_part');
                                                    }
                                                    ?>">
                                                           <?php echo '<label id="txt_item_part_error2" class="validation-error-label" for="txt_item_part">' . form_error('txt_item_part') . '</label>'; ?>
                                                </div>
                                            </div>
                                        </div>                            
                                        <div class="col-md-12 internal_part">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-lg-4 col-md-12 col-xs-12 control-label">Alternate Part No or SKU:</label>
                                                <div class="col-lg-8 col-md-12 col-xs-12">                                                       
                                                    <input type="text" class="form-control" id="hidden_internal_part" name="hidden_internal_part" value="<?php echo (isset($dataArr)) ? $dataArr['internal_part_no'] : ''; ?>">
                                                    <?php echo '<label id="hidden_internal_part_error2" class="validation-error-label" for="hidden_internal_part">' . form_error('hidden_internal_part') . '</label>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback" style="margin-bottom:15px">
                                                <label class="col-lg-4 col-md-12 control-label required">Item Description :</label>
                                                <div class="col-lg-8 col-md-12">
                                                    <textarea class="form-control" name="txt_item_description" id="txt_item_description" rows="4"><?php
                                                        if (isset($dataArr)) {
                                                            echo $dataArr['description'];
                                                        } else {
                                                            set_value('txt_item_description');
                                                        }
                                                        ?></textarea>
                                                    <?php echo '<label id="txt_item_description_error2" class="validation-error-label" for="txt_item_description">' . form_error('txt_item_description') . '</label>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback">
                                                <label class="col-lg-4 col-md-12 control-label">Part Location :</label>
                                                <div class="col-lg-8 col-md-12">
                                                    <input type="text" class="form-control" name="part_location" id="part_location" value="<?php
                                                    if (isset($dataArr)) {
                                                        echo $dataArr['part_location'];
                                                    } else {
                                                        set_value('part_location');
                                                    }
                                                    ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback">
                                                <label class="col-lg-4 col-md-12 control-label required">Cost :</label>
                                                <div class="col-lg-8 col-md-12">
                                                    <input type="text" min="0" class="form-control" name="txt_unit_cost" id="txt_unit_cost" value="<?php
                                                    if (isset($dataArr)) {
                                                        echo $dataArr['unit_cost'];
                                                    } else {
                                                        set_value('txt_unit_cost');
                                                    }
                                                    ?>">
                                                           <?php echo '<label id="txt_unit_cost_error2" class="validation-error-label" for="txt_unit_cost">' . form_error('txt_unit_cost') . '</label>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback">
                                                <label class="col-lg-4 col-md-12 control-label required">Price :</label>
                                                <div class="col-lg-8 col-md-12">
                                                    <input type="text" min="0" class="form-control" name="txt_retail_price" id="txt_retail_price" value="<?php
                                                    if (isset($dataArr)) {
                                                        echo $dataArr['retail_price'];
                                                    } else {
                                                        set_value('txt_retail_price');
                                                    }
                                                    ?>">
                                                    <?php echo '<label id="txt_retail_price_error2" class="validation-error-label" for="txt_retail_price">' . form_error('txt_retail_price') . '</label>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 pad_zero pl-xs-0 pr-xs-0">
                                     <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-md-12 col-lg-4 col-xs-12 control-label">Department :</label>
                                                <div class="col-md-12 col-lg-8 col-xs-12">        
                                                    <select class="select select-size-sm" data-placeholder="Select a Department..." id="txt_department" name="txt_department" <?php
                                                    if (empty($dept_Arr)) {
                                                        echo 'disabled';
                                                    }
                                                    ?>>
                                                        <option value=""></option>
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
                                                    <?php /* echo '<label id="txt_department_error2" class="validation-error-label" for="txt_department">' . form_error('txt_department') . '</label>'; */ ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="col-md-12 col-lg-4 col-xs-12 control-label">Vendor :</label>
                                                <div class="col-md-12 col-lg-8 col-xs-12">        
                                                    <select class="select select-size-sm" data-placeholder="Select a Vendor..." id="txt_pref_vendor" name="txt_pref_vendor" 
                                                    <?php /*
                                                    if (empty($vendor_list)) {
                                                        echo 'disabled';
                                                    } */
                                                    ?>
                                                    >
                                                    <option value=""></option>
                                                    <?php if($vendor_list != "") { ?>
                                                        <?php foreach ($vendor_list as $k => $v) {
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
                                                    <?php } else { ?>
                                                        <?php foreach ($vendors as $k => $v) {
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
                                                    <?php } ?>
                                                    </select>
                                                    <?php /* echo '<label id="txt_pref_vendor_error2" class="validation-error-label" for="txt_pref_vendor">' . form_error('txt_pref_vendor') . '</label>'; */ ?>
                                                </div>
                                            </div>
                                        </div>  
                                        
                                        <?php
                                        $segment = $this->uri->segment(3);
                                        if($segment != "")
                                        {
                                            $i_quantity = array_column($dataArr['items_quantity'],'total_quantity');
                                            $item_quantity = implode($i_quantity, ',');
                                        ?>
                                            <div class="col-md-12">
                                                <div class="form-group has-feedback right-touchspin-feedback touchspin-validation-msg">
                                                    <label class="col-md-12 col-lg-4 col-xs-12 control-label">Quantity In-Stock :</label>
                                                    <div class="col-md-12 col-lg-8 col-xs-12">
                                                        <input type="number" value="<?php echo (isset($item_quantity) && ($item_quantity > 0 )) ? $item_quantity : 0;
                                                    ?>" class="form-control" name="txt_in_stock" id="txt_in_stock" disabled>
                                                    </div>
                                                    <?php echo '<label id="txt_in_stock_error2" class="validation-error-label" for="txt_in_stock">' . form_error('txt_in_stock') . '</label>'; ?>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-md-12">
                                                <div class="form-group has-feedback">
                                                    <label class="col-md-12 col-lg-4 col-xs-12 control-label">Quantity In-Stock :</label>
                                                    <div class="col-md-12 col-lg-8 col-xs-12">
                                                        <input type="text" value="<?php echo (isset($dataArr)) ? $dataArr['qty_on_hand'] : 0;
                                                        ?>" class="form-control" name="txt_in_stock" id="txt_in_stock">
                                                        <?php echo '<label id="txt_in_stock_error2" class="validation-error-label" for="txt_in_stock">' . form_error('txt_in_stock') . '</label>'; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <div class="col-md-12">
                                            <div class="form-group has-feedback">
                                                <label class="col-md-12 col-lg-4 col-xs-12 control-label">Low Inventory Notification Point :</label>
                                                <div class="col-md-12 col-lg-8 col-xs-12">
                                                    <input type="text" value="<?php echo (isset($dataArr) && $dataArr['low_inventory_limit'] > 0) ? $dataArr['low_inventory_limit'] : '';
                                                        ?>" class="form-control" name="txt_low_inventory" id="txt_low_inventory">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group has-feedback">
                                                <label class="col-md-12 col-lg-4 col-xs-12 control-label">Manufacturer :</label>
                                                <div class="col-md-12 col-lg-8 col-xs-12">
                                                    <input type="text" class="form-control tags-input" name="txt_manufacturer" id="txt_manufacturer" value="<?php
                                                    if (isset($dataArr)) {
                                                        echo $dataArr['manufacturer'];
                                                    } else {
                                                        set_value('txt_manufacturer');
                                                    }
                                                    ?>">
                                                           <?php /* echo '<label id="txt_manufacturer_error2" class="validation-error-label" for="txt_manufacturer">' . form_error('txt_manufacturer') . '</label>'; */ ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback">
                                                <label class="col-md-12 col-lg-4 col-xs-12 control-label">UPC Barcode :</label>
                                                <div class="col-md-12 col-lg-8 col-xs-12">
                                                    <input type="text" class="form-control" name="txt_upc_barcode" id="txt_upc_barcode" value="<?php
                                                    if (isset($dataArr)) {
                                                        echo $dataArr['upc_barcode'];
                                                    } elseif (isset($itemArr)) {
                                                        echo '0.00';
                                                    } else {
                                                        set_value('txt_upc_barcode');
                                                    }
                                                    ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <?php if (!empty($dataArr)) { ?>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-12 col-lg-4 col-xs-12 control-label">Image</label>
                                                    <div class="col-md-12 col-lg-8 col-xs-12">
                                                        <div class="media no-margin-top">
                                                            <input type = "hidden" name = 'item_image_hidden' id = "item_image_hidden" value="<?php echo $dataArr['image']; ?>" />
                                                            <div class="media-left" id="image_preview_div">
                                                                <?php
                                                                if (!empty($dataArr) && $dataArr['image'] && file_exists(ITEMS_IMAGE_PATH . '/' . $dataArr['image'])) {
                                                                    $required = '';
                                                                    ?>
                                                                    <img name='item_image' class='item_image' src="<?php echo ITEMS_IMAGE_PATH . '/' . $dataArr['image'] ?>" style="width: 58px; height: 58px; border-radius: 2px;" alt="">
                                                                    <?php } else { ?>
                                                                        <img src="assets/images/key_icon_blue.png" style="width: 58px; height: 58px; border-radius: 2px;" alt="">
                                                                    <?php } ?>
                                                            </div>
                                                            <div class="media-body" id="uniform-image_link">
                                                                <input type="file" name="image_link" id="image_link" class="file-styled" onchange="readURL(this);">
                                                                <span class="help-block">Accepted formats: png, jpg. Max file size 2Mb</span>
                                                            </div>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-md-12">
                                                <div class="form-group has-feedback">
                                                    <label class="col-md-12 col-lg-4 col-xs-12 control-label">Image :</label>
                                                    <div class="col-md-12 col-lg-8 col-xs-12">
                                                        <input type="file" name="image_link" id="image_link" class="file-styled">
                                                        <span class="help-block">Accepted formats: png, jpg. Max file size 2Mb</span>
                                                        <?php if (isset($menu_item_image_validation))
                                                        echo '<label id="image_link-error" class="validation-error-label" for="image_link">' . $item_image_validation . '</label>'; ?>
                                                        <span id="image_message_alert" style="color: red;"></span>
                                                    </div>
                                                    <div class="image_wrapper_alert col-sm-2"></div>
                                                </div>
                                            </div>   
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-4 control-label">Select Compatiblity</label>
                                <div class="col-md-12">
                                    <div class="table-responsive table_bottom_one">
                                        <table class="table table-bordered table-xxs" id="tbl_additional_data">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th style="width:33%">Make</th>
                                                    <th style="width:33%">Model</th>
                                                    <th style="width:33%">Year</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($trans_Arr) && !empty($trans_Arr)):
                                                    foreach ($trans_Arr as $k => $val):
                                                        $key = ++$k;
                                                        ?>
                                                        <tr class="tr_class" data-value="<?php echo $key ?>">
                                                            <td>
                                                                <?php
                                                                if ($key <= (count($trans_Arr) - 1)):
                                                                    echo '<a href="javascript:void(0)" style="color:#d66464"><i class="icon-minus-circle2 btn_remove_extra_field"></i></a>';
                                                                else:
                                                                    echo '<a href="javascript:void(0)" style="color:#009688"><i class="icon-plus-circle2 btn_add_extra_field"></i></a>';
                                                                endif;
                                                                ?>

                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="trans_item_id[]" value="<?php echo $val['id'] ?>"/>
                                                                <select class="select select-size-sm additional_field_txt txt_make_name" data-placeholder="Select a Company..." id="txt_make_name_<?php echo $key ?>" name="txt_make_name[]" <?php
                                                                if (empty($companyArr)) {
                                                                    echo 'disabled';
                                                                }
                                                                ?>>
                                                                    <option></option>
                                                                    <?php
                                                                    foreach ($companyArr as $k => $v) {
                                                                        if ($v['id'] == $val['make_id']) {
                                                                            $selected = "selected='selected'";
                                                                        } else {
                                                                            $selected = "";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $v['id']; ?>" <?= $selected ?>><?php echo $v['name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="select select-size-sm additional_field_txt txt_model_name" data-placeholder="Select a Model..." id="txt_model_name_<?php echo $key ?>" name="txt_model_name[]" >
                                                                    <option></option>
                                                                    <?php
                                                                    foreach ($modelArr as $k => $v) {
                                                                        if ($val['model_id'] == $v['id']) {
                                                                            $selected = "selected='selected'";
                                                                        } else {
                                                                            $selected = "";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $v['id']; ?>" <?= $selected ?>><?php echo $v['name']; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="select select-size-sm additional_field_txt txt_year_name" data-placeholder="Select a Year..." id="txt_year_name_<?php echo $key ?>" name="txt_year_name[]">
                                                                    <option></option>
                                                                    <?php
                                                                    foreach ($yearArr as $k => $v) {
                                                                        if ($v['id'] == $val['year_id']) {
                                                                            $selected = "selected='selected'";
                                                                        } else {
                                                                            $selected = "";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $v['id']; ?>" <?= $selected ?>><?php echo $v['name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    endforeach;
                                                else:
                                                    ?>
                                                    <tr class="tr_class" data-value="1">
                                                        <td><a href="javascript:void(0)" style="color:#009688"><i class="icon-plus-circle2 btn_add_extra_field"></i></a></td>
                                                        <td>
                                                            <select class="select select-size-sm additional_field_txt txt_make_name" data-placeholder="Select a Company..." id="txt_make_name_1" name="txt_make_name[]" <?php
                                                            if (empty($companyArr)) {
                                                                echo 'disabled';
                                                            }
                                                            ?>>
                                                                <option></option>
                                                                <?php foreach ($companyArr as $k => $v) { ?>
                                                                    <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="select select-size-sm additional_field_txt txt_model_name" data-placeholder="Select a Model..." id="txt_model_name_1" name="txt_model_name[]">
                                                                <option></option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="select select-size-sm additional_field_txt txt_year_name" data-placeholder="Select a Year..." id="txt_year_name_1" name="txt_year_name[]">
                                                                <option></option>
                                                                <?php foreach ($yearArr as $k => $v) { ?>
                                                                    <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                        <label id="additional_data_error2" class="validation-error-label" for="additional_data"></label>
                                    </div>
                                </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn bg-teal custom_save_button">Save</button>
                                <button type="button" class="btn btn-default custom_cancel_button" onclick="
                                        if (history.length > 55) {
                                            window.history.back()
                                        } else {
                                            window.location.href = 'items';
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

        <div class="col-md-6">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">Vehicle Search</h6>
                </div>
                <div class="panel-body">
                    <form method="post" class="form-validate-jquery" id="search_transponder_form" name="search_transponder_form">
                        <div class="row mt-20">
                            <div class="col-md-12">
                                <div class="form-group has-feedback select_form_group">
                                    <label class="required">Make</label>
                                    <select data-placeholder="Select a Company..." class="select select-size-sm" id="txt_make_name" name="txt_make_name">
                                        <option></option>
                                        <?php
                                        foreach ($companyArr as $k => $v) {
                                            $selected = '';
                                            if (isset($searchData) && !empty($searchData)):
                                                if ($v['id'] == $searchData['make_id']):
                                                    $selected = 'selected="selected"';
                                                endif;
                                            endif;
                                            ?>
                                            <option value="<?php echo $v['id']; ?>" <?php echo $selected ?>><?php echo $v['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group has-feedback select_form_group">
                                    <label class="required">Model</label>
                                    <select data-placeholder="Select a Model..." class="select select-size-sm" id="txt_model_name" name="txt_model_name">
                                        <option></option>
                                        <?php
                                        if ((isset($searchData) && !empty($searchData)) && (isset($modelArr) && !empty($modelArr))):
                                            foreach ($modelArr as $k => $v) {
                                                $selected = '';
                                                if ($v['id'] == $searchData['model_id']):
                                                    $selected = 'selected="selected"';
                                                endif;
                                                ?>
                                                <option value="<?php echo $v['id']; ?>" <?php echo $selected ?>><?php echo $v['name']; ?></option>
                                                <?php
                                            }
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group has-feedback select_form_group">
                                    <label class="required">Year</label>
                                    <select data-placeholder="Select a Year..." class="select select-size-sm" id="txt_year_name" name="txt_year_name">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn bg-teal custom_save_button" id="btn_search">Search</button>
                                <button type="button" class="btn btn-default custom_cancel_button" id="btn_reset">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-12 hide" id="div_list_of_parts">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">List Of Parts</h6>
                </div>
                <div class="stock_legend_div">
                    <span class="status-mark border-orange position-left"></span>Global Part&nbsp;&nbsp;
                    <span class="status-mark border-success position-left"></span>In Stock&nbsp;&nbsp;
                    <span class="status-mark border-danger position-left"></span>Out Of Stock
                </div>
                <div class="panel-body p-2 pb-0">
                    <div class="div_part_list p-0">
                    </div>                                
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('Templates/footer.php'); ?>
</div>

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
if (!empty($companyArr)) {
    echo "<script>var companyArr = " . json_encode($companyArr) . "</script>";
}
if (!empty($yearArr)) {
    echo "<script>var yearArr = " . json_encode($yearArr) . "</script>";
}
?>
<script>
    var ITEMS_IMAGE_PATH = '<?php echo ITEMS_IMAGE_PATH ?>';
    var edit_div = '<?php echo $edit_div ?>';
    var add_item = '<?php echo $add_item ?>';
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/items.js?version='<?php echo time();?>'"></script>
<style>
    .stock_legend_div{
        height: auto;
        margin: 0;
        top: 100%;
        padding: 7px 20px;
        right: 0;
        background-color: #91cdf5;
        white-space: nowrap;
    }
    .modal-open{ padding-right:3px !important; }
    .hide{ display: none!important }
    .custom_clear_button:hover, .custom_clear_button[class*=bg-]:focus, .custom_clear_button[class*=bg-].focus {
        background-color: #03A9F4;
        border-color: #03A9F4;
    }

    @media(min-width:1025px) and (max-width:1400px){
        .media-left#image_preview_div img {width: 40px !important;height: auto !important;}
        .media-left#image_preview_div {max-width: 50px;position: absolute;left: -50px;}
    }
    @media(max-width:320px){ 
        div#image_preview_div img {width: 40px !important;height: 40px !important;}
        div#image_preview_div{width: 20% !important;display: inline-block !important;}
        .media-body{width: 75% !important;display: inline-block !important;}
    }
</style>