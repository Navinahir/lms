//-- This function is used to edit particular records
function cancel_click() {
    $('#custom_loading').addClass('hide');
    $('#custom_loading img').removeClass('hide');
    $('#model_form_row').css('z-index', '0');
    $('.bulk_upload_div').removeClass('hide');
    $('#txt_model_name').val('');
    $('#txt_model_id').val('');
    var $example = $('#txt_make_name').select2();
    $example.val('').trigger("change");
    $("#txt_model_name").rules("add", {
        remote: site_url + "admin/product/checkUnique_Model_Name/",
        messages: {
            remote: $.validator.format("This name already exist!")
        }
    });
    $('#txt_model_name').valid();
    $("#add_model_form").validate().resetForm();
    $('.form-control-feedback').remove();
    $('body').css('overflow', 'auto');
}

//-- This function is used to validate form
var validator = $("#add_setting_form").validate({
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
        STRIPE_PUBLISH_KEY: { required: true },
        STRIPE_SECRET_KEY: { required: true },
        STRIPE_PRODUCT_KEY: { required: true },
    },
    messages: {
    },
    submitHandler: function (form) {
        form.submit();
        $('.custom_save_button').prop('disabled', true);
    },
    invalidHandler: function () {
        $('.custom_save_button').prop('disabled', false);
    }
});

$('.stripe-mode').on('change', function () {
    $.ajax({
        url: site_url + 'admin/settings/get_stripe_data',
        type: "POST",
        data: { mode: $(this).val() },
        success: function (response) {
            response = JSON.parse(response);
            $('#STRIPE_PUBLISH_KEY').val(response.STRIPE_PUBLISH_KEY);
            $('#STRIPE_SECRET_KEY').val(response.STRIPE_SECRET_KEY);
            $('#STRIPE_PRODUCT_KEY').val(response.STRIPE_PRODUCT_KEY);
        }
    });
});