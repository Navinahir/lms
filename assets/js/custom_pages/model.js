//-- This function is used to display list of records in datatable
$(function () {
    $('.datatable-basic').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[1, "asc"]],
        ajax: site_url + 'admin/product/get_model',
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
                data: "make_name",
                visible: true,
            },
            {
                data: "modified_date",
                visible: true,
            },
            {
                data: "action",
                visible: true,
                render: function (data, type, full, meta) {
                    action = '';
                    action += '<a id="edit_' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs edit" title="Edit">Edit</a>';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/product/model/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
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
    var url = base_url + 'admin/product/get_model_by_id';
    $('#custom_loading').removeClass('hide');
    $('#custom_loading img').addClass('hide');
    $('#model_form_row').css('z-index', '999999');
    $('.bulk_upload_div').addClass('hide');
    $.ajax({
        type: 'POST',
        url: url,
        async: false,
        dataType: 'JSON',
        data: {id: id},
        success: function (data) {
            $('#txt_model_name').val(data.name);
            $('#txt_model_name').focus();
            $('#txt_model_id').val(data.id);
            var $example = $('#txt_make_name').select2();
            $example.val(data.make_id).trigger("change");
            $("#txt_model_name").rules("add", {
                // remote: site_url + "admin/product/checkUnique_Model_Name/" + data.id,
                remote: site_url + "admin/product/Unique_Make_Name",
                messages: {
                    remote: $.validator.format("This name already exist!")
                }
            });
            $("#add_model_form").validate().resetForm();
            $('html, body').animate({scrollTop: 0}, 500);
            setTimeout(function () {
                $('body').css('overflow', 'hidden');
            }, 500);
        }
    });
});


function cancel_click() {
    $('#custom_loading').addClass('hide');
    $('#custom_loading img').removeClass('hide');
    $('#model_form_row').css('z-index', '0');
    $('.bulk_upload_div').removeClass('hide');
    $('#txt_model_name').val('');
    $('#txt_model_id').val('');
    var $example = $('#txt_make_name').select2();
    $example.val('').trigger("change");
    $("#txt_model_name").rules("add", {
        remote: site_url + "admin/product/checkUnique_Model_Name/",
        messages: {
            remote: $.validator.format("This name already exist!")
        }
    });
    $('#txt_model_name').valid();
    $("#add_model_form").validate().resetForm();
    $('.form-control-feedback').remove();
    $('body').css('overflow', 'auto');
}

//-- This function is used to delete particular record
function confirm_alert(e) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this model!",
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
var validator = $("#add_model_form").validate({
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
        txt_make_name: { required: true },
        txt_model_name: {
            required: true,
            maxlength: 150,
            remote: remoteURL
        }
    },
    messages: {
        txt_make_name: {
            remote: $.validator.format("This name already exist!")
        },
        txt_model_name: {
            remote: $.validator.format("This name already exist!")
        }
    },
    submitHandler: function(form){
        form.submit();
        $('.custom_save_button').prop('disabled', true);
    },
   invalidHandler: function() {
        $('.custom_save_button').prop('disabled', false);
   }
});