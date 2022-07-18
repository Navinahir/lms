<script src="assets/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="assets/js/plugins/notifications/jgrowl.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/daterangepicker.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/anytime.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.date.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.time.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/legacy.js"></script>

<script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/styling/switch.min.js"></script>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Subscription</li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <?php $this->load->view('alert_view'); ?>
        <form method="post" class="form-validate-jquery" id="add_subscription_form" name="add_subscription_form">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Manage Subscriptions</h5>
                </div>
                <div class="panel-body">
                    <?php // pr($dataArr); ?>
                    <div class="row mt-20">
                        <div class="col-md-6">
                            <div class="form-group form-group-material has-feedback">
                                <label>Name <font color="red">*</font></label>
                                <input type="text" class="form-control" name="txt_name" id="txt_name" required="required" placeholder="Enter Name" value="<?php echo (isset($dataArr['name']) ? $dataArr['name'] : set_value('txt_name')) ?>" autocomplete = "off">
                                <input type="hidden" class="form-control" name="txt_subscription_id" id ="txt_subscription_id" value="<?php echo (isset($dataArr['id']) ? $dataArr['id'] : null) ?>">
                                <span style="color: red; display: none;" class="special_car_alert">Special characters not allowed.</span>
                            </div>
                            <div class="form-group form-group-material has-feedback">
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">
                                        <label>Discount based on:</label>
                                        <?php
                                        $checked = '';
                                        if (isset($dataArr) && !empty($dataArr)):
                                            if ($dataArr['amount_off'] == 0 && $dataArr['percent_off'] == 0):
                                                $checked = 'checked="checked"';
                                                $amount = 0;
                                            elseif ($dataArr['percent_off'] != 0):
                                                $checked = '';
                                                $amount = $dataArr['percent_off'];
                                            else:
                                                $checked = 'checked="checked"';
                                                $amount = $dataArr['amount_off'];
                                            endif;
                                        else:
                                            $checked = 'checked="checked"';
                                        endif;
                                        ?>
                                        <div class="checkbox checkbox-switchery switchery-double">
                                            <label>
                                                Percent
                                                <input type="checkbox" 
                                                       class="switchery" 
                                                       name="is_amount" id="is_amount" <?php echo $checked; ?>
                                                       data-on-text="Amount" data-off-text="Percent" />
                                                Amount
                                            </label>
                                        </div>
                                        <span>By Default, Discount is based on amount</span>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <label id="label_amount">Amount</label>
                                        <input type="number" class="form-control" name="txt_amount" id="txt_amount" placeholder="Enter Amount" value="<?php echo (isset($amount) && $amount > 0) ? $amount : 0 ?>" min="1">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-material has-feedback">
                                <label>Coupon Duration <font color="red">*</font></label>
                                <select class="select select-size-sm" data-placeholder="Select a Duration" id="txt_coupon_duration" name="txt_coupon_duration">
                                    <?php if (!empty($dataArr) && $dataArr['duration'] == 'FOREVER') { ?>
                                        <option value="forever" selected>Forever</option>
                                    <?php } else if (!empty($dataArr) && $dataArr['duration'] == 'ONCE') { ?>
                                        <option value="once" selected>Once</option>
                                    <?php } else if (!empty($dataArr) && $dataArr['duration'] == 'REPEATING') { ?>
                                        <option value="repeating" selected>Multiple months</option>
                                    <?php } else { ?>
                                        <option value="forever">Forever</option>
                                        <option value="once">Once</option>
                                        <option value="repeating">Multiple months</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="">
                            <div class="form-group form-group-material has-feedback">
                                <label>No. Of Redemption Allowed <font color="red">*</font></label>
                                <input type="number" class="form-control" name="txt_redemptions" id="txt_redemptions" required="required" placeholder="Enter No. Of Redemption" min="1" value="<?php echo (isset($dataArr['max_redemption']) ? $dataArr['max_redemption'] : set_value('txt_redemptions')) ?>">
                            </div>
                            <div class="form-group form-group-material has-feedback">
                                <label>Expiration Date <font color="red">*</font></label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-calendar5"></i></span>
                                    <input type="text" class="form-control pickadate-selectors" name="expiration_date" id="expiration_date" placeholder="Try me&hellip;" value="<?php echo (isset($dataArr['expiry_date']) ? $dataArr['expiry_date'] : set_value('expiration_date')) ?>">
                                </div>
                            </div>
                            <div class="form-group form-group-material has-feedback">
                                <label>Description</label>
                                <textarea class="form-control" name="txt_description" id="txt_description" placeholder="Enter Description"><?php echo (isset($dataArr['description']) ? $dataArr['description'] : set_value('txt_description')) ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row multiple_month_duration_div hide">
                        <div class="col-md-6">
                            <div class="form-group form-group-material has-feedback">
                                <label>Months <font color="red">*</font></label>
                                <select class="select select-size-sm" data-placeholder="Select a Months..." id="txt_months" name="txt_months">
                                    <?php
                                    $selected = '';
                                    for ($i = 1; $i <= 12; $i++):
                                        $month = ($i == 1) ? " Month" : " Months";
                                        if (isset($dataArr['no_of_months']) && ($dataArr['no_of_months'] == $i)) {
                                            $selected = 'selected="selected"';
                                        } else {
                                            $selected = '';
                                        }
                                        echo '<option value="' . $i . '"  ' . $selected . '>' . $i . ' ' . $month . '</option>';
                                    endfor;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="submit" class="btn bg-teal custom_save_button">Save</button>
                            <button type="button" class="btn btn-default custom_cancel_button" onclick="if (history.length > 2){
                                window.history.back()
                            } else {
                                window.location.href = 'admin/subscriptions';
                            }">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<script>
    var selectedDate = '';
    remoteURL = site_url + "admin/subscriptions/checkUniqueName";
<?php if (isset($dataArr)) { ?>
        var id = '<?php echo $dataArr['id'] ?>';
        remoteURL = site_url + "admin/subscriptions/checkUniqueName/" + id;
        var selectedDate = '<?php echo $dataArr['expiry_date']; ?>';
<?php } ?>
</script>
<script type="text/javascript" src="assets/js/custom_pages/subscriptions.js?version='<?php echo time();?>'"></script>
