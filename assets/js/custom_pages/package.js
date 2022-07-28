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
        ajax: site_url + 'admin/product/get_package',
        responsive: {
            details: {
                type: 'column',
                target: -1
            }
        },
        columns: [
            {
                data: "sr_no",
                sortable: false,
            },
            {
                data: "name",
                visible: true,
                render: function (data, type, full, meta) {
                    var months = (full.months == 1) ? ' Month' : ' Months';
                    result = '<b>Name : </b>' + full.name;
                    result += '<br><span class="text-size-large text-uppercase text-teal"><b>$' + full.price + '</b>/' + full.months + months + '</span>';
                    result += '<br><b>No. of Users : </b>' + full.no_of_users;
                    result += '<br><b>No. of Locations : </b>' + full.no_of_locations;
                    if(full.quickbook_status == '1')
                    {
                        result += '<br><b>QuickBooks Status : </b> Yes';
                    } else {
                        result += '<br><b>QuickBooks Status : </b> No';
                    }
                    return result;
                }
            },
            {
                data: "modified_date",
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    action = '';
                    action += '<a id="edit_' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs edit" title="Edit">Edit</a>';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/package/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/package/view/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="View">View</a>';
                    return action;
                },
                sortable: false,
            },
            {
                data: 'responsive',
                className: 'control',
                orderable: false,
                targets: -1,
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
    var url = base_url + 'admin/product/get_package_by_id';
    $('#custom_loading').removeClass('hide');
    $('#custom_loading img').addClass('hide');
    $('#make_form_row').css('z-index', '999999');
    $('.bulk_upload_div').addClass('hide');
    $.ajax({
        type: 'POST',
        url: url,
        async: false,
        dataType: 'JSON',
        data: { id: id },
        success: function (data) {
            $('#txt_name').val(data.name);
            $('#txt_package_id').val(data.id);
            $('#txt_price').val(data.price);
            $('#txt_users').val(data.no_of_users);
            $('#txt_locations').val(data.no_of_locations);
            $('#quickbook_status').val(data.quickbook_status).change();
            $('#quickbook_status option[value=' + data.quickbook_status + ']').attr('selected', 'selected');
            // $('#txt_months').val(data.months).change();
            // $('#txt_months option[value=' + data.months + ']').attr('selected', 'selected');
            // $('#txt_name').focus();
            $("#txt_name").rules("add", {
                remote: site_url + "admin/product/checkUnique_Package_Name/" + data.id,
                messages: {
                    remote: $.validator.format("This name already exist!")
                }
            });
            CKEDITOR.instances['txt_description'].setData(data.description);
            $("#add_package_form").validate().resetForm();
        }
    });
});


function cancel_click() {
    $('#custom_loading').addClass('hide');
    $('#custom_loading img').removeClass('hide');
    $('#make_form_row').css('z-index', '0');
    $('.bulk_upload_div').removeClass('hide');
    $('#txt_name').val('');
    $("#txt_description").rules("add", {
        remote: site_url + "admin/product/checkUnique_Make_Name/",
        messages: {
            remote: $.validator.format("This name already exist!")
        }
    });
    $('#txt_name').valid();
    $("#add_package_form").validate().resetForm();
    $('.form-control-feedback').remove();
    $('body').css('overflow', 'auto');
}

//-- This function is used to delete particular record
function confirm_alert(e) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this Package!",
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
var validator = $("#add_package_form").validate({
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
        txt_name: {
            required: true,
            maxlength: 150,
            remote: remoteURL
        },
    },
    messages: {
        txt_name: {
            remote: $.validator.format("This name already exist!")
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

$(function () {
    CKEDITOR.replace('txt_description', {
        height: '400px',
        extraPlugins: 'forms'
    });
});