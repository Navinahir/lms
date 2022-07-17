var service_id = 0;
$(document).ready(function() {
    var unsync_id = $("#unsync_id").val();
    if(unsync_id == '')
    {
        $("#add_to_service").css("display", "none");
    }
    $("#add_to_service").click(function(e){
        e.preventDefault();
        if(unsync_id != '')
        {
            var l = Ladda.create(this);
            l.start();
            $.ajax({
                url: site_url + 'services/add_to_quickbook',
                type: 'POST',
                dataType : "json",
                data: {unsync_id: unsync_id},
                success: function(data)
                {
                    l.stop();
                    if(data.sync_id_not_found != null && data.sync_id_not_found == "sync id not found")
                    {
                        location.reload(true);
                    }
                    if(data.services != null && data.services == "not available")
                    {
                        location.reload(true);
                    }
                    if(data.error != null && data.error == "error")
                    {
                        location.reload(true);
                    }
                    if(data.session != null && data.session == "exprired")
                    {
                        location.reload(true);
                    }
                    if(data.success != null && data.success == "success")
                    {
                        location.reload(true);
                    }
                }
            });
        }
    });
});
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
    ajax: site_url + 'quickbook/get_service',
    columns: [
        {
            data: "sr_no",
            visible: true,
            sortable: false
        },
     /*   {
            data: "id",
            visible: true,
            sortable: false
        },*/
        {
            data: "name",
            visible: true,
        },
        {
            data: "description",
            visible: true,
        },
        {
            data: "rate",
            visible: true,
        },
        {
            data: "modified_date",
            visible: true,
        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                var action = '';
                service_id = full.id;
                if(session_set_status == 'yes')
                {
                    if(full.quickbooks == 1)
                    {
                        action += '<a href="' + site_url + 'services/add_to_quickbook/' + btoa(full.id) + '" class="btn custom_dt_action_button_quickbook btn-xs AddToQuickbook" title="Add To Quickbook">add to quickbooks</a>&nbsp;&nbsp;';
                    }
                }
                if (edit == 1) {
                    action += '<a id="edit_' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs edit" title="Edit">Edit</a>';
                }
                if (dlt == 1) {
                    action += '<a href="' + site_url + 'services/action/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this,\'Delete\')" title="Delete">Delete</a>';
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
var add_button = '<div class="text-right"><a id="add_to_service" class="btn bg-teal-400 btn-labeled custom_add_button ladda-button" data-style="expand-right" data-size="l"><b><i class="icon-plus-circle2"></i></b> <span class="ladda-label">Add To Quicknbook</span></a></div>';
$('.datatable-header').append(add_button);

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});
$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');

//-- Sweet Alert Delete Popup
function confirm_alert(e, action) {
    swal({
        title: "Are you sure?",
        text: "You would like to " + action + " this Service!",
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

var validator = $("#add_service_form").validate({
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
        txt_service_name: {
            required: true,
            remote: remoteURL,
            normalizer: function (value) {
                return $.trim(value);
            }
        },
        txt_rate: { required: true }
    },
    messages: {
        txt_service_name: { remote: $.validator.format("This Service already exist!") },

    },
    submitHandler: function (form) {
        form.submit();
        $('.custom_save_button').prop('disabled', true);
    },
    invalidHandler: function () {
        $('.custom_save_button').prop('disabled', false);
    }
});

$(document).on('click', '.edit', function () {
    var id = $(this).attr('id').replace('edit_', '');
    var url = base_url + 'services/get_service_by_id';
    $.ajax({
        type: 'POST',
        url: url,
        async: false,
        dataType: 'JSON',
        data: { id: id },
        success: function (data) {
            $('#add_service_form').removeClass('disabled_div')
            $('#txt_service_name').val(data.name);
            $('#txt_rate').val(data.rate);
            $('#txt_description').val(data.description);
            $('#txt_service_name').focus();
            $('#txt_service_id').val(data.id);
            $("#txt_service_name").rules("add", {
                remote: site_url + "services/checkUniqueName/" + data.id,
                messages: {
                    remote: $.validator.format("This services already exist!")
                }
            });
            $('#add_service_form').attr('action', site_url + "services/edit/" + btoa(data.id));
            $("#add_service_form").validate().resetForm();
        }
    });
});
if (add == 0) {
    $('#add_service_form').addClass('disabled_div');
}

function cancel_click() {
    $('#txt_service_name').val('');
    $('#txt_description').val('');
    $('#txt_rate').val('0');
    $('#txt_service_id').val('');
    $("#txt_service_name").rules("add", {
        remote: site_url + "services/checkUniqueName",
        messages: {
            remote: $.validator.format("This name already exist!")
        }
    });
    $('#txt_service_name').valid();
    $("#add_service_form").validate().resetForm();
    $('#add_service_form').attr('action', site_url + "services/add");
    setTimeout(function () {
        $('.form-control-feedback').remove();
    }, 10);

}
