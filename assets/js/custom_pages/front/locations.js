$('.datatable-basic').dataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    language: {
        search: '<span>Filter:</span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' },
        emptyTable: 'No data currently available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[0, "asc"]],
    ajax: site_url + 'locations/get_ajax_data',
    columns: [
        {
            data: "sr_no",
            visible: true,
            sortable: false
        },
        {
            data: "name",
            visible: true,
        },
        {
            data: "description",
            visible: true,
        },
        {
            data: "is_active",
            visible: true,
            render: function (data, type, full, meta) {
                if (full.is_active == 1) {
                    action = '<span class="label bg-success-400">Active</span>';
                } else {
                    action = '<span class="label bg-danger-400">Blocked</span>';
                }
                return action;
            }
        },
        {
            data: "modified_date",
            visible: true,
        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                action = '';
                if (full.is_active == 1) {
                    if (edit == 1) {
                        action += '<a id="edit_' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs edit" title="Edit">Edit</a>';
                    }
                    // if (full.is_default == 0) {
                    //     if (dlt == 1) {
                    //         action += '&nbsp;&nbsp;<a href="' + site_url + 'locations/action/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this,\'Delete\')" title="Delete">Delete</a>';
                    //     }
                    // }
                    if (view == 1) {
                        action += '&nbsp;&nbsp;<a href="' + site_url + 'locations/view/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="View">View</a>';
                    }
                }
                return action;
            },
            sortable: false,
        },
    ],
    "fnDrawCallback": function () {
        var info = document.querySelectorAll('.switchery-info');
        $(info).each(function () {
            var switchery = new Switchery(this, { color: '#95e0eb' });
        });
    }
});

if (typeof (location_id) !== 'undefined') {
    $('.datatable-responsive-control-right').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' },
            emptyTable: 'No data currently available.'
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[0, "asc"]],
        ajax: site_url + 'locations/get_loc_item_ajax_data/' + location_id,
        fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if (aData['quantity'] < 1) {
                $('td', nRow).css('background-color', 'rgba(51, 169, 245, 0.08)');
            }
        },
        columns: [
            {
                data: "sr_no",
                visible: true,
                sortable: false
            },
            {
                data: "global_part_no",
                visible: true,
            },
            {
                data: "part_no",
                visible: true,
            },
            {
                data: "pref_vendor_name",
                visible: true,
            },
            {
                data: "quantity",
                visible: true,
            },
            {
                data: "modified_date",
                visible: true,
            },
            {
                data: "is_deleted",
                visible: false,
            }
        ],
        "fnDrawCallback": function () {
            var info = document.querySelectorAll('.switchery-info');
            $(info).each(function () {
                var switchery = new Switchery(this, { color: '#95e0eb' });
            });
        }
    });
}

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});
$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');

//-- Sweet Alert Delete Popup
function confirm_alert(e, action) {
    swal({
        title: "Are you sure?",
        text: "You would like to " + action + " this Location!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, " + action + " it!"
    },
        function (isConfirm) {
            if (isConfirm) {
                window.location.href = $(e).attr('href');
                return true;
            }
            else {
                return false;
            }
        });
    return false;
}

var validator = $("#add_location_form").validate({
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
        txt_location_name: {
            required: true, remote: remoteURL, normalizer: function (value) {
                return $.trim(value);
            }
        },
        // txt_quantity: { required: true },
    },
    messages: {
        txt_location_name: { remote: $.validator.format("This Location already exist!") },

    },
    submitHandler: function (form) {
        form.submit();
        $('.custom_save_button').prop('disabled', true);
        // $('form input[type=submit]').html('Saving..');
    },
    invalidHandler: function () {
        $('.custom_save_button').prop('disabled', false);
    }
});

$(document).on('click', '.edit', function () {
    var id = $(this).attr('id').replace('edit_', '');
    var url = base_url + 'locations/get_location_by_id';
    $('#custom_loading').removeClass('hide');
    $('#custom_loading img').addClass('hide');
    $.ajax({
        type: 'POST',
        url: url,
        async: false,
        dataType: 'JSON',
        data: { id: id },
        success: function (data) {
            $('#add_location_form').removeClass('disabled_div')
            $('#txt_location_name').val(data.name);
            // $('#txt_quantity').val(data.quantity);
            $('#txt_description').val(data.description);
            $('#txt_location_name').focus();
            $('#txt_location_id').val(data.id);
            $("#txt_location_name").rules("add", {
                remote: site_url + "locations/checkUniqueName/" + data.id,
                messages: {
                    remote: $.validator.format("This location already exist!")
                }
            });
            $('#add_location_form').attr('action', site_url + "locations/edit/" + btoa(data.id));
            $("#add_location_form").validate().resetForm();
        }
    });
});
if (add == 0) {
    $('#add_location_form').addClass('disabled_div');
}

function cancel_click() {
    $('#txt_location_name').val('');
    $('#txt_description').val('');
    $('#txt_location_id').val('');
    $("#txt_location_name").rules("add", {
        remote: site_url + "locations/checkUniqueName",
        messages: {
            remote: $.validator.format("This location already exist!")
        }
    });
    $('#txt_location_name').valid();
    $("#add_location_form").validate().resetForm();
    $('#add_location_form').attr('action', site_url + "locations/add");
    setTimeout(function () {
        $('.form-control-feedback').remove();
    }, 10);
}
