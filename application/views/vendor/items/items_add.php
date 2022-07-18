<!--<script type="text/javascript" src="assets/js/plugins/forms/tags/tagsinput.min.js"></script>-->
<?php
if (checkVendorLogin('R') != 5) {
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

if (isset($dataArr)) {
    $form_action = site_url('vendor/products/edit/' . base64_encode($dataArr['id']));
} else {
    $form_action = site_url('vendor/products/add');
}
?>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('vendor/home'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('vendor/products') ?>">Products</a></li>
            <li class="active">
                <?php
                if (isset($dataArr))
                    echo "Edit";
                else
                    echo "Add";
                ?>
            </li>
        </ul>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-body login-form">
                <?php $this->load->view('alert_view'); ?>
                <form method="post" action="<?php echo $form_action; ?>" class="form-horizontal" id="add_item_form" enctype="multipart/form-data" >
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Image :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <div class="media no-margin-top">
                                                <div class="media-left" id="image_preview_div">
                                                    <?php
                                                    if (isset($dataArr) && $dataArr['image'] && file_exists(ITEMS_IMAGE_PATH . '/' . $dataArr['image'])) {
                                                        $required = '';
                                                        ?>
                                                        <img src="<?php echo ITEMS_IMAGE_PATH . '/' . $dataArr['image'] ?>" style="width: 58px; height: 58px; border-radius: 2px;" alt="">
                                                        <?php
                                                    } else {
                                                        $required = 'required';
                                                        ?>
                                                        <img src="assets/images/key_icon_blue.png" style="width: 58px; height: 58px; border-radius: 2px;" alt="">
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
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Item Part No :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_item_part" id="txt_item_part" style="text-transform: uppercase;" value="<?php echo (isset($dataArr)) ? $dataArr['part_no'] : set_value('txt_item_part'); ?>">
                                            <input type="hidden" class="form-control" name="txt_item_hidden" id="txt_item_hidden" value="<?php echo (isset($dataArr)) ? $dataArr['id'] : null; ?>">
                                            <?php echo '<label id="txt_item_part_error2" class="validation-error-label" for="txt_item_part">' . form_error('txt_item_part') . '</label>'; ?>
                                            <span class="validation-error-label" id="exists_part"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Alternate Part No or SKU :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_internal_part" id="txt_internal_part" style="text-transform: uppercase;" value="<?php echo (isset($dataArr)) ? $dataArr['internal_part_no'] : set_value('txt_internal_part'); ?>">
                                            <?php echo '<label id="txt_internal_part_error2" class="validation-error-label" for="txt_internal_part">' . form_error('txt_internal_part') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Department :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">        
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
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <?php if (!empty($dataArr) && !empty($dataArr['item_qr_code'])) { ?>
                                    <div class="col-md-12">
                                        <div class="form-group has-feedback">
                                            <div class="col-md-7">
                                                <img src="<?php echo base_url() . QRCODE_IMAGE_PATH . '/' . $dataArr['item_qr_code']; ?>" style="width: 90px;height: 90px">
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Item Description :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <textarea class="form-control" name="txt_item_description" id="txt_item_description" rows="3"><?php echo (isset($dataArr)) ? $dataArr['description'] : set_value('txt_item_description'); ?></textarea>
                                            <?php echo '<label id="txt_item_description_error2" class="validation-error-label" for="txt_item_description">' . form_error('txt_item_description') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Vendor :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">        
                                            <select class="select select-size-sm" data-placeholder="Select a Preferred Vendor..." id="txt_pref_vendor" name="txt_pref_vendor" required <?php
                                            if (empty($vendor_Arr)) {
                                                echo 'disabled';
                                            }
                                            ?>>
                                                <option></option>
                                                <?php
                                                foreach ($vendor_Arr as $k => $v) {
                                                    if (checkVendorLogin('R') == 5 && checkVendorLogin('I') == $v['user_id']) {
                                                        $selected = 'selected';
                                                    } else if (checkVendorLogin('R') != 5 && $user_data['business_user_id'] == $v['user_id']) {
                                                        $selected = 'selected';
                                                    } else {
                                                        $selected = 'disabled="disabled"';
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
                                    <div class="form-group has-feedback" style="margin-bottom:15px">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Manufacturer :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control tags-input" name="txt_manufacturer" id="txt_manufacturer"  value="<?php echo (isset($dataArr)) ? $dataArr['manufacturer'] : set_value('txt_manufacturer'); ?>"/>
                                            <?php echo '<label id="txt_manufacturer_error2" class="validation-error-label" for="txt_manufacturer">' . form_error('txt_manufacturer') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group has-feedback" style="margin-bottom:15px">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Link :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="url" class="form-control" name="item_link" id="item_link" placeholder="Part Link" value="<?php echo (isset($dataArr)) ? $dataArr['item_link'] : set_value('item_link'); ?>">
                                            <?php // echo '<label id="item_link_error2" class="validation-error-label" for="txt_internal_part">' . form_error('item_link') . '</label>';  ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive overflow-visible-md">
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
                                        $main_key = 0;
                                        if (isset($trans_items_data) && !empty($trans_items_data)):
                                            foreach ($trans_items_data as $k => $val):
                                                $key = ++$k;
                                                ?>
                                                <tr class="tr_class" data-value="<?php echo $key ?>">
                                                    <td>
                                                        <?php
                                                        if ($key <= (count($trans_items_data) - 1)):
                                                            echo '<a href="javascript:void(0)" style="color:#d66464"><i class="icon-minus-circle2 btn_remove_extra_field"></i></a>';
                                                        else:
                                                            echo '<a href="javascript:void(0)" style="color:#009688"><i class="icon-plus-circle2 btn_add_extra_field"></i></a>';
                                                        endif;
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="trans_item_id[]" value="<?php echo $val['id'] ?>"/>
                                                        <select class="select select-size-sm additional_field_txt txt_make_name" data-placeholder="Select a Company..." id="txt_make_name_<?php echo $key ?>" name="txt_make_name[]" required <?php
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
                                                        <select class="select select-size-sm additional_field_txt txt_model_name" data-placeholder="Select a Model..." id="txt_model_name_<?php echo $key ?>" name="txt_model_name[]" required>
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
                                                        <select class="select select-size-sm additional_field_txt txt_year_name multiselect-ui" multiple="multiple" data-placeholder="Select a Year..." id="txt_year_name_<?php echo $key ?>" data-keyid="<?= $main_key ?>" name="txt_year_name[<?= $main_key ?>][]" required>
                                                            <option></option>
                                                            <?php
                                                            if (!empty($year_data[$val['id']])) {
                                                                $item_array = $selected_years[$val['make_id']][$val['model_id']];
                                                                foreach ($year_data[$val['id']] as $k => $v) {
                                                                    if (!empty($v) && in_array($v['id'], $item_array)) {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = '';
                                                                    }
                                                                    ?>
                                                                    <option value="<?= $v['id']; ?>" <?= $selected ?> ><?= $v['name']; ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <?php
                                                $main_key++;
                                            endforeach;
                                        else:
                                            ?>
                                            <tr class="tr_class" data-value="0">
                                                <td><a href="javascript:void(0)" style="color:#009688"><i class="icon-plus-circle2 btn_add_extra_field"></i></a></td>
                                                <td>
                                                    <select class="select select-size-sm additional_field_txt txt_make_name" data-placeholder="Select a Company..." id="txt_make_name_0" name="txt_make_name[]" required <?php
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
                                                    <select class="select select-size-sm additional_field_txt txt_model_name" data-placeholder="Select a Model..." id="txt_model_name_0" name="txt_model_name[]" required>
                                                        <option></option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="select select-size-sm additional_field_txt txt_year_name multiselect-ui" multiple="multiple" data-placeholder="Select a Year..." id="txt_year_name_0" name="txt_year_name[0][]" required>
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
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="submit" class="btn bg-teal custom_save_button">Save</button>
                            <button type="button" class="btn btn-default custom_cancel_button" onclick="if (history.length > 2) {
                                        window.history.back()
                                    } else {
                                        window.location.href = 'vendor/products';
                                    }">Cancel</button>
                        </div>
                    </div>
                </form>
                <br>
                <div class="bulk_upload_div m-10">
                    <div class="content-divider text-muted form-group"><span>OR</span></div>
                    <form method="post" class="form-validate-jquery" id="bulk_item_form" name="bulk_item_form" action="<?php echo site_url('vendor/products/item_bulk_add'); ?>" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <h6>From here you can do bulk upload</h6>
                            </div>
                            <div class="col-md-9 col-sm-9 col-xs-12 mb-3">
                                <div class="uploader" id="uniform-upload_csv">
                                    <input type="file" class="file-styled-primary" name="upload_csv" id="upload_csv">
                                    <span class="filename" style="user-select: none;">No file selected</span>
                                    <span class="action btn bg-teal" style="user-select: none;">Choose File</span>
                                </div>
                                <code><a href="<?php echo ITEM_DUMMY_CSV; ?>" style="text-align: left">Click Here</a> , to get a CSV format.</code>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <button type="submit" class="btn bg-teal" style="border-radius: 2px">Upload<i class="icon-arrow-up13 position-right"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('Templates/footer.php'); ?>
</div>
<?php
if (!empty($companyArr)) {
    echo "<script>var companyArr = " . json_encode($companyArr) . "</script>";
}
if (!empty($yearArr)) {
    echo "<script>var yearArr = " . json_encode($yearArr) . "</script>";
}
?>
<script type="text/javascript">
    var product_id = '<?php echo (isset($dataArr)) ? $dataArr['id'] : null; ?>';
</script>
<script type="text/javascript" src="assets/js/custom_pages/vendor/items.js"></script>
<style>
    .modal-open{ padding-right:3px !important; }
    /*.uploader .filename{max-width:170px;}*/
    .table-responsive {margin-bottom: 20px;}
    @media(min-width:1024px) and (max-width:1300px){
        .media.no-margin-top{ position:relative;}
        /*.media-left#image_preview_div{max-width:50px; position: absolute; left: -50px;}*/
        .media-left#image_preview_div img{width:40px !important;height:auto !important;}
        /*.uploader .filename{max-width:140px;}*/
    }
    @media(max-width:480px){  
        div#image_preview_div {padding-right: 5px ;}
        div#image_preview_div img{width: 40px !important; height: 40px !important;}
    }
</style>