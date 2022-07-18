<style>
    .logo_main {
        width: 200px;
        margin: auto;
    }
    .logo_main img {
        width: 100%;
        height: auto;
    }

    /* Sotp crome autocomplete affect */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        transition: background-color 5000s ease-in-out 0s;
        -webkit-text-fill-color: #fff !important;
    }
</style>
<?php
    $redirect = "";
    $redirect_url = "";
    $redirect = $this->input->get('redirect_on');

    if($redirect == "admin_login"){
        $redirect_url = site_url('admin');
    } else {
        $redirect_url = site_url('login');
    }
?>
<form action="" method="post" id="forgot_password_form">
    <div class="forget_password_page login_bg">
        <div class="login_container">
            <div class="login_left_panel">
                 <div class="logo_main">
                    <img src="<?php echo site_url('/assets/images/logo-white.png'); ?>" class="img-fluid">
                </div>
                <h3>Always Reliable Keys</h3>
                <p>We have been working very hard to create a complete and comprehensive application for Automotive Security Professionals. </p>
                <a><i class="fas fa-envelope"></i>contact@arksecurity.com</a>
            </div>
            <div class="login_right_panel">
                <div class="reset_header">
                    <h2><span>F</span>orgot <span>P</span>assword</h2>
                    <h3>Please enter the e-mail address and you will receive an email to reset your password to the email address you entered.</h3>
                </div>
                <?php $this->load->view('alert_view'); ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group custom_form_container">
                            <input type="text" class="form-control ark_form" name="txt_email" id="txt_email" placeholder="Email">
                        </div>
                    </div>
                </div>
                <div class="login_footer">
                    <div style="display:inline-block">
                        <a href="<?php echo $redirect_url; ?>" class="back_login_url"><i class="fas fa-long-arrow-alt-left"></i> Back to Login</a>
                    </div>
                    <button class="Login_btn reset_btn btn_forgot_pwd">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    var validator = $("#forgot_password_form").validate({
        ignore: 'input[type=hidden], .select2-search__field, #txt_status', // ignore hidden fields
        errorClass: 'validation-error-label',
        successClass: 'validation-valid-label',
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        errorPlacement: function (error, element) {
            $(element).parent().find('.form_success_vert_icon').remove();
            if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container')) {
                if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                    error.appendTo(element.parent().parent().parent().parent());
                } else {
                    error.appendTo(element.parent().parent().parent().parent().parent());
                }
            } else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
                error.appendTo(element.parent().parent().parent());
            } else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
                error.appendTo(element.parent());
            } else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                error.appendTo(element.parent().parent());
            } else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
                error.appendTo(element.parent().parent());
            } else {
                error.insertAfter(element);
            }
        },
        validClass: "validation-valid-label",
        success: function (element) {
            $(element).parent().find('.form_success_vert_icon').remove();
            $(element).parent().append('<div class="form_success_vert_icon form-control-feedback"><i class="icon-checkmark-circle"></i></div>');
            $(element).remove();
        },
        rules: {
            txt_email: {
                required: true, 
                email: true,
                stricemailonly: true,
            }
        },
        submitHandler: function (form) {
            form.submit();
            $('.btn_forgot_pwd').prop('disabled', true);
        },
        invalidHandler: function () {
            $('.btn_forgot_pwd').prop('disabled', false);
        }
    });

    jQuery.validator.addMethod("stricemailonly", function(value, element) {
      return this.optional(element) || /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i.test(value);
    }, "Invalid Email Format.");
</script>