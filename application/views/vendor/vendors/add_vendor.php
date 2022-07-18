<script type="text/javascript" src="<?= site_url('assets/js/plugins/forms/inputs/formatter.min.js   ') ?>"></script>
<?php
if (isset($dataArr)) {
    $form_action = site_url('vendor/users/edit/' . base64_encode($dataArr['id']));
} else {
    $form_action = site_url('vendor/users/add');
}
?>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/vendor/home'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('/vendor/users') ?>">Users</a></li>
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
            <form method="post" action="<?php echo $form_action; ?>" id="add_user_form">
                <div class="panel panel-body login-form">
                    <?php $this->load->view('alert_view'); ?>
                    <legend class="text-bold"><?php echo (isset($dataArr)) ? 'Edit' : 'New' ?> User</legend>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback has-feedback-left">
                                <select data-placeholder="Select a User Type..." class="select" name='user_role' id='user_role'>
                                    <option></option>
                                    <optgroup label="User's Role">
                                        <?php
                                        if (isset($roles) && !empty($roles)):
                                            foreach ($roles as $role) {
                                                $selected = '';
                                                if (isset($dataArr) && !empty($dataArr)) {
                                                    if ($role['id'] == $dataArr['user_role']) {
                                                        $selected = 'selected="selected"';
                                                    }
                                                }
                                                ?>
                                                <option value="<?php echo $role['id'] ?>" <?php echo $selected; ?>><?php echo $role['role_name'] ?></option>
                                                <?php
                                            }
                                        endif;
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <input type="text" class="form-control input-lg" placeholder="First name" name="first_name" id="first_name" value='<?php echo (isset($dataArr['first_name']) ? $dataArr['first_name'] : set_value('first_name')) ?>'>
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                            </div>
                            <div class="form-group has-feedback has-feedback-left">
                                <input type="text" class="form-control input-lg" placeholder="Business Name" name="business_name" id="business_name" value='<?php echo (isset($dataArr['business_name']) ? $dataArr['business_name'] : set_value('business_name')) ?>'>
                                <div class="form-control-feedback">
                                    <i class=" icon-office text-muted"></i>
                                </div>
                            </div>
                            <div class="form-group has-feedback has-feedback-left">
                                <input type="text" class="form-control input-lg" placeholder="Email" name="email_id" id="email_id" value='<?php echo (isset($dataArr['email_id']) ? $dataArr['email_id'] : set_value('email_id')) ?>'>
                                <div class="form-control-feedback">
                                    <i class=" icon-envelop5 text-muted"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <input type="text" class="form-control input-lg" placeholder="Last name" name="last_name" id="last_name" value='<?php echo (isset($dataArr['last_name']) ? $dataArr['last_name'] : set_value('last_name')) ?>'>
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                            </div>
                            <div class="form-group has-feedback has-feedback-left">
                                <input type="text" class="form-control input-lg format-phone-number" placeholder="Contact Number" name="contact_number" id="contact_number" value='<?php echo (isset($dataArr['contact_number']) ? $dataArr['contact_number'] : set_value('contact_number')) ?>'>
                                <div class="form-control-feedback">
                                    <i class=" icon-phone2 text-muted"></i>
                                </div>
                            </div>

                            <div class="form-group has-feedback has-feedback-left">
                                <textarea class="form-control input-lg" placeholder="Address" name="address" id="address"><?php echo (isset($dataArr['address']) ? $dataArr['address'] : set_value('address')) ?></textarea>
                                <div class="form-control-feedback">
                                    <i class="icon-location4 text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <button type="submit" class="btn bg-blue custom_save_button btn_login">Save</button>
                                <button type="button" class="btn btn-default custom_cancel_button" onclick="if (history.length > 2) {
                                            window.history.back()
                                        } else {
                                            window.location.href = 'users/roles';
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
    var remoteURL = site_url + "home/checkUnique_Email";
    var user_role = '';
<?php if (isset($dataArr)) { ?>
        var user_id = '<?php echo $dataArr['id'] ?>';
        user_role = '<?php echo $dataArr['user_role'] ?>';
        remoteURL = site_url + "home/checkUnique_Email/" + user_id;
<?php } ?>
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/add_user.js"></script>