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
    ajax: site_url + 'invoices/get_items_data_trash',
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
            sortable: false,
        },
        {
            data: "estimate_date",
            visible: true,
        },
        {
            data: "estimate_id",
            visible: true,
        },
        {
            data: "cust_name",
            visible: true,
        },
        {
            data: "full_name",
            visible: true,
        },
        {
            data: "is_sent",
            visible: true,
            render: function (data, type, full, meta) {
                var action = '';
                if (full.is_save_draft == 1) {
                    action = '<span class="label bg-teal-400">DRAFT</span>';
                } else {  
                    if (full.is_sent == 0) {
                        action = '<span class="label bg-orange-400">NOT SENT</span>';
                    } else if (full.is_sent == 1) {
                         action = '<span class="label bg-orange-400">NOT SENT</span>';
                    } else if (full.is_sent == 2) {
                         action = '<span class="label bg-success-400">SENT</span>';
                    }
                }
                return action;
            }
        },
        {
            data: "total",
            visible: true,
            render: function (data, type, full, meta) {
                return parseFloat(full.total).toFixed(2);
            }
        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                action = '';
                action += '<a href="' + site_url + 'invoices/trash_recover/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_recover_alert(this)" title="Recover">Recover</a>';
                action += '<a href="' + site_url + 'invoices/trash_delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_delete_alert(this)" title="Delete">Delete</a>';
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
    initComplete: function () {
        this.api().columns('.slct', {page: 'current'}).every(function () {
            var that = this;

            $('input', this.footer()).on('keyup change', function () {
                if (that.search() !== this.value) {
                    that.search(this.value).draw();
                }
            });
        });
    },
    "fnDrawCallback": function () {
        var info = document.querySelectorAll('.switchery-info');
        $(info).each(function () {
            var switchery = new Switchery(this, {color: '#95e0eb'});
        });
    }
});

// Setup - add a text input to each footer cell
$('.datatable-responsive-control-right tfoot th').each(function () {
    var title = $(this).text();
    if ($(this).hasClass('slct')) {
        $(this).html('<input type="text" placeholder="Search ' + title + '" />');
    }
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
            text: "Are you sure you want to recover invoice?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF7043",
            confirmButtonText: "Yes, Recover it!"
        }, function () {
            $.ajax({
                url: 'Invoices/recover_multiple',
                method: 'POST',
                data: {recover_id : recover_id},
                success: function(data){
                    if(data == 0) {
                        alert('Something going wrong.');
                    } else {
                        window.location.href = site_url + 'invoices';
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
                url: 'Invoices/delete_multiple',
                method: 'POST',
                data: {delete_id : delete_id},
                success: function(data){
                    if(data == 0) {
                        alert('Something going wrong.');
                    } else {
                        window.location.href = site_url + 'invoices/invoice_trash';
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
        text: "Are You sure you want to recover this invoice?",
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