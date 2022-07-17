<script type="text/javascript" src="assets/js/plugins/forms/inputs/formatter.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Setting</li>
            <li class="active">Company Profile</li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>
<?php
    if (checkUserLogin('R') != 4) {
        $controller = $this->router->fetch_class();
        if (!empty(MY_Controller::$access_method) && (array_key_exists('view', MY_Controller::$access_method['company_profile']) && !array_key_exists('edit', MY_Controller::$access_method['company_profile']))) {
            $view = 1;
        }
        if (!empty(MY_Controller::$access_method) && (array_key_exists('change_password', MY_Controller::$access_method['company_profile']))) {
            $profile_chnage_password = 1;
        }
        if (!empty(MY_Controller::$access_method) && (array_key_exists('billing_detail', MY_Controller::$access_method['company_profile']))) {
            $billing_detail_access = 1;
        }
    } 
?>
<script>
    var view_profile = '<?php echo (isset($view) && $view == 1) ? $view : 0; ?>';
</script>

<div class="content" id="">
    <div class="row">
        <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <div class="tabbable tab-content-bordered">
                            <ul class="nav nav-tabs nav-tabs-highlight">
                                <li class="active"><a href="#bordered-tab1" data-toggle="tab"><b>Company Profile</b></a></li>

                                <?php if($profile_chnage_password == 1 || checkUserLogin('R') == 4) { ?>
                                    <li><a href="#bordered-tab2" data-toggle="tab"><b>Change Password</b></a></li>
                                <?php } ?>
                                <?php if($billing_detail_access == 1 || checkUserLogin('R') == 4) { ?>
                                    <li><a href="#bordered-tab3" data-toggle="tab"><b>Billing Invoices</b></a></li>
                                    <li><a href="#bordered-tab4" data-toggle="tab"><b>Upcoming Billing Invoices</b></a></li>
                                <?php } ?>
                                <?php if(checkUserLogin('R') == 4) { ?>
                                    <li><a href="#bordered-tab5" data-toggle="tab"><b>Cancel Subscription</b></a></li>
                                <?php } ?>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane has-padding active profile_content" id="bordered-tab1">
                                    <form method="post" action="<?php echo site_url('company_profile'); ?>" id="add_company_form" enctype="multipart/form-data">
                                    <div class="login-form">
                                        <?php $this->load->view('alert_view'); ?>
                                        <div id="payment-errors"></div>
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
                                                <div class="form-group image_upload-div pl-xs-0">
                                                    <div class="image_wrapper">
                                                        <?php
                                                        if (isset($dataArr['profile_pic']) && !empty($dataArr['profile_pic'])) {
                                                            ?>
                                                            <img src="<?php echo base_url('uploads/profile') . '/' . $dataArr['profile_pic']; ?>" alt="" height="60px" width="80px">  
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <img src="assets/images/placeholder.jpg" alt="" height="60px" width="60px">
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class ="uploader-wrap">
                                                        <div class = "uploader">
                                                            <input type = "file" name = "profile_pic" id = "profile_pic" class = "file-styled-primary">
                                                            <span class = "filename" style = "-webkit-user-select: none;"></span>
                                                            <span class = "action btn bg-info-400" style = "-webkit-user-select: none;">Choose Images</span>
                                                        </div>
                                                        <span class="help-block">Accepted formats: png, jpg. Max file size 2Mb</span>
                                                        <span id="image_message_alert" style="color: red;"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <legend class="text-bold">Company Address</legend>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <textarea class="form-control input-lg" placeholder="Address" name="address" id="address"><?php echo (isset($dataArr['address']) ? $dataArr['address'] : set_value('address')) ?></textarea>
                                                    <div class="form-control-feedback">
                                                        <i class="icon-location4 text-muted"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group custom_form_container has-feedback-left">
                                                    <input type="text" class="form-control input-lg" id="city" Placeholder="City"  name="city" id="city" value='<?php echo (isset($dataArr['city']) ? $dataArr['city'] : set_value('city')) ?>'/>
                                                    <div class="form-control-feedback">
                                                        <i class="fa fa-building text-muted"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <select data-placeholder="Select a State" class="select-size-lg" name="state_id" id='state_id'>
                                                        <option></option>
                                                        <optgroup label="State">
                                                            <?php
                                                            if (isset($states) && !empty($states)):
                                                                foreach ($states as $s):
                                                                    $selected = '';
                                                                    if ($s['id'] == $dataArr['state_id']):
                                                                        $selected = 'selected="selected"';
                                                                    endif;
                                                                    ?>
                                                                    <option value="<?php echo $s['id'] ?>" <?php echo $selected ?>><?php echo $s['name'] ?></option>
                                                                    <?php
                                                                endforeach;
                                                            endif;
                                                            ?>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group custom_form_container has-feedback-left">
                                                    <input type="text" class="form-control input-lg" id="zip_code" Placeholder="Zip Code"  name="zip_code" id="city" value='<?php echo (isset($dataArr['zip_code']) ? $dataArr['zip_code'] : set_value('zip_code')) ?>'/>
                                                    <div class="form-control-feedback">
                                                        <i class="fa fa-building text-muted"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <legend class="text-bold">Payment and Billing Information</legend>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group custom_form_container has-feedback-left">
                                                    <input type="text" class="form-control input-lg format-credit-card" placeholder="Credit Card Number" name="credit_card" id="card_num" value="<?php echo (isset($dataArr['card_number']) ? 'XXXX XXXX XXXX ' . $dataArr['card_number'] : set_value('card_num')) ?>">
                                                    <div class="form-control-feedback">
                                                        <i class="far fa-credit-card text-muted"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group custom_form_container"> 
                                                    <ul class="card_div">
                                                        <li id="Visa"><img class="payment_img" src="assets/images/visa.png"></li>
                                                        <li id="MasterCard"><img class="payment_img" src="assets/images/master_card.png"></li>
                                                        <li id="Discover"><img class="payment_img" src="assets/images/discover.png"></li>
                                                        <li id="AmericanExpress"><img class="payment_img" src="assets/images/american_express.png"></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group custom_form_container has-feedback-left">
                                                    <input type="text" name="exp_month" maxlength="2" class="form-control input-lg format-month" id="card-expiry-month" placeholder="MM" value="<?php echo (isset($dataArr['exp_month']) ? $dataArr['exp_month'] : set_value('exp_month')) ?>">
                                                    <div class="form-control-feedback">
                                                        <i class="far fa-calendar-alt text-muted"></i>
                                                    </div>
                                                    <?php echo '<label id="exp_month_error2" class="validation-error-label" for="exp_month">' . form_error('exp_month') . '</label>'; ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group custom_form_container has-feedback-left">
                                                    <input type="text" name="exp_year" class="form-control input-lg format-year" maxlength="4" id="card-expiry-year" placeholder="YYYY" value="<?php echo (isset($dataArr['exp_year']) ? $dataArr['exp_year'] : set_value('exp_year')) ?>">
                                                    <div class="form-control-feedback">
                                                        <i class="far fa-calendar-alt text-muted"></i>
                                                    </div>
                                                    <?php echo '<label id="exp_year_error2" class="validation-error-label" for="exp_year">' . form_error('exp_year') . '</label>'; ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group custom_form_container has-feedback-left">
                                                    <input type="password" class="form-control input-lg " placeholder="V-code" name="v_code" id="card-cvc" maxlength="4">
                                                    <!-- class = "format-csv" -->
                    <!--value="<?php echo (isset($dataArr['v_code']) ? $dataArr['v_code'] : set_value('v_code')) ?>"-->
                                                    <div class="form-control-feedback">
                                                        <i class="far fa-address-card text-muted"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <legend class="text-bold">Billing Information</legend>
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group custom_form_container has-feedback-left">
                                                    <input type="text" class="form-control input-lg" placeholder="Billing Name" name="billing_name" id="billing_name" value="<?php echo (isset($dataArr['billing_name']) ? $dataArr['billing_name'] : set_value('billing_name')) ?>">
                                                    <div class="form-control-feedback">
                                                        <i class="far fa-user text-muted"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group custom_form_container has-feedback-left">
                                                    <input type="text" class="form-control input-lg format-phone-number" placeholder="Billing Phone" name="billing_phone" id="billing_phone" value="<?php echo (isset($dataArr['billing_phone']) ? $dataArr['billing_phone'] : set_value('billing_phone')) ?>">
                                                    <div class="form-control-feedback">
                                                        <i class="fas fa-phone text-muted"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group custom_form_container has-feedback-left">
                                                    <textarea class="form-control input-lg" rows="3" placeholder="Billing Address" name="billing_address" id="billing_address"><?php echo (isset($dataArr['billing_address']) ? $dataArr['billing_address'] : set_value('billing_address')) ?></textarea>
                                                    <div class="form-control-feedback">
                                                        <i class="fas fa-building text-muted"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>	
                                        <div class="row">
                                            <div class="col-sm-12 col-md-4">
                                                <div class="form-group custom_form_container has-feedback-left">
                                                    <input type="text" class="form-control input-lg" name="billing_city" id="billing_city" placeholder="City" value="<?php echo (isset($dataArr['billing_city']) ? $dataArr['billing_city'] : set_value('billing_city')) ?>">
                                                    <div class="form-control-feedback">
                                                        <i class="fas fa-building text-muted"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <select data-placeholder="Select a State" class="select-size-lg" name="billing_state_id" id='billing_state_id'>
                                                        <option></option>
                                                        <optgroup label="State">
                                                            <?php
                                                            if (isset($states) && !empty($states)):
                                                                foreach ($states as $s):
                                                                    $selected = '';
                                                                    if ($s['id'] == $dataArr['billing_state_id']):
                                                                        $selected = 'selected="selected"';
                                                                    endif;
                                                                    ?>
                                                                    <option value="<?php echo $s['id'] ?>" <?php echo $selected ?>><?php echo $s['name'] ?></option>
                                                                    <?php
                                                                endforeach;
                                                            endif;
                                                            ?>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group custom_form_container has-feedback-left">
                                                    <input type="text" class="form-control input-lg" name="billing_zip_code" id="billing_zip_code" placeholder="Zip Code" value="<?php echo (isset($dataArr['billing_zip_code']) ? $dataArr['billing_zip_code'] : set_value('billing_zip_code')) ?>">
                                                    <div class="form-control-feedback">
                                                        <i class="fas fa-building text-muted"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>	
                                        <legend class="text-bold">Packages Details</legend>
                                        <?php
                                        if (isset($package_Arr) && !empty($package_Arr)):
                                            ?>
                                            <div class='row'>
                                                <?php
                                                foreach ($package_Arr as $key => $p):
                                                    $class = '';
                                                    if ($dataArr['package_id'] == $p['id']) {
                                                        $class = 'package_active';
                                                        ?>
                                                        <input type="hidden" id="old_package_id" name="old_package_id" value="<?php echo $p['id'] ?>" data-name="<?php echo $p['name'] ?>" data-price="<?php echo $p['price'] ?>"/>
                                                        <input type="hidden" id="package_id" name="package_id" value="<?php echo $p['id'] ?>" data-name="<?php echo $p['name'] ?>" data-price="<?php echo $p['price'] ?>"/>
                                                    <?php } ?>
                                                    <div class="col-sm-12 col-md-4">
                                                        <div class="packages_section <?php echo $class ?>" id="package_<?php echo $p['id'] ?>" data-id="<?php echo $p['id'] ?>" data-name="<?php echo $p['name'] ?>" data-price="<?php echo $p['price'] ?>">
                                                            <h4><?php echo $p['name']; ?></h4>
                                                            <div class="package_content">
                                                                <span>$<?php echo $p['price']; ?></span>
                                                                <p><?php echo $p['short_description']; ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <?php
                                        endif;
                                        ?>
                                        <legend class="text-bold">Other Details</legend>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <select data-placeholder="Select a Currency Type" class="select-size-lg" name="currency_id" id='currency_id'>
                                                        <option></option>
                                                        <optgroup label="Currency">
                                                            <?php
                                                            if (isset($currencies) && !empty($currencies)):
                                                                foreach ($currencies as $c):
                                                                    $selected = '';
                                                                    if ($c['id'] == $dataArr['currency_id']):
                                                                        $selected = 'selected="selected"';
                                                                    endif;
                                                                    ?>
                                                                    <option value="<?php echo $c['id'] ?>" <?php echo $selected ?>><?php echo $c['symbol'] . ' : ' . $c['code'] . ' - ' . $c['currency'] ?></option>
                                                                    <?php
                                                                endforeach;
                                                            endif;
                                                            ?>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <select data-placeholder="Select a Date Format" class="select-size-lg" name="date_format_id" id='date_format_id'>
                                                        <option></option>
                                                        <optgroup label="Date Format">
                                                            <?php
                                                            if (isset($dateFormats) && !empty($dateFormats)):
                                                                foreach ($dateFormats as $d):
                                                                    $selected = '';
                                                                    if ($d['id'] == $dataArr['date_format_id']):
                                                                        $selected = 'selected="selected"';
                                                                    endif;
                                                                    ?>
                                                                    <option value="<?php echo $d['id'] ?>" <?php echo $selected ?>><?php echo $d['name'] . ' [ ' . date($d['format']) . ' ] ' ?></option>
                                                                    <?php
                                                                endforeach;
                                                            endif;
                                                            ?>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row save_btn_div">
                                            <div class="form-group">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pl-xs-3 pr-xs-3">
                                                    <button type="submit" class="btn bg-blue custom_save_button btn_login">Save</button>
                                                    <button type="button" class="btn btn-default custom_cancel_button" onclick="if (history.length > 2) {
                                                                window.history.back()
                                                            } else {
                                                                window.location.href = 'dashboard';
                                                            }">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                </div>
                                <?php if($profile_chnage_password == 1 || checkUserLogin('R') == 4) { ?>
                                    <div class="tab-pane has-padding change_password" id="bordered-tab2">
                                         <form method="post" action="<?php echo site_url('company_profile/change_password'); ?>" id="change_password_form">
                                            <div class="row ">
                                                <div class="col-md-12">
                                                    <div class="alert alpha-blue border-blue alert-styled-left alert-bordered">
                                                        Your password must include:
                                                        <ul>
                                                            <li>A minimum 8 characters</li>
                                                            <li>Have at least one number between [0-9]</li>
                                                            <li>Have at least one alphabet character between [A-Z] or [a-z]</li>
                                                            <li>Have at least one special character [! @ # $ % ^ & * ( ) + ?]</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="col-md-12 col-lg-3 control-label lb-w-150">Password</label>
                                                    <div class="col-md-12 col-lg-9 input-controlstyle-150 mb-20">
                                                        <div class="progress progress-micro pwd_progress1 hide">
                                                            <div class="progress-bar pwd_strength_bar1" style="width:0%;"></div>
                                                        </div>
                                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off">
                                                        <label id="password-error" class="validation-error-label" for="password"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="col-md-12 col-lg-3 control-label lb-w-150">Confirm Password</label>
                                                    <div class="col-md-12 col-lg-9 input-controlstyle-150 mb-20">
                                                        <div class="progress progress-micro pwd_progress2 hide">
                                                            <div class="progress-bar pwd_strength_bar2 progress-bar-danger" style="width: 0%;"></div>
                                                        </div>
                                                        <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Confirm Password">
                                                        <label id="cpassword-error" class="validation-error-label" for="cpassword"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="col-md-12 col-lg-3 control-label lb-w-150"></label>
                                                <div class="col-md-12 col-lg-9 input-controlstyle-150">
                                                    <button type="submit" class="btn bg-blue btn-block custom_save_button" style="letter-spacing: 1px;text-transform: uppercase;font-weight: 500;">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                <?php } ?>
                                <?php if($billing_detail_access == 1 || checkUserLogin('R') == 4) { ?>
                                    <div class="tab-pane has-padding change_password" id="bordered-tab3">
                                        <div class="mb-20">
                                            <h1>Billing Invoices</h1>
                                            <table class="table table-bordered table-hover" id="billing-invoices-listing">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Paid Amount</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($subscription_invoices) && !empty($subscription_invoices['data'])) {
                                                        foreach ($subscription_invoices['data'] as $invoice) {
                                                            ?>
                                                            <tr>
                                                                <td><?= date('m-d-Y H:i A', $invoice['date']) ?></td>
                                                                <td>$<?= number_format($invoice['amount_paid'] / 100, 2) ?></td>
                                                                <td>
                                                                    <?php if ($invoice['status'] == 'paid') { ?>
                                                                        <span class="label label-success"><?= $invoice['status'] ?></span>
                                                                    <?php } else { ?>
                                                                        ---
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-info" target="_blank" href="<?= site_url() . 'subscription/invoice/' . base64_encode($invoice['id']) ?>" title="Download & View Invoice"><i class="icon-download"></i></a>
                                                                    <a class="btn btn-info" href="<?= site_url() . 'subscription/invoice/email/' . base64_encode($invoice['id']) ?>" title="Email Invoice"><i class="icon-envelop"></i></a>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane has-padding change_password" id="bordered-tab4">
                                        <div class="mb-20">
                                            <h1>Upcoming Billing Invoice</h1>
                                            <div class="datatable-scroll">
                                                <table class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Invoice Number #</th>
                                                            <th>Date</th>
                                                            <th>Paid Amount</th>
                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (!empty($upcoming_invoice)) {
                                                            ?>
                                                            <tr>
                                                                <td><?= $upcoming_invoice['number'] ?></td>
                                                                <td><?= date('m-d-Y H:i A', $upcoming_invoice['date']) ?></td>
                                                                <td>$<?= number_format($upcoming_invoice['total'] / 100, 2) ?></td>
                                                                <td>
                                                                    <?php if ($upcoming_invoice['status'] == 'draft') { ?>
                                                                        <span class="label label-info"><?= $upcoming_invoice['status'] ?></span>
                                                                    <?php } else { ?>
                                                                        ---
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-info upcoming-invoice-details" data-customerid="<?= base64_encode($upcoming_invoice['customer']) ?>" title="View Upcoming Invoice"><i class="icon-eye4"></i></a>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <tr>
                                                                <td colspan="5" class="text-center">No Details Found.</td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if(checkUserLogin('R') == 4) { ?>
                                    <div class="tab-pane has-padding change_password" id="bordered-tab5">
                                        <div class="mb-20">
                                            <h1>Cancel Subscription</h1>

                                            <?php if (!empty($subscription_invoices) && $subscription_data['customer_id']) { ?>
                                                <a href="<?= site_url('subscription/cancel') ?>" class="btn btn-primary btn-lg" onclick="return confirm_alert(this, 'Cancel');">Cancel Subscription</a>
                                                <label class="warning-text">
                                                    <span>Warning:</span> IF YOU CANCEL YOUR SUBSCRIPTION YOUR ACCOUNT WILL BE DEACTIVATED IMMEDIATELY . YOU WILL NO LONGER BE ABLE TO ACCESS YOUR ACCOUNT.<br>
                                                    For more information please contact to the admin. <a href="<?= site_url('about_us#contact_us') ?>" target="_blank">Click Here</a>
                                                </label>
                                            <?php } else { ?>
                                                <label class="danger-text">
                                                    CURRENTLY, SITE IS ON <?= strtoupper($stripe_mode) ?> MODE.
                                                </label>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php $this->load->view('Templates/footer.php'); ?>
</div>
<!-- Primary modal -->
<div id="modal_theme_primary" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Upgrade or Downgrade Package</h6>
            </div>

            <div class="modal-body">
                <p>You are changing your subscription from 
                    <span class="old_package_name text-semibold">STANDARD</span> to 
                    <span class="package_name text-semibold">PROFESSIONAL</span>. 
                    Your <span class="old_package_name">STANDARD</span> Package subscription activated on 
                    <?php echo date('F j, Y', strtotime(checkUserLogin('Date'))); ?>, will be canceled immediately and 
                    you will be billed for <span class="package_name">PROFESSIONAL</span> Package rate effective immediately. <br>
                </p>

                <p>Any prorated amounts or adjustments will be reflected on your next billing cycle invoice and can be viewed
                    under the <strong>Company Profile</strong> page. </p>
                <p>If you have any questions please contact us at <span class="text-semibold"><?= SYSTEM_CONTACT_NO ?></span></p>
                <!--<p class="text-semibold">Additional users and locations will be deactivated.</p>-->
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-agree">I Agree – please update my subscription</button>
                <button type="button" class="btn btn-cancel bg-danger" data-dismiss="modal">No – Cancel and return</button>
            </div>
        </div>
    </div>
</div>
<!-- /primary modal -->

<!-- Primary modal -->
<div id="modal_upcoming_invoice_detail" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Upcoming Billing Invoice Details</h6>
            </div>
            <div class="modal-body" id="upcoming_invoice_detail_body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel bg-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /primary modal -->


<!-- Footer -->
<!-- /footer -->
<script type="text/javascript">
    var old_credit_card = "<?php echo (isset($dataArr['card_number']) ? $dataArr['card_number'] : null) ?>";
    var old_month = '<?php echo (isset($dataArr['exp_month']) ? $dataArr['exp_month'] : null) ?>';
    var old_year = '<?php echo (isset($dataArr['exp_year']) ? $dataArr['exp_year'] : null) ?>';
    var old_cvc = '<?php echo (isset($dataArr['v_code']) ? $dataArr['v_code'] : null) ?>';
    // var remoteURL = site_url + "home/checkUnique_Email";
    var remoteURL = site_url + "uniq_email";
    var STRIPE_PUBLISH_KEY = '<?php echo $stripe_data['STRIPE_PUBLISH_KEY'] ?>';
    <?php if (isset($dataArr)) { ?>
        var user_id = '<?php echo $dataArr['uid'] ?>';
        // remoteURL = site_url + "home/checkUnique_Email/" + user_id;
        remoteURL = site_url + "uniq_email/" + user_id;
    <?php } ?>
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/profile.js?version='<?php echo time();?>'"></script>
<style>
    .image_wrapper{
        /*height:70px;
        width:80px;*/
        position:absolute; top:0; left:0; 
    }
    /*#imagePreview {
        width: 400px;
        height: 180px;
        background-position: center center;
        background-size: contain;
        -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, .3);
        display: inline-block;
        float: left;
        margin: 9px;
        background-repeat: no-repeat; 
    }*/
    #imagePreview {
        width: 80px;
        height: 70px;
        background-position: center center;
        -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, .3);
        display: inline-block;
        float: left;
        background-repeat: no-repeat;
    }
    #imagePreview_msg {
        width: 100%;
        height: 180px;
        background-position: center center;
        background-size: cover;
        -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, .3);
    }
    /*    .image_wrapper thumb-inner{
            max-width: auto; 
        }
        .profile_image_wrapper{
            width: 25%;
        }*/
    .packages_section h4{
        background-color: #0f1c26;
        margin: 0;
        font-size: 18px;
        color: #ffffff;
        text-align: center;
        padding-top: 15px;
        padding-bottom: 48px;
        font-family: 'Roboto';
    }
    .packages_section{
        min-height: 350px !important;
        background-color: rgba(21, 35, 44, .8);
        margin-bottom: 40px;
        position: relative;
    }
    .package_content span{
        color: #ffffff;
        font-size: 18px;
        width: 72px;
        height: 72px;
        display: block;
        margin: 0 auto;
        border-radius: 50%;
        line-height: 72px;
        text-align: center;
        margin-top: -35px;
        position: absolute;
        left: 50%;
        transform: translate(-50%,0);
        background-color: rgba(3, 169, 244, .5);
    }
    .package_content p{
        font-size: 15px;
        color: #ffffff;
        text-align: center;
        /* font-family: 'Roboto light'; */
        padding: 60px 15px 15px 15px;
    }
    .package_try_btn{
        background-color: rgba(3, 169, 244, .6);
        border: none;
        color: #fff;
        font-size: 13px;
        text-transform: uppercase;
        font-weight: 500;
        padding: 12px 24px;
        display: block;
        margin: 0 auto;
        position: absolute;
        left: 50%;
        transform: translate(-50%);
        bottom: 29px;
        border-radius: 2px;
    }
    .packages_section:hover h4 {
        background-color: #17aaed;
    }
    .packages_section:hover .package_content span{
        background-color: rgba(15, 28, 38, .5);
    }
    .packages_section:hover  {
        min-height: 300px;
        background-color: rgba(23, 170, 237, .6);
        cursor:pointer;
    }
    .packages_section:hover .package_try_btn {
        background-color: rgba(15, 28, 38, .6);
    }
    .package_active{
        min-height: 300px;
        background-color: rgba(23, 170, 237, .6);
        cursor: pointer;
    }
    .package_active h4 {
        background-color: #17aaed;
    }
    .package_active span {
        background-color: rgba(15, 28, 38, .5);
    }
    .btn-cancel[class*=bg-]:hover, btn-cancel[class*=bg-]:focus, btn-cancel[class*=bg-].focus {
        color: #FFF;
        background-color: #E53935;
    }

    .image_upload-div{ position:relative; padding-left:100px;}  
    .alert[class*=alert-styled-]:after, .alert[class*=alert-styled-][class*=bg-blue]:after {
        content: '\e9b9';      
    }
    .alert{
        font-weight: 400;
        font-size: 100%;
    }
    .disabled_div {
        background: #d0d0d061;
    }
    ul.card_div{padding-left: 0;}

    .dataTables_paginate {
        padding-top: 7px !important;
    }

    #upcoming_invoice_detail_body .alert strong {
        font-weight: bolder;
        text-transform: uppercase;
    }

    .warning-text{
        font-size: 15px;
        background: #bdf2ff;
        padding: 10px;
        font-weight: bolder;
        border: 1px solid #0db7e0;
        text-transform: uppercase;
        color: #4c4c4c;
        margin-top: 10px;
        box-shadow: 1px 1px 1px 1px #b3b3b3;
        border-radius: 5px;
    }

    .danger-text{
        font-size: 15px;
        background: #ffdfdf;
        padding: 10px;
        font-weight: bolder;
        border: 1px solid #ec3e3e;
        text-transform: uppercase;
        color: #696767;
        margin-top: 10px;
        box-shadow: 1px 1px 1px 1px #b3b3b3;
        border-radius: 5px;
    }

    label.warning-text span {
        color: #ff7272;
        font-size: 18px;
    }
    #billing-invoices-listing_length select{
        padding: 7px 0;
        border-radius: 3px;
        border: 1px solid #ddd;
        margin: 0 5px;
    }

    @media(min-width:1025px) and (max-width:1200px){
       /* .packages_section , .package_active{ min-height: 370px;}*/
         .packages_section, .package_active{
            min-height: 330px !important;
         }
         .package_content p{
            font-size: 12px;
         }
         .packages_section h4{
            font-size: 16px !important;
         }
    }

    @media(max-width:1024px){
        .packages_section  ,.package_active, .package_content p {min-height: inherit !important;}
         .packages_section{
            margin-bottom: 30px; 
        }
    }

    @media screen and (max-width:768px){
        .dataTables_filter input{
            width: calc(100% - 45px);
        }
        .packages_section{
            margin-bottom: 25px; 
        }
        .package_content p{
            font-size: 13px;
        }
    }

    @media(max-width:767px){
        .login-form {padding: 0px !important;}
        .datatable-header {text-align: left;float: left;}
        .login-form .form-group > div.form-control-feedback{width: 42px;position: absolute;}
    }
    @media(max-width:380px){
        .uploader .filename{padding: 0;}
    }
</style>    
