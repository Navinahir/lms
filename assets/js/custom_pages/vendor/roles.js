$('.datatable-basic').dataTable({
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
    order: [[0, "asc"]],
    ajax: site_url + 'vendor/roles/filter_roles_data',
    columns: [
        {
            data: "sr_no",
            visible: true,
            sortable: false
        },
        {
            data: "role_name",
            visible: true,
            render: function (data, type, full, meta) {
                if (full.id == 4) {
                    return 'Admin (Owner)';
                } else {
                    return full.role_name
                }
            }
        },
        {
            data: "description",
            visible: true,
            // render: function (data, type, full, meta) {
            //     console.log(full.description);
            //     if (full.description == null) {
            //         return '<span class="text-center"> --- </span>';
            //     }
            // }
        },
        {
            data: "modified_date",
            visible: true,
        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                var action = '';
                if (full.id != 4) {
                    if (edit == 1) {
                        action += '<a href="' + site_url + 'vendor/roles/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
                    }
                    if (dlt == 1) {
                        action += '&nbsp;&nbsp;<a href="' + site_url + 'vendor/roles/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" data-id="' + full.id + '" onclick="return confirm_alert(this,\'Delete\')" title="Delete">Delete</a>';
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
            var switchery = new Switchery(this, {color: '#95e0eb'});
        });
    }
});

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});
$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
if (add == 1) {
    var add_button = '<div class="text-right"><a href="' + site_url + 'vendor/roles/add" class="btn bg-teal-400 btn-labeled custom_add_button"><b><i class="icon-plus-circle2"></i></b> Add Role</a></div>';
    $('.datatable-header').append(add_button);
}

//-- Sweet Alert Delete Popup
function confirm_alert(e, action) {
    swal({
        title: "Are you sure?",
        text: "You would like to " + action + " this Role!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, " + action + " it!"
    }, function (isConfirm) {
        if (isConfirm) {
            var id = $(e).attr('data-id');
            $.ajax({
                type: 'POST',
                url: site_url + 'vendor/roles/count_total_user',
                async: false,
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    if (data.code == 200) {
                        window.location.href = $(e).attr('href');
                        return true;
                    } else if (data.code == 400) {
                        var user = (data.data == 1) ? " User" : "Users";
                        var message = "There are " + data.data + " " + user + ". Please change role or delete user.";
                        setTimeout(function () {
                            swal("User exists!", message);
                        }, 1000);

                    }
                }
            });
            return false;
        } else {
            return false;
        }
    });
    return false;
}

var validator = $("#add_role_form").validate({
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
        txt_role_name: {required: true, remote: remoteURL},
        permission: {required: true},
    },
    messages: {
        txt_role_name: {remote: $.validator.format("This Rolename already exist!")},

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

var check_boxes = $(document).find('.custom-control-input.hide');
$.each(check_boxes, function (i, item) {
    if ($(item).prop('checked') === true) {
        if ($(item).hasClass('exist') == false) {
            // $(item).attr('disabled', 'disabled');
            setTimeout(function () {
                var checker = $(item).siblings('.custom-checkbox').children().children().children('.checker');
                $.each(checker, function (i1, v1) {
                    if (i1 == 0) {
                        $(v1).children().children().attr('disabled', 'disabled');
                    }
                });
            }, 500);
        }
    }
});

$(document).on('click', '.access_roles input', function () {
    if (typeof $(this).data('dependent') !== 'undefined') {
        if ($(this).prop('checked') === true) {
            $('.' + $(this).data('dependent')).parent().addClass('checked');
            $('.' + $(this).data('dependent')).prop('checked', true);
            $('.' + $(this).data('dependent')).not('.hide').prop('disabled', true);
        } else {
            if ($('.' + $(this).data('dependent')).data('checked') == 0 && $('[data-dependent="' + $(this).data('dependent') + '"]:checked').length == 0) {
                $('.' + $(this).data('dependent')).not('.exist').parent().removeClass('checked');
                $('.' + $(this).data('dependent')).not('.exist').prop('checked', false);
                $('.' + $(this).data('dependent')).not('.hide').prop('disabled', false);
            } else if ($('[data-dependent="' + $(this).data('dependent') + '"]:checked').length == 0) {
                $('.' + $(this).data('dependent')).not('.hide').prop('disabled', false);
            }
        }
    }
});
// $(document).on('click', '.company_profile_edit', function () {
//     if ($(this).prop('checked') === true) {
//         $('.company_profile_view').parent().addClass('checked');
//         $('.company_profile_view').prop('checked', true);
//         // $('.company_profile_view').prop('disabled', true);
//     } else {
//         $('.company_profile_view').parent().removeClass('checked');
//         $('.company_profile_view').prop('checked', false);
//         // $('.company_profile_view').prop('disabled', false);
//     }

// });
$(document).on('click', "input:checkbox[name='perm_list[]']", function () {
    var id = $(this).data('id');
    if ($(this).prop('checked') == true) {
        $("[data-hidden-id='" + id + "']").prop('checked', true);
    } else {
        $("[data-hidden-id='" + id + "']").prop('checked', false);
    }
});
