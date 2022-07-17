$('#txt_make_name').change(function () {
    var selected_val = $(this).val();
    $.ajax({
        url: site_url + 'vendor/home/change_make_get_ajax',
        dataType: "json",
        type: "POST",
        data: {id: selected_val},
        success: function (response) {
            $('#txt_model_name').html(response);
            $('#txt_model_name').select2({containerCssClass: 'select-sm'});
        }
    });
});

// $('#btn_search').on('click',function(){
//     var make_name = $('#txt_make_name').val();
//     var model_name = $('#txt_model_name').val();
//     var year_name = $('#txt_year_name').val();
//     $.ajax({
//         url: site_url + 'admin/dashboard/get_transponder_details',
//         dataType: "json",
//         type: "POST",
//         data: {_make_id: make_name, _model_id:model_name, _year_id:year_name},
//         success: function (response) {
//             $('#div_transponder_result').removeClass('hide');
//             $('#tbl_dashboard_trans').html(response);
//         }
//     });
// });

var validator = $("#search_transponder_form").validate({
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
        txt_make_name: {required: true},
        txt_model_name: {required: true},
        txt_year_name: {required: true}
    },
    submitHandler: function (form) {
        $('#custom_loading').removeClass('hide');
        $('#custom_loading').css('display', 'block');
        var make_name = $('#txt_make_name').val();
        var model_name = $('#txt_model_name').val();
        var year_name = $('#txt_year_name').val();
        $.ajax({
            url: site_url + 'vendor/home/get_transponder_details',
            dataType: "json",
            type: "POST",
            data: {_make_id: make_name, _model_id: model_name, _year_id: year_name},
            success: function (response) {
                if (response.table_body2 == '') {
                    $('#div_transponder_result, #div_list_of_parts').removeClass('hide');
                    $('.no_data_found').removeClass('hide');
                    $('.found_data').addClass('hide');
                    $('.div_part_list').html(response.div_part_list);
                    $('#div_tool_list').removeClass('hide').html(response.div_tool_list);
                    $('#no_data_found').html(response.table_body);
                } else {
                    $('#div_transponder_result, #div_list_of_parts').removeClass('hide');
                    $('.no_data_found').addClass('hide');
                    $('.found_data').removeClass('hide');
                    $('#tbl_dashboard_trans').html(response.table_body);
                    $('#tbl_dashboard_trans_2').html(response.table_body2);
                    $('.div_part_list').html(response.div_part_list);
                    $('#div_tool_list').removeClass('hide').html(response.div_tool_list);
                }
                $("#custom_loading").fadeOut(2500);
            }
        });
    },
    invalidHandler: function () {
        $('.custom_save_button').prop('disabled', false);
    }
});

$(document).on('click', '#btn_reset', function () {
    $('#txt_make_name').val('').select2('');
    $('#txt_model_name').val('').select2('');
    $('#txt_year_name').val('').select2('');
    $("#search_transponder_form").validate().resetForm();
    $('.form-control-feedback').remove();
    $('#div_transponder_result').addClass('hide');
    $('#div_list_of_parts').addClass('hide');
    $('#div_tool_list').addClass('hide');
});

$(document).on('click', '.btn_home_item_view', function () {
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    $.ajax({
        url: site_url + 'vendor/home/get_item_data_ajax_by_id',
        type: "POST",
        data: {id: this.id},
        success: function (response) {
            $('#dash_view_body1').html(response);
            $('#dash_view_modal1').modal('show');
            $("#custom_loading").fadeOut(2000);
        }
    });
});


var validator = $("#change_password").validate({
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

var part_validator = $("#searchPartDetailsForm").validate({
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
        txt_part_no: { required: true, },
    },
    submitHandler: function (form) {
        form.submit();
        $('.bg-teal').prop('disabled', true);
        // $('form input[type=submit]').html('Saving..');
    },
    invalidHandler: function () {
        $('.bg-teal').prop('disabled', false);
    }
});

// Reset validation
$(".btn-default").click(function() {
    $("#searchPartDetailsForm").validate().resetForm(); 
});

// Remove validation from dropdown change
$('select').on('change', function () {
    $(this).valid();
});

$(document).submit("#searchPartDetailsForm", function (e) {
    e.preventDefault();

    var part_no = $("input[name=txt_part_no]").val();
    var part_description = $("input[name=txt_part_description]").val();

    if (part_no != '' || part_description != '') {
        $('#custom_loading').removeClass('hide');
        $('#custom_loading').css('display', 'block');

        var data = {
            part_no: part_no,
            part_description: part_description
        }

        var url = site_url + 'vendor/home/getPartDetails';

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'HTML',
            success: function (data) {
                $('#div_transponder_result, #div_list_of_parts').removeClass('hide');
                $(".div_part_list").empty().append(data);
                $("#custom_loading").fadeOut(2500);
            }
        });
    }
});

$('#txt_model_name').on('change', function () {
    var make_id = $("#txt_make_name").find('option:selected').val();
    var model_id = $(this).find('option:selected').val();

    var data = {
        make_id: make_id,
        model_id: model_id
    };

    $.ajax({
        url: site_url + 'vendor/products/get_transponder_item_years',
        dataType: "json",
        type: "POST",
        data: data,
        success: function (response) {
            $('#txt_year_name').html(response);
            $('#txt_year_name').select2({containerCssClass: 'select-sm'});
        }
    });
});

$(document).on('click', '.view-part-image', function () {
    var item_id = $(this).attr('data-id');

    $.ajax({
        url: site_url + 'vendor/home/get_item_image',
        type: 'POST',
        data: {item_id: item_id},
        success: function (data) {
            data = jQuery.parseJSON(data);

            swal({
                title: '',
                imageUrl: data.image_path,
                imageWidth: 400,
                imageHeight: 400,
                imageAlt: 'Custom image',
                animation: true
            });
        }
    });
});