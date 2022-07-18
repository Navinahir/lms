<form action="" method="post" id="reset_password_form">
    <div class="forget_password_page reset_page login_bg">
        <div>
            <div>
                <div>
                    <div class="login_container">
                        <div class="login_left_panel">
                            <div class="logo_main">
                                <img src="<?php echo site_url('/assets/images/logo-white.png'); ?>" class="img-fluid">
                            </div>
                            <a href="<?php echo site_url('/') ?>"><h3>Always Reliable Keys</h3></a>
                            <p>   We have been working very hard to create a complete and comprehensive application for Automotive Security Professionals. </p>
                            <a><i class="fas fa-envelope"></i>contact@arksecurity.com</a>
                        </div>
                        <div class="login_right_panel">
                            <div class="reset_header">
                                <h2><span>R</span>eset <span>P</span>assword</h2>
                                <div class="row">
                                    <div class="alert-password-policy alpha-blue border-blue alert-styled-left alert-bordered">
                                        <h2><span>Your password must include:</span></h2>
                                        <ul>
                                            <li>A minimum 8 characters</li>
                                            <li>Have at least one number between [0-9]</li>
                                            <li>Have at least one alphabet character between [A-Z] or [a-z]</li>
                                            <li>Have at least one special character [! @ # $ % ^ & * ( ) + ?]</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php $this->load->view('alert_view'); ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group custom_form_container">
                                        <input type="password" class="form-control ark_form" name="txt_password" id="txt_password" placeholder="Password">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group custom_form_container">
                                        <input type="password" class="form-control ark_form" name="txt_c_password" id="txt_c_password" placeholder="Confirm Password">
                                    </div>
                                </div>
                            </div>
                            <div class="login_footer">
                                <div style="float:left">
                                    <a href="<?php echo site_url('login'); ?>" class="back_login_url"><i class="fas fa-long-arrow-alt-left"></i> Back to Login</a>
                                </div>
                                <button class="Login_btn reset_btn btn_reset_pwd">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
var validator = $("#reset_password_form").validate({
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
        txt_password: 
        { 
            required: true, 
            minlength: 8, 
            pwcheck: true,
            noSpace: true,
        },
        txt_c_password: 
        { 
            required: true, 
            equalTo: '#txt_password',
            pwcheck: true,
            noSpace: true,
        }
    },
    messages: {
        txt_password: {
            required: 'Please enter password!',
            minlength: 'Password must be atleast 8 characters long!'
        },
        txt_c_password: {
            required: 'Please confirm password!',
            equalTo: 'Password does not match!'
        },
    },
    submitHandler: function(form){
    form.submit();
        $('.btn_reset_pwd').prop('disabled', true);
    },
    invalidHandler: function() {
        $('.btn_reset_pwd').prop('disabled', false);
    }
});

// Customize password pattern
jQuery.validator.addMethod("pwcheck", function(value, element) {
/*   value = value.replace(/\s+/g, "");
    return this.optional(element) || value.length > 8 && 
    value.match(/^(?=.*[A-Z].*[A-Z])(?=.*[!@#$&*])(?=.*[0-9].*[0-9])(?=.*[a-z].*[a-z].*[a-z]).{8}$/);*/
    //return this.optional(element) || /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/i.test(value);
    return this.optional(element) || /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[!@#$%^&*()_+{}:"'?<>|]).{8,}$/i.test(value);
}, "Follow above password pattern.");

// Blank space not allow in poassword
jQuery.validator.addMethod("noSpace", function(value, element) { 
  return value.indexOf(" ") < 0 && value != ""; 
}, "Blank space not allowed.");

</script>