$(document).ready(function () {
    $("#add_user_form").validate({
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
            user_role: { required: true },
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
                remote: remoteURL, normalizer: function (value) {
                    return $.trim(value);
                }
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
        },
        messages: {
            contact_number: 'Please enter valid phone number.',
            email_id: { remote: $.validator.format("This Email already exist!") },
        },
        submitHandler: function (form) {
            form.submit();
            $('.btn_login').prop('disabled', true);
        },
        invalidHandler: function () {
            $('.btn_login').prop('disabled', false);
        }
    });

    $('.format-phone-number').formatter({
        pattern: '({{999}}) {{999}} - {{9999}}'
    });

    if (user_role != '') {
        $('#user_role').val(user_role).change();
    }
});