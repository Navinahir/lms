$(document).ready(function () {
    $(".touchspin-empty").TouchSpin({
        min: 0,
        max: 1000000000,
        step: 1,
        booster: true
    });
});

/**********************************************************
 Intitalize Data Table
 ***********************************************************/

$('.datatable-basic').dataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    // stateSave: true,
    language: {
        search: '<span>Filter:</span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
        emptyTable: 'No lead currently available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[1, "asc"]],
    ajax: site_url + 'admin/categories/get_categories',
    responsive: {
        details: {
            type: 'column',
            target: -1
        }
    },
    columns: [
		{
			data: "sr_no",
			visible: true,
			sortable: false,
		},
		{
			data: "name",
			visible: true,
		},
        {
            data: "action",
            render: function (data, type, full, meta) {
                action = '';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/categories/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/categories/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
                return action;
            },
            sortable: false,
        }
    ],
    "fnDrawCallback": function () {
        var info = document.querySelectorAll('.switchery-info');
        $(info).each(function () {
            var switchery = new Switchery(this, {color: '#95e0eb'});
        });

        $(".styled, .multiselect-container input").uniform({
            radioClass: 'choice'
        });
    }
});

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});
$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
if (user_type == 1) {
    var add_button = '<div class="text-right add_action_button"><a href="' + site_url + 'admin/categories/add" class="btn bg-teal-400 btn-labeled custom_add_button  mt-2"><b><i class="icon-plus-circle2"></i></b> Add Categories</a></div>';
    $('.datatable-header').append(add_button);
}
/**********************************************************
 Confirm Alert
 ***********************************************************/
function confirm_alert(e) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, delete it!"
    },
            function (isConfirm) {
                if (isConfirm) {
                    window.location.href = $(e).attr('href');
                    return true;
                } else {
                    return false;
                }
            });
    return false;
}

/**********************************************************
 Form Validation
 ***********************************************************/
var validator = $("#add_transponder_form").validate({
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
        txt_year_name: {required: true},
        txt_max_no_of_keys: {number: true, min: 0}
    },
    submitHandler: function (form) {
        form.submit();
        $('.custom_save_button').prop('disabled', true);
    },
    invalidHandler: function () {
        $('.custom_save_button').prop('disabled', false);
    }
});


$(document).on('keypress', '.tool_textarea', function () {
    if ($(this).hasClass("error") == true) {
        $(this).removeClass('error');
    }
});

$(document).on('click', '.add_modal', function () {
    var id = $(this).attr('id');
    if (id == 'add_make_modal') {
        $('#add_form_modal .modal-title').html('Add Make');
        $('#add_make_form_body').removeClass('hide');
        $('#add_model_form_body, #add_year_form_body').addClass('hide');
    } else if (id == 'add_model_modal') {
        $('#add_form_modal .modal-title').html('Add Model Name');
        $('#add_model_form_body').removeClass('hide');
        $('#add_make_form_body, #add_year_form_body').addClass('hide');
    } else if (id == 'add_year_modal') {
        $('#add_form_modal .modal-title').html('Add Year Name');
        $('#add_year_form_body').removeClass('hide');
        $('#add_model_form_body, #add_make_form_body').addClass('hide');
    }
    $('#add_form_modal').modal('show');
});

var validator_make = $("#add_make_form").validate({
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
        txt_modal_make_name: {
            required: true, 
            maxlength: 255, 
            remote: remoteURL,
            normalizer: function (value) {
                return $.trim(value);
            },
        },
        // 'tool_details[]': { required: true },
    },
    messages: {
        txt_modal_make_name: {
            remote: $.validator.format("This name already exist!")
        }
    },
    submitHandler: function (form) {
        var txt_modal_make_name = $('#txt_modal_make_name').val();
        $.ajax({
            url: site_url + 'admin/product/add_make_data_ajax',
            dataType: "json",
            type: "POST",
            data: {txt_modal_make_name: txt_modal_make_name},
            success: function (response) {
                if (response.status == 'success') {
                    $('#add_form_modal').modal('hide');
                    var options = "";
                    options = '<option value=' + response.id + '>' + response.name + '</option>';
                    $('#txt_make_name').append(options);
                    $('#txt_make_name').removeAttr('disabled');
                    $('#txt_make_name .no_makes').remove();
                    $("#txt_make_name").select2("destroy").select2();
                    $("#txt_modal_make_name").val('');
                    $('#btn_submit_make_data').removeAttr('disabled');
                    $('#txt_modal_make_name2').append(options);
                    $("#txt_modal_make_name2").select2("destroy").select2();
                }
            }
        });
        $('#btn_submit_make_data').prop('disabled', true);
    },
    invalidHandler: function () {
        $('#btn_submit_make_data').prop('disabled', false);
    }
});

var validator_model = $("#add_model_form").validate({
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
        txt_modal_model_name: {
            required: true, 
            maxlength: 255,
            remote: remoteURL_model,
            normalizer: function (value) {
                return $.trim(value);
            },
        },
        txt_modal_make_name2: {
            required: true,
            normalizer: function (value) {
                return $.trim(value);
            },
        }
    },
    messages: {
        txt_modal_model_name: {
            remote: $.validator.format("This name already exist!")
        }
    },
    submitHandler: function (form) {
        var txt_modal_model_name = $('#txt_modal_model_name').val();
        var txt_modal_make_name = $('#txt_modal_make_name2').val();
        $.ajax({
            url: site_url + 'admin/product/add_model_data_ajax',
            dataType: "json",
            type: "POST",
            data: {txt_modal_model_name: txt_modal_model_name, txt_modal_make_name: txt_modal_make_name},
            success: function (response) {
                if (response.status == 'success') {
                    $('#add_form_modal').modal('hide');
                    var options = "";
                    options = '<option value=' + response.id + '>' + response.name + '</option>';
                    $('#txt_model_name').append(options);
                    $('#txt_model_name').removeAttr('disabled');
                    $('#txt_model_name .no_makes').remove();
                    $("#txt_model_name").select2("destroy").select2();
                    $('#btn_submit_model_data').removeAttr('disabled');
                    $('#txt_modal_model_name').val('');
                    $("#txt_modal_make_name2").val(null).trigger("change"); 
                }
            }
        });
        $('#btn_submit_model_data').prop('disabled', true);
    },
    invalidHandler: function () {
        $('#btn_submit_model_data').prop('disabled', false);
    }
});

$(document).on('click', '.transponder_view_btn', function () {
    $.ajax({
        url: site_url + 'admin/product/view_transponder_ajax',
        type: "POST",
        data: {id: this.id},
        success: function (response) {
            $('#transponder_view_body').html(response);
            $('#transponder_view_modal').modal('show');
        }
    });
});

$('#txt_make_name').change(function () {
    var selected_val = $(this).val();
    $.ajax({
        url: site_url + 'admin/product/change_make_get_ajax',
        dataType: "json",
        type: "POST",
        data: {id: selected_val},
        success: function (response) {
            $('#txt_model_name').html(response);
            //$('#txt_model_name').select2({containerCssClass : 'select-sm'});
        }
    });
});

$(document).on('click', '.btn_add_extra_field', function () {
    var $nonempty = $('.additional_field_txt').filter(function () {
        return this.value != '';
    });
    if ($nonempty.length != 0) {
        $(this).parents('td').html('<a href="javascript:void(0)" style="color:#d66464"><i class="icon-minus-circle2 btn_remove_extra_field"></i></a>');
        $('#tbl_additional_data tbody').append('<tr><td><a href="javascript:void(0)" style="color:#009688"><i class="icon-plus-circle2 btn_add_extra_field"></i></a></td><td><input type="text" class="form-control additional_field_txt" name="txt_field_name[]" placeholder="Field Name"></td><td><input type="text" class="form-control additional_value_txt" name="txt_field_value[]" placeholder="Value"></td></tr>');
    } else {
        $('#additional_data_error2').css('padding-left', '45px');
        $('#additional_data_error2').html('Please Fill Existing Field.');
    }
});

$(document).on('click', '.btn_remove_extra_field', function () {
    $(this).parents('tr').remove();
});
