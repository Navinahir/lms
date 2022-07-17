$(document).ready(function () {
    $("#add_setting_form").validate({
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
            vendor_id: { required: true },
        },
        messages: {
        },
        submitHandler: function (form) {
            form.submit();
            $('.btn_login').prop('disabled', true);
        },
        invalidHandler: function () {
            $('.btn_login').prop('disabled', false);
        }
    });

    if (typeof (view_setting) != 'undefined' && view_setting == 1) {
        $('#setting_content').addClass('disabled_div');
        $('.save_btn_div').addClass('hide');
    }

    $(function () {
        $('.wysihtml5-default').wysihtml5({
            parserRules: wysihtml5ParserRules
        });
    });


    $(document).on('change','#label_size',function(){
        var height_width = $('#label_size option:selected').val();
        var height = height_width[0]*110;
        var width = height_width[2]*110;
        $(".blankbox").height(height);
        $(".blankbox").width(width);
    });

    $(function(){
        var height_width = $('#label_size option:selected').val();
        var height = height_width[0]*110;
        var width = height_width[2]*110;
        $(".blankbox").height(height);
        $(".blankbox").width(width);
    });
  
    // $(".blankbox").width(200).height(200);

});