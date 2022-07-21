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
                                        <label class="col-md-12 col-lg-3 control-label lb-w-150 control-label">Project :</label>
                                        <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                            <input type="text" class="form-control" name="name" id="name" value="<?php echo (isset($dataArr)) ? $dataArr['name'] : set_value('name'); ?>">
                                            <?php echo '<label id="name_error2" class="validation-error-label" for="name">' . form_error('name') . '</label>'; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 pl-xs-0 pr-xs-0">
                            <button type="submit" class="btn bg-teal custom_save_button">Save</button>
                            <button type="button" class="btn btn-default custom_cancel_button" onclick="if (history.length > 2) {
                                        window.history.back()
                                    } else {
                                        window.location.href = 'admin/project';
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

