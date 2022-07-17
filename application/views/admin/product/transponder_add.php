<!--<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>-->
<script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/wysihtml5.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/toolbar.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/parsers.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/locales/bootstrap-wysihtml5.ua-UA.js"></script>
<script type="text/javascript" src="assets/js/plugins/notifications/jgrowl.min.js"></script>
<?php
if (isset($dataArr)) {
    $form_action = site_url('admin/product/transponder/edit/' . base64_encode($dataArr['id']));
} else {
    $form_action = site_url('admin/product/transponder/add');
}
?>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('admin/product/transponder') ?>">Application Data</a></li>
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
            <form method="post" action="<?php echo $form_action; ?>" class="form-horizontal" id="add_transponder_form">
                <?php if (!empty($record_id)) { ?>
                    <input type="hidden" name="record_id" value="<?= $record_id ?>" />
                <?php } ?>
                <div class="panel panel-body login-form">
                    <?php $this->load->view('alert_view'); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback select_form_group">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Make :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150 form_mobile_100 pr-xs-0">
                                            <div class="row select2-plus-dropdown">
                                                <div class="col-md-10 col-sm-10 col-xs-10 select2-dropdown-custom" style="padding-right:0px">        
                                                    <select class="select select-size-sm" data-placeholder="Select a Company..." id="txt_make_name" name="txt_make_name" required <?php
                                                    if (empty($companyArr)) {
                                                        echo 'disabled';
                                                    }
                                                    ?>>
                                                                <?php if (empty($companyArr)) { ?>
                                                            <option value="" class="no_makes">No data added yet</option>
                                                        <?php } else { ?>
                                                            <option value=""></option>
                                                        <?php } ?>
                                                        <?php foreach ($companyArr as $k => $v) { ?>
                                                            <?php if (isset($dataArr)) { ?>
                                                                <option value="<?php echo $v['id']; ?>" <?php
                                                                if ($dataArr['make_id'] == $v['id']) {
                                                                    echo "selected";
                                                                }
                                                                ?>><?php echo $v['name']; ?></option>
                                                                    <?php } else if (set_value('txt_make_name') == $v['id']) { ?>
                                                                <option value="<?php echo $v['id']; ?>" selected><?php echo $v['name']; ?></option>
                                                            <?php } else { ?>
                                                                <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo '<label id="txt_make_name_error2" class="validation-error-label" for="txt_make_name">' . form_error('txt_make_name') . '</label>'; ?>
                                                </div>
                                                <div class="col-md-2 col-sm-2 col-xs-2 btn_s select2-dropdown-plus" style="padding:0px">
                                                    <span class="input-group-btn">
                                                        <button class="btn bg-teal btn-sm add_modal" type="button" id="add_make_modal" style="border-top-right-radius:5px;border-bottom-right-radius:5px;padding: 7px 12px;"><i class="icon-plus-circle2"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback select_form_group form_mobile_100">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 required">Model :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150 form_mobile_100 pr-xs-0">
                                            <div class="row select2-plus-dropdown">
                                                <div class="col-md-10 col-sm-10 col-xs-10 select2-dropdown-custom" style="padding-right:0px">        
                                                    <select class="select select-size-sm" data-placeholder="Select a Model..." id="txt_model_name" name="txt_model_name" required>
                                                        <option></option>
                                                        <?php
                                                        if (isset($dataArr)) {
                                                            foreach ($modelArr as $k => $v) {
                                                                ?>
                                                                <option value="<?php echo $v['id']; ?>" <?php
                                                                if ($dataArr['model_id'] == $v['id']) {
                                                                    echo 'selected';
                                                                }
                                                                ?>><?php echo $v['name']; ?></option>
                                                                        <?php
                                                                    }
                                                                } else if (set_value('txt_model_name') == $v['id']) {
                                                                    ?>
                                                            <option value="<?php echo $v['id']; ?>" selected><?php echo $v['name']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php echo '<label id="txt_model_name_error2" class="validation-error-label" for="txt_model_name">' . form_error('txt_model_name') . '</label>'; ?>
                                                </div>
                                                <div class="col-md-2 col-sm-2 col-xs-2 btn_s select2-dropdown-plus" style="padding:0px">
                                                    <span class="input-group-btn">
                                                        <button class="btn bg-teal btn-sm add_modal" type="button" id="add_model_modal" style="border-top-right-radius:5px;border-bottom-right-radius:5px;padding: 7px 12px;"><i class="icon-plus-circle2"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback select_form_group">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 control-label required">Year :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">        
                                            <select class="select select-size-sm" data-placeholder="Select a Year..." id="txt_year_name" name="txt_year_name" required>
                                                <?php if (empty($yearArr)) { ?>
                                                    <option value="" class="no_makes">No data added yet</option>
                                                <?php } else { ?>
                                                    <option value=""></option>
                                                <?php } ?>
                                                <?php foreach ($yearArr as $k => $v) { ?>
                                                    <?php if (isset($dataArr)) { ?>
                                                        <option value="<?php echo $v['id']; ?>" <?php
                                                        if ($dataArr['year_id'] == $v['id']) {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $v['name']; ?></option>
                                                            <?php } else if (set_value('txt_year_name') == $v['id']) { ?>
                                                        <option value="<?php echo $v['id']; ?>" selected><?php echo $v['name']; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <!-- <div class="col-md-2 col-sm-11 col-xs-2" style="padding:0px">
                                            <span class="input-group-btn">
                                                <button class="btn bg-teal btn-sm add_modal" type="button" id="add_year_modal" style="border-top-right-radius:5px;border-bottom-right-radius:5px"><i class="icon-plus-circle2"></i></button>
                                            </span>
                                        </div> -->
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Transponder Equipped:</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <select class="select select-size-sm" id="txt_transponder_equipped" name="txt_transponder_equipped">
                                                <option value="yes" <?php
                                                if (isset($dataArr)) {
                                                    if ($dataArr['transponder_equipped'] == 'yes') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>>Yes</option>
                                                <option value="no" <?php
                                                if (isset($dataArr)) {
                                                    if ($dataArr['transponder_equipped'] == 'no') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>>No</option>
                                                <option value="optional" <?php
                                                if (isset($dataArr)) {
                                                    if ($dataArr['transponder_equipped'] == 'optional') {
                                                        echo 'selected';
                                                    }
                                                } else {
                                                    echo 'selected';
                                                }
                                                ?>>Optional</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Key Type:</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <div class="multi-select-full">
                                                <select class="multiselect-filtering" multiple="multiple" name="txt_key_value[]" id="txt_key_value">
                                                    <?php
                                                    if (isset($dataArr)) {
                                                        $key_value_data = explode(',', $dataArr['key_value']);
                                                    } else {
                                                        $key_value_data = [];
                                                    }
                                                    ?>
                                                    <option value="regular" <?php
                                                    if (in_array('regular', $key_value_data)) {
                                                        echo 'selected';
                                                    }
                                                    ?>>Regular</option>
                                                    <option value="high_security" <?php
                                                    if (in_array('high_security', $key_value_data)) {
                                                        echo 'selected';
                                                    }
                                                    ?>>High Security</option>
                                                    <option value="prox" <?php
                                                    if (in_array('prox', $key_value_data)) {
                                                        echo 'selected';
                                                    }
                                                    ?>>Prox</option>
                                                    <option value="other" <?php
                                                    if (in_array('other', $key_value_data)) {
                                                        echo 'selected';
                                                    }
                                                    ?>>Other</option>
                                                    <!-- <?php
                                                    foreach ($itemArr as $k => $v) {
                                                        if (isset($dataArr)) {
                                                            if (in_array($v['id'], $trans_items)) {
                                                                $selected = 'selected';
                                                            } else {
                                                                $selected = '';
                                                            }
                                                        } else {
                                                            $selected = '';
                                                        }
                                                        ?>
                                                    <?php } ?> -->
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">System :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_mvp_system" id="txt_mvp_system" value="<?php echo (isset($dataArr)) ? $dataArr['mvp_system'] : set_value('txt_mvp_system'); ?>">
                                            <?php echo '<label id="txt_mvp_system_error2" class="validation-error-label" for="txt_mvp_system">' . form_error('txt_mvp_system') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <!--                                <div class="col-md-12">
                                                                    <div class="form-group has-feedback">
                                                                        <label class="col-md-4 control-label">Dongle :</label>
                                                                        <div class="col-md-7">
                                                                            <input type="text" class="form-control" name="txt_dongle" id="txt_dongle" value="<?php echo (isset($dataArr)) ? $dataArr['dongle'] : set_value('txt_dongle'); ?>">
                                <?php echo '<label id="txt_dongle_error2" class="validation-error-label" for="txt_dongle">' . form_error('txt_dongle') . '</label>'; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>-->
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Pin-Code Required:</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <select class="select select-size-sm" id="txt_pincode_required" name="txt_pincode_required">
                                                <option value="yes" <?php
                                                if (isset($dataArr)) {
                                                    if ($dataArr['pincode_required'] == 'yes') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>>Yes</option>
                                                <option value="no" <?php
                                                if (isset($dataArr)) {
                                                    if ($dataArr['pincode_required'] == 'no') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>>No</option>
                                                <option value="optional" <?php
                                                if (isset($dataArr)) {
                                                    if ($dataArr['pincode_required'] == 'optional') {
                                                        echo 'selected';
                                                    }
                                                } else {
                                                    echo 'selected';
                                                }
                                                ?>>Optional</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Pin-Code Reading Available :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_pincode_avail" id="txt_pincode_avail" value="<?php echo (isset($dataArr)) ? $dataArr['pincode_reading_available'] : set_value('txt_pincode_avail'); ?>">
                                            <?php echo '<label id="txt_pincode_avail_error2" class="validation-error-label" for="txt_pincode_avail">' . form_error('txt_pincode_avail') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Key On-board Programing :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_key_onboard_prog" id="txt_key_onboard_prog" value="<?php echo (isset($dataArr)) ? $dataArr['key_onboard_progaming'] : set_value('txt_key_onboard_prog'); ?>">
                                            <?php echo '<label id="txt_key_onboard_prog_error2" class="validation-error-label" for="txt_key_onboard_prog">' . form_error('txt_key_onboard_prog') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Remote On-board Programing :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_remote_onboard_prog" id="txt_remote_onboard_prog" value="<?php echo (isset($dataArr)) ? $dataArr['remote_onboard_progaming'] : set_value('txt_remote_onboard_prog'); ?>">
                                            <?php echo '<label id="txt_remote_onboard_prog_error2" class="validation-error-label" for="txt_remote_onboard_prog">' . form_error('txt_remote_onboard_prog') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Test Key :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_test_key" id="txt_test_key" value="<?php echo (isset($dataArr)) ? $dataArr['test_key'] : set_value('txt_test_key'); ?>">
                                            <?php echo '<label id="txt_test_key_error2" class="validation-error-label" for="txt_test_key">' . form_error('txt_test_key') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">IIco :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_iico" id="txt_iico" value="<?php echo (isset($dataArr)) ? $dataArr['iico'] : set_value('txt_iico'); ?>">
                                            <?php echo '<label id="txt_iico_error2" class="validation-error-label" for="txt_iico">' . form_error('txt_iico') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">JET :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_jet" id="txt_jet" value="<?php echo (isset($dataArr)) ? $dataArr['jet'] : set_value('txt_jet'); ?>">
                                            <?php echo '<label id="txt_jet_error2" class="validation-error-label" for="txt_jet">' . form_error('txt_jet') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">JMA :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_jma" id="txt_jma" value="<?php echo (isset($dataArr)) ? $dataArr['jma'] : set_value('txt_jma'); ?>">
                                            <?php echo '<label id="txt_jma_error2" class="validation-error-label" for="txt_jma">' . form_error('txt_jma') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Keyline :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_keyline" id="txt_keyline" value="<?php echo (isset($dataArr)) ? $dataArr['keyline'] : set_value('txt_keyline'); ?>">
                                            <?php echo '<label id="txt_keyline_error2" class="validation-error-label" for="txt_keyline">' . form_error('txt_keyline') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Strattec Non-Remote Key :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_str_non_rem_key" id="txt_str_non_rem_key" value="<?php echo (isset($dataArr)) ? $dataArr['strattec_non_remote_key'] : set_value('txt_str_non_rem_key'); ?>">
                                            <?php echo '<label id="txt_str_non_rem_key_error2" class="validation-error-label" for="txt_str_non_rem_key">' . form_error('txt_str_non_rem_key') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Strattec Remote Key :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_str_rem_key" id="txt_str_rem_key" value="<?php echo (isset($dataArr)) ? $dataArr['strattec_remote_key'] : set_value('txt_str_rem_key'); ?>">
                                            <?php echo '<label id="txt_str_rem_key_error2" class="validation-error-label" for="txt_str_rem_key">' . form_error('txt_str_rem_key') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>                            
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">OEM Non-Remote Key :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_oem_non_rem_key" id="txt_oem_non_rem_key" value="<?php echo (isset($dataArr)) ? $dataArr['oem_non_remote_key'] : set_value('txt_oem_non_rem_key'); ?>">
                                            <?php echo '<label id="txt_oem_non_rem_key_error2" class="validation-error-label" for="txt_oem_non_rem_key">' . form_error('txt_oem_non_rem_key') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">OEM Remote Key :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_oem_rem_key" id="txt_oem_rem_key" value="<?php echo (isset($dataArr)) ? $dataArr['oem_remote_key'] : set_value('txt_oem_rem_key'); ?>">
                                            <?php echo '<label id="txt_oem_rem_key_error2" class="validation-error-label" for="txt_oem_rem_key">' . form_error('txt_oem_rem_key') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <!--                                <div class="col-md-12">
                                                                    <div class="form-group has-feedback">
                                                                        <label class="col-md-4 control-label">Other :</label>
                                                                        <div class="col-md-7">
                                                                            <input type="text" class="form-control" name="txt_other_non_rem_key" id="txt_other_non_rem_key" value="<?php echo (isset($dataArr)) ? $dataArr['other_non_remote_key'] : set_value('txt_other_non_rem_key'); ?>">
                                <?php echo '<label id="txt_other_non_rem_key_error2" class="validation-error-label" for="txt_other_non_rem_key">' . form_error('txt_other_non_rem_key') . '</label>'; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>-->
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Other :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_other" id="txt_other" value="<?php echo (isset($dataArr)) ? $dataArr['other'] : set_value('txt_other'); ?>">
                                            <?php echo '<label id="txt_other_error2" class="validation-error-label" for="txt_other">' . form_error('txt_other') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Code Series :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_code_series" id="txt_code_series" value="<?php echo (isset($dataArr)) ? $dataArr['code_series'] : set_value('txt_code_series'); ?>">
                                            <?php echo '<label id="txt_code_series_error2" class="validation-error-label" for="txt_code_series">' . form_error('txt_code_series') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">FCC ID # :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_fcc_id" id="txt_fcc_id" value="<?php echo (isset($dataArr)) ? $dataArr['fcc_id'] : set_value('txt_fcc_id'); ?>">
                                            <?php echo '<label id="txt_fcc_id_error2" class="validation-error-label" for="txt_fcc_id">' . form_error('txt_fcc_id') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">IC # :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_ic" id="txt_ic" value="<?php echo (isset($dataArr)) ? $dataArr['ic'] : set_value('txt_ic'); ?>">
                                            <?php echo '<label id="txt_ic_error2" class="validation-error-label" for="txt_ic">' . form_error('txt_ic') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Frequency :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_frequency" id="txt_frequency" value="<?php echo (isset($dataArr)) ? $dataArr['frequency'] : set_value('txt_frequency'); ?>">
                                            <?php echo '<label id="txt_frequency_error2" class="validation-error-label" for="txt_frequency">' . form_error('txt_frequency') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Chip ID :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_chip_id" id="txt_chip_id" value="<?php echo (isset($dataArr)) ? $dataArr['chip_ID'] : set_value('txt_chip_id'); ?>">
                                            <?php echo '<label id="txt_chip_id_error2" class="validation-error-label" for="txt_chip_id">' . form_error('txt_chip_id') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Transponder Re-use :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <select class="select select-size-sm" id="txt_transponder_re_use" name="txt_transponder_re_use">
                                                <option value="yes" <?php
                                                if (isset($dataArr)) {
                                                    if ($dataArr['transponder_re_use'] == 'yes') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>>Yes</option>
                                                <option value="no" <?php
                                                if (isset($dataArr)) {
                                                    if ($dataArr['transponder_re_use'] == 'no') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>>No</option>
                                                <option value="optional" <?php
                                                if (isset($dataArr)) {
                                                    if ($dataArr['transponder_re_use'] == 'optional') {
                                                        echo 'selected';
                                                    }
                                                } else {
                                                    echo 'selected';
                                                }
                                                ?>>Optional</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Max. Number of Keys :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_max_no_of_keys" id="txt_max_no_of_keys" value="<?php echo (isset($dataArr)) ? $dataArr['max_no_of_keys'] : set_value('txt_max_no_of_keys'); ?>">
                                            <?php echo '<label id="txt_max_no_of_keys_error2" class="validation-error-label" for="txt_max_no_of_keys">' . form_error('txt_max_no_of_keys') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Key Shell :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="txt_key_shell" id="txt_key_shell" value="<?php echo (isset($dataArr)) ? $dataArr['key_shell'] : set_value('txt_key_shell'); ?>">
                                            <?php echo '<label id="txt_key_shell_error2" class="validation-error-label" for="txt_key_shell">' . form_error('txt_key_shell') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <!--                                <div class="col-md-12">
                                                                    <div class="form-group has-feedback">
                                                                        <label class="col-md-4 control-label">Cloneable Chip :</label>
                                                                        <div class="col-md-7">
                                                                            <input type="text" class="form-control" name="txt_cloneable_chip" id="txt_cloneable_chip" value="<?php echo (isset($dataArr)) ? $dataArr['cloneable_chip'] : set_value('txt_cloneable_chip'); ?>">
                                <?php echo '<label id="txt_cloneable_chip_error2" class="validation-error-label" for="txt_cloneable_chip">' . form_error('txt_cloneable_chip') . '</label>'; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>-->
                                <!--                                <div class="col-md-12">
                                                                    <div class="form-group has-feedback">
                                                                        <label class="col-md-4 control-label">Decoders and Readers :</label>
                                                                        <div class="col-md-7">
                                                                            <input type="text" class="form-control" name="txt_decoders_readers" id="txt_decoders_readers" value="<?php echo (isset($dataArr)) ? $dataArr['decoders_readers'] : set_value('txt_decoders_readers'); ?>">
                                <?php echo '<label id="txt_decoders_readers_error2" class="validation-error-label" for="txt_decoders_readers">' . form_error('txt_decoders_readers') . '</label>'; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>-->
                                <!--                                <div class="col-md-12">
                                                                    <div class="form-group has-feedback">
                                                                        <label class="col-md-4 control-label">Tumbler Information :</label>
                                                                        <div class="col-md-7">
                                                                            <input type="text" class="form-control" name="txt_tumbler" id="txt_tumbler" value="<?php echo (isset($dataArr)) ? $dataArr['tumbler_info'] : set_value('txt_tumbler'); ?>">
                                <?php echo '<label id="txt_tumbler_error2" class="validation-error-label" for="txt_tumbler">' . form_error('txt_tumbler') . '</label>'; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>-->
                                
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Parts :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <div class="multi-select-full">
                                                <select class="multiselect-filtering" multiple="multiple" name="txt_items[]" id="txt_items">
                                                    <?php
                                                    foreach ($itemArr as $k => $v) {
                                                        if (isset($dataArr)) {
                                                            if (in_array($v['id'], $trans_items)) {
                                                                $selected = 'selected';
                                                            } else {
                                                                $selected = '';
                                                            }
                                                        } else {
                                                            $selected = '';
                                                        }
                                                        ?>
                                                        <option value="<?php echo $v['id']; ?>" <?php echo $selected; ?>><?php echo $v['part_no'] . ' <b>(Vendor : ' . $v['pref_vendor_name'] . ')</b>'; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Compatible Tool :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <div class="multi-select-full">
                                                <select class="multiselect-filtering" multiple="multiple" id="txt_tools">
                                                    <?php
                                                    foreach ($toolArr as $k => $v) {
                                                        if (isset($dataArr)) {
                                                            $tools = explode(',', $dataArr['tools']);
                                                            if (in_array($v['id'], $tools)) {
                                                                $selected = 'selected';
                                                            } else {
                                                                $selected = '';
                                                            }
                                                        } else {
                                                            $selected = '';
                                                        }
                                                        ?>
                                                        <option class='tools' value="<?php echo $v['id']; ?>" <?php echo $selected; ?>><?php echo $v['equip_name'] . ' <b>(' . $v['manu_name'] . ')</b>'; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Notes :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <textarea class="form-control" name="txt_notes" id="txt_notes"><?php echo (isset($dataArr)) ? $dataArr['notes'] : set_value('txt_note'); ?></textarea>
                                            <?php echo '<label id="txt_note_error2" class="validation-error-label" for="txt_note">' . form_error('txt_note') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 pl-xs-0 pr-xs-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-xxs" id="tbl_additional_data">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th style="width:50%">Field Name</th>
                                                    <th style="width:50%">Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($dataArr)) {
                                                    $tot_cnt = count($additional_Arr);
                                                    if (empty($additional_Arr)) {
                                                        ?>
                                                        <tr>
                                                            <td><a href="javascript:void(0)" style="color:#009688"><i class="icon-plus-circle2 btn_add_extra_field"></i></a></td>
                                                            <td><input type="text" class="form-control additional_field_txt" name="txt_field_name[]" placeholder="Field Name"></td>
                                                            <td><input type="text" class="form-control additional_value_txt" name="txt_field_value[]" placeholder="Value"></td>
                                                        </tr>
                                                        <?php
                                                    } else {
                                                        foreach ($additional_Arr as $k => $v) {
                                                            if ($tot_cnt == ($k + 1)) {
                                                                $class = '<a href="javascript:void(0)" style="color:#009688"><i class="icon-plus-circle2 btn_add_extra_field"></i></a>';
                                                            } else {
                                                                $class = '<a href="javascript:void(0)" style="color:#d66464"><i class="icon-minus-circle2 btn_remove_extra_field"></i></a>';
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $class; ?></td>
                                                                <td><input type="text" class="form-control additional_field_txt" name="txt_field_name[]" placeholder="Field Name" value="<?php echo $v['field_name']; ?>"></td>
                                                                <td><input type="text" class="form-control additional_value_txt" name="txt_field_value[]" placeholder="Value" value="<?php echo $v['field_value']; ?>"></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td><a href="javascript:void(0)" style="color:#009688"><i class="icon-plus-circle2 btn_add_extra_field"></i></a></td>
                                                        <td><input type="text" class="form-control additional_field_txt" name="txt_field_name[]" placeholder="Field Name"></td>
                                                        <td><input type="text" class="form-control additional_value_txt" name="txt_field_value[]" placeholder="Value"></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>


                                            </tbody>
                                        </table>
                                        <label id="additional_data_error2" class="validation-error-label" for="additional_data"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 tool_details pl-0 pr-0">
                            <?php
                            if (isset($dataArr) && !empty($dataArr)) {
                                if ($dataArr['tools'] != null) {
                                    $tools = explode(',', $dataArr['tools']);

                                    $tool_details = explode(':-:', $dataArr['tool_details']);
                                    if (!empty($tools)) {
                                        foreach ($tools as $k => $t) {
                                            $key = array_search($t, array_column($toolArr, 'id'));
                                            ?>
                                            <div class="col-md-6 tool_div" id="div_<?php echo $t ?>">
                                                <div class="panel panel-primary panel-bordered">
                                                    <div class="panel-heading">
                                                        <h6 class="panel-title"><?php echo $toolArr[$key]['equip_name'] . ' (' . $toolArr[$key]['manu_name'] . ')' ?></h6>
                                                    </div>
                                                    <div class="panel-body">
                                                        <input type="hidden" name="txt_tools[]" value="<?= $t ?>">
                                                        <textarea placeholder="Place to add addtional details and instructions" name="tool_details[]" class="wysihtml5 wysihtml5-default form-control tool_textarea" rows="4"><?php echo (isset($tool_details[$k])) ? $tool_details[$k] : '' ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 pl-xs-0 pr-xs-0">
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

<!-- Add Tax Modal -->
<div id="add_form_modal" class="modal fade addmake-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header custom_modal_header bg-teal-400">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h6 class="modal-title text-center"></h6>
            </div>
            <div class="modal-body panel-body hide" id="add_make_form_body">
                <form method="post" id="add_make_form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-material has-feedback">
                                <label class="required" aria-required="true"><b>Name</b></label>
                                <input type="text" class="form-control" name="txt_modal_make_name" id="txt_modal_make_name" placeholder="Make Name" required="" aria-required="true">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="submit" class="btn bg-teal btn-block custom_save_button" name="btn_submit_make_data" id="btn_submit_make_data">Save</button>
                            <button type="button" class="btn btn-default custom_cancel_button" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-body panel-body hide" id="add_model_form_body">
                <form method="post" id="add_model_form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-material has-feedback">
                                <label class="required" aria-required="true"><b>Name</b></label>
                                <input type="text" class="form-control" name="txt_modal_model_name" id="txt_modal_model_name" placeholder="Model Name" required="" aria-required="true">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-group-material has-feedback">
                                <label><b>List of Make</b></label>
                                <select class="select select-size-sm" data-placeholder="Select a company..." data-width="100%" name="txt_modal_make_name2" id="txt_modal_make_name2">
                                    <option></option>
                                    <?php foreach ($companyArr as $k => $v) { ?>
                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="submit" class="btn bg-teal btn-block custom_save_button" name="btn_submit_model_data" id="btn_submit_model_data">Save</button>
                            <button type="button" class="btn btn-default custom_cancel_button" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-body panel-body hide" id="add_year_form_body">
                <form method="post" id="add_year_form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-material has-feedback">
                                <label class="required" aria-required="true"><b>Name</b></label>
                                <input type="text" class="form-control" name="txt_tax_name" id="txt_tax_name" placeholder="Year Name" required="" aria-required="true">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="submit" class="btn bg-teal btn-block custom_save_button" name="btn_submit_tax_data" id="btn_submit_tax_data">Send</button>
                            <button type="button" class="btn btn-default custom_cancel_button" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    user_type = '<?php echo checkLogin('R'); ?>';
    remoteURL = site_url + "admin/product/checkUnique_Company_Name";
    remoteURL_model = site_url + "admin/product/Unique_Model";
</script>
<script type="text/javascript" src="assets/js/custom_pages/transponder.js?version='<?php echo time();?>'"></script>
<style>
    #additional_data_error2:before{ left: 15px; }
    .modal-open{ padding-right:3px !important; }
    .form-horizontal .checkbox{
        /*padding-top: 0px;*/
    }
    .form-horizontal .checkbox .checker{
        top: 18px;
    }
    .tool_textarea{
        max-height: 250px!important;
        margin-top: 0px;
        margin-bottom: 0px;
        height: 200px!important;
        min-height: 100px!important;
    }
    .tool_div .panel-title{
        max-height: 20px;
        font-size: unset;
    }
    .error{
        border-color: red;
    }
    .multiselect-container > li > a > label{
        word-break: break-word !important;
        white-space: normal;  
    }
    @media (max-width:1024px){
        .table-responsive {margin-bottom: 10px;}
        .tool_div {padding: 0 !important;}
        ul.wysihtml5-toolbar li a.dropdown-toggle {position: unset;}
        .tool_details .panel-body {padding: 10px;}
        ul.wysihtml5-toolbar {padding: 10px 10px;}
    }
    @media screen and (max-width:768px){
        .multiselect-container > li > a > label{
            padding: 8px 12px 8px 40px !important;
        }
    }
</style>