<script type="text/javascript" src="assets/js/plugins/forms/tags/tagsinput.min.js"></script>
<?php
if (isset($dataArr)) {
    $form_action = site_url('admin/inventory/items/edit/' . base64_encode($dataArr['id']));
} else {
    $form_action = site_url('admin/inventory/items/add');
}
?>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('admin/inventory/items') ?>">Items</a></li>
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
            <form method="post" action="<?php echo $form_action; ?>" class="form-horizontal" id="add_item_form" enctype="multipart/form-data" >
                <div class="panel panel-body login-form">
                    <?php $this->load->view('alert_view'); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150">Image :</label>
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
                                                        <img src="assets/images/placeholder.jpg" style="width: 58px; height: 58px; border-radius: 2px;" alt="">
                                                    <?php } ?>
                                                </div>
                                                <div class="media-body">
                                                    <input type="file" name="image_link" id="image_link" class="file-styled" onchange="readURL(this);">
                                                    <span class="help-block">Accepted formats: png, jpg. Max file size 2Mb</span>
                                                    <span id="image_message_alert" style="color: red;"></span>
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
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Alternate Part No or SKU:</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_internal_part" id="txt_internal_part" style="text-transform: uppercase;" value="<?php echo (isset($dataArr)) ? $dataArr['internal_part_no'] : set_value('txt_internal_part'); ?>">
                                            <?php echo '<label id="txt_internal_part_error2" class="validation-error-label" for="txt_internal_part">' . form_error('txt_internal_part') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback select_form_group mb-xs-4">
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
                                <div class="col-md-12">
                                    <div class="form-group has-feedback" style="margin-bottom:15px">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Manufacturer :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control tags-input" name="txt_manufacturer" id="txt_manufacturer"  value="<?php echo (isset($dataArr)) ? $dataArr['manufacturer'] : set_value('txt_manufacturer'); ?>"/>
                                            <?php echo '<label id="txt_manufacturer_error2" class="validation-error-label" for="txt_manufacturer">' . form_error('txt_manufacturer') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <?php if (!empty($dataArr) && !empty($dataArr['item_qr_code'])) { ?>
                                    <div class="col-md-12">
                                        <div class="form-group has-feedback" style="margin-bottom:15px">
                                            <div class="col-md-7">
                                                <img src="<?php echo base_url() . QRCODE_IMAGE_PATH . '/' . htmlentities($dataArr['item_qr_code']); ?>" style="width: 90px;height: 90px">
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($dataArr) && !empty($dataArr['item_qr_code'])) { ?>
                                <div class="col-lg-12">
                                    <div class="col-lg-12 pl-xs-0">
                                        <div class="form-group">
                                            <button type="submit" class="btn" style="background-color: #33a9f5; color: #FFFFFF; ">Generate New QR Code</button>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback" style="margin-bottom:15px">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Item Description :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <textarea class="form-control" name="txt_item_description" id="txt_item_description" rows="4"><?php echo (isset($dataArr)) ? $dataArr['description'] : set_value('txt_item_description'); ?></textarea>
                                            <?php echo '<label id="txt_item_description_error2" class="validation-error-label" for="txt_item_description">' . form_error('txt_item_description') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback select_form_group">
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
                                                    if (isset($dataArr)) {
                                                        if ($dataArr['preferred_vendor'] == $v['id']) {
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

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-lg-12 pl-xs-0 pr-xs-0">
                            <button type="submit" class="btn bg-teal custom_save_button">Save</button>
                            <button type="button" class="btn btn-default custom_cancel_button" onclick="if (history.length > 2) {
                                        window.history.back()
                                    } else {
                                        window.location.href = 'admin/product/transponder';
                                    }">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php $this->load->view('Templates/footer.php'); ?>
</div>
<script type="text/javascript" src="assets/js/custom_pages/items.js?version='<?php echo time();?>'"></script>
<style>
    .modal-open{ padding-right:3px !important; }
    /*.uploader .filename{max-width:170px;}*/
    @media(min-width:1024px) and (max-width:1300px){
        .media.no-margin-top{ position:relative;}
        /*.media-left#image_preview_div{max-width:50px; position: absolute; left: -50px;}*/
        .media-left#image_preview_div img{width:40px !important;height:auto !important;}
        /*.uploader .filename{max-width:140px;}*/
    }
    @media(max-width:320px){
      div#image_preview_div {padding-right: 5px;}
      div#image_preview_div  img {width: 40px !important;height: 40px !important;}
    }
</style>