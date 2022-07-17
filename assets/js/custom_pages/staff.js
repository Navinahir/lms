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
    order: [[2, "asc"]],
    ajax: site_url + 'admin/staff/get_ajax_data',
    columns: [
        {
            data: "sr_no",
            visible: true,
            sortable: false
        },
        {
            data: "status",
            visible: true,
            render: function (data, type, full, meta) {
                status = '';
                if (full.is_delete == 1) {
                    status = '<span class="label label-danger">Deleted</span>';
                } else {
                    var toggle_status = '';
                    if(full.status == 'active'){
                        toggle_status = 'checked';
                    } else {
                        toggle_status = '';
                    }

                    if (full.status == 'block') {
                        status = '<label class="switch"><input type="checkbox" class="status_toggle" value = "'+ full.status +'" id="txt_status_' + full.id + '" data-id = "'+ full.id +'" '+toggle_status+'><span class="slider round"></span></label>';
                    } else if (full.status == 'active') {
                        status = '<label class="switch"><input type="checkbox" class="status_toggle" value = "'+ full.status +'" id="txt_status_' + full.id + '" data-id = "'+ full.id +'" '+toggle_status+'><span class="slider round"></span></label>';
                    }
                }
                return status;
            }
        },
        {
            data: "role_name",
            visible: true,
            render: function (data, type, full, meta) {
                if (full.role_name == 'admin') {
                    return '<span class="label bg-success">Admin</span>';
                } else if (full.role_name == 'admin_assistant') {
                    return '<span class="label bg-info">Admin Assistant</span>';
                } else if (full.role_name == 'staff') {
                    return '<span class="label bg-grey-400">Staff</span>';
                }
            }
        },
        {
            data: "full_name",
            visible: true,
        },
        {
            data: "username",
            visible: true,
        },
        {
            data: "email_id",
            visible: true,
        },
        {
            data: "modified_date",
            visible: true,
        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                action = '';
                //action += '<a href="javascript:void(0);" class="btn btn-xs custom_dt_action_button menu_cat_view_btn" title="View" id="' + btoa(full.id) + '">View</a>';
                action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/staff/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
                action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/staff/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
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

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});
$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
var add_button = '<div class="text-right"><a href="' + site_url + 'admin/staff/add" class="btn bg-teal-400 btn-labeled custom_add_button"><b><i class="icon-plus-circle2"></i></b> Add Staff</a></div>';
$('.datatable-header').append(add_button);

// Change status
$(document).on('click','.status_toggle',function(){
    var staff_id = $(this).attr('data-id');
    var staff_status = $(this).val();
    if(staff_status == 'block') {
        staff_status = 'active';
        $('.status_toggle').val('active');
    } else {
        staff_status = 'block';
        $('.status_toggle').val('block');
    }    
    $.ajax({
        url: base_url + 'admin/staff/change_status',
        method: 'POST',
        data: {staff_status: staff_status, staff_id: staff_id },
        dataType: 'json',
        success: function(result){
            if(result.affected_row > 0){
                success_alert();
            } else {
                $('#txt_status_'+result.staff_id).prop('checked', false); 
                fail_alert();
            }
        },
    });
});

// Success alert
function success_alert(){
    swal("Success!", "Status changed successfully!", "success");
}

// Success alert
function fail_alert(){
    swal("Fail", "Something going wrong.", "error");
}

// Contact number formater
if(uri_segment != "" && uri_segment != null)
{        
    $('.format-phone-number').formatter({
        pattern: '({{999}}) {{999}} - {{9999}}'
    });
}

//-- Sweet Alert Delete Popup
function confirm_alert(e) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this staff!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, delete it!"
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
var validator = $("#add_staff_form").validate({
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
        txt_first_name: { required: true, maxlength: 50 },
        txt_last_name: { required: true, maxlength: 50 },
        txt_email_id: { required: true, email: true, remote: remoteURL },
        txt_user_name: { required: username_req, remote: remoteURL2 },
        txt_role: { required: true },
        txt_contact_no: { minlength: 16, maxlength: 16, }
    },
    messages: {
        txt_email_id: { remote: $.validator.format("This Email already exist!") },
        txt_user_name: { remote: $.validator.format("This Username already exist!") },
        txt_contact_no: 'Please enter valid phone number!'
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

// Remove validation on dropdown change
$('select').on('change', function () {
    $(this).valid();
});

//-- Switchery 
var info = document.querySelector('.switchery-info');
if (info != null) {
    var switchery = new Switchery(info, { color: '#95e0eb' });
}
var clickCheckbox = document.querySelector('#txt_status');
if (clickCheckbox != null) {
    clickCheckbox.addEventListener('click', function () {
        if (clickCheckbox.checked) {
            $('.switchery small').removeClass('custom_switchery_before');
            $('.switchery small').addClass('custom_switchery_after');
        } else {
            $('.switchery small').removeClass('custom_switchery_after');
            $('.switchery small').addClass('custom_switchery_before');
        }
    });
}
if (status == 'checked') {
    $('.switchery small').removeClass('custom_switchery_before');
    $('.switchery small').addClass('custom_switchery_after');
} else if (status == 'unchecked' || status == 'null') {
    $('.switchery small').removeClass('custom_switchery_after');
    $('.switchery small').addClass('custom_switchery_before');
}