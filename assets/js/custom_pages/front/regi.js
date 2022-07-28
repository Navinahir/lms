$(document).ready(function () {
    Stripe.setPublishableKey(STRIPE_PUBLISH_KEY);

    //callback to handle the response from stripe
    function stripeResponseHandler(status, response) {
        if (response.error) {
            //enable the submit button
            $('#payBtn').removeAttr("disabled");
            //display the errors on the form
            // $('#payment-errors').attr('hidden', 'false');
            $('#payment-errors').addClass('alert alert-danger');
            $("#payment-errors").html(response.error.message);
            $('html, body').animate({
                scrollTop: $("#payment-errors").offset().top
            }, 2000);
            $('.btn_login').prop('disabled', false);
            return false;
        } else {
            var form$ = $("#User_Register_Form");
            //get token id
            var token = response['id'];
            var str = response.card.brand;
            str = str.replace(/\s+/g, '');
            $('card_div').removeClass('active');
            $('#' + str).addClass('active');
            // var card_type = $("input[name='card_type']:checked").val();
            if (str.match(/Visa/g) || str.match(/MasterCard/g) || str.match(/Discover/g) || str.match(/AmericanExpress/g)) {
                form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                form$.append("<input type='hidden' name='card_type' value='" + str + "' />");
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
    //on form submit
    // $("#User_Register_Form").submit(function (event) {
    //     //disable the submit button to prevent repeated clicks
    //     event.preventDefault();
    //     $('#btn_login').attr("disabled", "disabled");

    //     //create single-use token to charge the user

    //     //submit from callback
    //     return false;
    // });

    function check_stipe() {
        var card = Stripe.card.validateCardNumber($('#card_num').val());
        var card_expiry = Stripe.card.validateExpiry($('#card-expiry-month').val(), $('#card-expiry-year').val());
        var cvc = Stripe.card.validateCVC($('#card-cvc').val());
        var type = Stripe.card.cardType($('#card_num').val());
        if (card == true && card_expiry == true && cvc == true && type !== '') {
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
    
    $("#User_Register_Form").validate({
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
                remote: remoteURL,
                stricemailonly: true,
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
            // card_type: { required: true },
            credit_card: {
                required: true,
                minlength: 18,
                maxlength: 19,
            },
            exp_month: {required: true, min: 1, max: 12},
            exp_year: {required: true, min: (new Date()).getFullYear()},
            v_code: {required: true},
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
            promotion_code: {
                remote: codeURL
            },
        },
        messages: {
            email_id: {remote: $.validator.format("This Email already exist!")},
            promotion_code: {remote: $.validator.format("This Code is not valid!")},
            contact_number: 'Please enter valid Phone number!',
            billing_phone: 'Please enter valid Phone number!',
            credit_card: 'Please enter valid Card number!'
        },
        submitHandler: function (form) {
            // form.submit();
            if ($('#card-expiry-year').val() >= (new Date()).getFullYear() && $('#card-expiry-month').val() >= (new Date).getMonth()) {
                check_stipe();
                $('.btn_login').prop('disabled', true);
            } else {
                $('#payment-errors').addClass('alert alert-danger');
                $("#payment-errors").html('Please check payment details!!');
                $('.btn_login').prop('disabled', false);
                $('html, body').animate({
                    scrollTop: $("#registration_containers").offset().top
                }, 2000);
                return false;
            }
        },
        invalidHandler: function () {
            $('.btn_login').prop('disabled', false);
        }
    });
    
    jQuery.validator.addMethod("stricemailonly", function(value, element) {
      return this.optional(element) || /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i.test(value);
    }, "Invalid Email Format.");

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
        pattern: '{{9999}}'
    });

    $(document).on("input", ".format-phone-number,#card_num,#card-expiry-month,#card-expiry-year,#card-cvc", function() {
        this.value = this.value.replace(/\D/g,'');
    });

    $('.packages_section').on('click', function () {
        $('.packages_section').removeClass('package_active');
        var id = $(this).attr('id');
        $('#package_id').val($(this).attr('data-id'));
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
})
