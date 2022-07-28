$('#txt_make_name').change(function () {
    var selected_val = $(this).val();
    $.ajax({
        url: site_url + 'dashboard/change_make_get_ajax',
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
        setTimeout(function () {
            trans_details(make_name, model_name, year_name);
        }, 200);
    },
    invalidHandler: function () {
        $('.custom_save_button').prop('disabled', false);
    }
});

// remove validation on derodown change
$(document).on('change','select',function(){
    $(this).valid();
});

$(function () {
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    setTimeout(function () {
        var querystring = window.location.search;
        if (querystring, querystring.length > 0) {
            var make_name = $('#txt_make_name').val();
            var model_name = $('#txt_model_name').val();
            var year_name = $('#txt_year_name').val();
            trans_details(make_name, model_name, year_name);
        }
    }, 200);
})

$(document).on('click', '#btn_reset', function () {
    $('#txt_make_name').val('').select2('');
    $('#txt_model_name').val('').select2('');
    $('#txt_year_name').val('').select2('');
    //$("#search_transponder_form").validate().resetForm();
    $('.form-control-feedback').remove();
    $('#div_transponder_result').addClass('hide');
    $('#div_list_of_parts').addClass('hide');
    $('#div_tool_list').addClass('hide');
    $('.div_recent_search').removeClass('hide');
});

$(document).on('click', '.btn_home_item_view', function () {
    var make_id = $('#txt_make_name').val();
    var model_id = $('#txt_model_name').val();
    var year_id = $('#txt_year_name').val();
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    $.ajax({
        url: site_url + 'items/get_item_data_ajax_by_id',
        type: "POST",
        data: {id: this.id, make_id: make_id, model_id: model_id, year_id: year_id},
        success: function (response) {
            $('#dash_view_body1').html(response);
            $('#dash_view_modal1').modal('show');
            $("#custom_loading").fadeOut(1000);
        }
    });
});
$(document).on('click', '.btn_non_global_item_view', function () {
    var make_id = $('#txt_make_name').val();
    var model_id = $('#txt_model_name').val();
    var year_id = $('#txt_year_name').val();
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    $.ajax({
        url: site_url + 'items/get_item_data_ajax_by_id',
        type: "POST",
        data: {id: this.id, make_id: make_id, model_id: model_id, year_id: year_id, part_type: 'non_global'},
        success: function (response) {
            $('#dash_view_body1').html(response);
            $('#dash_view_modal1').modal('show');
            $("#custom_loading").fadeOut(1000);
        }
    });
});
$(document).on('click', '.btn_global_item_view', function () {
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    $.ajax({
        url: site_url + 'items/get_global_item_data_ajax_by_id',
        type: "POST",
        data: {id: this.id},
        success: function (response) {
            $('#dash_view_body1').html(response);
            $('#dash_view_modal1').modal('show');
            $("#custom_loading").fadeOut(1000);
        }
    });
});

$(document).on('click', '.btn_global_item_view_compatibility', function () {
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    $.ajax({
        url: site_url + 'items/get_global_item_data_compatibility_ajax_by_id',
        type: "POST",
        data: {id: this.id},
        success: function (response) {
            $('#dash_view_body1').html(response);
            $('#dash_view_modal1').modal('show');
            $("#custom_loading").fadeOut(1000);
        }
    });
});

$(document).on('click', '.btn_view', function () {
    var id = $(this).attr('id');
    var make_id = $('#make_id_' + id).data('id');
    var model_id = $('#model_id_' + id).data('id');
    var year_id = $('#year_id_' + id).data('id');
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    setTimeout(function () {
        trans_details(make_id, model_id, year_id, 1);
    }, 200);
});

function trans_details(make_id, model_id, year_id, view) {
    $.ajax({
        url: site_url + 'dashboard/get_transponder_details',
        dataType: "json",
        type: "POST",
        data: {_make_id: make_id, _model_id: model_id, _year_id: year_id},
        success: function (response) {
            if (view == 1) {
                $('#txt_make_name').val(make_id).trigger('change');
                setTimeout(function () {
                    $('#txt_model_name').val(model_id).trigger('change');
                }, 2000);
                setTimeout(function () {
                    $('#txt_year_name').val(year_id).trigger('change');
                    $('#txt_year_name').select2().trigger('change');
                }, 3500)
            }
    
            if (response.table_body2 == '') {
                $('#div_transponder_result, #div_list_of_parts, #div_my_list_of_parts').removeClass('hide');
                $('.no_data_found').removeClass('hide');
                $('.found_data').addClass('hide');

                $('.div_part_list').html(response);
                
                $('.div_part_list').html(response.div_part_list);
                $('.div_my_part_list').html(response.div_my_part_list);
                $('#div_tool_list').removeClass('hide').html(response.div_tool_list);
                $('#no_data_found').html(response.table_body);
                $('.div_recent_search').removeClass('hide');
            } else {
                $('#div_transponder_result, #div_list_of_parts , #div_my_list_of_parts').removeClass('hide');
                $('.no_data_found').addClass('hide');
                $('.found_data').removeClass('hide');
                $('#tbl_dashboard_trans').html(response.table_body);
                $('#tbl_dashboard_trans_2').html(response.table_body2);
                
                $('.div_part_list').html(response);

                $('.div_part_list').html(response.div_part_list);                
                $('.div_my_part_list').html(response.div_my_part_list);
                $('#div_tool_list').removeClass('hide').html(response.div_tool_list);
                $('.div_recent_search').addClass('hide');
            }
            $("#custom_loading").fadeOut(1000);
        }
    });
}

var validator = $("#search_all").validate({
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
        search_type: {required: true},
        q: {
            required: true, normalizer: function (value) {
                return $.trim(value);
            }
        },
    },
    submitHandler: function (form) {
        $('#custom_loading').removeClass('hide');
        $('#custom_loading').css('display', 'block');
        $("#custom_loading").fadeOut(1000);
        $('.custom_search_button').prop('disabled', true);
        form.submit();
    },
    invalidHandler: function () {
        $('.custom_search_button').prop('disabled', false);
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
        url: site_url + 'dashboard/get_transponder_item_years',
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
    var part_type = $(this).attr('data-parttype');

    $.ajax({
        url: site_url + 'dashboard/get_item_image',
        type: 'POST',
        data: {item_id: item_id, part_type: part_type},
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