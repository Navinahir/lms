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
    $form_action = site_url('admin/lead/edit/' . base64_encode($dataArr['id']));
} else {
    $form_action = site_url('admin/lead/add');
}
?>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('admin/lead') ?>">Lead Data</a></li>
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
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 lb-w-150 control-label">Source :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<select class="select select-size-sm" id="source" name="source">
												<option value="1" >Source1</option>
												<option value="2" >Source2</option>
												<option value="3" >Source2</option>
											</select>
										</div>
									</div>
								</div>

                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Firstname :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo (isset($dataArr)) ? $dataArr['firstname'] : set_value('firstname'); ?>">
                                            <?php echo '<label id="firstname_error2" class="validation-error-label" for="firstname">' . form_error('firstname') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Lastname :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo (isset($dataArr)) ? $dataArr['lastname'] : set_value('lastname'); ?>">
											<?php echo '<label id="lastname_error2" class="validation-error-label" for="lastname">' . form_error('lastname') . '</label>'; ?>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Email :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<input type="text" class="form-control" name="email" id="email" value="<?php echo (isset($dataArr)) ? $dataArr['email'] : set_value('email'); ?>">
											<?php echo '<label id="email_error2" class="validation-error-label" for="email">' . form_error('email') . '</label>'; ?>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Phone Number :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<input type="text" class="form-control" name="phone_number" id="phone_number" value="<?php echo (isset($dataArr)) ? $dataArr['phone_number'] : set_value('phone_number'); ?>">
											<?php echo '<label id="phone_number_error2" class="validation-error-label" for="phone_number">' . form_error('phone_number') . '</label>'; ?>
										</div>
									</div>
								</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 lb-w-150 control-label">Address :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<textarea class="form-control" name="address" id="address"><?php echo (isset($dataArr)) ? $dataArr['address'] : set_value('address'); ?></textarea>
											<?php echo '<label id="address_error2" class="validation-error-label" for="address">' . form_error('address') . '</label>'; ?>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 lb-w-150 control-label">State :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<select class="select select-size-sm" id="state" name="state">
												<option value="1" >State1</option>
												<option value="2" >State2</option>
												<option value="3" >State3</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">PostCode :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<input type="text" class="form-control" name="post_code" id="post_code" value="<?php echo (isset($dataArr)) ? $dataArr['post_code'] : set_value('post_code'); ?>">
											<?php echo '<label id="post_code_error2" class="validation-error-label" for="post_code">' . form_error('post_code') . '</label>'; ?>
										</div>
									</div>
								</div>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-12 col-lg-3 lb-w-150 control-label">Notes :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <textarea class="form-control" name="note" id="note"><?php echo (isset($dataArr)) ? $dataArr['note'] : set_value('note'); ?></textarea>
                                            <?php echo '<label id="note_error2" class="validation-error-label" for="note">' . form_error('note') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 pl-xs-0 pr-xs-0">
                            <button type="submit" class="btn bg-teal custom_save_button">Save</button>
                            <button type="button" class="btn btn-default custom_cancel_button" onclick="if (history.length > 2) {
                                        window.history.back()
                                    } else {
                                        window.location.href = 'admin/lead';
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
