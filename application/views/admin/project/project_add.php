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
    $form_action = site_url('admin/project/edit/' . base64_encode($dataArr['id']));
} else {
    $form_action = site_url('admin/project/add');
}
?>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('admin/project') ?>">Project Data</a></li>
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
										<label class="col-md-12 col-lg-3 lb-w-150 control-label">Category :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<select class="select select-size-sm" id="category" name="category">
												<?php foreach ($categories as $k => $v) { ?>
													<option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">System Size # kW :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<input type="text" class="form-control" name="system_size" id="system_size" value="<?php echo (isset($dataArr)) ? $dataArr['system_size'] : set_value('system_size'); ?>">
											<?php echo '<label id="system_size_error2" class="validation-error-label" for="system_size">' . form_error('system_size') . '</label>'; ?>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 lb-w-150 control-label">Brand :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<select class="select select-size-sm" id="brand" name="brand">
												<option value="1" >brand1</option>
												<option value="2" >brand2</option>
												<option value="3" >brand3</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Warranties :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<input type="text" class="form-control" name="warranties" id="warranties" value="<?php echo (isset($dataArr)) ? $dataArr['warranties'] : set_value('warranties'); ?>">
											<?php echo '<label id="warranties_error2" class="validation-error-label" for="warranties">' . form_error('warranties') . '</label>'; ?>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 lb-w-150 control-label">Mounting System :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<select class="select select-size-sm" id="mounting" name="mounting">
												<option value="1" >mounting1</option>
												<option value="2" >mounting2</option>
												<option value="3" >mounting3</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Electric Kit :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<input type="text" class="form-control" name="electric_kit" id="electric_kit" value="<?php echo (isset($dataArr)) ? $dataArr['electric_kit'] : set_value('electric_kit'); ?>">
											<?php echo '<label id="electric_kit_error2" class="validation-error-label" for="electric_kit">' . form_error('electric_kit') . '</label>'; ?>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 lb-w-150 control-label">Power Phase :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<select class="select select-size-sm" id="power_phase" name="power_phase">
												<option value="1" >power1</option>
												<option value="2" >power2</option>
												<option value="3" >power3</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 lb-w-150 control-label">Export Limit :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<select class="select select-size-sm" id="export_limit" name="export_limit">
												<option value="1" >Yes</option>
												<option value="2" >No</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 lb-w-150 control-label">House Type :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<select class="select select-size-sm" id="house_type" name="house_type">
												<option value="1" >house_type1</option>
												<option value="2" >house_type2</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 lb-w-150 control-label">Roof Type :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<select class="select select-size-sm" id="roof_type" name="roof_type">
												<option value="1" >roof_type1</option>
												<option value="2" >roof_type2</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">

								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 lb-w-150 control-label">Roof Angle :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<select class="select select-size-sm" id="roof_angle" name="roof_angle">
												<option value="1" >roof_angle1</option>
												<option value="2" >roof_angle2</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Basic System Cost :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<input type="text" class="form-control" name="basic_system_cost" id="basic_system_cost" value="<?php echo (isset($dataArr)) ? $dataArr['basic_system_cost'] : set_value('basic_system_cost'); ?>">
											<?php echo '<label id="basic_system_cost_error2" class="validation-error-label" for="basic_system_cost">' . form_error('basic_system_cost') . '</label>'; ?>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Special Discount :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<input type="text" class="form-control" name="special_discount" id="special_discount" value="<?php echo (isset($dataArr)) ? $dataArr['special_discount'] : set_value('special_discount'); ?>">
											<?php echo '<label id="special_discount_error2" class="validation-error-label" for="special_discount">' . form_error('special_discount') . '</label>'; ?>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Other Price :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<input type="text" class="form-control" name="other_price" id="other_price" value="<?php echo (isset($dataArr)) ? $dataArr['other_price'] : set_value('other_price'); ?>">
											<?php echo '<label id="other_price_error2" class="validation-error-label" for="other_price">' . form_error('other_price') . '</label>'; ?>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Total Price :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<input disabled type="text" class="form-control" >
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Deposit Required :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<input type="text" class="form-control" name="deposit_required" id="deposit_required" value="<?php echo (isset($dataArr)) ? $dataArr['deposit_required'] : set_value('deposit_required'); ?>">
											<?php echo '<label id="deposit_required_error2" class="validation-error-label" for="deposit_required">' . form_error('deposit_required') . '</label>'; ?>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Balance Due :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<input type="text" class="form-control" name="balance_due" id="balance_due" value="<?php echo (isset($dataArr)) ? $dataArr['balance_due'] : set_value('balance_due'); ?>">
											<?php echo '<label id="balance_due_error2" class="validation-error-label" for="balance_due">' . form_error('balance_due') . '</label>'; ?>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 lb-w-150 control-label">Spacial Note :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<textarea class="form-control" name="special_note" id="special_note"><?php echo (isset($dataArr)) ? $dataArr['special_note'] : set_value('special_note'); ?></textarea>
											<?php echo '<label id="special_note_error2" class="validation-error-label" for="special_note">' . form_error('special_note') . '</label>'; ?>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group has-feedback">
										<label class="col-md-12 col-lg-3 lb-w-150 control-label">Project Note :</label>
										<div class="col-md-12 col-lg-9 input-controlstyle-150">
											<textarea class="form-control" name="project_note" id="project_note"><?php echo (isset($dataArr)) ? $dataArr['project_note'] : set_value('project_note'); ?></textarea>
											<?php echo '<label id="project_note_error2" class="validation-error-label" for="project_note">' . form_error('project_note') . '</label>'; ?>
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

<script>
    user_type = '<?php echo checkLogin('R'); ?>';
    remoteURL = site_url + "admin/product/checkUnique_Company_Name";
    remoteURL_model = site_url + "admin/product/Unique_Model";
</script>
<script type="text/javascript" src="assets/js/custom_pages/project.js?version='<?php echo time();?>'"></script>

