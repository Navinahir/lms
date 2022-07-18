<script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
<script type="text/javascript">
// Checkboxes/radios (Uniform)
    // ------------------------------

    // Default initialization
    $(".styled, .multiselect-container input").uniform({
        radioClass: 'choice'
    });

    // File input
    $(".file-styled").uniform({
        wrapperClass: 'bg-blue',
        fileButtonHtml: '<i class="icon-file-plus"></i>'
    });

</script>
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
if (isset($dataArr)) {
    $form_action = site_url('users/roles/edit/' . base64_encode($dataArr['id']));
} else {
    $form_action = site_url('users/roles/add');
}
?>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('/users/roles') ?>">Roles</a></li>
            <li class="active">
                <?php
                if (isset($dataArr)) {
                    echo "Edit";
                } else {
                    echo "Add";
                }
                ?>
            </li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="<?php echo $form_action; ?>" id="add_role_form">
                <div class="panel panel-body role-form">
                    <?php $this->load->view('alert_view'); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-material has-feedback">
                                <label class="required">Role Name</label>
                                <input type="text" class="form-control" name="txt_role_name" id="txt_role_name" placeholder="Role Name" value="<?php echo (isset($dataArr)) ? $dataArr['role_name'] : set_value('txt_role_name'); ?>">
                                <?php echo '<label id="txt_role_name_error2" class="validation-error-label" for="txt_role_name">' . form_error('txt_role_name') . '</label>'; ?>
                            </div>
                            <div class="form-group form-group-material has-feedback">
                                <label class="">Description</label>
                                <textarea class="form-control" name="txt_description" id="txt_description" placeholder="Role Description"><?php echo (isset($dataArr)) ? $dataArr['description'] : set_value('txt_description'); ?></textarea>
                                <?php echo '<label id="txt_description_error2" class="validation-error-label" for="txt_description">' . form_error('txt_description') . '</label>'; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mt-3 permissions">
                                <label class="">Access</label> 
                                <div class="row access_roles">
                                    <?php
                                    $all = [];
                                    if (isset($access) && !empty($access)) {
                                        $all = explode(',', $access);
                                    }
                                    if (isset($permissions) && !empty($permissions)) {
                                        foreach ($permissions as $key => $val) {
                                            $checked = '';
                                            $key1 = true;
                                            $key1 = array_search(1, array_column($val, 'is_display'));
                                            if ($key1 !== false) {
                                                if (strpos(strtolower($val[0]['controller_name']), 'locations') !== false) {
                                                    $class = 'col-md-12';
                                                } else {
                                                    $class = 'col-md-6';
                                                }
                                                ?>
                                                <div class="col-md-12">
                                                    <div class="panel panel-flat">
                                                        <div class="panel-heading">
                                                            <h6 class="panel-title"><span class="text-semibold text-center"><?php echo $val[0]['controller_name'] ?></span></h6>
                                                        </div>

                                                        <!-- <div class="panel-body <?php echo strtolower($val[0]['controller_name']) ?>"> -->
                                                        <div class="panel-body">
                                                            <div class="row col-md-12">
                                                                <?php
                                                                foreach ($val as $v) {
                                                                    $class_child = (strtolower($val[0]['controller_name']) == 'reports') ? 'col-md-3' : 'col-md-3';
                                                                    $class = '';
                                                                    if (in_array($v['id'], $all)) {
                                                                        $checked = 'checked="checked"';
                                                                    } else {
                                                                        $checked = '';
                                                                    }
                                                                    if (in_array($v['id'], $exist_permission)) {
                                                                        $class = 'exist';
                                                                        $checked = 'checked="checked" disabled="disabled"';
                                                                    }
                                                                    if ($v['is_display'] == 1) {
                                                                        if (strpos(strtolower($v['name']), 'list') !== false) {
                                                                            ?>
                                                                            <input type="checkbox" class="custom-control-input hide <?php echo str_replace(' ', '_', strtolower($val[0]['controller_name'] . '_' . $v['name'])) ?> <?php echo $class ?>"  name="permission[]" id="per_<?php echo $v['id'] ?>"  data-checked="0" value="<?php echo $v['id'] ?>" data-hidden-id="<?php echo $v['id'] ?>" <?php echo $checked; ?>>
                                                                            <span class="custom-control-indicator custom-control-color hide"></span><span class="hide custom-control-description"><?php echo $v['name'] ?>
                                                                            </span>                                                                          
                                                                            <label class="custom-control custom-checkbox <?php echo $class_child ?>">
                                                                                <div class="checkbox">
                                                                                    <label>
                                                                                        <input type="checkbox" class="styled custom-control-input <?php echo str_replace(' ', '_', strtolower($val[0]['controller_name'] . '_' . $v['name'])) ?> <?php echo $class ?>" name="perm_list[]"data-method='<?php echo str_replace(' ', '_', strtolower($v['name'])) ?>'  data-id="<?php echo $v['id'] ?>" data-class='<?php echo strtolower($val[0]['controller_name']) ?>' data-checked="0" value="<?php echo $v['id'] ?>" <?php echo $checked; ?>>
                                                                                        <?php echo $v['name'] ?>
                                                                                    </label>
                                                                                </div>
                                                                            </label>
                                                                        <?php } else {
                                                                            ?>

                                                                            <label class="custom-control custom-checkbox <?php echo $class_child ?> <?php echo str_replace(' ', '_', strtolower($v['name'])) ?>">
                                                                                <div class="checkbox">
                                                                                    <label>
                                                                                        <input type="checkbox" class="styled custom-control-input <?php echo str_replace(' ', '_', strtolower($val[0]['controller_name'] . '_' . $v['name'])) ?>" <?= strpos(strtolower($v['name']), 'list') === false ? 'data-dependent="' . str_replace(' ', '_', strtolower($val[0]['controller_name'] . '_list')) . '"' : '' ?> name="permission[]" id="per_<?php echo $v['id'] ?>" data-method='<?php echo str_replace(' ', '_', strtolower($v['name'])) ?>' data-id="<?php echo $v['id'] ?>" data-class='<?php echo strtolower($val[0]['controller_name']) ?>' data-checked="0" value="<?php echo $v['id'] ?>" <?php echo $checked; ?>>
                                                                                        <?php echo $v['name'] ?>
                                                                                    </label>
                                                                                </div>
                                                                            </label>
                                                                            <?php
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </div>
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
    remoteURL = site_url + "roles/checkUnique_Rolename";
<?php if (isset($dataArr)) { ?>
        var role_id = '<?php echo $dataArr['id'] ?>';
        remoteURL = site_url + "roles/checkUnique_Rolename/" + role_id;
<?php } ?>
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/roles.js"></script>

<style>
    @media(min-width:1024px) and (max-width:1400px){
        .access_roles .col-md-6 {width: 100%;}
    }
</style>