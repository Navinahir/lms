//-- This function is used to display list of records in datatable
$(function () {
    $('.datatable-basic').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' },
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[1, "asc"]],
        ajax: site_url + 'admin/equipments/get_equipment_name',
        columns: [
            {
                data: "sr_no",
                sortable: false,
            },
            {
                data: "man_name",
            },
            {
                data: "type_name",
            },
            {
                data: "name_description",
            },
            {
                data: "modified_date",
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    action = '';
                    action += '<a id="edit_' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs edit" title="Edit">Edit</a>';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/equipments/name/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
                    return action;
                },
                sortable: false,
            }
        ]
    });

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
    $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
});

//-- This function is used to edit particular records
$(document).on('click', '.edit', function () {
    var id = $(this).attr('id').replace('edit_', '');
    var url = base_url + 'admin/equipments/get_equipment_name_by_id';
    $('#custom_loading').removeClass('hide');
    $('#custom_loading img').addClass('hide');
    $.ajax({
        type: 'POST',
        url: url,
        async: false,
        dataType: 'JSON',
        data: { id: id },
        success: function (data) {
            setTimeout(function () {
                $("#manufacturer_id").val(data.manufacturer_id).trigger('change');
                $("#manufacturer_id").select2("destroy").select2();
                $('#equipment_type_id').val(data.equipment_type_id).trigger('change');
                $("#equipment_type_id").select2("destroy").select2();
                $('#txt_description').val(data.description);
                // CKEDITOR.instances['txt_description'].setData(data.description);
                $('#txt_name_id').val(data.id);
                // $('html, body').animate({ scrollTop: 0 }, 500);
                // $('body').css('overflow', 'hidden');
            }, 500);
        }
    });
});


function cancel_click() {
    $('#custom_loading').addClass('hide');
    $('#custom_loading img').removeClass('hide');
    $('#names_form_row').css('z-index', '0');
    $('.bulk_upload_div').removeClass('hide');
    $('#equipment_type_id').select2('destroy').val('').select2();
    $('#manufacturer_id').select2('destroy').val('').select2();
    $('#txt_description').val('');
    $("#add_name_form").validate().resetForm();
    $('.form-control-feedback').remove();
}

//-- This function is used to delete particular record
function confirm_alert(e) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this type!",
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

//-- This function is used to validate form
var validator = $("#add_name_form").validate({
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
        txt_description: {
            required: true,
            normalizer: function (value) {
                return $.trim(value);
            },

        }
    },
    submitHandler: function (form) {
        form.submit();
        $('.custom_save_button').prop('disabled', true);
    },
    invalidHandler: function () {
        $('.custom_save_button').prop('disabled', false);
    }
});

$(document).on('click', '.add_modal', function () {
    var id = $(this).attr('id');
    if (id == 'add_manu_modal') {
        $('#add_form_modal .modal-title').html('Add Manufaturer');
        $('#add_type_form').addClass('hide');
        $('#add_manu_form').removeClass('hide');
    } else if (id == 'add_type_modal') {
        $('#add_form_modal .modal-title').html('Add Equipement Type');
        $('#add_type_form').removeClass('hide');
        $('#add_manu_form').addClass('hide');
    }
    $('#add_form_body').removeClass('hide');
    $('#add_form_modal').modal('show');
});

var validator = $("#add_manu_form").validate({
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
        txt_name: {
            required: true,
            maxlength: 150,
            remote: site_url + "admin/equipments/checkUnique_Manufacturer_Name",
            normalizer: function (value) {
                return $.trim(value);
            }
        },
        txt_desc: {
            required: true,
            normalizer: function (value) {
                return $.trim(value);
            }
        },

    },
    messages: {
        txt_name: {
            remote: $.validator.format("This name already exist!")
        }
    },
    submitHandler: function (form) {
        var txt_name = $('#txt_name').val();
        var txt_description = $('#txt_desc').val();
        $.ajax({
            url: site_url + 'admin/equipments/add_manufacturer_data_ajax',
            dataType: "json",
            type: "POST",
            data: { txt_name: txt_name, txt_description: txt_description },
            success: function (response) {
                if (response.status == 'success') {
                    $('#add_form_modal').modal('hide');
                    var options = '<option value=' + response.id + '>' + response.name + '</option>';
                    $('#manufacturer_id').append(options);
                    $("#manufacturer_id").val(response.id).trigger('change');
                    $("#manufacturer_id").select2("destroy").select2();
                    $("#txt_desc").val('');
                    $("#txt_name").val('');
                    $('#btn_submit').prop('disabled', false);
                }
            }
        });
        $('#btn_submit').prop('disabled', true);
    },
    invalidHandler: function () {
        $('#btn_submit').prop('disabled', false);
    }
});

var validator = $("#add_type_form").validate({
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
        txt_type_name: {
            required: true,
            maxlength: 150,
            remote: site_url + "admin/equipments/checkUnique_Type_Name",
            normalizer: function (value) {
                return $.trim(value);
            }
        },
        txt_type_desc: {
            required: true,
            normalizer: function (value) {
                return $.trim(value);
            }
        },
    },
    messages: {
        txt_type_name: {
            remote: $.validator.format("This name already exist!")
        }
    },
    submitHandler: function (form) {
        var txt_name = $('#txt_type_name').val();
        var txt_description = $('#txt_type_desc').val();
        $.ajax({
            url: site_url + 'admin/equipments/add_type_data_ajax',
            dataType: "json",
            type: "POST",
            data: { txt_name: txt_name, txt_description: txt_description },
            success: function (response) {
                if (response.status == 'success') {
                    $('#add_form_modal').modal('hide');
                    var options = '<option value=' + response.id + '>' + response.name + '</option>';
                    $('#equipment_type_id').append(options);
                    $("#equipment_type_id").val(response.id).trigger('change');
                    $("#equipment_type_id").select2("destroy").select2();
                    $("#txt_type_desc").val('');
                    $("#txt_type_name").val('');
                    $('#btn_type_submit').prop('disabled', false);
                }
            }
        });
        $('#btn_type_submit').prop('disabled', true);
    },
    invalidHandler: function () {
        $('#btn_type_submit').prop('disabled', false);
    }
});

$('.type_cancel').on('click', function () {
    $("#txt_type_desc").val('');
    $("#txt_type_name").val('');
    $('#btn_type_submit').prop('disabled', false);
});
$('.name_cancel').on('click', function () {
    $("#txt_desc").val('');
    $("#txt_name").val('');
    $('#btn_submit').prop('disabled', false);
});
