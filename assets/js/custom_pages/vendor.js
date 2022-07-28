/**********************************************************
 Intitalize Data Table
 ***********************************************************/
$('.datatable-responsive-control-right').dataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    language: {
        search: '<span>Filter:</span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
        emptyTable: 'No data currently available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[0, "desc"]],
    ajax: site_url + 'admin/inventory/get_vendors_data',
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
            sortable: false
        },
        {
            data: "is_active",
            visible: true,
            render: function (data, type, full, meta) {
                var is_checked = '';
                if (full.is_active == 1) {
                    is_checked = 'checked';
                } else {
                    is_checked = '';
                }
                status = '<label class="switch" for="checkbox' + full.id + '">' +
                        '<input type="checkbox" id="checkbox' + full.id + '" ' + is_checked + ' class="vendors-actions" data-id="' + full.id + '" /> ' +
                        '<div class="slider round"></div>' +
                        '</label>';
                return status;
            }
        },
        {
            data: "name",
            visible: true,
        },
        {
            data: "email_id",
            visible: true,
        },
        {
            data: "username",
            visible: true,
        },
        {
            data: "contact_name",
            visible: true,
            render: function (data, type, full, meta) {
                result = '<b>Name : </b>' + full.contact_person;
                result += '<br><b>Contact No : </b>' + full.contact_no;
                return result;
            }
        },
        {
            data: "description",
            visible: true
        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                action = '';
                action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/inventory/vendors/review/' + btoa(full.user_id) + '" target="_blank" class="btn custom_dt_action_button btn-xs" title="Review">Review</a>';
                action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/inventory/vendors/api-token/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Review">Generate API Token</a>';
                action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/inventory/vendors/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
                action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/inventory/vendors/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
                action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/inventory/vendors/reset_password/' + btoa(full.user_id) + '" class="btn custom_dt_action_button btn-xs" onclick="return reset_password(this)" title="Reset Password">Reset Password</a>';
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
    ],
    "fnDrawCallback": function () {
        var info = document.querySelectorAll('.switchery-info');
        $(info).each(function () {
            var switchery = new Switchery(this, {color: '#95e0eb'});
        });
    }
});

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});
$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
var add_button = '<div class="text-right"><a href="' + site_url + 'admin/inventory/vendors/add" class="btn bg-teal-400 btn-labeled custom_add_button"><b><i class="icon-plus-circle2"></i></b> Add Vendors</a></div>';
$('.datatable-header').append(add_button);

if(phone_formate == 1){
    $('.format-phone-number').formatter({
        pattern: '({{999}}) {{999}} - {{9999}}'
    });
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

function reset_password(e) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover old password!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, Reset it!"
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
var validator = $("#add_vendor_form").validate({
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
        txt_name: {required: true, maxlength: 100},
        txt_contact_person: {required: true, maxlength: 100},
        txt_contact_no: {required: true, minlength: 16, maxlength: 16},
        txt_desc: {required: true, maxlength: 500},
        txt_email_id: {required: true, email: true, remote: remoteURL},
    },
    messages: {
        txt_email_id: {remote: $.validator.format("This Email already exist!")},
        txt_contact_no: "Please enter validate contact number."
    },
    submitHandler: function (form) {
        form.submit();
        $('.custom_save_button').prop('disabled', true);
    },
    invalidHandler: function () {
        $('.custom_save_button').prop('disabled', false);
    }
});

$(document).ready(function () {
    $("#can_add_multi_staff").click(function () {
        var value = 0;
        if ($(this).prop('checked') === true) {
            value = 1;
        } else {
            value = 0;
        }

        $(this).val(value);
    });

    $(document).on("click", ".vendors-actions", function () {
        var is_active = 0;
        var vendor_id = $(this).attr('data-id');
        var self = this;

        if (!self.checked) {
            is_active = 0;
        } else {
            is_active = 1;
        }

        var data = {
            is_active: is_active,
            vendor_id: vendor_id
        }
        var url = site_url + 'admin/inventory/change_vendor_status';
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function (response) {
                if (response == 1) {
                    swal("Success!", "Vendor status has been updated successfully.", "success")
                } else {
                    swal("Error!", "Something went wrong.", "error")
                }
            }
        });
    });
});