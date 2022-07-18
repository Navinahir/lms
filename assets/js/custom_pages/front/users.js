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
    ajax: site_url + 'users/get_users_ajax_data',
    columns: [
        {
            data: "sr_no",
            visible: true,
            sortable: false
        },
        {
            data: "full_name",
            visible: true,
            render: function (data, type, full, meta) {
                var s = full.status;
                var cls = 'text-success';
                status = s[0].toUpperCase() + s.slice(1);
                if (s == 'block') {
                    cls = 'text-danger';
                }
                result = '<b>' + full.full_name + '</b> <span class="' + cls + '">(' + status + ')</span>';
                result += '<br>' + full.email_id;
                return result;
            }
        },
        {
            data: "username",
            visible: true,
        },
        {
            data: "role_name",
            visible: true,
            render: function (data, type, full, meta) {
                if (full.user_role == 4) {
                    return 'Admin (Owner)';
                } else {
                    return full.role_name
                }
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
                if (full.id != user_id) {
                    if (full.status == 'active') {
                        if (edit == 1) {
                            action += '<a href="' + site_url + 'users/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs"  title="Edit">Edit</a>';
                        }
                        if (full.user_role != 4) {
                            if (full.status == 'active') {
                                if(pause == 1) {
                                    action += '&nbsp;&nbsp;<a href="' + site_url + 'users/action/block/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this,\'Pause\')" title="Pause">Pause</a>';
                                }
                            }
                        }
                    } else if (full.status == 'block') {
                        if (full.status == 'block') {
                            if(pause == 1) {
                                action += '<a href="' + site_url + 'users/action/active/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this,\'Reactivate\')" title="Reactivate">Reactivate</a>';
                            }
                        }
                    }
                    if (dlt == 1) {
                        action += '&nbsp;&nbsp;<a href="' + site_url + 'users/action/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this,\'Delete\')" title="Delete">Delete</a>';
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

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});
$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
if (add == 1) {
    var add_button = '<div class="text-right"><a href="' + site_url + 'users/add" class="btn bg-teal-400 btn-labeled custom_add_button"><b><i class="icon-plus-circle2"></i></b> Invite User</a></div>';
    $('.datatable-header').append(add_button);
}

//-- Sweet Alert Delete Popup
function confirm_alert(e, action) {
    swal({
        title: "Are you sure?",
        text: "You would like to " + action + " this User!",
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