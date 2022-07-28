$('.datatable-basic').dataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    // scrollX: true,
    language: {
        search: '<span>Filter:</span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
        emptyTable: 'No data currently available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[0, "desc"]],
    ajax: site_url + 'admin/users/get_users_ajax_data',
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
                    } else if (full.status == 'rejected') {
                        status = '<td class="text-center"><span class="label label-success bg-info">Rejected</span></td>';
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
                action += '<a href="' + site_url + 'admin/users/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="View">Edit</a>';
                action += '<a href="' + site_url + 'admin/users/delete/' + btoa(full.id) + '" data-userid="' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
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
var add_button = '<div class="text-right add_action_button"><a href="' + site_url + 'admin/users/add" class="btn bg-teal-400 btn-labeled custom_add_button  mt-2"><b><i class="icon-plus-circle2"></i></b> Add Users</a></div>';
$('.datatable-header').append(add_button);
//-- Sweet Alert Delete Popup
function confirm_alert(e, action) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, delete it!"
    }, function (isConfirm) {
        if (isConfirm) {
            window.location.href = $(e).attr('href');
            return true;
        } else {
            return false;
        }
    });
    return false;
}

//-- This function is used to delete particular record
function confirm_delete_alert(e) {
    var user_id = $(e).attr('data-userid');
    swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, delete it!"
    }, function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: site_url + 'admin/users/send_otp_notification',
                method:'POST',
                data: {user_id: user_id},
                dataType: 'json',
                success: function(response){
                    if(response == 1)
                    {
                        swal({
                            type: 'success',
                            title: 'OTP has been sent to your email. Please check!',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        
                        setTimeout(function () {
                            $("#otp_verify_view_modal_form").trigger("reset");
                            $("#delete_user_id").val(user_id);
                            $("#otp_verify_view_modal").modal("show");
                        }, 3005);

                    } else {
                        swal({
                            title: "Something going wrong.",
                            type: "error",
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                },
                error: function(response){
                    swal({
                        title: "Something going wrong.",
                        type: "error",
                        showConfirmButton: false,
                        timer: 3000
                    });
                },
            });

            return true;
        } else {
            return false;
        }
    });
    return false;
}

$("#otp_verify_view_modal_form").validate({
    rules: {
        "otp_verification": {
            required: true,
            minlength: 5,
            maxlength: 10
        },
    },
    messages: {
        "otp_verification": {
            required: "Please enter OTP code."
        }
    },
    submitHandler: function (form) { // for demo
        var data = {
            otp_verification: $("#otp_verification").val(),
            user_id: $("#delete_user_id").val(),
        };

        $.ajax({
            type: 'POST',
            url: site_url + "admin/users/otp_verifitcatio_and_delete_user",
            data: data,
            success: function (response) {
                response = jQuery.parseJSON(response);

                if (response.status == 'success') {
                    $("#otp_verify_view_modal").modal("hide");

                    swal({
                        type: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 3000
                    });

                    setTimeout(function () {
                        window.location.reload(true);
                    }, 3005);
                } else if (response.status == 'error') {
                    swal({
                        type: 'error',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal({
                    type: 'error',
                    title: 'Something went wrong, Please try again!',
                    showConfirmButton: false,
                    timer: 3000
                })
            }
        });

        return false;
    }
});

