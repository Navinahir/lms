<script type="text/javascript" src="assets/js/plugins/forms/inputs/formatter.min.js"></script>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">My Profile</li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <form method="post" class="form-horizontal" id="userPasswordForm" name="userProfileForm" action="<?php echo site_url('admin/update_password'); ?>">
                                    <div class="tabbable tab-content-bordered">
                                        <ul class="nav nav-tabs nav-tabs-highlight">
                                            <li class="active"><a href="#bordered-passtab1" data-toggle="tab"><b>Change Password</b></a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane has-padding active" id="bordered-passtab1">
                                                <div class="row">
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
                                                <div class="form-group">
                                                    <label class="control-label col-lg-2 required">Password</label>
                                                    <div class="col-lg-10">
                                                        <div class="progress progress-micro pwd_progress1 hide">
                                                            <div class="progress-bar pwd_strength_bar1" style="width:0%;"></div>
                                                        </div>
                                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-2 required">Confirm Password</label>
                                                    <div class="col-lg-10">
                                                        <div class="progress progress-micro pwd_progress2 hide">
                                                            <div class="progress-bar pwd_strength_bar2 progress-bar-danger" style="width: 0%;"></div>
                                                        </div>
                                                        <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Confirm Password">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-offset-2 col-lg-10">
                                                        <button type="submit" class="btn bg-blue btn-block custom_save_button" style="letter-spacing: 1px;text-transform: uppercase;font-weight: 500;">Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <form method="post" class="form-horizontal" id="userProfileForm" name="userProfileForm" action="<?php echo site_url('admin/profile'); ?>" enctype="multipart/form-data">
                                    <div class="tabbable tab-content-bordered">
                                        <ul class="nav nav-tabs nav-tabs-highlight">
                                            <li class="active"><a href="#bordered-tab1" data-toggle="tab"><b>Profile Details</b></a></li>
                                        </ul>

                                        <div class="tab-content">
                                            <div class="tab-pane has-padding active" id="bordered-tab1">
                                                <div class="form-group">
                                                    <label class="control-label col-lg-2 required">First Name</label>
                                                    <div class="col-lg-10">
                                                        <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Enter First Name" value="<?php echo $this->session->userdata('first_name'); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-2 required">Last Name</label>
                                                    <div class="col-lg-10">
                                                        <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Enter Last Name" value="<?php echo $this->session->userdata('last_name'); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-lg-2">Phone</label>
                                                    <div class="col-lg-10">
                                                        <input type="text" class="form-control format-phone-number" name="phone" id="phone" placeholder="Enter Phone" value="<?php echo $this->session->userdata('phone'); ?>">
                                                    </div>
                                                </div>
												<div class="form-group">
													<label class="control-label col-lg-2">Image :</label>
													<div class="col-lg-10">
														<div class="media no-margin-top">
															<div class="media-left" id="image_preview_div">
																<?php
																if ( $this->session->userdata('profile') != '' && file_exists(ITEMS_IMAGE_PATH . '/' . $this->session->userdata('profile'))) {
																	$required = '';
																	?>
																	<img src="<?php echo ITEMS_IMAGE_PATH . '/' . $this->session->userdata('profile') ?>" style="width: 58px; height: 58px; border-radius: 2px;" alt="">
																	<?php
																} else {
																	$required = 'required';
																	?>
																	<img src="assets/images/placeholder.jpg" style="width: 58px; height: 58px; border-radius: 2px;" alt="">
																<?php } ?>
															</div>
															<div class="media-body">
																<input type="file" name="image_link" id="image_link" class="file-styled" onchange="readURL(this);">
																<span class="help-block">Accepted formats: png, jpg. Max file size 2Mb</span>
																<span id="image_message_alert" style="color: red;"></span>
															</div>
														</div>
														<?php
														if (isset($menu_item_image_validation))
															echo '<label id="image_link-error" class="validation-error-label" for="image_link">' . $item_image_validation . '</label>';
														?>
													</div>
												</div>
                                                <div class="form-group">
                                                    <div class="col-lg-10 col-lg-offset-2 form-btn">
                                                        <button type="submit" class="btn bg-blue custom_save_button" style="letter-spacing: 1px;text-transform: uppercase;font-weight: 500;">Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <form method="post" class="form-horizontal" id="userEmailForm" name="userProfileForm" action="<?php echo site_url('admin/update_email'); ?>">
                                    <div class="tabbable tab-content-bordered">
                                        <ul class="nav nav-tabs nav-tabs-highlight">
                                            <li class="active"><a href="#bordered-emailtab1" data-toggle="tab"><b>Change Email</b></a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane has-padding active" id="bordered-emailtab1">
                                                <div class="form-group">
                                                    <label class="control-label col-lg-2 required">Email</label>
                                                    <div class="col-lg-10">
                                                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email" value="<?php echo $this->session->userdata('email_id'); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-10 col-lg-offset-2 form-btn">
                                                        <button type="submit" class="btn bg-blue custom_save_button" style="letter-spacing: 1px;text-transform: uppercase;font-weight: 500;">Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <form method="post" class="form-horizontal" id="userUsernameForm" name="userProfileForm" action="<?php echo site_url('admin/username_email'); ?>">
                                    <div class="tabbable tab-content-bordered">
                                        <ul class="nav nav-tabs nav-tabs-highlight">
                                            <li class="active"><a href="#bordered-usernametab1" data-toggle="tab"><b>Change Username</b></a></li>
                                        </ul>

                                        <div class="tab-content">
                                            <div class="tab-pane has-padding active" id="bordered-usernametab1">
                                                <div class="form-group">
                                                    <label class="control-label col-lg-2 required">User Name</label>
                                                    <div class="col-lg-10">
                                                        <input type="text" class="form-control" name="username" id="username" placeholder="Enter User Name" value="<?php echo$this->session->userdata('username'); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-10 col-lg-offset-2 form-btn">
                                                        <button type="submit" class="btn bg-blue custom_save_button" style="letter-spacing: 1px;text-transform: uppercase;font-weight: 500;">Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var user_id = '<?php echo $profile_details['id'] ?>';
    
    // remoteURL = site_url + "admin/login/check_unique_email/" + user_id;
    // remoteURL2 = site_url + "admin/login/check_unique_username/" + user_id;

    remoteURL = site_url + "admin/login/check_unique_email/";
    remoteURL2 = site_url + "admin/login/check_unique_username/";

    $('.format-phone-number').formatter({
        pattern: '({{999}}) {{999}} - {{9999}}'
    });

    // Profile validation
    $("#userProfileForm").validate({
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
            $(element).parent().find('.form_success_icon').remove();
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
            $(element).parent().find('.form_success_icon').remove();
            $(element).parent().append('<div class="form_success_icon form-control-feedback"><i class="icon-checkmark-circle"></i></div>');
            $(element).remove();
        },
        rules: {
            firstname: {required: true, maxlength: 50},
            lastname: {required: true, maxlength: 50},
            email: {required: true, email: true, remote: remoteURL},
            phone: {maxlength: 16, minlength: 16},
            password: {minlength: 8},
            cpassword: {equalTo: '#password'},
        },
        messages: {
            firstname: {required: 'Please enter first name'},
            lastname: {required: 'Please enter last name'},
            email: {
                required: 'Please enter email!',
                email: 'Please enter a valid email',
                remote: $.validator.format("This Email already exist!")
            },
            phone: {
                minlength: 'Please enter valid phone number.',
                maxlength: 'Please enter valid phone number.'
            }
        },
        submitHandler: function (form) {
            form.submit();
            $('.custom_save_button').prop('disabled', true);
        }
    });

    // Profile username validation
    $("#userUsernameForm").validate({
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
            $(element).parent().find('.form_success_icon').remove();
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
            $(element).parent().find('.form_success_icon').remove();
            $(element).parent().append('<div class="form_success_icon form-control-feedback"><i class="icon-checkmark-circle"></i></div>');
            $(element).remove();
        },
        rules: {
            username: {required: true, remote: remoteURL2},
        },
        messages: {
            username: {
                required: 'Please enter user name',
                remote: $.validator.format("This Username already exist!")
            },
        },
        submitHandler: function (form) {
            form.submit();
            $('.custom_save_button').prop('disabled', true);
        }
    });

    //Change password validation
    $("#userPasswordForm").validate({
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
            $(element).parent().find('.form_success_icon').remove();
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
            $(element).parent().find('.form_success_icon').remove();
            $(element).parent().append('<div class="form_success_icon form-control-feedback" style="right:0;left:auto"><i class="icon-checkmark-circle"></i></div>');
            $(element).remove();
        },
        rules: {
            password: {
                required: true,
                minlength: 8,
                pwcheck: true,
                noSpace: true,
            },
            cpassword: {
                required: true,
                equalTo: '#password',
                pwcheck: true,
                noSpace: true,
            },
        },
        messages:{
            password: {
                required: "Please enter your new password",
            },
            cpassword: {
                required: "Please enter your confirm password",
            }
        },
        submitHandler: function (form) {
            form.submit();
            return false;
        },
        invalidHandler: function (e,validator) {
            for (var i=0;i<validator.errorList.length;i++){
                console.log(validator.errorList[i]);
            }

            //validator.errorMap is an object mapping input names -> error messages
            for (var i in validator.errorMap) {
              console.log(i, ":", validator.errorMap[i]);
            }

            $('.btn_login').prop('disabled', false);
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

    // Email validation
    $("#userEmailForm").validate({
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
            $(element).parent().find('.form_success_icon').remove();
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
            $(element).parent().find('.form_success_icon').remove();
            $(element).parent().append('<div class="form_success_icon form-control-feedback"><i class="icon-checkmark-circle"></i></div>');
            $(element).remove();
        },
        rules: {
            email: {required: true, email: true, remote: remoteURL},
        },
        messages: {
            email: {
                required: 'Please enter email!',
                email: 'Please enter a valid email',
                remote: $.validator.format("This Email already exist!")
            },
        },
        submitHandler: function (form) {
            form.submit();
            $('.custom_save_button').prop('disabled', true);
        }
    });

    $(document).on('keyup', '#password', function () {
        var pass_str = $('#password').val();
        pass_length = pass_str.length;
        if (pass_length < 6) {
            $('.pwd_progress1').removeClass('hide');
            $('.pwd_strength_bar1').css({'width': '25%', 'background-color': '#F44336'});
        }
        if (pass_length >= 6 && (pass_str.match(/([a-zA-Z])/) || pass_str.match(/([0-9])/))) {
            $('.pwd_progress1').removeClass('hide');
            $('.pwd_strength_bar1').css({'width': '50%', 'background-color': '#2196F3'});
        }
        if (pass_length >= 6 && pass_str.match(/([a-zA-Z])/) && pass_str.match(/([0-9])/) && pass_str.match(/([!,@,#,$,%,^,&,*,(,),+,?,_,~])/)) {
            $('.pwd_progress1').removeClass('hide');
            $('.pwd_strength_bar1').css({'width': '75%', 'background-color': '#00BCD4'});
        }
        if (pass_length >= 6 && pass_str.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/) && pass_str.match(/([0-9])/) && pass_str.match(/([!,@,#,$,%,^,&,*,(,),+,?,_,~])/)) {
            $('.pwd_progress1').removeClass('hide');
            $('.pwd_strength_bar1').css({'width': '100%', 'background-color': '#4CAF50'});
        }
        if (pass_length == 0) {
            $('.pwd_progress1').addClass('hide');
            $('.pwd_strength_bar1').css({'width': '0%'});
        }
    });
    $(document).on('keyup', '#cpassword', function () {
        var pass_str = $('#cpassword').val();
        pass_length = pass_str.length;
        if (pass_length < 6) {
            $('.pwd_progress2').removeClass('hide');
            $('.pwd_strength_bar2').css({'width': '25%', 'background-color': '#F44336'});
        }
        if (pass_length >= 6 && (pass_str.match(/([a-zA-Z])/) || pass_str.match(/([0-9])/))) {
            $('.pwd_progress2').removeClass('hide');
            $('.pwd_strength_bar2').css({'width': '50%', 'background-color': '#2196F3'});
        }
        if (pass_length >= 6 && pass_str.match(/([a-zA-Z])/) && pass_str.match(/([0-9])/) && pass_str.match(/([!,@,#,$,%,^,&,*,(,),+,?,_,~])/)) {
            $('.pwd_progress2').removeClass('hide');
            $('.pwd_strength_bar2').css({'width': '75%', 'background-color': '#00BCD4'});
        }
        if (pass_length >= 6 && pass_str.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/) && pass_str.match(/([0-9])/) && pass_str.match(/([!,@,#,$,%,^,&,*,(,),+,?,_,~])/)) {
            $('.pwd_progress2').removeClass('hide');
            $('.pwd_strength_bar2').css({'width': '100%', 'background-color': '#4CAF50'});
        }
        if (pass_length == 0) {
            $('.pwd_progress2').addClass('hide');
            $('.pwd_strength_bar2').css({'width': '0%'});
        }
    });
</script>
<style>
    .alert[class*=alert-styled-]:after, .alert[class*=alert-styled-][class*=bg-blue]:after {
        content: '\e9b9';
    }
    #bordered-tab1 .form-group:last-child {margin-bottom: 0;}
    .nav-tabs.nav-tabs-highlight > li > a {
        border-bottom: 2px solid #fff !important;
    }
    @media screen and (max-width:768px){
        .nav-tabs.nav-tabs-highlight > li > a {
            border-bottom: none !important;
        }
    }
</style>
