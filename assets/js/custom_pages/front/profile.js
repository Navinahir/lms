$(document).ready(function () {
    Stripe.setPublishableKey(STRIPE_PUBLISH_KEY);

    //callback to handle the response from stripe
    function stripeResponseHandler(status, response) {
        if (response.error) {
            //enable the submit button
            $('#payBtn').removeAttr("disabled");
            $('.btn_login').prop('disabled', false);
            //display the errors on the form
            // $('#payment-errors').attr('hidden', 'false');
            $('#payment-errors').addClass('alert alert-danger');
            $("#payment-errors").html(response.error.message);
            $('html, body').animate({
                scrollTop: $("#payment-errors").offset().top
            }, 2000);
            return false;
        } else {
            var form$ = $("#add_company_form");
            //get token id
            var token = response['id'];
            var str = response.card.brand;
            str = str.replace(/\s+/g, '');
            $('card_div').removeClass('active');
            $('#' + str).addClass('active');
            // var card_type = $("input[name='card_type']:checked").val();
            if (str.match(/Visa/g) || str.match(/MasterCard/g) || str.match(/Discover/g) || str.match(/AmericanExpress/g)) {

                // if ((str.match(/Visa/g) || str.match(/MasterCard/g) || str.match(/Discover/g) || str.match(/American Express/g)) && (str.toLowerCase() == card_type.toLowerCase())) {
                form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                form$.append("<input type='hidden' name='card_type' value='" + str + "' />");
                form$.append("<input type='hidden' name='card_change' value='1' />");
                //submit form to the server
                form$.get(0).submit();
            } else {
                $('#payment-errors').addClass('alert alert-danger');
                $("#payment-errors").html('Please check payment details!! We are not accepting <b>' + str + '<b/> card.');
                $('.btn_login').prop('disabled', false);
                $('html, body').animate({
                    scrollTop: $("#payment-errors").offset().top
                }, 2000);
            }
            //insert the token into the form
        }
    }

    function check_stipe() {
        var card = Stripe.card.validateCardNumber($('#card_num').val());
        var card_expiry = Stripe.card.validateExpiry($('#card-expiry-month').val(), $('#card-expiry-year').val());
        var cvc = Stripe.card.validateCVC($('#card-cvc').val());
        var type = Stripe.card.cardType($('#card_num').val());
        if (card == true && card_expiry == true && cvc == true && type !== 'Unknown') {
            Stripe.createToken({
                number: $('#card_num').val(),
                cvc: $('#card-cvc').val(),
                exp_month: $('#card-expiry-month').val(),
                exp_year: $('#card-expiry-year').val()
            }, stripeResponseHandler);
        } else {
            $('#payment-errors').addClass('alert alert-danger');
            $("#payment-errors").html('Please check Card details!!');
            $('.btn_login').prop('disabled', false);
            $('html, body').animate({
                scrollTop: $("#payment-errors").offset().top
            }, 2000);
        }
    }

    $("#add_company_form").validate({
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
            first_name: {
                required: true, maxlength: 100, normalizer: function (value) {
                    return $.trim(value);
                }
            },
            last_name: {
                required: true, maxlength: 100, normalizer: function (value) {
                    return $.trim(value);
                }
            },
            business_name: {
                required: true, maxlength: 255, normalizer: function (value) {
                    return $.trim(value);
                }
            },
            email_id: {
                required: true,
                email: true,
                remote: remoteURL
                        // accept: "[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,5}"
            },
            contact_number: {
                required: true,
                minlength: 16,
                maxlength: 16,
            },
            address: {
                required: true, normalizer: function (value) {
                    return $.trim(value);
                }
            },
            city: {
                required: true, normalizer: function (value) {
                    return $.trim(value);
                }
            },
            state_id: {required: true},
            zip_code: {
                required: true, normalizer: function (value) {
                    return $.trim(value);
                }
            },
            package_id: {required: true},
            card_type: {required: true},
            credit_card: {
                required: true,
                minlength: 18,
                maxlength: 19,
            },
            exp_month: {required: true, min: 1, max: 12},
            exp_year: {required: true, min: (new Date()).getFullYear()},
            // v_code: { required: true },
            billing_name: {
                required: true, normalizer: function (value) {
                    return $.trim(value);
                }
            },
            billing_address: {
                required: true, normalizer: function (value) {
                    return $.trim(value);
                }
            },
            billing_phone: {
                required: true,
                minlength: 16,
                maxlength: 16,
            },
            billing_city: {
                required: true, normalizer: function (value) {
                    return $.trim(value);
                }
            },
            billing_state_id: {required: true},
            billing_zip_code: {
                required: true, normalizer: function (value) {
                    return $.trim(value);
                }
            },
            password: {minlength: 8},
            cpassword: {equalTo: '#password'},
        },
        messages: {
            email_id: {remote: $.validator.format("This Email already exist!")},
            contact_number: 'Please enter valid Phone number!',
            billing_phone: 'Please enter valid Phone number!',
            credit_card: 'Please enter valid Card number!'
        },
        submitHandler: function (form) {
            var $form = $(form),
                    cc = $form.find('input[name=credit_card]').val(),
                    credit_card = cc.substr(cc.length - 4, 4),
                    old_package_id = $form.find('input[name=old_package_id]').val(),
                    package_id = $form.find('input[name=package_id]').val();

            if (old_package_id != package_id) {
                $('.old_package_name').html($form.find('input[name=old_package_id]').attr('data-name'));
                $('.package_name').html($form.find('input[name=package_id]').attr('data-name'));
                $('.package_price').html('$' + $form.find('input[name=package_id]').attr('data-price'));
                $('#modal_theme_primary').modal('show');
                $('#modal_theme_primary').on("shown.bs.modal", function (event) {
                    event.preventDefault();
                    $(document).on("click", ".btn-agree", function (event) {
                        $('.btn-agree').prop('disabled', true);
                        $('.btn-cancel').prop('disabled', true);
                        $("#add_company_form").append("<input type='hidden' name='package_change' value='1' />");
                        if (old_credit_card != credit_card)
                            check_stipe();
                        else
                            form.submit();

                    });
                    $(document).on("click", ".btn-cancel", function (event) {
                        $('.btn-agree').prop('disabled', true);
                        $('.btn-cancel').prop('disabled', true);
                        $('#package_id').val($form.find('input[name=old_package_id]').val());
                        $('#package_id').attr('data-name', $form.find('input[name=old_package_id]').attr('data-name'));
                        $('#package_id').attr('data-price', $form.find('input[name=old_package_id]').attr('data-price'));
                        if (old_credit_card != credit_card)
                            check_stipe();
                        else
                            form.submit();
                    });
                });
            } else {
                if (old_credit_card != credit_card)
                    check_stipe();
                else
                    form.submit();
            }
            $('.btn_login').prop('disabled', true);
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

	$("#change_password_form").validate({
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

    $('.format-phone-number').formatter({
        pattern: '({{999}}) {{999}} - {{9999}}'
    });
    $('.format-credit-card').formatter({
        pattern: '{{9999}} {{9999}} {{9999}} {{9999}}'
    });
    $('.format-month').formatter({
        pattern: '{{99}}'
    });
    $('.format-year').formatter({
        pattern: '{{9999}}'
    });
    $('.format-csv').formatter({
        pattern: '{{999}}'
    });

    $('.packages_section').on('click', function () {
        $('.packages_section').removeClass('package_active');
        var id = $(this).attr('id');
        $('#package_id').val($(this).attr('data-id'));
        $('#package_id').attr('data-name', $(this).attr('data-name'));
        $('#package_id').attr('data-price', $(this).attr('data-price'));
        $("#" + id).addClass('package_active');
    });

    var state_id = $('#state_id').val();
    var billing_state_id = $('#billing_state_id').val();

    $('#state_drop_down li[id="' + state_id + '"]').addClass('active');
    $('#billing_state_drop_down li[id="' + billing_state_id + '"]').addClass('active');

    $('#state_drop_down li').on('click', function () {
        $('#state_drop_down li').removeClass('active');
        $('.user_custom_dropdown').html($(this).html());
        $('#state_id').val(this.id);
        $('#state_drop_down li[id="' + this.id + '"]').addClass('active');
    });

    $('#billing_state_drop_down li').on('click', function () {
        $('#billing_state_drop_down li').removeClass('active');
        $('.billing_custom_dropdown').html($(this).html());
        $('#billing_state_id').val((this.id));
        $('#billing_state_drop_down li[id="' + this.id + '"]').addClass('active');
    });

    $("#profile_pic").on("change", function () {
        $('.image_wrapper').html('');
        var files = !!this.files ? this.files : [];
        var sizeKB = files[0].size / 1024 / 1024;
        if (!files.length || !window.FileReader) {
            return; // no file selected, or no FileReader support
        }
        // append attachment name
        $('.filename').html(files[0].name);
        if(sizeKB <= 2) {
            var i = 0;
            for (var key in files) {
                if (/^image/.test(files[key].type) && files[key].type != 'image/gif') { // only image file
                    var reader = new FileReader(); // instance of the FileReader
                    reader.readAsDataURL(files[key]); // read the local file
                    reader.onloadend = function (e) { // set image data as background of div
                        var html = '<img src="' + e.target.result + '" style="width: 80px; height: 60px; border-radius: 2px;" alt="">';
                        $('.image_wrapper').html(html);
                        $('#image_message_alert').hide();
                        ++i;
                    }
                } else {
                    var html = '<img src="'+ site_url + 'uploads/common_images/block_image.png" style="width: 60px; height: 60px; border-radius: 2px;" alt="">';
                    $('.image_wrapper').html(html);
                    $('#image_message_alert').addClass('error').html("Please select proper image.");
                    $('#image_message_alert').show();
                }
            }
        } else {
            var html = '<img src="'+ site_url + 'uploads/common_images/block_image.png" style="width: 60px; height: 60px; border-radius: 2px;" alt="">';
            $('.image_wrapper').html(html);
            $('#image_message_alert').addClass('error').html("Image size must be less than 2MB.");
            $('#image_message_alert').show();
        }
    });

    $('#card_num').keypress(function () {
        $("#card-cvc").rules("add", {required: true});
    });

    if (typeof (view_profile) != 'undefined' && view_profile == 1) {
        $('.profile_content').addClass('disabled_div');
        $('.save_btn_div').addClass('hide');
    }
})

$(document).on('keyup', '#password', function () {
    var pass_str = $('#password').val();
    pass_length = pass_str.length;
    if (pass_length < 8) {
        $('.pwd_progress1').removeClass('hide');
        $('.pwd_strength_bar1').css({'width': '25%', 'background-color': '#F44336'});
    }
    if (pass_length >= 8 && (pass_str.match(/([a-zA-Z])/) || pass_str.match(/([0-9])/))) {
        $('.pwd_progress1').removeClass('hide');
        $('.pwd_strength_bar1').css({'width': '50%', 'background-color': '#2196F3'});
    }
    if (pass_length >= 8 && pass_str.match(/([a-zA-Z])/) && pass_str.match(/([0-9])/) && pass_str.match(/([!,@,#,$,%,^,&,*,(,),+,?,_,~])/)) {
        $('.pwd_progress1').removeClass('hide');
        $('.pwd_strength_bar1').css({'width': '75%', 'background-color': '#00BCD4'});
    }
    if (pass_length >= 8 && pass_str.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/) && pass_str.match(/([0-9])/) && pass_str.match(/([!,@,#,$,%,^,&,*,(,),+,?,_,~])/)) {
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
    if (pass_length < 8) {
        $('.pwd_progress2').removeClass('hide');
        $('.pwd_strength_bar2').css({'width': '25%', 'background-color': '#F44336'});
    }
    if (pass_length >= 8 && (pass_str.match(/([a-zA-Z])/) || pass_str.match(/([0-9])/))) {
        $('.pwd_progress2').removeClass('hide');
        $('.pwd_strength_bar2').css({'width': '50%', 'background-color': '#2196F3'});
    }
    if (pass_length >= 8 && pass_str.match(/([a-zA-Z])/) && pass_str.match(/([0-9])/) && pass_str.match(/([!,@,#,$,%,^,&,*,(,),+,?,_,~])/)) {
        $('.pwd_progress2').removeClass('hide');
        $('.pwd_strength_bar2').css({'width': '75%', 'background-color': '#00BCD4'});
    }
    if (pass_length >= 8 && pass_str.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/) && pass_str.match(/([0-9])/) && pass_str.match(/([!,@,#,$,%,^,&,*,(,),+,?,_,~])/)) {
        $('.pwd_progress2').removeClass('hide');
        $('.pwd_strength_bar2').css({'width': '100%', 'background-color': '#4CAF50'});
    }
    if (pass_length == 0) {
        $('.pwd_progress2').addClass('hide');
        $('.pwd_strength_bar2').css({'width': '0%'});
    }
});


var validator = $("#change_password").validate({
    ignore: '.select2-search__field, #txt_status', // ignore hidden fields
    errorClass: 'validation-error-label',
    successClass: 'validation-valid-label',
    highlight: function (element, errorClass, validClass) {
        var elem = $(element);
        if (elem.hasClass("select2-offscreen")) {
            $("#s2id_" + elem.attr("id") + " ul").removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element);
        if (elem.hasClass("select2-offscreen")) {
            $("#s2id_" + elem.attr("id") + " ul").removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
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
        if ($(element).parent('div').hasClass('media-body')) {
            $(element).parent().find('.form_success_vert_icon').remove();
            $(element).remove();
        } else {
            $(element).parent().find('.form_success_vert_icon').remove();
            $(element).parent().append('<div class="form_success_vert_icon form-control-feedback"><i class="icon-checkmark-circle"></i></div>');
            $(element).remove();
        }
    },
    rules: {
        password: {minlength: 8},
        cpassword: {equalTo: '#password'},
    },
    submitHandler: function (form) {
        form.submit();
        $('.custom_save_button').prop('disabled', true);
    },
    invalidHandler: function () {
        $('.custom_save_button').prop('disabled', false);
    }
});

$(document).ready(function () {
    $("#billing-invoices-listing").dataTable({
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[0, "asc"]],
    });

    $(".upcoming-invoice-details").click(function () {
        var customer_id = $(this).attr('data-customerid');

        $('#custom_loading').removeClass('hide');
        $('#custom_loading').css('display', 'block');

        $.ajax({
            url: site_url + "subscription/upcoming/invoice",
            type: 'POST',
            data: {
                stripe_customer_id: customer_id
            },
            success: function (data) {
                $('#custom_loading').removeClass('hide');
                $('#custom_loading').css('display', 'none');

                $("#upcoming_invoice_detail_body").empty().html(data);
                $("#modal_upcoming_invoice_detail").modal('show');

            }
        });
    });
});

//-- confirm alert for asking about cancel subscription 
function confirm_alert(e, action) {
    swal({
        title: "Are you sure?",
        text: "Would you like to cancel subscription?",
        type: "error",
        showCancelButton: true,
        confirmButtonColor: "#f44336",
        confirmButtonText: "Yes, " + action + " it!"
    }, function (isConfirm) {
        if (isConfirm) {
            window.location.href = $(e).attr('href');
            return true;
        } else {
            return false;
        }
    });
    return false;
}