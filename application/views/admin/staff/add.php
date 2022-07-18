<?php
    if (isset($dataArr)) {
        $form_action = site_url('admin/staff/edit/' . base64_encode($dataArr['id']));
    } else {
        $form_action = site_url('admin/staff/add');
    }
?>
<script type="text/javascript" src="assets/js/plugins/forms/inputs/formatter.min.js"></script>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('admin/staff') ?>">Staff Members</a></li>
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
            <form method="post" action="<?php echo $form_action; ?>" id="add_staff_form">
                <div class="panel panel-body">
                    <?php $this->load->view('alert_view'); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-material has-feedback">
                                <label class="required">First Name</label>
                                <input type="text" class="form-control" name="txt_first_name" id="txt_first_name" placeholder="First Name" value="<?php echo (isset($dataArr)) ? $dataArr['first_name'] : set_value('txt_first_name'); ?>">
                                <?php echo '<label id="txt_first_name_error2" class="validation-error-label" for="txt_first_name">' . form_error('txt_first_name') . '</label>'; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group form-group-material has-feedback">
                                <label class="required">Last Name</label>
                                <input type="text" class="form-control" name="txt_last_name" id="txt_last_name" placeholder="Last Name" value="<?php echo (isset($dataArr)) ? $dataArr['last_name'] : set_value('txt_last_name'); ?>">
                                <?php echo '<label id="txt_last_name_error2" class="validation-error-label" for="txt_last_name">' . form_error('txt_last_name') . '</label>'; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-material has-feedback">
                                <label class="required">Email ID</label>
                                <input type="text" class="form-control" name="txt_email_id" id="txt_email_id" placeholder="Email ID" value="<?php echo (isset($dataArr)) ? $dataArr['email_id'] : set_value('txt_email_id'); ?>">
                                <?php echo '<label id="txt_email_id_error2" class="validation-error-label" for="txt_email_id">' . form_error('txt_email_id') . '</label>'; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group form-group-material has-feedback">
                                <label>Contact No</label>
                                <input type="text" class="form-control format-phone-number" name="txt_contact_no" id="txt_contact_no" placeholder="Contact Number" value="<?php echo (isset($dataArr)) ? $dataArr['contact_number'] : set_value('txt_contact_no'); ?>">
                                <?php echo '<label id="txt_contact_no_error2" class="validation-error-label" for="txt_contact_no">' . form_error('txt_contact_no') . '</label>'; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php if(isset($dataArr)){ ?>
                            <div class="col-md-6">
                                <div class="form-group form-group-material has-feedback">
                                    <label class="required">Username</label>
                                    <input type="text" class="form-control" name="txt_user_name" id="txt_user_name" placeholder="User Name" value="<?php echo (isset($dataArr)) ? $dataArr['username'] : set_value('txt_user_name'); ?>">
                                    <?php echo '<label id="txt_user_name_error2" class="validation-error-label" for="txt_user_name">' . form_error('txt_user_name') . '</label>'; ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-6">
                            <label>Status </label>
                            <div class="checkbox checkbox-switchery">
                                <label class="switch">
                                    <input type="checkbox" id="txt_status" name="txt_status" required="required" <?php
                                    if (isset($dataArr)) {
                                        if ($dataArr['status'] == 'active') {
                                            echo 'checked';
                                        }
                                    } else {
                                        echo 'checked';
                                    }
                                    ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label class="required">Role Type </label>      
                                <select class="select select-size-sm" data-placeholder="Select a role..." id="txt_role" name="txt_role" required>
                                    <?php if (empty($roleArr)) { ?>
                                        <option value="" class="no_makes">No data added yet</option>
                                    <?php }else{ ?>
                                        <option value=""></option>
                                    <?php } ?>
                                    <?php foreach ($roleArr as $k => $v) { ?>
                                        <?php if (isset($dataArr)) { ?>
                                            <option value="<?php echo $v['id']; ?>" <?php if ($dataArr['user_role'] == $v['id']) { echo "selected"; } ?>><?php echo ucwords(str_replace('_', ' ', $v['role_name'])); ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $v['id']; ?>"><?php echo ucwords(str_replace('_', ' ', $v['role_name'])); ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <button type="submit" class="btn bg-blue custom_save_button">Save</button>
                                <button type="button" class="btn btn-default custom_cancel_button" onclick="if (history.length > 2) {
                                    window.history.back()
                                } else {
                                    window.location.href = 'admin/staff';
                                }">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Footer -->
    <?php $this->load->view('Templates/footer.php'); ?>
    <!-- /footer -->
</div>

<script type="text/javascript">
    remoteURL = site_url + "admin/staff/checkUnique_Email";
    remoteURL2 = '';
    username_req = false;
    var uri_segment = '<?php echo $this->uri->segment('3'); ?>'

    <?php if (isset($dataArr)) { ?>
        var user_id = '<?php echo $dataArr['id'] ?>';
        remoteURL = site_url + "admin/staff/checkUnique_Email/"+user_id;
        remoteURL2 = site_url + "admin/staff/checkUnique_Username/"+user_id;
        username_req = true;
    <?php } ?>
    var status = '<?php
        if (isset($dataArr)) {
            if ($dataArr["status"] == 'active') {
                echo "checked";
            } else {
                echo "unchecked";
            }
        } else {
            echo "checked";
        }
        ?>';
</script>
<script type="text/javascript" src="assets/js/custom_pages/staff.js?version='<?php echo time();?>'"></script>