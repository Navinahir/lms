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
    order: [[0, "desc"]],
    ajax: site_url + 'admin/users/get_ajax_data',
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
            data: "status",
            visible: true,
            render: function (data, type, full, meta) {
                status = '';
                if (full.is_delete == 1) {
                    status = '<span class="label label-danger">Deleted</span>';
                } else {
                    if (full.status == 'block') {
                        status = '<td class="text-center"><span class="label label-success bg-danger">Paused</span></td>';
                    } else if (full.status == 'active') {
                        status = '<td class="text-center"><span class="label label-success">Activated</span></td>';
                    } else if (full.status == 'pending') {
                        status = '<td class="text-center"><span class="label label-success bg-orange">pending</span></td>';
                    }
                }
                return status;
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
                action += '&nbsp;&nbsp;<a data-id="' + full.id + '" class="btn custom_dt_action_button btn-xs requested_user_details" title="View">View</a>';
                action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/users/action/approve/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this,\'Approve\')" title="Approve">Approve</a>';
                action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/users/action/reject/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this,\'Reject\')" title="Reject">Reject</a>';
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

$('.datatable-basic-cancel-subscription').dataTable({
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
    ajax: site_url + 'admin/users/get_cancel_subscription_users_ajax_data',
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
            data: "status",
            visible: true,
            render: function (data, type, full, meta) {
                status = '';
                if (full.is_delete == 1) {
                    status = '<span class="label label-danger">Deleted</span>';
                }
                return status;
            }
        },
        {
            data: "full_name",
            render: function (data, type, full, meta) {
                var full_name = "";
                if(full.full_name != "" && full.full_name != null)
                {
                    full_name = full.full_name;
                } else {
                    full_name = '<span style="display: flow-root;">---</span>';
                }
                return full_name;
            },
            visible: true,
        },
        {
            data: "business_name",
            render: function (data, type, full, meta) {
                var business_name = "";
                if(full.business_name != "" && full.business_name != null)
                {
                    business_name = full.business_name;
                } else {
                    business_name = '<span style="display: flow-root;">---</span>';
                }
                return business_name;
            },
            visible: true,
        },
        {
            data: "email_id",
            render: function (data, type, full, meta) {
                var email_id = "";
                if(full.email_id != "" && full.email_id != null)
                {
                    email_id = full.email_id;
                } else {
                    email_id = '<span style="display: flow-root;">---</span>';
                }
                return email_id;
            },
            visible: true,
        },
        {
            data: "contact_number",
            render: function (data, type, full, meta) {
                var contact_number = "";
                if(full.contact_number != "" && full.contact_number != null)
                {
                    contact_number = full.contact_number;
                } else {
                    contact_number = '<span style="display: flow-root;">---</span>';
                }
                return contact_number;
            },
            visible: true,
        },
        {
            data: "package_name",
            render: function (data, type, full, meta) {
                var package_name = "";
                if(full.package_name != "" && full.package_name != null)
                {
                    package_name = full.package_name;
                } else {
                    package_name = '<span style="display: flow-root;">---</span>';
                }
                return package_name;
            },
            visible: true,
        },
        {
            data: "modified_date",
            render: function (data, type, full, meta) {
                var modified_date = "";
                if(full.modified_date != "" && full.modified_date != null)
                {
                    modified_date = full.modified_date;
                } else {
                    modified_date = '<span style="display: flow-root;">---</span>';
                }
                return modified_date;
            },
            visible: true,
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

//-- Sweet Alert Delete Popup
function confirm_alert(e, action) {
    swal({
        title: "Are you sure?",
        text: "You are about to " + action.toLowerCase() + " this User.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, " + action + " it!"
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

//Show requested user details
$(document).on('click', '.requested_user_details', function () {
    var id = $(this).attr('data-id');

    $.ajax({
        url: site_url + "admin/users/get_requested_user_details",
        data: {
            user_id: id
        },
        type: 'POST',
        dataType: 'HTML',
        success: function (data) {
            $("#requested_user_details_body").empty().html(data);
            $("#requested_user_details_modal").modal('show');
        }
    });
});