/**********************************************************
 Intitalize Data Table
 ***********************************************************/
var table = $('.datatable-responsive-control-right').dataTable({
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
    order: [[2, "desc"]],
    ajax: site_url + 'customers/get_customer_data_trash',
    responsive: {
        details: {
            type: 'column',
            target: -1
        }
    },
    columns: [
        {
            data: "id",
            render: function (data, type, full, meta) {
                var checkbox = '';
                checkbox = '<input type="checkbox" class="checkbox" id="'+btoa(full.id)+'" style="height: 20px; width: 20px;">';
                return checkbox;
            },
            visible: true,
            sortable: false,
        },
        {
            data: "start_date",
            visible: true,
            render: function (data, type, full, meta) {
                var days_left = '';
                var start_date = full.start_date;    
                var d = new Date();
                var month = d.getMonth()+1;
                var day = d.getDate();
                var current_date = d.getFullYear() + '-' +
                    ((''+month).length<2 ? '0' : '') + month + '-' +
                    ((''+day).length<2 ? '0' : '') + day;
                var days_left = daysdifference(start_date, current_date);
                                
                function daysdifference(firstDate, secondDate){
                    var startDay = new Date(firstDate);
                    var endDay = new Date(secondDate);
                    var millisBetween = startDay.getTime() - endDay.getTime();
                    var days_left = millisBetween / (1000 * 3600 * 24) + 1;
                    return Math.round(Math.abs(days_left) - 30);
                }
                
                var day_s = '';
                var day_color = '';
                if(Math.abs(days_left) > 1) {
                    day_s = 'Days';
                } else {
                    day_s = 'Day';
                }
                if(Math.abs(days_left) <= 3) {
                    day_color = 'red';
                } else {
                    day_color = 'black';
                }
                
                var final_days = '<span style="color: '+ day_color +'; ">' + Math.abs(days_left) + day_s + '</span>';
                return final_days;
            },
            sortable: false,
        },
        {
            data: "sr_no",
            visible: true,
        },
        {
            data: "first_name",
            visible: true,
            render: function (data, type, full, meta) {
                return full.first_name + ' ' + full.last_name;
            }
        },
        {
            data: "company",
            visible: true,
        },
        {
            data: "display_name_as",
            visible: true,
        },
        {
            data: "phone",
            visible: true,
            sortable: false,
        },
        {
            data: "mobile",
            visible: true,
            sortable: false,
        },
        {
            data: "email",
            render: function (data, type, full, meta) {
                var id = btoa(full.id);
                var customer_email = '';
                $.ajax({
                    async: false,
                    global: false,
                    url: 'customers/email_detail',
                    method: 'POST',
                    dataType: "json",
                    data: {id: id},
                    success: function(data){
                        customer_email = data.customer_email;
                    },
                });                
                return customer_email;
            },
            sortable: false,
        },
        {
            data: "fax",
            visible: true,
            sortable: false,
        },
        {
            data: "created_date",
            visible: true,  
            sortable: false,
        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                action = '';
                action += '<a href="' + site_url + 'customers/trash_recover_customer/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_recover_alert(this)" title="Recover">Recover</a>';
                action += '<a href="' + site_url + 'customers/trash_delete_customer/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_delete_alert(this)" title="Delete">Delete</a>';
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
});


$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});

$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
var delete_recover_button = '<div class="text-right"><a href="javascript:void(0);" class="btn bg-teal-400 btn-labeled custom_recover_button mb-3"><b><i class="icon-plus-circle2"></i></b> Recover</a>&nbsp;<a href="javascript:void(0);" class="btn bg-danger-400 btn-labeled custom_delete_button mb-3"><b><i class="icon-trash"></i></b> Delete</a></div>';
$('.datatable-header').append(delete_recover_button);

// Recover multiple invoice
$(document).on('click','.custom_recover_button',function(){
    var recover_id = [];
    $(".checkbox:checked").each(function(){    
        var id = $(this).attr('id');
        recover_id.push(id);
    });
    if(recover_id != "")
    {
        swal({
            title: "Are you sure?",
            text: "Are you sure you want to recover customers?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF7043",
            confirmButtonText: "Yes, Recover it!"
        }, function () {
            $.ajax({
                url: 'customers/recover_multiple',
                method: 'POST',
                data: {recover_id : recover_id},
                success: function(data){
                    if(data == 0) {
                        alert('Something going wrong.');
                    } else {
                        window.location.href = site_url + 'customers';
                    }
                },
            });
        });
    } else {
        alert("Please select some records to recover.");
    }
});

// Delete multiple invoice
$(document).on('click','.custom_delete_button',function(){
    var delete_id = [];
    $(".checkbox:checked").each(function(){    
        var id = $(this).attr('id');
        delete_id.push(id);
    });
    if(delete_id != "")
    {
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF7043",
            confirmButtonText: "Yes, Delete it!"
        }, function () {
            $.ajax({
                url: 'customers/delete_multiple',
                method: 'POST',
                data: {delete_id : delete_id},
                success: function(data){
                    if(data == 0) {
                        alert('Something going wrong.');
                    } else {
                        window.location.href = site_url + 'customers/customer_trash';
                    }
                },
            });
        });
    } else {
        alert("Please select some records to delete.");
    }
});

// Checkbox check uncheck
$(document).on('change','#check_all',function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});

/**********************************************************
 Confirm recover alert
 ***********************************************************/
function confirm_recover_alert(e) {
    swal({
        title: "Are you sure?",
        text: "Are You sure you want to recover this customer?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, Recover it!"
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

/**********************************************************
 Confirm delete alert
 ***********************************************************/
function confirm_delete_alert(e) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, Delete it!"
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