<?php
$action = site_url('register');
?>
<style>
    .login-cover {
        background-size: auto !important;
    }
</style>
<script type="text/javascript" src="assets/js/plugins/forms/inputs/formatter.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<link rel="stylesheet" type="text/css" href="assets\css\core.css">

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<style type="text/css">
    /* Sotp crome autocomplete affect */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        transition: background-color 5000s ease-in-out 0s;
        -webkit-text-fill-color: #fff !important;
    }
    
    textarea:-webkit-autofill,
    textarea:-webkit-autofill:hover,
    textarea:-webkit-autofill:focus,
    textarea:-webkit-autofill:active {
        transition: background-color 5000s ease-in-out 0s;
        -webkit-text-fill-color: #fff !important;
    }
</style>

<!-- Reload page when user come on this page using back button -->
<script type="text/javascript">
    window.addEventListener( "pageshow", function ( event ) {
      var historyTraversal = event.persisted || 
                             ( typeof window.performance != "undefined" && 
                                  window.performance.navigation.type === 2 );
      if ( historyTraversal ) {
        // Handle page restore.
        window.location.reload();
      }
    });
</script>

<div class="registration_container" id="div_top">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2 class="page_header"><span>A</span>lways <span>R</span>eliable <span>K</span>eys</h2>
            </div>
        </div>
        <div class="registration_form">
            <div class="registration-head">
                <div class="login_back">
                    <a href="<?php echo base_url('login'); ?>" class="back_login_url"><i class="fas fa-long-arrow-alt-left"></i> 
                        Back to Login
                    </a>
                </div>
                <h5 class="form_title">
                    Fill in the form below to register in system now!
                </h5>
                <div class="login_home">
                    <a href="<?php echo base_url('/'); ?>" class="back_login_url">
                        Back to Home <i class="fas fa-long-arrow-alt-right"></i> 
                    </a>
                </div>
            </div>
            <form method="post" action="<?php echo $action; ?>" id="User_Register_Form">
                <?php $this->load->view('alert_view'); ?>
                <div id="payment-errors"></div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control ark_form" placeholder="First name" name="first_name" id="first_name" pattern="^[a-zA-Z0-9]+$">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control ark_form" placeholder="Last name" name="last_name" id="last_name">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-briefcase"></i></span>
                            <input type="text" class="form-control ark_form" placeholder="Business Name" name="business_name" id="business_name">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-phone"></i></span>
                            <input type="text" class="form-control ark_form format-phone-number" placeholder="Contact Number" name="contact_number" id="contact_number">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-envelope"></i></span>
                            <input type="text" class="form-control ark_form" placeholder="Email" name="email_id" id="email_id">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-building"></i></i></span>
                            <textarea class="form-control ark_form" rows="3" placeholder="Address" name="address" id="address"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-building"></i></span>
                            <input type="text" class="form-control ark_form" id="city" Placeholder="City"  name="city" id="city"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <!-- <div class="form-group custom_form_container">
                            <span><i class="fas fa-building"></i></span>
                            <div class="dropdown custom_dropdown_div contry_dwopdown">
                                <button class="btn dropdown-toggle custom_dropdown user_custom_dropdown" type="button" data-toggle="dropdown">Alabama
                                    <span class="caret"></span>
                                </button>
                                <input type="hidden"  name="state_id" id='state_id' value="3919"/>
                                <ul class="dropdown-menu " id='state_drop_down'>
                                    <?php
                                    if (isset($states) && !empty($states)):
                                        foreach ($states as $s):
                                            echo "<li id='" . $s['id'] . "'>" . $s['name'] . "</li>";
                                        endforeach;
                                    endif;
                                    ?>
                                </ul>
                            </div>
                        </div> -->

                        <div class="form-group select2_trance">
                            <select class="select" id="state_id" name="state_id" style="width: 100%">
                                <option value="">Select state</option>
                                <?php
                                    if (isset($states) && !empty($states)):
                                        foreach ($states as $s):
                                        ?>
                                            <option value="<?php echo $s['id']; ?>"><?php echo $s['name']; ?></option>    
                                        <?php  
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-building"></i></span>
                            <input type="text" class="form-control ark_form" name="zip_code" id="zip_code" Placeholder="Zip Code" maxlength="6">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="form_section_header">Discount/Promotion Code</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-building"></i></span>
                            <input type="text" class="form-control ark_form" name="promotion_code" id="promotion_code" Placeholder="Discount/Promotion Code">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="form_section_header">OUR AFFORDABLE RATES</h3>
                        <p class="text-content">At Always Reliable Keys, we believe in offering top-notch services at a reasonable rate. We
                        offer three different packages to fit you and your company just right, so you only pay for what
                        you need.</p>
                        <div class="package-content">
                            <h5>It’s All Included!</h5>
                            <div class="table-responsive">
                                <table>
                                <tbody>
                                    <tr>
                                        <td>Full access to search 1000's of vehicles and see
                                        all compatible keys, remotes, transponder chips,
                                        key shells, proximity fobs
                                        </td>
                                        <td><i class="fas fa-check"></i></td>
                                        <td><i class="fas fa-check"></i></td>
                                        <td><i class="fas fa-check"></i></td>
                                    </tr>
                                    <tr>
                                        <td>Search by Year, Make, and Model to see all the
                                        details and compatible parts for that vehicle
                                        </td>
                                        <td><i class="fas fa-check"></i></td>
                                        <td><i class="fas fa-check"></i></td>
                                        <td><i class="fas fa-check"></i></td>
                                    </tr>
                                    <tr>
                                        <td>Manage and track your inventory quickly
                                        </td>
                                        <td><i class="fas fa-check"></i></td>
                                        <td><i class="fas fa-check"></i></td>
                                        <td><i class="fas fa-check"></i></td>
                                    </tr>
                                    <tr>
                                        <td>Create professional estimates and invoices and
                                        send them to your customers 
                                        </td>
                                        <td><i class="fas fa-check"></i></td>
                                        <td><i class="fas fa-check"></i></td>
                                        <td><i class="fas fa-check"></i></td>
                                    </tr>
                                    <tr>
                                        <td>Manage and track special orders for your
                                        customers 
                                        </td>
                                        <td><i class="fas fa-check"></i></td>
                                        <td><i class="fas fa-check"></i></td>
                                        <td><i class="fas fa-check"></i></td>
                                    </tr>
                                    <tr>
                                        <td>Programming procedures from the most popular
                                        automotive locksmith tools on the market 
                                        </td>
                                        <td><i class="fas fa-check"></i></td>
                                        <td><i class="fas fa-check"></i></td>
                                        <td><i class="fas fa-check"></i></td>
                                    </tr>
                                    <tr>
                                        <td>Track productivity, jobs, and sales for your
                                        technicians 
                                        </td>
                                        <td><i class="fas fa-check"></i></td>
                                        <td><i class="fas fa-check"></i></td>
                                        <td><i class="fas fa-check"></i></td>
                                    </tr>
                                    <tr>
                                        <td>View and print reports about your business that
                                        will help you improve your operations  
                                        </td>
                                        <td><i class="fas fa-check"></i></td>
                                        <td><i class="fas fa-check"></i></td>
                                        <td><i class="fas fa-check"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="form_section_header">Select Packages</h3>
                    </div>
                </div>
                <?php
                if (isset($package_Arr) && !empty($package_Arr)):
                    ?>
                    <div class='row'  id="package_list">
                        <input type="hidden" id="package_id" name="package_id" value="1"/>
                        <?php
                        foreach ($package_Arr as $key => $p):
                            ?>
                            <div class="col-sm-4">
                                <div class="packages_section <?php echo ($key == 0) ? 'package_active' : '' ?>" id="package_<?php echo $p['id'] ?>" data-id="<?php echo $p['id'] ?>" data-rate="<?php echo $p['price']; ?>">
                                    <h4><?php echo $p['name']; ?></h4>
                                    <div class="package_content">
                                        <input type="hidden" name="" id="initial_price" value="<?php echo $p['price']; ?>">
                                        <span>$<?php echo $p['price']; ?></span>
                                        <p><?php echo $p['short_description']; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endforeach; 
                        ?>
                    </div>
                    <?php
                endif;
                ?>
                <div class="partner-logo">
                    <p>Want to Integrate your Always Reliable Keys with Quickbooks Online? We can do that. Sync all
                    your customers, estimates, invoices, and inventory items and quantities to your quickbooks
                    online account.
                    </p>
                    <ul>
                        <li>
                            <img src="<?php echo base_url('assets/images/logo-white.png') ?>"/>
                        </li>
                        <li><i class="fas fa-plus"></i></li>
                        <li>
                            <img src="<?php echo base_url('assets/images/quickbook.png') ?>"/>
                        </li>
                    </ul>
                    <div class="plan-selection">
                        <div class="chk-box-wrap">
                            <label class="chk-box-container text-white">
                                Add the Always Reliable Keys quickbooks integration to any package for just:
                            <!--     <input type="checkbox" id="select_quickbook">
                                <span class="checkmark"></span> -->
                            </label>                        
                        </div>
                        <span>$19.99/mo</span>
                        <!-- <p class="quickbook_total_title hide">Total Amount : $<span class="quickbook_total hide" ></span></p>
                        <input type="hidden" class="quickbook_total_hidden" name="quickbook_total_hidden" value="">
                        <input type="hidden" name="quickbook_status" class="quickbook_status" value="0"> -->
                    </div>
                </div>
    
                <div class="payment_info">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="form_section_header">Payment and Billing Information</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group custom_form_container">
                                <span><i class="far fa-credit-card"></i></span>
                                <input type="tel" class="form-control ark_form format-credit-card" placeholder="Credit Card Number" name="credit_card" id="card_num" maxlength="19" minlength="19">
                            </div>
                        </div>
                        <div class="col-sm-6">
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
                            <div class="form-group custom_form_container">
                                <span><i class="far fa-calendar-alt"></i></span>
                                <input type="text" name="exp_month" maxlength="2" class="form-control ark_form format-month" id="card-expiry-month" placeholder="MM" value="<?php echo set_value('exp_month'); ?>" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group custom_form_container">
                                <span><i class="far fa-calendar-alt"></i></span>
                                <input type="text" name="exp_year" class="form-control ark_form format-year" maxlength="4" id="card-expiry-year" placeholder="YYYY" required="" value="<?php echo set_value('exp_year'); ?>">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group custom_form_container">
                                <span><i class="far fa-address-card"></i></span>
                                <input type="password" class="form-control ark_form format-csv" placeholder="V-code" name="v_code" id="card-cvc" maxlength="4">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="billing_info">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="custom-control billing-checkbox">
                                <span class="checked">
                                    <input type="checkbox" class="styled copy_address" value="0" id="copy_billing_address" />
                                </span>Same as above
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="form_section_header">Billing Information</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group custom_form_container">
                                <span><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control ark_form register_readonly" placeholder="Billing Name" name="billing_name" id="billing_name">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group custom_form_container">
                                <span><i class="fas fa-phone"></i></span>
                                <input type="text" class="form-control ark_form register_readonly format-phone-number" placeholder="Billing Phone" name="billing_phone" id="billing_phone">
                            </div>
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group custom_form_container">
                                <span><i class="fas fa-building"></i></span>
                                <textarea class="form-control ark_form register_readonly" rows="3" placeholder="Billing Address" name="billing_address" id="billing_address"></textarea>
                            </div>
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group custom_form_container">
                                <span><i class="fas fa-building"></i></span>
                                <input type="text" class="form-control ark_form register_readonly" name="billing_city" id="billing_city" placeholder="City">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- <div class="form-group custom_form_container">
                                <span><i class="fas fa-building"></i></span>
                                <div class="dropdown custom_dropdown_div contry_dwopdown">
                                    <button class="btn dropdown-toggle custom_dropdown billing_custom_dropdown" type="button" data-toggle="dropdown">Alabama
                                        <span class="caret"></span>
                                    </button>
                                    <input type="hidden"  name="billing_state_id" id='billing_state_id' value="3919"/>
                                    <ul class="dropdown-menu" id='billing_state_drop_down'>
                                        <?php
                                        if (isset($states) && !empty($states)):
                                            foreach ($states as $s):
                                                echo "<li id='" . $s['id'] . "' class=''>" . $s['name'] . "</li>";
                                            endforeach;
                                        endif;
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            -->
                            <div class="form-group select2_trance">
                                <select class="select" id="billing_state_id" name="billing_state_id" style="width: 100%">
                                    <option value="">Select state</option>
                                    <?php
                                        if (isset($states) && !empty($states)):
                                            foreach ($states as $s):
                                            ?>
                                                <option value="<?php echo $s['id']; ?>"><?php echo $s['name']; ?></option>    
                                            <?php  
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                            </div>

                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group custom_form_container">
                                <span><i class="fas fa-building"></i></span>
                                <input type="text" class="form-control ark_form register_readonly" name="billing_zip_code" id="billing_zip_code" placeholder="Zip Code">
                            </div>
                        </div>
                    </div>              
                </div>
                <button type="submit" class="btn btn-default register-button form_submit_btn btn_login">Register</button>
            </form>
        </div>
    </div>
</div>

<a id="scroll-top-button"></a>

<script>
    var site_url = '<?php echo site_url(); ?>'
    // var remoteURL = site_url + "home/checkUnique_Email";
    var remoteURL = site_url + "uniq_email";
    var codeURL = site_url + "home/checkUniquePromotionCode";
    var STRIPE_PUBLISH_KEY = '<?php echo $stripe_data['STRIPE_PUBLISH_KEY'] ?>';
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/regi.js?version='<?php echo time();?>'"></script>
<style>
    @media (max-width: 991px){
    .payment_img {width: inherit;}
}
   @media (max-width: 767px){
    ul.card_div {padding: 0;}
    .packages_section , .package_active {min-height: inherit;}    
}
@media (max-width: 580px){
    .login_back {display: block;float: none;}
}
</style>
<script type="text/javascript">

    $("#state_id").select2();  
    $("#billing_state_id").select2();  

    if($.trim($('quickbook_total_hidden').val()) == ''){
        var final_total = parseFloat($('#initial_price').val()).toFixed(2);
        $('.quickbook_total_hidden').val(final_total);
    }

    $('#package_list').click(function(event) {
        if($("#select_quickbook").prop('checked') == true){
            var q_status = 1;
            var quickbook_rate = 19.99;
            var package_rate = $("#package_list").find('.package_active').attr('data-rate');
            final_total = parseFloat(parseFloat(quickbook_rate) + parseFloat(package_rate)).toFixed(2);
            $('.quickbook_total').removeClass('hide');
            $('.quickbook_total_title').removeClass('hide');
            $('.quickbook_total').html(final_total);
            $('.quickbook_total_hidden').val(final_total);
            $('.quickbook_status').val(q_status);
        } else {
            var q_status = 0;
            var final_total = $("#package_list").find('.package_active').attr('data-rate');
            $('.quickbook_total').removeClass('hide');
            $('.quickbook_total_title').removeClass('hide');
            $('.quickbook_total').html(final_total);
            $('.quickbook_total_hidden').val(final_total);
            $('.quickbook_status').val(q_status);
        }
    });

    $('#select_quickbook').change(function(event) {
        if($("#select_quickbook").prop('checked') == true){
            var q_status = 1;
            var quickbook_rate = 19.99;
            var package_rate = $("#package_list").find('.package_active').attr('data-rate');
            final_total = parseFloat(parseFloat(quickbook_rate) + parseFloat(package_rate)).toFixed(2);
            $('.quickbook_total').removeClass('hide');
            $('.quickbook_total_title').removeClass('hide');
            $('.quickbook_total').html(final_total);
            $('.quickbook_total_hidden').val(final_total);
            $('.quickbook_status').val(q_status);
        } else {
            var q_status = 0;
            var final_total = parseFloat($("#package_list").find('.package_active').attr('data-rate')).toFixed(2);
            $('.quickbook_total').removeClass('hide');
            $('.quickbook_total_title').removeClass('hide');
            $('.quickbook_total').html(final_total);
            $('.quickbook_total_hidden').val(final_total);
            $('.quickbook_status').val(q_status);
        }
    });

    // Update Same as above data if already checked
    $(document).on('keyup','#first_name,#last_name,#business_name,#contact_number,#address,#city,#zip_code',function(){
        if($("#copy_billing_address").prop('checked') == true){
            var first_name = $("#first_name").val();
            var last_name = $("#last_name").val();
            var full_name = $("#first_name").val() + ' ' + $("#last_name").val();
            var phone_numebr = $("#contact_number").val();
            var billing_address = $("#address").val();
            var billing_city = $("#city").val();
            var billing_zip = $("#zip_code").val();
            var state_name = $('#state_id :selected').text();  
            var state_val = $('#state_id').val();
            
            if(first_name != null && first_name != '' && first_name != undefined) {
                $("#billing_name").val(first_name);
            }

            if(last_name != null && last_name != '' && last_name != undefined) {
                $("#billing_name").val(last_name);
            }

            if(first_name != null && first_name != '' && first_name != undefined && last_name != null && last_name != '' && last_name != undefined) {
                $("#billing_name").val(full_name);
            }

            if(phone_numebr != null && phone_numebr != '' && phone_numebr != undefined) {
                $("#billing_phone").val(phone_numebr);
            }            

            if(billing_address != null && billing_address != '' && billing_address != undefined) {
                $("#billing_address").val(billing_address);
            }

            if(billing_city != null && billing_city != '' && billing_city != undefined) {
                $("#billing_city").val(billing_city);
            }

            if(billing_zip != null && billing_zip != '' && billing_zip != undefined) {
                $("#billing_zip_code").val(billing_zip);
            }

        }
    });

    // Update billing state dropdown if same as above checked
    $(document).on('change','#state_id',function(){
        if($("#copy_billing_address").prop('checked') == true){
            var state_val = $('#state_id').val();
            $('#billing_state_id option').each(function(){
                if($(this).val() == state_val){
                    $(this).attr("selected","selected");
                    $('#billing_state_id').select2({containerCssClass: 'select-sm'});
                    $('#billing_state_id').valid();
                    $('#billing_state_id').attr("disabled", true); 
                }
            });
        }
    });

    $('#copy_billing_address').change(function(event) {
        if ($(this).prop("checked") == true) {

            var first_name = $("#first_name").val();
            var last_name = $("#last_name").val();
            var full_name = $("#first_name").val() + ' ' + $("#last_name").val();
            var phone_numebr = $("#contact_number").val();
            var billing_address = $("#address").val();
            var billing_city = $("#city").val();
            var billing_zip = $("#zip_code").val();
            var state_name = $('#state_id :selected').text();  
            var state_val = $('#state_id').val();
            
            if(first_name != null && first_name != '' && first_name != undefined) {
                $("#billing_name").val(first_name);
                $('#billing_name').attr('readonly', true);
                $('#billing_name').valid();
            }

            if(last_name != null && last_name != '' && last_name != undefined) {
                $("#billing_name").val(last_name);
                $('#billing_name').attr('readonly', true);
                $('#billing_name').valid();
            }

            if(first_name != null && first_name != '' && first_name != undefined && last_name != null && last_name != '' && last_name != undefined) {
                $("#billing_name").val(full_name);
                $('#billing_name').attr('readonly', true);
                $('#billing_name').valid();
            }

            if(phone_numebr != null && phone_numebr != '' && phone_numebr != undefined) {
                $("#billing_phone").val(phone_numebr);
                $('#billing_phone').attr('readonly', true);
                $('#billing_phone').valid();
            }            

            if(billing_address != null && billing_address != '' && billing_address != undefined) {
                $("#billing_address").val(billing_address);
                $('#billing_address').attr('readonly', true);
                $('#billing_address').valid();
            }

            if(billing_city != null && billing_city != '' && billing_city != undefined) {
                $("#billing_city").val(billing_city);
                $('#billing_city').attr('readonly', true);
                $('#billing_city').valid();
            }

            if(billing_zip != null && billing_zip != '' && billing_zip != undefined) {
                $("#billing_zip_code").val(billing_zip);
                $('#billing_zip_code').attr('readonly', true);
                $('#billing_zip_code').valid();
            }

            $('#billing_state_id option').each(function(){
                if($(this).val() == state_val){
                    $(this).attr("selected","selected");
                    $('#billing_state_id').select2({containerCssClass: 'select-sm'});
                    $('#billing_state_id').valid();
                    $('#billing_state_id').attr("disabled", true); 
                }
            });
            
        } else if ($(this).prop("checked") == false) {
            $("#billing_name").val('');
            $("#billing_phone").val('');
            $("#billing_address").val('');
            $("#billing_city").val('');
            $("#billing_zip_code").val('');

            // Remove readonly attribute
            $('#billing_name').attr('readonly', false);
            $('#billing_phone').attr('readonly', false);
            $('#billing_address').attr('readonly', false);
            $('#billing_city').attr('readonly', false);
            $('#billing_zip_code').attr('readonly', false);
            $('#billing_state_id').attr("disabled", false); 

            $('#billing_state_id').select2('data', null);
            $.ajax({
                url: site_url + 'home/update_state',
                type: 'POST',
                dataType: 'json',
                success:function(response){
                    $('#billing_state_id').html(response);
                    $('#billing_state_id').select2({containerCssClass: 'select-sm'});
                }
            });
        }
    });

    // Remover validation on dropdown change
    $('select').change(function(){
        $(this).valid();
    });

    // Bottom to top scroll 
    var btn = $('#scroll-top-button');

    $(window).scroll(function() {
      if ($(window).scrollTop() > 300) {
        btn.addClass('show');
      } else {
        btn.removeClass('show');
      }
    });

    btn.on('click', function(e) {
      e.preventDefault();
      $('html, body').animate({scrollTop:0}, '300');
    });
    // Bottom to top scroll end

    // Prevent special character 
    $('#first_name,#last_name').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
           event.preventDefault();
           return false;
        }
    });

    // Prevent numebr character 
    $("#first_name,#last_name").keypress(function(e) {
        var key = e.keyCode;
        if (key >= 48 && key <= 57) {
            e.preventDefault();
        }
    });

    // Onlu number
    $('#zip_code').bind('keyup paste', function(){
        this.value = this.value.replace(/[^0-9]/g, '');
    });

</script>