<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Settings</li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <?php $this->load->view('alert_view'); ?>
        <div class="col-md-5" id="model_form_row">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Manage Stripe Details</h5>
                </div>
                <div class="panel-body">
                    <form method="post" class="form-validate-jquery" id="add_setting_form" name="add_setting_form">
                        <div class="row mt-20">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="required">Stripe Mode</label>
                                    <select data-placeholder="Select a Mode..." class="select-size-sm stripe-mode" id="STRIPE_MODE" name="STRIPE_MODE">
                                        <option></option>
                                        <option value="live" <?php echo ($mode == 'live') ? 'selected="selected"' : '' ?>>Production</option>
                                        <option value="test" <?php echo ($mode == 'test') ? 'selected="selected"' : '' ?>>Development</option>
                                    </select>
                                </div>
                            </div>
                            <?php
//                            if (!empty($Arr)) {
//                                if ($mode == 'test') {
//                                    $STRIPE_PUBLISH_KEY = $Arr['STRIPE_TEST_PUBLISH_KEY'];
//                                    $STRIPE_SECRET_KEY = $Arr['STRIPE_TEST_SECRET_KEY'];
//                                } else {
//                                    $STRIPE_PUBLISH_KEY = $Arr['STRIPE_PUBLISH_KEY'];
//                                    $STRIPE_SECRET_KEY = $Arr['STRIPE_SECRET_KEY'];
//                                }
//                            }
                            ?>
                            <div class="col-md-12">
                                <div class="form-group form-group-material has-feedback">
                                    <label class="required">Publish Key </label>
                                    <input type="text" class="form-control" name="STRIPE_PUBLISH_KEY" id="STRIPE_PUBLISH_KEY" required="required" placeholder="Enter publish Key" value="<?php echo $Arr['STRIPE_PUBLISH_KEY'] ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-group-material has-feedback">
                                    <label class="required">Secret Key </label>
                                    <input type="text" class="form-control" name="STRIPE_SECRET_KEY" id="STRIPE_SECRET_KEY" required="required" placeholder="Enter secret Key" value="<?php echo $Arr['STRIPE_SECRET_KEY'] ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-group-material has-feedback">
                                    <label class="required">Secret Key </label>
                                    <input type="text" class="form-control" name="STRIPE_PRODUCT_KEY" id="STRIPE_PRODUCT_KEY" required="required" placeholder="Enter product Key" value="<?php echo $Arr['STRIPE_PRODUCT_KEY'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn bg-teal custom_save_button">Save</button>
                                <button type="button" class="btn btn-default custom_cancel_button" onclick="cancel_click()">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<script type="text/javascript" src="assets/js/custom_pages/setting.js"></script>